<?php

/**
 * @author brianha289
 */

namespace Modules\Acp\Controllers\Blog;

use Modules\Acp\Entities\MenuItem;
use Modules\Acp\Enums\PostTypeEnum;
use Modules\Acp\Models\Blog\MenuItemsModel;
use Modules\Acp\Models\Blog\MenuModel;
use Modules\Acp\Controllers\AcpController;
use Modules\Acp\Models\Blog\CategoryModel;
use Modules\Acp\Models\Blog\PostModel;
use Modules\Acp\Models\ConfigModel;
use Modules\Acp\Models\LangModel;
use Modules\Acp\Traits\deleteItem;
use Modules\Acp\Traits\SystemLog;

class MenuController extends AcpController
{
    use deleteItem;
    use SystemLog;

    protected $_menuItemsModel;
    protected $_categoryModel;
    protected $_postsModel;

    public function __construct()
    {
        parent::__construct();
        $this->_model = model(MenuModel::class);
        $this->_menuItemsModel = model(MenuItemsModel::class);
        $this->_categoryModel = model(CategoryModel::class);
        $this->_postsModel = model(PostModel::class);
    }

    public function index()
    {
        $this->_data['title'] = lang("Menu.menu_title");
        $this->_data['data'] = $this->_model
                            ->where('lang_id', $this->_data['curLang']->id)
                            ->findAll();
        $this->_render('\blog\menu\index', $this->_data);
    }

    public function edit($id)
    {
        $menuItem = $this->_model->find($id);
        $this->_data['title'] = lang("Menu.menu_title");
        if (empty($menuItem)) return redirect()->back()->with('error', lang('Acp.no_item_found'));

        // get categories by group
        $catList = [];
        if (isset($this->config->catGroup)) {
            foreach ($this->config->catGroup as $key => $val) {
                $catData = $this->_categoryModel->getCategories($key, $this->_data['curLang']->id);

                $catList[$key] = [
                    'title' => $val,
                    'data' => $catData
                ];
            }
        } else {
            $catList[] = [
                'title' => lang('Menu.category_list'),
                'data' => $this->_categoryModel
                    ->where('cat_status', 'publish')
                    ->findAll()
            ];
        }

        $this->_data['category_list'] = $catList;
        $this->_data['lstMenus'] = $this->_model->findAll();
        $this->_data['menuItem'] = $menuItem;
        $this->_data['menu_location'] = model(ConfigModel::class)->getMenuLocation();
        $this->_data['lstparentMenuItems'] = $this->_menuItemsModel
            ->where(['menu_id' => $menuItem->id ?? 0, 'parent_id' => 0])
            ->orderBy('order ASC')
            ->findAll();
        $this->_render('\blog\menu\edit', $this->_data);
    }


