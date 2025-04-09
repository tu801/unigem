<?php

/**
 * @author tmtuan
 * created Date: 28-Jul-21
 */

namespace Modules\Acp\Controllers\Blog;

use Modules\Acp\Controllers\AcpController;
use Modules\Acp\Controllers\Traits\PostImage;
use Modules\Acp\Enums\PostTypeEnum;
use Modules\Acp\Models\Blog\PostContentModel;
use Modules\Acp\Models\Blog\PostModel;
use Modules\Acp\Traits\deleteItem;
use Modules\Acp\Traits\SystemLog;

class Post extends AcpController
{
    use SystemLog;
    use deleteItem;
    use PostImage;

    public function __construct()
    {
        parent::__construct();
        if (empty($this->_model)) {
            $this->_model = model(PostModel::class);
        }
    }

    public function index()
    {
        $this->_data['title'] = lang("Post.post_title");
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

        $this->_model->where('post_type', 'post');

        if (isset($getData['search'])) {
            if (isset($getData['title']) && $getData['title'] !== '') {
                $this->_model->like('title', $getData['title']);
                $this->_data['search_title'] = $getData['title'];
            }
        }
        if (isset($getData['mdelete'])) {
            if (isset($getData['sel']) && !empty($getData['sel'])) {
                $this->_model->delete($getData['sel']);
            }
        }
        if (isset($getData['category']) && $getData['category'] > 0) {
            $this->_model->join('post_categories', "post_categories.post_id = post.id")
                        ->join('category_content', "category_content.cat_id = {$getData['category']}")
                        ->where('category_content.lang_id', $this->_data['curLang']->id)
                        ->where("post_categories.cat_id = {$getData['category']}");
            $this->_data['select_cat'] = $getData['category'];
        }
        if (isset($getData['post_status']) && $getData['post_status'] !== '') {
            $this->_model->like('post_status', $getData['post_status']);
            $this->_data['post_status'] = $getData['post_status'];
        }

        //get Data
        $this->_model
            ->select('post.*,post_content.*')
            ->join('post_content', 'post_content.post_id = post.id')
            ->where('post_content.lang_id', $this->_data['curLang']->id)
            ->where('post_type', PostTypeEnum::POST)
            ->orderBy('post.id DESC');

        $this->_data['data'] = $this->_model->paginate();
        $this->_data['pager'] = $this->_model->pager;

        $this->_render('\blog\post\index', $this->_data);
    }

    /**
     * Display Add Post Page
     */
    public function add()
    { //$this->cachePage(30);
        $this->_data['title'] = lang('Post.title_add');

        //$this->_data['modelCategory'] = $this->_modelCategory;
        $this->_render('\blog\post\add', $this->_data);
    }

