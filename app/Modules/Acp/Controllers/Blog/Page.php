<?php
/**
 * @author tmtuan
 * created Date: 28-Jul-21
 */

namespace Modules\Acp\Controllers\Blog;

use CodeIgniter\I18n\Time;
use Modules\Acp\Controllers\AcpController;
use Modules\Acp\Controllers\Traits\PostImage;
use App\Entities\Post;
use App\Enums\Post\PostTypeEnum;
use App\Models\Blog\PostContentModel;
use Modules\Acp\Models\Blog\PostModel;
use App\Models\LangModel;
use Modules\Acp\Traits\deleteItem;
use CodeIgniter\HTTP\RedirectResponse;

class Page extends AcpController {
    use deleteItem;
    use PostImage;

    public function __construct()
    {
        parent::__construct();
        if ( empty($this->_model)) {
            $this->_model = new PostModel();
        }
    }

    /**
     * List pages
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function index() {
        $this->_data['title']= lang("Post.page_title");
        $postData = $this->request->getPost();
        $getData = $this->request->getGet(); 

        switch ($getData['listtype'] ?? '') {
            case 'deleted':
                $this->_model->onlyDeleted();
                $this->_data['listtype'] = 'deleted';
                break;
            case 'user':
                $this->_model->where("user_init", $this->user->id);
                $this->_data['listtype'] = 'user';
                break;
            default:
                $this->_data['listtype'] = 'all';
                break;
        }

        if ( isset($postData) && !empty($postData) ) {
            if ( !empty($postData['sel']) ) {
                $this->_model->delete($postData['sel']);
            } else return redirect()->back()->with('error', lang('Acp.no_item_to_delete'));
        }

        $this->_model->join('post_content', 'post_content.post_id = post.id', 'LEFT')
            ->where('post_type', PostTypeEnum::PAGE)
            ->where('lang_id', $this->currentLang->id);

        $this->_data['data'] = $this->_model->paginate();
        $this->_data['pager'] = $this->_model->pager;
        $this->_data['countAll'] = $this->_model->countAll();
        $this->_render('\blog\page\index', $this->_data);
    }

    /**
     * show add page form
     */
    public function addPage() {
        $this->_data['title']= lang("Post.add_page_title");

        $this->_render('\blog\page\add', $this->_data);
    }

    public function addPageAction() {
        $postData = $this->request->getPost();

        //validate the input
        if (! $this->validate($this->_validRules()))
        {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        } else if ( !$this->_model->checkTitle($postData['title']) ) return redirect()->back()->withInput()->with('errors', lang('Post.title_is_exist'));

        // check slug
        $slug = clean_url($postData['title']);
        if ($this->_model->checkSlug($slug, $this->currentLang->id)) {
            return redirect()->back()->withInput()->with('errors', [
                'title' =>  lang('Post.slug_can_not_create')
            ]);
        }

        //good then save the new post
        $newPost = new Post($postData);
        $newPost->user_type = $this->user->model_class;
        //upload image first
        $image   = $this->request->getFile('image');
        if ($image->getName()) {
            $response = $this->uploadPostImage($postData, $image);
            if ( $response instanceof RedirectResponse) return $response;
            $newPost->image = $response;
        }

        $newPost->slug = clean_url($postData['title']);
        $newPost->post_type = 'page';
        if ( $newPost->post_status === 'publish' ) $newPost->publish_date = date('Y-m-d H:i:s');

        if ( !isset($postData['lang_id']) || empty($postData['lang_id']) || $postData['lang_id'] == 0 ) {
            $privLang = model(LangModel::class)->getPrivLang();
            $newPost->lang_id = $privLang->id;
        }

        if (!$this->_model->addPost($newPost) )
        {
            return redirect()->back()->withInput()->with('errors', $this->_model->errors());
        }

        // Success!
        $item = $this->_model->join('post_content', 'post_content.post_id = post.id', 'LEFT')
            ->where('slug', $newPost->slug)->where('lang_id', $this->currentLang->id)->first();

        // save seo meta
        $postData['lang_id'] = $this->currentLang->id;
        $item->setSeoMeta($postData);

        //log Action
        $logData = [
            'title' => lang('Log.add_page'),
            'description' => lang('Log.add_page_desc',[$this->user->username,$item->slug]),
            'properties' => $item->toArray(),
            'subject_id' => $item->id,
            'subject_type' => PostModel::class,
        ];
        $this->logAction($logData);

        if ( isset($postData['save']) ) return redirect()->route('edit_page', [$item->id])->with('message', lang('Post.addPageSuccess', [$item->title]));
        else if ( isset($postData['save_exit']) ) return redirect()->route('page')->with('message', lang('Post.addPageSuccess', [$item->title]));
        else if ( isset($postData['save_addnew']) ) return redirect()->route('add_page')->with('message', lang('Post.addPageSuccess', [$item->title]));

    }

    public function editPage($id) {
        $this->_data['title'] = lang('Post.edit_page_title');
        $item = $this->_model->join('post_content', 'post_content.post_id = post.id', 'LEFT')
            ->where('lang_id', $this->currentLang->id)->find($id);

        if ( !isset($item->id) ) return redirect()->route('page')->with('error', lang('Acp.invalid_request'));
        $this->_data['itemData'] = $item;
        $this->_render('\blog\page\edit', $this->_data);
    }