    /**
     * Add Menu Item
     * @param $catId
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function addMenuItem($catId)
    {
        $catData = $this->_categoryModel
            ->select('category.*, category_content.title, category_content.slug')
            ->join('category_content', 'category_content.cat_id = category.id')
            ->where('lang_id', $this->_data['curLang']->id)
            ->find($catId);
        if (!isset($catData->id)) return redirect()->back()->with('error', lang('Acp.invalid_request'));

        $key = $this->request->getGet('key');
        $menuData = $this->_model->where('slug', $key)->first();

        if (!isset($catData->id) || !isset($menuData->id)) return redirect()->route('menu')->with('error', lang('Acp.invalid_request'));
        else {
            if (!isset($key) || empty($key)) return redirect()->route('menu')->with('error', lang('Acp.invalid_request'));
            //check item in menu
            $menuCheck = $this->_menuItemsModel
                ->where('related_id', $catData->id)
                ->where('menu_id', $menuData->id)
                ->where('type', MenuItem::CAT_TYPE)
                ->first();
            if (isset($menuCheck->id)) return redirect()->back()->with('error', lang('Menu.title_is_exist'));

            $countall = $this->_menuItemsModel->where(['menu_id' => $menuData->id])->countAllResults();

            $menuOrder = $countall + 1;
            $insertArray = array(
                'user_init' => $this->user->id,
                'user_type' => $this->user->model_class,
                'menu_id' => $menuData->id,
                'related_id' => $catData->id,
                'type' => MenuItem::CAT_TYPE,
                'title' => $catData->title,
                'url' => $catData->slug,
                'order' => $menuOrder,
            );

            $menu = $this->_menuItemsModel->insert($insertArray);
            $item = $this->_menuItemsModel->find($menu);
            //log Action
            $logData = [
                'title' => 'Add MenuItem',
                'description' => "#{$this->user->username} đã thêm MenuItem #{$item->url}",
                'properties' => $item,
                'subject_id' => $item->id,
                'subject_type' => MenuItemsModel::class,
            ];
            $this->logAction($logData);

            if (!$menu) return redirect()->back("menu")->with('errors', $this->_model->errors());
            else return redirect()->back()->with('message', lang('Menu.addSuccess'));
        }
    }

    public function addPageItem($pageId)
    {
        $key = $this->request->getGet('key');
        $menuData = $this->_model->find($key);

        $pageData = model(PostModel::class)
            ->select('post.*, post_content.title')
            ->join('post_content', 'post_content.post_id = post.id')
            ->where('post_content.lang_id', $this->_data['curLang']->id)
            ->find($pageId);

        if (!isset($pageData->id) || !isset($menuData->id)) return redirect()->route('menu')->with('error', lang('Acp.invalid_request'));
        else {
            //check item in menu
            $menuCheck = $this->_menuItemsModel
                ->where('related_id', $pageData->id)
                ->where('menu_id', $menuData->id)
                ->where('type', MenuItem::PAGE_TYPE)
                ->first();
            if (isset($menuCheck->id)) return redirect()->back()->with('error', lang('Menu.title_is_exist'));

            $countall = $this->_menuItemsModel->where(['menu_id' => $menuData->id])->countAllResults();

            $menuOrder = $countall + 1;
            $insertArray = array(
                'user_init' => $this->user->id,
                'user_type' => $this->user->model_class,
                'menu_id' => $menuData->id,
                'related_id' => $pageData->id,
                'type' => MenuItem::PAGE_TYPE,
                'title' => $pageData->title,
                'url' => $pageData->url,
                'order' => $menuOrder,
            );

            $menu = $this->_menuItemsModel->insert($insertArray);
            //log Action
            $item = $this->_menuItemsModel->find($menu);
            $logData = [
                'title' => 'Add PageItem',
                'description' => "#{$this->user->username} đã thêm PageItem #{$item->url}",
                'properties' => $item,
                'subject_id' => $item->id,
                'subject_type' => MenuItemsModel::class,
            ];
            $this->logAction($logData);

            if (!$menu) return redirect()->back("menu")->with('errors', $this->_model->errors());
            else return redirect()->back()->with('message', lang('Menu.addSuccess'));
        }
    }

    /**
     * Save the current menu order
     */
    public function saveMenu()
    {
        $postData = $this->request->getPost();
        if ($postData) {
            if (!isset($postData['menuHierarchical']) || $postData['menuHierarchical'] === '') {
                return redirect()->back()->with('error', lang('Acp.invalid_request'));
            }
            //validate
            $validRules = [
                'menu_id' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Menu không hợp lệ'
                    ]
                ],
                'name' => [
                    'rules' => 'required|min_length[2]|is_unique[menu.name,id,'.$postData['menu_id'].']',
                    'errors' => [
                        'required' => lang('Menu.menu_required'),
                        'min_length' => lang('Menu.menu_min_length'),
                        'is_unique' => lang('Menu.menu_is_exist'),
                    ]
                ]
            ];
            if (!$this->validate($validRules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $menuItem = $this->_model->find($postData['menu_id']);
            if (!isset($menuItem->id) || empty($menuItem)) return redirect()->back()->with('error', lang('Menu.no_menu_select'));

            //update menu data
            $menuItem->name = $postData['name'];
            $menuItem->slug = clean_url($postData['name']);
            $menuItem->status = $postData['status'];
            if (isset($postData['location']) && !empty($postData['location'])) $menuItem->location = json_encode($postData['location']);
            $this->_model->update($menuItem->id, $menuItem);


            //update menu items order
            $menuHierarchical = json_decode($postData['menuHierarchical']); 
            if (!empty($menuHierarchical)) {
                foreach ($menuHierarchical as $index => $menu) {
                    if ( !empty($menu) && isset($menu->id) ) {
                        $updateData = array(
                            'order' => $index,
                            'parent_id' => 0
                        );
                        $this->_menuItemsModel->update($menu->id, $updateData);
                        if (isset($menu->children) && !empty($menu->children)) $this->updateChild($menu->children, $menu->id);
                    }                    
                }

                $mess = lang('Menu.update_success');
                $mess .=  " " . $menuItem->slug;
                return redirect()->to("/acp/menu?menu={$menuItem->slug}")->with('message', $mess);
            } else return redirect()->to("/acp/menu?key={$menuItem->slug}");
        } else return redirect()->route('menu');
    }

    /**
     * Update child menu
     * @param $menu
     * @param $parent
     * @throws \ReflectionException
     */
    private function updateChild($menu, $parent)
    {
        foreach ($menu as $index => $item) {
            $updateData = array(
                'order' => $index,
                'parent_id' => $parent
            );
            $this->_menuItemsModel->update($item->id, $updateData);
            if (isset($item->children) && !empty($item->children)) $this->updateChild($item->children, $item->id);
        }
    }

