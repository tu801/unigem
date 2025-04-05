<?php

namespace Modules\Acp\Controllers\System\Config;

use Modules\Acp\Controllers\AcpController;
use Modules\Acp\Models\AttachModel;
use Modules\Acp\Models\ConfigModel;
use Modules\Acp\Models\PostModel;
use Modules\Acp\Traits\SystemLog;

class Config extends AcpController
{
    use SystemLog;
    public function __construct()
    {
        parent::__construct();
        if (empty($this->_model)) {
            $this->_model = model(ConfigModel::class);
        }
        $this->_modelPost = model(PostModel::class);
    }

    public function index()
    {
        $this->_data['title']= lang("Config.config_title");
        $postData = $this->request->getPost(); //print_r($postData);exit;
        $getData = $this->request->getGet(); //print_r($postData);exit;

        //get Data
        if (isset($postData['search'])) {
            if (isset($postData['title']) && $postData['title'] !== '') {
                $this->_model->like('title', $postData['title']);
                $this->_data['search_title'] = $postData['title'];
            }
        }

        if (isset($getData['group']) && $getData['group'] !== '') {
            $this->_model->where('group_id', $getData['group']);
            $this->_data['dfGroup'] = $getData['group'];
        } else {
            $defCfkey = array_key_first($this->config->cfGroup);
            $this->_model->where('group_id', $defCfkey);
            $this->_data['dfGroup'] = $defCfkey;
        }

        $this->_data['data'] = $this->_model->findAll();

        //echo "<pre>"; print_r($this->_data['data']); exit;
        $this->_render('\system\config\index', $this->_data);
    }

    //show config custom page
    public function custom($group)
    {
        $this->_data['title'] = lang("Acp.config_customtitle");

        if ($this->config->sys['themes_name'] === 'default') return redirect()->back()->with('error', lang('Acp.themes_name_invalid'));

        $cfData = $this->_model->where('group_id', 'themes')
            ->where('key', $this->config->cfGroup['custom_theme']['type'])->first();

        if (empty($cfData)) {
            $themeConfigData = config($this->config->sys['themes_name']);
            if (empty($themeConfigData)) return redirect()->route('dashboard')->with('error', lang('Acp.themes_config_invalid'));
            $insertData = [
                'group_id' => $group,
                'title' => $this->config->cfGroup['theme']['title'],
                'key' => $this->config->cfGroup['theme']['type'],
                'value' => json_encode($themeConfigData->data),
                'is_json' => 1
            ];

            if (!$cfId = $this->_model->insert($insertData))
                return redirect()->back()->withInput()->with('errors', $this->_model->errors());
            $item = $this->_model->find($cfId);
            $cfData = json_decode($item->value, true);
            $this->_data['config_id'] = $item->id;
        } else {
            $this->_data['config_id'] = $cfData->id;
            $cfData = json_decode($cfData->value, true);
        }
        $this->_data['cfData'] = $cfData;
        $this->_render('\system\config\custom', $this->_data);
    }

