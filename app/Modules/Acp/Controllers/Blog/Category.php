<?php

namespace Modules\Acp\Controllers\Blog;

use App\Enums\CacheKeys;
use App\Enums\CategoryEnum;
use App\Models\AttachMetaModel;
use Modules\Acp\Controllers\AcpController;
use App\Models\Blog\CategoryContentModel;
use Modules\Acp\Models\Blog\CategoryModel;

class Category extends AcpController
{
    protected $attachMetaModel;

    public function __construct()
    {
        parent::__construct();
        if (empty($this->_model)) {
            $this->_model = model(CategoryModel::class);
        }
        $this->attachMetaModel = model(AttachMetaModel::class);
    }

    public function index($type)
    {
        // check permission
        switch ($type) {
            case 'product':
                if (!$this->user->inGroup('superadmin', 'admin', 'sale_manager')) return redirect()->route('dashboard')->with('error', lang('Acp.no_permission'));
                break;
            case 'post':
                if (!$this->user->inGroup('superadmin', 'admin', 'content_manager')) return redirect()->route('dashboard')->with('error', lang('Acp.no_permission'));
                break;
        }

        $this->_data['title'] = lang("Category.category_title");
        $postData = $this->request->getPost();
        $getData = $this->request->getGet();

        //get parent category
        $this->_data['list_parent'] = $this->_model->join('category_content', 'category_content.cat_id = category.id')
            ->where([
                'lang_id' => $this->currentLang->id,
                'parent_id' => 0,
                'cat_type' => $type,
            ])
            ->findAll();

        if (!isset($type) || !in_array($type, $this->config->cat_type)) return redirect()->route('dashboard')->with('error', lang('Acp.invalid_request'));
        //get Data
        if (isset($postData['search'])) {
            if (isset($postData['title']) && $postData['title'] !== '') {
                $this->_model->like('title', $postData['title']);
                $this->_data['search_title'] = $postData['title'];
            }
        }

        if (isset($postData['act_submit'])) {
            if (isset($postData['action']) && $postData['action'] == 'mdelete') {
                if ($this->_model->delete($postData['sel'])) return redirect()->route('category')->with('message', lang('Acp.delete_success'));
                else return redirect()->route('category')->with('error', lang('Acp.delete_fail'));
            } else return redirect()->route('category');
        }

        if (isset($getData['deleted']) && $getData['deleted'] == 1) {
            $this->_model->onlyDeleted();
            $this->_data['action'] = 'deleted';
        } else $this->_data['action'] = 'all';

        // $this->_data['data'] = $this->_model->join('category_content', 'category_content.cat_id = category.id')
        //     ->where([
        //         'lang_id'  => $this->currentLang->id,
        //         'cat_type' => $type,
        //     ])->get();


        $this->_data['cat_type'] = $type;
        $this->_render('\blog\category\index', $this->_data);
    }

    /**
     * Display Add Category Page
     */
    public function add($type)
    {
        $this->_data['title'] = lang('Category.title_add');

        if (!isset($type) || !in_array($type, $this->config->cat_type)) return redirect()->route('dashboard')->with('error', lang('Acp.invalid_request'));

        $this->_data['list_category'] = $this->_model->findAll();
        $this->_render('\blog\category\add', $this->_data);
    }