    /**
     * Attempt to add a new post.
     */
    public function addAction()
    {
        $postData = $this->request->getPost();
        // Validate here first, since some things wrong
        $rules = [
            'title'          => 'required|min_length[3]|is_unique[post_content.title]',
            'categories'    => 'required',
            'content'       => 'required',
        ];
        $errMess = [
            'title' => [
                'required' => lang('Post.title_required'),
                'min_length' => lang('Post.min_length'),
                'is_unique' => lang('Post.title_is_exist')
            ],
            'categories' => [
                'required' => lang('Post.cat_required'),
            ],
            'content' => [
                'required' => lang('Post.content_required')
            ],
        ];

        //validate the input
        if (!$this->validate($rules, $errMess)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $slug = clean_url($postData['title']);
        if ($this->_model->checkSlug($slug, $this->_data['curLang']->id)) {
            return redirect()->back()->withInput()->with('errors', [
                'title' =>  lang('Post.slug_can_not_create')
            ]);
        }

        //good then save the new post
        $postData['slug'] = $slug;
        $newPost = new \Modules\Acp\Entities\Post($postData);

        if ($newPost->post_status === 'publish') $newPost->publish_date = date('Y-m-d H:i:s');
        if (isset($postData['tagcloud']) && !empty($postData['tagcloud']))  $newPost->tags = json_encode($postData['tagcloud']);
        //echo "<pre>"; print_r($insertData);exit;
        //upload image first
        $image   = $this->request->getFile('image');
        if ($image->getName()) {
            $response = $this->uploadPostImage($postData, $image);
            if ( $response instanceof RedirectResponse) return $response;
            $newPost->image = $response;
        }

        if (!$this->_model->addPost($newPost) ) {
            return redirect()->back()->withInput()->with('errors', $this->_model->errors());
        }

        // Success!
        $item = $this->_model->join('post_content', 'post_content.post_id = post.id', 'LEFT')
            ->where('slug', $newPost->slug)->where('lang_id', $this->_data['curLang']->id)->first();
        //add seo meta
        $postData['lang_id'] = $this->_data['curLang']->id;
        $item->setSeoMeta($postData);
        //add category
        foreach ($postData['categories'] as $catId) {
            if ($catId > 0) {
                // $this->_model->addCategories($item->id, $catId);
                if ($catId == $postData['cat_is_primary']) $this->_model->addCategories($item->id, $catId, 1);
                else $this->_model->addCategories($item->id, $catId);
            }
        }

        //log Action
        $logData = [
            'title' => 'Add Post',
            'description' => "#{$this->user->username} đã thêm post #{$item->slug}",
            'properties' => $item->toArray(),
            'subject_id' => $item->id,
            'subject_type' => PostModel::class,
        ];
        $this->logAction($logData);

        if (isset($postData['save'])) return redirect()->route('edit_post', [$item->id])->with('message', lang('Post.addSuccess', [$item->title]));
        else if (isset($postData['save_exit'])) return redirect()->route('post')->with('message', lang('Post.addSuccess', [$item->title]));
        else if (isset($postData['save_addnew'])) return redirect()->route('add_post')->with('message', lang('Post.addSuccess', [$item->title]));
    }

    /**
     * Display Edit Post Page
     */
    public function edit($itemID)
    {
        $this->_data['title'] = lang('Post.edit_title');
        $this->_model->join('post_content', 'post_content.post_id = post.id')
                     ->where('post_content.lang_id', $this->_data['curLang']->id);
        $item = $this->_model->find($itemID);
        if (isset($item->id)) {
            // $this->_model->convertData($item);
            $this->_data['itemData'] =  $item;
            // $this->_data['modelCategory'] = $this->_modelCategory;
            $this->_render('\blog\post\edit', $this->_data);
        } else {
            return redirect()->route('post')->with('error', lang('Post.no_item_found'));
        }
    }

    /**
     * Attempt to edit a post.
     */
    public function editAction($itemID = 0)
    {
        $this->_data['title'] = lang('Post.edit_title');
        $item = $this->_model
            ->join('post_content', 'post_content.post_id = post.id')
            ->where('post_content.lang_id', $this->_data['curLang']->id)
            ->find($itemID);

        if ($this->user->id !== $item->user_id) {
            if (!$this->user->inGroup('admin', 'superadmin', 'content_manager')) {
                return redirect()->route('post')->with('error', lang('Post.you_can_not_edit'));
            }
        }

        if (isset($item->id)) {
            $postData = $this->request->getPost();
            //validate the data
            $rules   = [
                'title'      => "required|min_length[3]|is_unique[post_content.title,post_id,{$item->id}]",
                'categories' => 'required',
                'content'    => 'required',
            ];
            $errMess = [
                'title'      => [
                    'required'   => lang('Post.title_required'),
                    'min_length' => lang('Post.min_length'),
                    'is_unique'  => lang('Post.title_is_exist')
                ],
                'categories' => [
                    'required' => lang('Post.cat_required'),
                ],
                'content'    => [
                    'required' => lang('Post.content_required')
                ],
            ];

            //validate the input
            if (!$this->validate($rules, $errMess)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
            //print_r($postData);exit;

            //good then save the item
            //upload image first
            $image = $this->request->getFile('image');
            if ($image->getName()) {
                $this->editPostImage($postData, $image, $item);
            }

            if (isset($postData['createSlug'])) {
                $postData['slug'] = clean_url($postData['title']);

                if ($this->_model->checkSlug($postData['slug'], $this->_data['curLang']->id)) {
                    return redirect()->back()->withInput()->with('errors', [
                        'title' => lang('Post.slug_can_not_create')
                    ]);
                }

            }

            if (isset($postData['tagcloud']) && !empty($postData['tagcloud']))  $postData['tags'] = json_encode($postData['tagcloud']);
            if ($postData['post_status'] === 'publish') $postData['publish_date'] = date('Y-m-d H:i:s');
            else $postData['publish_date'] = '';

            if (!$this->_model->update($itemID, $postData)) {
                return redirect()->back()->withInput()->with('errors', $this->_model->errors());
            }
            $modelPostContent = model(PostContentModel::class);
            $modelPostContent->where('post_id', $itemID)
                    ->where('post_content.lang_id', $this->_data['curLang']->id)
                    ->update(null, $postData);

            // Success!
            //update category
            $this->_model->removeCatById($item->id);
            foreach ($postData['categories'] as $cat) {
                if ($cat == $postData['cat_is_primary']) $this->_model->addCategories($item->id, $cat, 1);
                else $this->_model->addCategories($item->id, $cat);
            }
            $postData['lang_id'] = $this->_data['curLang']->id;
            $item->setSeoMeta($postData);

            //log Action
            $logData = [
                'title' => 'Edit Post',
                'description' => "#{$this->user->username} đã sửa post #{$item->slug}",
                'properties' => $item->toArray(),
                'subject_id' => $item->id,
                'subject_type' => PostModel::class,
            ];
            $this->logAction($logData);

            if (isset($postData['save'])) return redirect()->route('edit_post', [$item->id])->with('message', lang('Post.editSuccess', [$postData['title']]));
            else if (isset($postData['save_exit'])) return redirect()->route('post')->with('message', lang('Post.editSuccess', [$postData['title']]));
            else if (isset($postData['save_addnew'])) return redirect()->route('add_post')->with('message', lang('Post.editSuccess', [$postData['title']]));
        } else return redirect()->route('post')->with('error', lang('Acp.invalid_request'));
    }

    /**
     * return post upload image validate rule
     * @return array
     */
    private function getPostUploadRule()
    {
        $imgRules = [
            'image' => [
                'uploaded[image]',
                'mime_in['.$this->config->sys['default_mime_type'].']',
                'max_size[image,'.$this->maxSizeImage.']',
            ],
        ];
        $imgErrMess = [
            'image' => [
                'mime_in' => lang('Acp.invalid_image_file_type'),
                'max_size' => lang('Acp.image_to_large'),
            ]
        ];
        return ['validRules' => $imgRules, 'errMess' => $imgErrMess];
    }


    //AJAX FUNCTIONS

    /**
     * edit slug
     * @param bool $itemId
     * @throws \ReflectionException
     */
    public function ajxEditSlug($itemId = false)
    {
        $response = array();
        $postData = $this->request->getPost();

        if (!$itemId) {
            $response['error'] = 1;
            $response['text'] = lang('Acp.invalid_request');
        } else {
            $item = $this->_model->find($itemId);

            if (!isset($item->id) || $item->id < 0) {
                $response['error'] = 1;
                $response['text'] = lang('Post.no_item_found');
            } else {
                //validate the data
                $rules = [
                    'post_slug'           => "required|min_length[3]|is_unique[post_content.slug,post_id,{$itemId}]",
                ];

                $errMess = [
                    'post_slug' => [
                        'required' => lang('Post.slug_required'),
                        'min_length' => lang('Post.slug_min_length'),
                        'is_unique' => lang('Post.slug_is_exist')
                    ],
                ];
                if (!$this->validate($rules, $errMess)) {
                    $err = $this->validator->getErrors();
                    $textReturn = '';
                    foreach ($err as $mes) {
                        $textReturn .= $mes . '<br>';
                    }
                    $response['error'] = 1;
                    $response['text'] = $textReturn;
                } else {
                    $updateArray = array(
                        'slug' => $postData['post_slug']
                    );
                    $modelPostContent = model(PostContentModel::class);
                    $modelPostContent->where('post_content.lang_id', $this->_data['curLang']->id)
                                     ->where('post_id', $itemId);
                    if ($modelPostContent->update(null, $updateArray)) {
                        $this->_model->join('post_content', 'post_content.post_id = post.id');
                        $item = $this->_model->find($itemId);
                        $response['error'] = 0;
                        $response['text'] = lang('Post.updateSlug_success');
                        $returnData['postId'] = $itemId;
                        $returnData['slug'] = $postData['post_slug'];
                        $returnData['fullSlug'] = $item->url;
                        $response['postData'] = $returnData;
                    } else {
                        $response['error'] = 1;
                        $response['text'] = $this->_model->errors();
                    }
                }
            }
        }

        echo (json_encode($response));
        exit();
    }

    /**
     * update post status to publish
     * @param $itemId
     * @throws \ReflectionException
     */
    public function ajxreview($itemId)
    {
        $response = array();

        if (!$itemId) {
            $response['error'] = 1;
            $response['text'] = lang('Acp.invalid_request');
        } else {
            $item = $this->_model->find($itemId);
            if (!isset($item->id) || $item->id < 0) {
                $response['error'] = 1;
                $response['text'] = lang('Post.no_item_found');
            } else {
                $update['post_status'] = 'publish';
                $update['publish_date'] = date('Y-m-d H:i:s');
                if (!$this->_model->update($itemId, $update)) {
                    $response['error'] = 1;
                    $response['text'] = $this->_model->errors();
                } else {
                    $response['error'] = 0;
                    $response['text'] = lang('Post.publish_post_success', [$item->title]);
                }
            }
        }
        echo (json_encode($response));
        exit();
    }


    public function ajxGetPost($id)
    {
        $response = array();

        if (!$id) {
            $response['error'] = 1;
            $response['text'] = lang('Acp.invalid_request');
        } else {
            $item = $this->_model->find($id);
            if (!isset($item->id) || $item->id < 0) {
                $response['error'] = 1;
                $response['text'] = lang('Post.no_item_found');
            } else {
                $item->catData = $item->categories;
                $response['error'] = 0;
                $response['post'] = $item;
            }
        }
        return $this->response->setJSON($response);
    }
}