    /**
     * Save config custom
     * @param $group
     * @return \CodeIgniter\HTTP\RedirectResponse
     * @throws \ReflectionException
     */
    public function customAction($group)
    {
        $item = '';
        $themeConfigData = config($this->config->sys['themes_name']);
        if (empty($themeConfigData)) return redirect()->route('dashboard')->with('error', lang('Acp.themes_config_invalid'));

        $cfData = $this->_model->where('group_id', $group)
            ->where('key', $this->config->cfGroup['theme']['type'])->first();
        if (empty($cfData)) $cfData = $themeConfigData->data;
        else {
            $item = $cfData;
            $cfData = ($cfData->is_json == 1) ? json_decode($cfData->value, true) : $cfData->value;
        }
        $postData = $this->request->getPost();

        if (isset($postData['resetCustom'])) {
            if (isset($item->id) && $item->id > 0) {
                $cfData[$postData['resetCustom']] = $themeConfigData->data[$postData['resetCustom']];
                $cfData = json_encode($cfData);
                $updateData = [
                    'value' => $cfData
                ];
                $this->_model->update($item->id, $updateData);
                // $this->_model->where('id', $item->id )->delete();
            }
            return redirect()->route('config_custom', [$group])
                ->with('error', lang('Acp.cf_customedit_reset') . ' - ' . $postData['resetCustom']);
        } else {

            foreach ($cfData as $key => $val) {
                //check for images upload
                if (in_array('images', array_keys($val))) {
                    $imgPostName = "{$key}_images";
                    if (isset($postData[$imgPostName]) && !empty($postData[$imgPostName])) {
                        if ($cfData[$key]['images']['storage_type'] == 'single') {
                            $cfData[$key]['images']['data'] = $postData[$imgPostName];
                        } else if ($cfData[$key]['images']['storage_type'] == 'gallery') {
                            $cfData[$key]['images']['data'] = $postData[$imgPostName];
                        }
                    }
                }

                if (isset($postData[$key])) {
                    if (isset($postData['status']) && $postData['status'] === 'on') {
                        $cfData[$key]['status']['data'] = 1;
                    } else {
                        $cfData[$key]['status']['data'] = 0;
                    }
                    foreach ($postData as $kdt => $data) {
                        if ($key != $kdt && $kdt != 'status' && $kdt != $imgPostName) {
                            $cfData[$key][$kdt]['data'] = $data;
                        }
                    }
                }
            } //dd($postData); exit;

            $cfData = json_encode($cfData);
            if (isset($item->id) && $item->id > 0) {
                $updateData = [
                    'value' => $cfData
                ];
                if (!$this->_model->update($item->id, $updateData))
                    return redirect()->back()->withInput()->with('errors', $this->_model->errors());
            } else {
                $insertData = [
                    'group_id' => $group,
                    'title' => $this->config->cfGroup['theme']['title'],
                    'key' => $this->config->cfGroup['theme']['type'],
                    'value' => $cfData,
                    'is_json' => 1
                ];
                if (!$this->_model->insert($insertData))
                    return redirect()->back()->withInput()->with('errors', $this->_model->errors());
            }
        }
        return redirect()->route('config_custom', [$group])
            ->with('message', lang('Acp.cf_customedit_success', [$group]));
    }

    /**
     * Display Add Configs Page
     */
    public function add($group)
    {
        $this->_data['title'] = lang('Acp.cf_title_add');
        $this->_data['selected_group'] = $group;
        $this->_render('\system\config\add', $this->_data);
    }

