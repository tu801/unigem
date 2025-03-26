<?php
/**
 * @author tmtuan
 * created Date: 08-Sep-20
 */

namespace Modules\Acp\Controllers\System;

use Modules\Acp\Models\PermissionModel;
use Modules\Acp\Controllers\AcpController;
use Modules\Acp\Traits\deleteItem;
use Modules\Acp\Traits\SystemLog;

class Permission extends AcpController {
    use SystemLog;
    use deleteItem;

    public function __construct()
    {
        parent::__construct();
        $this->_model = model(PermissionModel::class);
    }

    public function index()
    {
        if ( !$this->user->is_root ) return redirect()->route('dashboard')->with('error', lang('Acp.no_permission'));

        $this->_data['title'] = lang("Permissions.main_title");
        $this->_data['action'] = 'all';
        $this->_render('\system\permission\index', $this->_data);
    }

    /**
     * List permissions
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function vlist_permissions()
    {
        $results = array();
        $list_data = $this->_model->orderBy('id', 'DESC')->findAll();

        if (isset($list_data) && count($list_data) > 0) {
            $results['data'] = $list_data;
            $results['error'] = 0;
        } else {
            $results['error'] = 1;
            $results['message'] = 'Không tìm thấy quyền nào! Vui lòng thêm quyền mới';
        }

        return $this->response->setJSON($results);
    }

    public function vpermissions_groups()
    {
        $results = array();
        $list_data = $this->config->permission_groups;

        if (isset($list_data) && count($list_data) > 0) {
            $results['data'] = $list_data;
            $results['error'] = 0;
        } else {
            $results['error'] = 1;
            $results['message'] = 'Không tìm thấy danh sách nhóm quyền';
        }

        return $this->response->setJSON($results);
    }

    /**
     * Update permission
     * @param $per_id
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function ajaxUpdate($per_id)
    {
        $post_input = $this->request->getPost();
        $results = array();

        //validate
        if (! $this->validate($this->_validRules())) {
            $errs = $this->validator->getErrors();
            $errorRespond = '';
            foreach ($errs as $key=>$mess) {
                if ( $mess !== '' ) $errorRespond .= $mess.'<br>';
            }
            $response['error'] = 1;
            $response['message'] = $errorRespond;
            return $this->response->setJSON($response);
        }

        $found_update = $this->_model->where('id', $per_id)->first();
        if (!isset($found_update) || empty($found_update)) {
            $results['error'] = 1;
            $results['message'] = lang('Acp.invalid_request');
            return $this->response->setJSON($results);
        }

        if (!$this->_model->update($per_id, $post_input)) {
            $results['error'] = 1;
            $results['message'] = lang('Permissions.update_pers_failed');
            return $this->response->setJSON($results);
        }
        $item = $this->_model->find($per_id);
        //log Action
        $logData = [
            'title' => 'Edit Permission',
            'description' => "#{$this->user->username} đã sửa quyền #{$item->description}",
            'properties' => (array)$item,
            'subject_id' => $item->id,
            'subject_type' => get_class($this->_model),
        ];
        $this->logAction($logData);

        $return_results = $this->_model->where('id', $per_id)->first();
        $results['error'] = 0;
        $results['data'] = $return_results;
        $results['message'] = lang('Permissions.update_pers_success');
        $results['_cstoken'] = csrf_hash();
        $results['_csname'] = csrf_token();
        return $this->response->setJSON($results);
    }

    /**
     * create permission
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function ajaxCreate()
    {
        $postData = $this->request->getPost();
        if (! $this->validate($this->_validRules())) {
            $errs = $this->validator->getErrors();
            $errorRespond = '';
            foreach ($errs as $key=>$mess) {
                if ( $mess !== '' ) $errorRespond .= $mess.'<br>';
            }
            $response['error'] = 1;
            $response['message'] = $errorRespond;
        }
        $results = array();

        //check permission
        $chk = $this->_model->where('name', $postData['name'])
            ->where('group', $postData['group'])
            ->where('action', $postData['action'])
            ->first();

        if ( !empty($chk) ) {
            $results['error'] = 1;
            $results['message'] = lang('pers.pers_exist');
        } else {
            $return_results = $this->_model->insert($postData);
            if (!isset($return_results)) {
                $results['error'] = 1;
                $results['message'] = lang('Permissions.create_new_pers_failed');
                return $this->response->setJSON($results);
            }
            $item = $this->_model->find($return_results);
            //log Action
            $logData = [
                'title' => 'Add Permission',
                'description' => "#{$this->user->username} đã thêm quyền #{$item->description}",
                'properties' => (array)$item,
                'subject_id' => $item->id,
                'subject_type' => get_class($this->_model),
            ];
            $this->logAction($logData);

            $results['error'] = 0;
            $results['data'] = $item;

            $results['message'] = lang('Permissions.create_new_pers_success');
            $results['_cstoken'] = csrf_hash();
            $results['_csname'] = csrf_token();
        }
        return $this->response->setJSON($results);
    }

    /**
     * permission validation rules
     * @return array
     */
    private function _validRules()
    {
        return [
            'name'   => [
                'rules' => 'required',
                'errors' => [
                    'required' => lang('pers.pers_name_required'),
                ]
            ],
            'group'   => [
                'rules' => 'required',
                'errors' => [
                    'required' => lang('pers.pers_group_required'),
                ]
            ],
            'description'   => [
                'rules' => 'required',
                'errors' => [
                    'required' => lang('pers.pers_desc_required'),
                ]
            ],
        ];
    }
}