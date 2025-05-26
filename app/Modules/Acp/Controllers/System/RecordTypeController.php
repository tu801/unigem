<?php
/**
 * @author tmtuan
 * created Date: 9/14/2021
 * project: fox_cms
 */
namespace Modules\Acp\Controllers\System;

use Modules\Acp\Controllers\AcpController;
use App\Models\RecordTypeModel;
use Modules\Acp\Traits\deleteItem;

class RecordTypeController extends AcpController {
    use deleteItem;

    public function __construct()
    {
        parent::__construct();
        if ( empty($this->_model)) {
            $this->_model = model(RecordTypeModel::class);
        }
    }

    public function index()
    {
        if ( !$this->user->is_root ) return redirect()->route('dashboard')->with('error', lang('Acp.no_permission'));

        $this->_data['title'] = lang("Acp.record_type_title");
        $this->_data['action'] = 'all';
        $this->_render('\system\record_type\index', $this->_data);
    }

    /**
     * List item
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function vlist_data()
    {
        $results = array();
        $list_data = $this->_model->findAll();

        if (isset($list_data) && count($list_data) > 0) {
            $results['data'] = $list_data;
            $results['error'] = 0;
        } else {
            $results['error'] = 1;
            $results['message'] = 'Không tìm thấy dữ liệu! Vui lòng thêm dữ liệu';
        }

        return $this->response->setJSON($results);
    }

    /**
     * Update record
     * @param $per_id
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function ajaxEdit($id)
    {
        $postData = $this->request->getPost();
        $recordData = $this->_model->find($id);
        $results = array();
        if ( !isset($recordData->id) ) {
            $results['error'] = 1;
            $results['message'] = lang('Acp.invalid_request');
            return $this->response->setJSON($results);
        }

        //check developer name
        $recordCheck = $this->_model->where('object_type', $postData['object_type'])
            ->where('developer_name', $postData['developer_name'])->first();

        if ( isset($recordCheck->id) && $recordCheck->id != $recordData->id ) {
            $results['error'] = 1;
            $results['message'] = "Developer Name {$postData['developer_name']} đã tồn tại với Object {$postData['object_type']}! Vui lòng điền Developer Name khác";;
            return $this->response->setJSON($results);
        }

        if (!$this->_model->update($recordData->id, $postData)) {
            $results['error'] = 1;
            $results['message'] = $this->_model->errors();
            return $this->response->setJSON($results);
        }
        $return_results = $this->_model->find($recordData->id);
        $results['error'] = 0;
        $results['data'] = $return_results;
        $results['message'] = 'Đã cập nhật thành công Record Type #'.$id;
        $results['_cstoken'] = csrf_hash();
        $results['_csname'] = csrf_token();
        return $this->response->setJSON($results);
    }

    /**
     * Create records
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function ajaxAdd()
    {
        $postData = $this->request->getPost();
        $rules = [
            'name'   => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Record Type Name: không được bỏ trống',
                ]
            ],
            'developer_name'   => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Developer Name: không được bỏ trống',
                ]
            ],
            'object_type'   => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Object Name: không được bỏ trống',
                ]
            ]
        ];
        if (!$this->validate($rules)) {
            $results['error'] = 1;
            $results['message'] = implode('<br>', $this->validator->getErrors());
            return $this->response->setJSON($results);
        }

        //check developer name
        $recordCheck = $this->_model->where('object_type', $postData['object_type'])
                    ->where('developer_name', $postData['developer_name'])->first();
        if ( isset($recordCheck->id) ) {
            $results['error'] = 1;
            $results['message'] = "Developer Name {$postData['developer_name']} đã tồn tại với Object {$postData['object_type']}! Vui lòng điền Developer Name khác";
            return $this->response->setJSON($results);
        }
        $results = array();
        $return_results = $this->_model->insert($postData);
        if (!isset($return_results)) {
            $results['error'] = 1;
            $results['message'] = $this->_model->errors();
            return $this->response->setJSON($results);
        }
        $results['error'] = 0;
        $results['data'] = $this->_model->where('object_type', $postData['object_type'])
                        ->where('developer_name', $postData['developer_name'])->first();

        $results['message'] = 'Đã tạo thành công Record Type mới';
        $results['_cstoken'] = csrf_hash();
        $results['_csname'] = csrf_token();
        return $this->response->setJSON($results);
    }

}