    public function editPageAction($id) {
        $this->_data['title'] = lang('Post.edit_page_title');
        $item = $this->_model->join('post_content', 'post_content.post_id = post.id', 'LEFT')
            ->where('lang_id', $this->currentLang->id)->find($id);

        $postData = $this->request->getPost();

        if ( $this->user->id !== $item->user_id) {
            if ( !$this->user->inGroup('admin', 'superadmin', 'content_manager') ) {
                return redirect()->route('page')->with('error', lang('Post.you_can_not_edit'));
            }
        }
        $rules = [
            'title' => [
                'rules' => 'required|min_length[2]|is_unique[post_content.title,post_id,'.$id.']',
                'errors' => [
                    'required' => lang('Post.title_required'),
                    'min_length' => lang('Post.min_length'),
                    'is_unique'  => lang('Post.title_is_exist')
                ]
            ],
            'content' => [
                'rules' => 'required',
                'errors' => [
                    'required' => lang('Post.content_required')
                ]
            ],
        ];
        if ( isset($item->id) ) {
            //validate the input
            if (! $this->validate($rules))
            {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            } else if ( !$this->_model->checkTitle($postData['title']) ) return redirect()->back()->withInput()->with('errors', lang('Post.title_is_exist'));

            //good then save the item
            $item->fill($postData);

            //upload image first
            $image = $this->request->getFile('image');
            if ($image->getName()) {
                $this->editPostImage($postData, $image, $item);
            }

            if ( isset($postData['createSlug']) ) $item->slug = clean_url($postData['title']);
            if ( $postData['post_status'] === 'publish' ) $item->publish_date = date('Y-m-d H:i:s');
            else $item->publish_date = null;

            $item->updated_at = Time::now();
            if (! $this->_model->update( $id, $postData) ) {
                return redirect()->back()->withInput()->with('errors', $this->_model->errors());
            }

            // Update content
            model(PostContentModel::class)->update($item->ct_id, $postData);

            // save seo meta
            $postData['lang_id'] = $this->currentLang->id;
            $item->setSeoMeta($postData);

            //log Action
            $logData = [
                'title' => lang('Log.edit_page'),
                'description' => lang('Log.edit_page_desc', [$this->user->username, $item->slug]),
                'properties' => $item->toArray(),
                'subject_id' => $item->id,
                'subject_type' => PostModel::class,
            ];
            $this->logAction($logData);

            if ( isset($postData['save']) ) return redirect()->route('edit_page', [$item->id])->with('message', lang('Post.editSuccess', [$item->title]));
            else if ( isset($postData['save_exit']) ) return redirect()->route('page')->with('message', lang('Post.editSuccess', [$item->title]));
            else if ( isset($postData['save_addnew']) ) return redirect()->route('add_page')->with('message', lang('Post.editSuccess', [$item->title]));

        } else return redirect()->route('post')->with('error', lang('Acp.invalid_request'));
    }

    public function permanentDeletePage() {
        $postData = $this->request->getPost();
        if (!isset($postData['id']) || empty($postData['id'])) {
            return $this->response->setJSON([
                'error' => 1,
                'message' => lang('Acp.invalid_request')
            ]);
        }

        $item = $this->_model->withDeleted()->find($postData['id']);
        if (!isset($item->id) || empty($item)) {
            return $this->response->setJSON([
                'error' => 1,
                'message' => lang('Acp.no_item_found')
            ]);
        }

        if ((isset($item->user_init) && $item->user_init != $this->user->id) || !$this->user->inGroup('superadmin', 'admin')) {
            return $this->response->setJSON([
                'error' => 1,
                'message' => lang('Acp.no_permission')
            ]);
        }

        if ($this->_model->deletePost($item, true)) {
            if (method_exists(__CLASS__, 'logAction')) {
                $prop = method_exists(get_class($item), 'toArray') ? $item->toArray() : (array)$item;
                $logData = [
                    'title' => 'Permanent Delete Post',
                    'description' => lang('Acp.delete_success', [$item->id]),
                    'properties' => $prop,
                    'subject_id' => $item->id,
                    'subject_type' => get_class($this->_model),
                ];
                $this->logAction($logData);
            }
            return $this->response->setJSON([
                'error' => 0,
                'message' => lang('Acp.delete_success', [$item->id])
            ]);
        }

        return $this->response->setJSON([
            'error' => 1,
            'message' => lang('Acp.delete_fail')
        ]);
    }

    private function _validRules()
    {
        return [
            'title' => [
                'rules' => 'required|min_length[2]|is_unique[post_content.title]',
                'errors' => [
                    'required' => lang('Post.title_required'),
                    'min_length' => lang('Post.min_length'),
                    'is_unique'  => lang('Post.title_is_exist')
                ]
            ],
            'content' => [
                'rules' => 'required',
                'errors' => [
                    'required' => lang('Post.content_required')
                ]
            ],
        ];
    }
}