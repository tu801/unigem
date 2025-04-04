<?php

namespace Modules\Acp\Controllers\Blog;

use Modules\Acp\Controllers\AcpController;
use Modules\Acp\Models\Blog\CategoryContentModel;
use Modules\Acp\Models\Blog\CategoryModel;
use Modules\Acp\Traits\SystemLog;

class Category extends AcpController
{
    use SystemLog;

    public function __construct()
    {
        parent::__construct();
        if (empty($this->_model)) {
            $this->_model = model(CategoryModel::class);
        }
    }

    public function index($type)
    {
        // check permission
        switch($type) {
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

        $this->_data['data'] = $this->_model->join('category_content', 'category_content.cat_id = category.id')
            ->where([
                'lang_id'  => $this->_data['curLang']->id,
                'cat_type' => $type,
            ])->findAll();

        //get parent
        $this->_data['list_parent'] = $this->_model->join('category_content', 'category_content.cat_id = category.id')
            ->where([
                'lang_id' => $this->_data['curLang']->id,
                'parent_id' => 0,
                'cat_type' => $type,
            ])
            ->findAll();

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
        if($this->_model->checkSlug($insertData['slug'], $this->_data['curLang']->id)) {
            return redirect()->back()->withInput()->with('errors', [
                'slug' =>  lang('Category.slug_is_exist')
            ]);
        }

        $insertData['cat_type']     = $type;
        $insertData['user_init']    = $this->user->id;
        $insertData['user_type']    = $this->user->model_class;
        $insertData['lang_id']      = $this->_data['curLang']->id;
        $insertData['user_type']    = config('Auth')->userProvider;
        $cat = $this->_model->insertOrUpdate($insertData);
        if (!$cat) {
            return redirect()->back()->withInput()->with('errors', $this->_model->errors());
        }
        // Success!
        $catItem = $this->_model->join('category_content', 'category_content.cat_id = category.id', 'LEFT')
                                ->where('id', $cat)
                                ->where('lang_id', $this->_data['curLang']->id)
                                ->first();

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
            ->where('lang_id', $this->_data['curLang']->id)
            ->withDeleted()
            ->find($idItem);

        if (isset($cat->id)) {
            $this->_data['list_parent'] = $this->_model
                ->join('category_content', 'category_content.cat_id = category.id')
                ->where('lang_id', $this->_data['curLang']->id)
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
                             ->where('lang_id', $this->_data['curLang']->id)
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
            $inputData['cur_lang_id'] = $this->_data['curLang']->id;
            $inputData['id'] = $idItem;
            
            if ( isset($inputData['onChangeSlug']) && $inputData['onChangeSlug'] == 'on') {
                $inputData['slug'] = clean_url($inputData['title']);
                if($this->_model->checkSlug($inputData['slug'], $this->_data['curLang']->id)) {
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

            //set seo meta
            $inputData['lang_id'] = $this->_data['curLang']->id;
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
    public function remove($idItem)
    {
        $item = $this->_model->join('category_content', 'category_content.cat_id = category.id')
                                ->where('id', $idItem)
                                ->where('lang_id', $this->_data['curLang']->id)
                                ->withDeleted()
                                ->find($idItem);
        if (isset($item->id)) {
            //log Action
            $logData = [
                'title' => 'Delete Category',
                'description' => "#{$this->user->username} đã xoá category #{$item->slug}",
                'properties' => $item->toArray(),
                'subject_id' => $item->id,
                'subject_type' => CategoryModel::class,
            ];
            $this->logAction($logData);
            if ($this->_model->delete($item->id, (bool) $item->deleted_at)) return redirect()->route('category', ['post'])->with('message', lang('Category.delete_success', [$item->id]));

            else return redirect()->route('category', ['post'])->with('error', lang('Acp.delete_fail'));
        } else return redirect()->route('category', ['post'])->with('error', lang('Acp.invalid_request'));
    }

    //AJAX
    //list category for vuejs
    public function vuejsListCat($type)
    {
        $response = array();
        $input = $this->request->getGet();

        if (isset($input['s']) && !empty($input['s'])) {
            $key = esc($input['s']);
            $this->_model->like('title', $key);
        }

        $catSelect = 'category.id, category.cat_status, parent_id, title, slug, description, seo_meta';
        $this->_model->select($catSelect)
            ->join('category_content', 'category_content.cat_id = category.id')
            ->where('lang_id', $this->_data['curLang']->id)
            ->where('cat_type', $type ?? 'post');

        if (isset($input['deleted']) && $input['deleted'] == 1) $catData = $this->_model->onlyDeleted()->findAll();
        else $catData = $this->_model->findAll();

        if (isset($catData) && count($catData) > 0) {
            foreach ($catData as $cat) {
                if ($cat->parent_id > 0) {
                    $parent = $this->_model->select($catSelect)
                        ->join('category_content', 'category_content.cat_id = category.id')
                        ->where('lang_id', $this->_data['curLang']->id)
                        ->find($cat->parent_id);
                    $cat->parent = $parent;
                }
                $cat->value = $cat->id;
                $cat->label = $cat->title;
                $cat->status = $this->config->cmsStatus['status'][$cat->cat_status];
                
            }
            $response['data'] = $catData;
            $response['error'] = 0;
        } else {
            $response['error'] = 1;
            $response['message'] = (isset($input['s']) && !empty($input['s']))
                ? 'Danh mục bạn tìm kiếm không tồn tại'
                : 'Không tìm thấy danh mục! Vui lòng thêm danh mục mới';
        }
        return $this->response->setJSON($response);
    }

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
                $response['text'] = lang('Acp.invalid_request');
            } else {
                //validate the data
                $rules = [
                    'category_slug'           => "required|min_length[3]|is_unique[category_content.slug,cat_id,{$itemId}]",
                ];

                $errMess = [
                    'category_slug' => [
                        'required' => lang('Category.slug_required'),
                        'min_length' => lang('Category.slug_min_length'),
                        'is_unique' => lang('Category.slug_is_exist')
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
                        'slug' => $postData['category_slug']
                    );
                    $modelCategoryContent = model(CategoryContentModel::class);
                    $modelCategoryContent->where('category_content.lang_id', $this->_data['curLang']->id)
                        ->where('cat_id', $itemId);

                    if ($modelCategoryContent->update(null, $updateArray)) {
                        $this->_model->join('category_content', 'category_content.cat_id = category.id');
                        $item = $this->_model->find($itemId);
                        $response['error'] = 0;
                        $response['text'] = lang('Category.updateSlug_success');
                        $returnData['categoryId'] = $itemId;
                        $returnData['slug'] = $postData['category_slug'];
                        $returnData['fullSlug'] = base_url(route_to('category_list', $item->slug, $item->id));
                        $response['postData'] = $returnData;
                    } else {
                        $response['error'] = 1;
                        $response['text'] = $this->_model->errors();
                    }
                }
            }
        }

        return $this->response->setJSON($response);
    }
}