    /**
     * Remove a Menu item
     */
    public function removeItem($idItem)
    {
        $item = $this->_menuItemsModel->find($idItem);
        $key = $this->request->getGet('key');
        if (!isset($key) || $key === '') return redirect()->route('menu')->with('error', lang('Acp.invalid_request'));

        $menu = $this->_model->where('slug', $key)->first();
        if (!isset($menu->id)) return redirect()->route('menu')->with('error', lang('Acp.invalid_request'));

        if (isset($item->id)) {
            if ($this->_menuItemsModel->delete($item->id)) {
                //log Action
                $logData = [
                    'title' => 'Delete MenuItem',
                    'description' => "#{$this->user->username} đã xoá MenuItem #{$item->url}",
                    'properties' => $item,
                    'subject_id' => $item->id,
                    'subject_type' => MenuItemsModel::class,
                ];
                $this->logAction($logData);
                return redirect()->route('edit_menu', [$menu->id])->with('message', lang('Menu.delete_success', [$item->title]));
            } else return redirect()->route('menu')->with('error', lang('Acp.delete_fail'));
        } else return redirect()->route('menu')->with('error', lang('Acp.invalid_request'));
    }

    /**
     * AJAX
     */
    /**
     * List menu
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function ajxListMenu()
    {
        $response = [];
        $inputData = $this->request->getGet();

        if (isset($inputData['name']) && !empty($inputData['name'])) $this->_model->like('name', $inputData['name']);

        if (!isset($inputData['lang'])) $this->_model->where('lang_id', $this->_data['curLang']->id);
        else $this->_model->where('lang_id', $inputData['lang']->id);

        $menuData = $this->_model->findAll();

        foreach ($menuData as $item) {
            $date = date_create($item->created_at);
            $item->created = date_format($date, 'd/m/Y');
        }
        if (empty($menuData)) {
            $response = [
                'error' => 1,
                'message' => lang('Acp.no_item_found')
            ];
        } else {
            $response = [
                'error' => 0,
                'data' => $menuData
            ];
        }
        return $this->response->setJSON($response);
    }

    /**
     * Add new menu
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function addMenuAjx()
    {
        $response = [];
        //check permission
        if (!$this->user->inGroup('superadmin', 'admin')) {
            $response = [
                'error' => 1,
                'message' => lang('Acp.no_permission_request')
            ];
        } else {
            $postData = $this->request->getPost();
            $rules = [
                'name'   => [
                    'rules' => 'required|min_length[2]|is_unique[menu.name]',
                    'errors' => [
                        'required' => lang('Menu.menu_required'),
                        'min_length' => lang('Menu.menu_min_length'),
                        'is_unique' => lang('Menu.menu_is_exist'),
                    ]
                ],
            ];

            //validate the input
            if (!$this->validate($rules)) {
                $err = $this->validator->getErrors();
                $textReturn = '';
                foreach ($err as $mes) {
                    $textReturn .= $mes . '<br>';
                }
                $response['error'] = 1;
                $response['message'] = $textReturn;
            } else {
                if ( !isset($postData['lang_id']) || empty($postData['lang_id']) || $postData['lang_id'] == 0 ) {
                    $postData['lang_id'] = $this->_data['curLang']->id;
                }

                $postData['slug'] = clean_url($postData['name']);
                $postData['status'] = 'draft';
                $postData['user_init'] = $this->user->id;
                $postData['user_type'] = $this->user->model_class;
                if (!$id = $this->_model->insert($postData)) {
                    $response['error'] = 1;
                    $response['message'] = $this->_model->errors();
                } else {
                    $menuData = $this->_model->find($id);
                    //log Action
                    $logData = [
                        'title' => 'Add Menu',
                        'description' => "#{$this->user->username} đã thêm menu #{$menuData->slug}",
                        'properties' => $menuData,
                        'subject_id' => $menuData->id,
                        'subject_type' => MenuModel::class,
                    ];
                    $this->logAction($logData);
                    $date = date_create($menuData->created_at);
                    $menuData->created = date_format($date, 'd/m/Y');
                    $response['error'] = 0;
                    $response['message'] = 'Đã thêm thành công menu mới';
                    $response['newItem'] = $menuData;
                }
            }
        }

        return $this->response->setJSON($response);
    }

    public function editAjx($itemId = false)
    {
        $response = array();
        $postData = $this->request->getPost();

        if (!$itemId) {
            $response['error'] = 1;
            $response['text'] = lang('Acp.invalid_request');
        } else {
            //validate the data
            $rules = [
                'title'           => "required|min_length[3]",
            ];

            $errMess = [
                'title' => [
                    'required' => lang('Menu.title_required'),
                    'min_length' => lang('Menu.min_length'),
                ],
            ];

            if ($postData['cat_id'] == 0) {
                $rules['url'] = "required";
                $errMess['url'] = ['required' => lang('Menu.url_required')];
            }

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
                    'title' => $postData['title']
                );

                if ($postData['cat_id'] == 0) {
                    $updateArray['url'] = $postData['url'];
                }

                if ($this->_menuItemsModel->update($itemId, $updateArray)) {
                    $menuItemData = $this->_menuItemsModel->find($itemId);
                    //log Action
                    $logData = [
                        'title' => 'Edit Menu',
                        'description' => "#{$this->user->username} đã sửa menu #{$menuItemData->title}",
                        'properties' => $menuItemData,
                        'subject_id' => $menuItemData->id,
                        'subject_type' => MenuModel::class,
                    ];
                    $this->logAction($logData);
                    $response['error'] = 0;
                    $response['text'] = lang('Menu.update_success');
                } else {
                    $response['error'] = 1;
                    $response['text'] = $this->_model->errors();
                }
            }
        }

        echo (json_encode($response));
        exit();
    }

    /**
     * Add custom URL to menu item
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function addUrlAjx()
    {
        $postData = $this->request->getPost();

        $rules = [
            'title'           => [
                'rules' => "required|min_length[3]",
                'errors' => [
                    'required' => lang('Menu.title_required'),
                    'min_length' => lang('Menu.min_length'),
                ]
            ],
            'url'           => [
                'rules' => "required",
                'errors' => [
                    'required' => lang('Menu.url_required'),
                ]
            ],
            'key'           => [
                'rules' => "required",
                'errors' => [
                    'required' => lang('Menu.key_required'),
                ]
            ]
        ];

        //validate the input
        if (!$this->validate($rules)) {
            $err = $this->validator->getErrors();
            $textReturn = '';
            foreach ($err as $mes) {
                $textReturn .= $mes . '<br>';
            }
            return $this->response->setJSON(['error' => 1, 'message' => $textReturn]);
        }
        $menuData = $this->_model->find($postData['key']);
        if (!isset($menuData->id)) return $this->response->setJSON(['error' => 1, 'message' => lang('Menu.invalid_key')]);

        //good then prepare to insert new item
        $countall = $this->_menuItemsModel->where(['menu_id' => $menuData->id])->countAllResults();

        $menuOrder = $countall + 1;
        $insertArray = array(
            'user_init' => $this->user->id,
            'user_type' => $this->user->model_class,
            'menu_id' => $menuData->id,
            'parent_id' => $postData['parent_id'] ?? 0,
            'related_id' => 0,
            'type' => MenuItem::URL_TYPE,
            'title' => $postData['title'],
            'url' => $postData['url'],
            'target' => $postData['target'],
            'order' => $menuOrder,
        );

        if (!$this->_menuItemsModel->insert($insertArray)) {
            $err = $this->_model->errors();
            $textReturn = '';
            foreach ($err as $mes) {
                $textReturn .= $mes . '<br>';
            }
            return $this->response->setJSON(['error' => 1, 'message' => $textReturn]);
        } else return $this->response->setJSON(['error' => 0, 'message' => lang('Menu.addSuccess')]);
    }

    public function listPageMenuAjx()
    {
        $inputData = $this->request->getGet();

        if (isset($inputData['title']) && !empty($inputData['title'])) {
            $s = str_replace('-', '%', url_title($inputData['title']));
            $this->_postsModel->like('slug', $s);
        }
        //get page item
        $pages = $this->_postsModel
            ->select('post.*,post_content.*')
            ->join('post_content', 'post_content.post_id = post.id')
            ->where('post_type', PostTypeEnum::PAGE)
            ->where('post_status', 'publish')
            ->where('post_content.lang_id', $this->_data['curLang']->id)
            ->findAll();

        foreach ($pages as $item) {
            $checkCat = model(MenuItemsModel::class)->checkPageExistInMenu($item->id, $inputData['menu'] ?? 0);
            if ($checkCat == false) {
                $item->add_to_menu = 1;
                $item->add_menu_url = base_url("{$this->_data['module']}/menu/add-page-item/{$item->id}?key={$inputData['menu']}");
            } else $item->add_to_menu = 0;
        }

        if (count($pages) > 0) return $this->response->setJSON(['error' => 0, 'data' => $pages]);
        else return $this->response->setJSON(['error' => 1, 'message' => lang('Acp.no_item_found')]);
    }
}