    /**
     * Attempt to add a new Category.
     */
    public function addAction($group)
    {
        $insertData = $this->request->getPost();
        // Validate here first, since some things,
        $rules = [
            'title'           => 'required|min_length[3]',
            'key'           => 'required|min_length[3]',
            'value'           => 'required|min_length[3]',
        ];

        $errMess = [
            'title' => [
                'required' => lang('Acp.cf_title_required'),
                'min_length' => lang('Acp.cf_min_length'),
            ],
            'key' => [
                'required' => lang('Acp.cf_key_required'),
                'min_length' => lang('Acp.cf_keymin_length'),
            ],
            'value' => [
                'required' => lang('Acp.cf_value_required'),
                'min_length' => lang('Acp.cf_value_length'),
            ],
        ];

        if (isset($insertData['slug']) && $insertData['slug'] !== '') {
            $rules['slug'] = 'is_unique[category.slug]';
            $errMess['slug'] = ['is_unique' => lang('Category.slug_is_exist')];
        }

        //validate the input
        if (!$this->validate($rules, $errMess)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        //good then save the new item
        //print_r($insertData);exit;
        if (!$this->_model->insert($insertData)) {
            return redirect()->back()->withInput()->with('errors', $this->_model->errors());
        }
        $item = $this->_model->getWhere(['key' => $insertData['key']])->getFirstRow();
        //log Action
        $logData = [
            'title' => 'Add Config',
            'description' => "#{$this->user->username} đã thêm config #{$item->key}",
            'properties' => $item,
            'subject_id' => $item->id,
            'subject_type' => ConfigModel::class,
        ];
        $this->logAction($logData);
        // Success!
        if (isset($insertData['save'])) return redirect()->route('edit_config', [$item->id])->with('message', lang('Acp.cf_addSuccess', [$item->title]));
        else if (isset($insertData['save_exit'])) return redirect()->route('config')->with('message', lang('Acp.cf_addSuccess', [$item->title]));
        else if (isset($insertData['save_addnew'])) return redirect()->route('add_config', [$group])->with('message', lang('Acp.cf_addSuccess', [$item->title]));
    }

    /**
     * Display Edit Category Page
     */
    public function edit($idItem)
    {
        $this->_data['title'] = lang('Acp.cf_edit_title');
        $item = $this->_model->withDeleted()
            ->find($idItem);

        if (isset($item->id)) {

            $this->_data['itemData'] = $item;

            $this->_render('\system\config\edit', $this->_data);
        } else {
            return redirect()->route('config')->with('error', lang('Acp.invalid_request'));
        }
    }

    /**
     * Attempt to edit a category.
     */
    public function editAction($idItem)
    {
        $this->_data['title'] = lang('Acp.cf_edit_title');

        $data = $this->_model->find($idItem);

        $inputData = $this->request->getPost();
        //validate the data
        $rules = [
            'title'             => "required|min_length[3]|is_unique[config.title,id,{$data->id}]",
            'key'               => "required|min_length[3]",
            'value'             => "required",
        ];

        $errMess = [
            'title' => [
                'required'      => lang('Acp.cf_title_required'),
                'min_length'    => lang('Acp.cf_min_length'),
                'is_unique'     => lang('Acp.cf_title_is_exist')
            ],
            'key' => [
                'required'      => lang('Acp.cf_key_required'),
                'min_length'    => lang('Acp.cf_keymin_length'),
            ],
            'value' => [
                'required'      => lang('Acp.cf_value_required'),
            ],
        ];

        //validate the input
        if (!$this->validate($rules, $errMess)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        //good then save the new item
        if (!$this->_model->update($data->id, $inputData)) {
            return redirect()->back()->withInput()->with('errors', $this->_model->errors());
        }
        //log Action
        $logData = [
            'title' => 'Edit Config',
            'description' => "#{$this->user->username} đã sửa config #{$data->key}",
            'properties' => $data,
            'subject_id' => $data->id,
            'subject_type' => ConfigModel::class,
        ];
        $this->logAction($logData);
        // Success!
        if (isset($inputData['save'])) return redirect()->route('config')->with('message', lang('Acp.cf_edit_Success'));
        else if (isset($inputData['save_exit'])) return redirect()->route('config')->with('message', lang('Acp.cf_edit_Success'));
        //else if ( isset($inputData['save_addnew']) ) return redirect()->route('add_config')->with('message', lang('Acp.cf_edit_Success'));

    }

    public function clone($id)
    {
        $item = $this->_model->find($id);

        if (isset($item->id)) {
            $insertData = [
                'group_id' => $item->group_id,
                'title' => $item->title,
                'key' => $item->key,
                'value' => $item->value,
                'is_json' => $item->is_json,
            ];

            if (!$newId = $this->_model->insert($insertData)) {
                return redirect()->back()->withInput()->with('errors', $this->_model->errors());
            }
            $newItem = $this->_model->find($newId);
            //log Action
            $logData = [
                'title' => 'Clone Config',
                'description' => "#{$this->user->username} đã thêm config #{$newItem->key}",
                'properties' => $newItem,
                'subject_id' => $newItem->id,
                'subject_type' => ConfigModel::class,
            ];
            $this->logAction($logData);

            return redirect()->route('edit_config', [$newItem->id])->with('error', lang('Config.clone_success', [$newItem->key]));
        } else {
            return redirect()->route('config')->with('error', lang('Config.no_config_found'));
        }

    }

    /**
     * Remove a config
     */
    public function remove($idItem)
    {
        $item = $this->_model->find($idItem);

        if (isset($item->id)) {

            if ($this->_model->delete($item->id)) {
                //log Action
                $logData = [
                    'title' => 'Edit Config',
                    'description' => "#{$this->user->username} đã xoá config #{$item->key}",
                    'properties' => $item,
                    'subject_id' => $item->id,
                    'subject_type' => ConfigModel::class,
                ];
                $this->logAction($logData);
                return redirect()->route('config')->with('message', lang('Acp.cf_delete_success', [$item->title]));
            } else return redirect()->route('config')->with('error', lang('Acp.delete_fail'));
        } else return redirect()->route('config')->with('error', lang('Acp.invalid_request'));
    }

    /**
     * AJAX
     */

    /**
     * get custome file attach in config
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getCustomAttachFile()
    {
        $inputData = $this->request->getGet();

        if (empty($inputData['dataType']) || empty($inputData['atts'])) {
            return $this->response->setJSON([
                'error' => 1,
                'message' => 'Invalid Request!'
            ]);
        }


        $_attach = new AttachModel();
        $imgsData = [];
        switch ($inputData['dataType']) {
            case 'single':
                $imgsData[] = $_attach->getAttFile($inputData['atts']);
                break;
            case 'multi':
                $imgsIds = explode(';', $inputData['atts']);
                foreach ($imgsIds as $id) $imgsData[] = $_attach->getAttFile($id);
                break;
        }
        return $this->response->setJSON([
            'error' => 0,
            'data' => $imgsData
        ]);
    }
}