    /**
     * Attempt to add a new Category.
     */
    public function addAction($type)
    {
        if (!isset($type) || !in_array($type, $this->config->cat_type)) return redirect()->route('dashboard')->with('error', lang('Acp.invalid_request'));

        $insertData = $this->request->getPost();
        // Validate here first, since some things,
        $rules = [
            'title'           => 'required|min_length[3]|is_unique[category_content.title]',
        ];

        $errMess = [
            'title' => [
                'required' => lang('Category.title_required'),
                'min_length' => lang('Category.min_length'),
                'is_unique' => lang('Category.title_is_exist')
            ],
        ];

        if (isset($insertData['slug']) && $insertData['slug'] !== '') {
            $rules['slug'] = 'is_unique[category_content.slug]|alpha_dash';
            $errMess['slug'] = [
                'is_unique' => lang('Category.slug_is_exist'),
                'alpha_dash' => lang('Category.slug_invalid')
            ];
        }

        //validate the input
        if (!$this->validate($rules, $errMess)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        //good then save the new category
        if (!isset($insertData['slug']) || $insertData['slug'] === '') $insertData['slug'] = clean_url($insertData['title']);

        // check slug
        if ($this->_model->checkSlug($insertData['slug'], $this->currentLang->id)) {
            return redirect()->back()->withInput()->with('errors', [
                'slug' =>  lang('Category.slug_is_exist')
            ]);
        }

        $insertData['cat_type']     = $type;
        $insertData['user_init']    = $this->user->id;
        $insertData['user_type']    = $this->user->model_class;
        $insertData['lang_id']      = $this->currentLang->id;
        $insertData['user_type']    = config('Auth')->userProvider;
        $cat = $this->_model->insertOrUpdate($insertData);
        if (!$cat) {
            return redirect()->back()->withInput()->with('errors', $this->_model->errors());
        }
        // Success!
        $catItem = $this->_model->join('category_content', 'category_content.cat_id = category.id', 'LEFT')
            ->where('id', $cat)
            ->where('lang_id', $this->currentLang->id)
            ->first();

        // save attach meta image
        if (!empty($insertData['cat_image'])) {
            $metaAttach = model(AttachMetaModel::class);
            $value      = [
                'image' => $insertData['cat_image'] ?? '',
            ];
            $configKey  = CategoryEnum::CAT_ATTACHMENT_PREFIX_KEY . $this->currentLang->locale;

            $metaAttach->insertOrUpdate([
                'mod_name' => $configKey,
                'mod_id'   => $catItem->id,
                'mod_type' => 'single',
                'value'    => json_encode($value),
            ]);
        }

        $catItem->setSeoMeta($insertData);

        //log Action
        $logData = [
            'title' => 'Add Category',
            'description' => "#{$this->user->username} đã thêm category #{$catItem->slug}",
            'properties' => $catItem->toArray(),
            'subject_id' => $catItem->id,
            'subject_type' => CategoryModel::class,
        ];
        $this->logAction($logData);

        return redirect()->to(base_url($this->_data['module'] . "/category/{$type}"))
            ->with('message', lang('Category.addSuccess'));
    }

    /**
     * Display Edit Category Page
     */
    public function edit($idItem)
    {
        $this->_data['title'] = lang('Category.edit_title');
        $cat = $this->_model
            ->join('category_content', 'category_content.cat_id = category.id')
            ->where('lang_id', $this->currentLang->id)
            ->withDeleted()
            ->find($idItem);

        if (isset($cat->id)) {
            $this->_data['list_parent'] = $this->_model
                ->join('category_content', 'category_content.cat_id = category.id')
                ->where('lang_id', $this->currentLang->id)
                ->where(['parent_id' => 0, 'category.cat_type' => $cat->cat_type])
                ->findAll();
            $this->_data['data'] = $cat;
            $this->_data['cat_type'] = $cat->cat_type;
            $this->_render('\blog\category\edit', $this->_data);
        } else {
            return redirect()->route('category', ['post'])->with('error', lang('Category.invalid_request'));
        }
    }

    /**
     * Attempt to edit a category.
     */
    public function editAction($idItem = 0)
    {
        $this->_data['title'] = lang('Category.edit_title');

        $data = $this->_model->join('category_content', 'category_content.cat_id = category.id')
            ->where('id', $idItem)
            ->where('lang_id', $this->currentLang->id)
            ->first();
        $this->_model->skipValidation(true);

        if (isset($data->id)) {
            $inputData = $this->request->getPost();
            //validate the data
            $rules = [
                'title'           => "required|min_length[3]|is_unique[category_content.title,cat_id,{$data->id}]",
            ];

            $errMess = [
                'title' => [
                    'required' => lang('Category.title_required'),
                    'min_length' => lang('Category.min_length'),
                    'is_unique' => lang('Category.title_is_exist')
                ],
            ];

            if (isset($inputData['slug']) && $inputData['slug'] !== '') {
                $rules['slug'] = "is_unique[category_content.slug,cat_id,{$data->id}]";
                $errMess['slug'] = ['is_unique' => lang('Category.slug_is_exist')];
            }

            //validate the input
            if (!$this->validate($rules, $errMess)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
            if (!isset($inputData['home_tab_display'])) $inputData['home_tab_display'] = 0;
            $inputData['cur_lang_id'] = $this->currentLang->id;
            $inputData['id'] = $idItem;

            if (isset($inputData['onChangeSlug']) && $inputData['onChangeSlug'] == 'on') {
                $inputData['slug'] = clean_url($inputData['title']);
                if ($this->_model->checkSlug($inputData['slug'], $this->currentLang->id)) {
                    return redirect()->back()->withInput()->with('errors', [
                        'slug' =>  lang('Category.slug_is_exist')
                    ]);
                }
            }

            $cat = $this->_model->insertOrUpdate($inputData);
            //good then save the new item
            if (!$cat) {
                return redirect()->back()->withInput()->with('errors', $this->_model->errors());
            }

            // save attach meta image
            $value = [
                'image'         => $inputData['cat_image'] ?? '',
            ];
            $configKey  = CategoryEnum::CAT_ATTACHMENT_PREFIX_KEY . $this->currentLang->locale;

            $dataAttach = [
                'mod_name' => $configKey,
                'mod_id'   => $idItem,
                'mod_type' => 'single',
                'value'    => json_encode($value),
            ];
            if (!empty($inputData['attach_meta_id'])) {
                $dataAttach['id'] = $inputData['attach_meta_id'];
            }

            $this->attachMetaModel->insertOrUpdate($dataAttach);

            //set seo meta
            $inputData['lang_id'] = $this->currentLang->id;
            $data->setSeoMeta($inputData);

            //log Action
            $logData = [
                'title' => 'Edit Category',
                'description' => "#{$this->user->username} đã sửa category #{$data->slug}",
                'properties' => $data->toArray(),
                'subject_id' => $data->id,
                'subject_type' => CategoryModel::class,
            ];
            $this->logAction($logData);

            // delete cache
            $this->_removeCache();

            if (isset($inputData['save'])) return redirect()->route('edit_category', [$data->id])->with('message', lang('Acp.edit_Success'));
            else if (isset($inputData['save_exit'])) {
                $route = base_url($this->_data['module'] . "/category/{$data->cat_type}");
                return redirect()->to($route)->with('message', lang('Acp.edit_Success'));
            }
        } else return redirect()->route('category', ['post'])->with('error', lang('Acp.invalid_request'));
    }

    /**
     * Remove a category
     */
    public function removeCat($idItem)
    {
        $cat = $this->_model
            ->join('category_content', 'category_content.cat_id = category.id')
            ->where('id', $idItem)
            ->where('lang_id', $this->currentLang->id)
            ->first();

        if (empty($cat) || !isset($cat->id)) {
            return redirect()->route('category', ['post'])->with('error', lang('Acp.no_item_found'));
        }

        // Kiểm tra nếu là category cha
        if ($this->_model->where('parent_id', $cat->id)->countAllResults() > 0) {
            return redirect()->route('category', ['post'])->with('error', lang('Category.error_delete_cat_has_child'));
        }

        if ($this->_model->delete($cat->id, (bool) $cat->deleted_at)) {
            // Ghi log
            $this->logAction([
                'title' => 'Remove Category',
                'description' => "#{$this->user->username} đã xóa category #{$cat->slug}",
                'properties' => $cat->toArray(),
                'subject_id' => $cat->id,
                'subject_type' => CategoryModel::class,
            ]);
            $this->_removeCache();
            return redirect()->back()->with('message', lang('Category.delete_success', [$cat->id]));
        }

        return redirect()->route('category', ['post'])->with('error', lang('Acp.delete_fail'));
    }

    private function _removeCache()
    {
        cache()->deleteMatching(CacheKeys::CATEGORY_PREFIX . '*');
        cache()->deleteMatching(CacheKeys::MENU_PREFIX . '*');
    }

    public function recoverCat($catId)
    {
        $cat = $this->_model
            ->where('id', $catId)
            ->onlyDeleted()
            ->first();

        if (empty($cat) || !isset($cat->id)) {
            return redirect()->route('category', ['post'])->with('error', lang('Acp.no_item_found'));
        }
        if (!$this->_model->recover($cat->id)) {
            return redirect()->back()->with('error', lang('Acp.recover_fail'));
        }

        $prop = method_exists(get_class($cat), 'toArray') ? $cat->toArray() : (array)$cat;
        $logData = [
            'title' => 'Recover Category',
            'description' => lang('Acp.recover_success', [$cat->id]),
            'properties' => $prop,
            'subject_id' => $cat->id,
            'subject_type' => get_class($this->_model),
        ];
        $this->logAction($logData);
        return redirect()->back()->with('message', lang('Acp.recover_success', [$cat->id]));
    }

    //AJAX
    //list category for vuejs
    public function vuejsListCat($type)
    {
        $input = $this->request->getGet();
        $isRootAdmin = $this->user->inGroup('superadmin');
        $searchKey = isset($input['s']) ? esc($input['s']) : null;
        $isDeleted = isset($input['deleted']) && $input['deleted'] == 1;

        $catSelect = 'category.id, category.cat_status, parent_id, title, slug, description, seo_meta';
        $this->_model->select($catSelect)
            ->join('category_content', 'category_content.cat_id = category.id')
            ->where('lang_id', $this->currentLang->id)
            ->where('cat_type', $type ?? 'post');

        if ($searchKey) {
            $this->_model->like('title', $searchKey);
        }

        $catData = $isDeleted ? $this->_model->onlyDeleted()->findAll() : $this->_model->findAll();

        if (!empty($catData)) {
            foreach ($catData as $cat) {
                $cat = $this->attachParentToCategory($cat, $catSelect);
                $cat->value = $cat->id;
                $cat->label = $cat->title;
                $cat->status = $this->__transformStatusView($cat->cat_status);
            }
            $response = [
                'data' => $catData,
                'isRootAdmin' => $isRootAdmin,
                'error' => 0,
            ];
        } else {
            $response = [
                'error' => 1,
                'message' => $searchKey
                    ? lang('Category.cat_not_found_search')
                    : lang('Category.cat_not_found'),
            ];
        }

        return $this->response->setJSON($response);
    }

    /**
     * Gắn thông tin parent cho category nếu có
     */
    private function attachParentToCategory($cat, $catSelect)
    {
        if ($cat->parent_id > 0) {
            $parent = $this->_model->select($catSelect)
                ->join('category_content', 'category_content.cat_id = category.id')
                ->where('lang_id', $this->currentLang->id)
                ->find($cat->parent_id);
            if (!empty($parent) && isset($parent->id)) {
                $cat->parent = $parent;
            } else {
                $cat->parent_not_found_message = lang('Category.cat_parent_not_found');
            }
        }
        return $cat;
    }

    public function ajxEditSlug($itemId = false)
    {
        $response = ['error' => 1, 'text' => lang('Acp.invalid_request')];
        $postData = $this->request->getPost();

        if (!$itemId) {
            return $this->response->setJSON($response);
        }

        $item = $this->_model->find($itemId);
        if (empty($item) || !isset($item->id) || $item->id < 0) {
            return $this->response->setJSON($response);
        }

        // Validate input
        $rules = [
            'category_slug' => "required|min_length[3]|is_unique[category_content.slug,cat_id,{$itemId}]",
        ];
        $errMess = [
            'category_slug' => [
                'required' => lang('Category.slug_required'),
                'min_length' => lang('Category.slug_min_length'),
                'is_unique' => lang('Category.slug_is_exist')
            ],
        ];

        if (!$this->validate($rules, $errMess)) {
            $errors = $this->validator->getErrors();
            $response['text'] = implode('<br>', $errors);
            return $this->response->setJSON($response);
        }

        // Update slug
        $updateArray = ['slug' => $postData['category_slug']];
        $modelCategoryContent = model(CategoryContentModel::class);
        $modelCategoryContent->where('category_content.lang_id', $this->currentLang->id)
            ->where('cat_id', $itemId);

        if ($modelCategoryContent->update(null, $updateArray)) {
            // Xoá cache
            $this->_removeCache();

            $this->_model->join('category_content', 'category_content.cat_id = category.id');
            $item = $this->_model->find($itemId);

            $response = [
                'error' => 0,
                'text' => lang('Category.updateSlug_success'),
                'postData' => [
                    'categoryId' => $itemId,
                    'slug' => $postData['category_slug'],
                    'fullSlug' => $item->url ?? '',
                ],
            ];
        } else {
            $response['text'] = $this->_model->errors();
        }

        return $this->response->setJSON($response);
    }

    private function __transformStatusView($cat_status)
    {
        switch ($cat_status) {
            case 'draft':
                return '<span class="badge badge-secondary">' . $this->config->cmsStatus['status'][$cat_status] . '</span>';
                break;
            case 'pending':
                return '<span class="badge badge-warning">' . $this->config->cmsStatus['status'][$cat_status] . '</span>';
                break;
            case 'publish':
                return '<span class="badge badge-success">' . $this->config->cmsStatus['status'][$cat_status] . '</span>';
                break;
        }
    }
}