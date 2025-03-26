<?php

/**
 * Created by tmtuan.
 * Email: tmtuan801@gmail.com
 */

namespace Modules\Acp\Controllers\User;

use Modules\Acp\Models\PermissionModel;
use Modules\Acp\Models\User\UsergModel;
use Modules\Acp\Controllers\AcpController;
use Modules\Acp\Traits\SystemLog;

class UserGroup extends AcpController
{
    use SystemLog;

    public function __construct()
    {
        parent::__construct();
        $this->_model = new UsergModel();

    }

    public function index()
    {
        //set title
        $this->_data['title'] = lang("User.userGroup_title");

        $query = $this->request->getGet();


        if (isset($query['deleted']) && $query['deleted'] == 1) {
            $this->_model->onlyDeleted();
            $this->_data['action'] = 'deleted';
        } else $this->_data['action'] = 'all';

        //        $this->_data['data'] = $this->_model->findAll();

        $this->_render('\user\userg\index', $this->_data);
    }

    /**
     * Show edit permission page
     */
    public function editPermission($id)
    {
        //check permission
        if ( !$this->user->can($this->currentAct) ) return redirect()->route('dashboard')->with('error', lang('Acp.no_permission'));

        $this->_data['title'] = lang('User.edit_permission_group_title');
        $userg = $this->_model->withDeleted()
            ->find($id);
        $_perModel = new PermissionModel();
        $persData = $_perModel->orderBy('name', 'ASC')->findAll();
        if (empty($persData)) return redirect()->route('permissions')->with('error', lang('Acp.empty_permissions'));

        $permissions = [];
        foreach ($persData as $perItem) {
            if (!array_key_exists($perItem->name, $permissions)) {
                $permissions[trim($perItem->name)] = [];
            }
            array_push($permissions[trim($perItem->name)], $perItem);
        }
        //        echo '<pre>'; print_r($permissions);exit;

        if (isset($userg->id)) {
            $userg->permissions = !empty($userg->permissions) ? json_decode($userg->permissions) : [];
            $this->_data['groupData'] = $userg;
            $this->_data['dataTitle'] = $userg->name;
            $this->_data['persData'] = $permissions;

            $this->_render('\user\userg\permission', $this->_data);
        } else {
            return redirect()->route('list_userg')->with('error', lang('User.invalid_user_group'));
        }
    }

    public function editPermissionAction($id)
    {
        $postData = $this->request->getPost();
        $userg = $this->_model->find($id);
        if (empty($userg)) return redirect()->route('list_userg')->with('error', lang('User.invalid_user_group'));

        if (empty($postData)) return redirect()->back()->with('error', lang('Acp.invalid_pers'));
        else {
            $userg->permissions = json_encode($postData['pers']);
            $this->_model->update($userg->id, $userg);
            return redirect()->route('list_userg', [$userg->id])->with('message', lang('User.save_group_permission_success', [$userg->name]));
        }
    }

    /**
     * AJAX
     */
    public function fetchUsersData()
    {
        //$response = array();
        if (!isset($this->user)) return $this->response->setJSON(['error' => 1, 'errMess' => "Invalid Request"]);

        $returnData = $this->_model->findAll();
        //echo json_encode($returnData); exit;
        //return data
        return $this->response->setJSON(['error' => 0, 'data' => $returnData]);
    }

    public function updateGroup()
    {
        //check permission
        if ( !$this->user->can($this->currentAct) ) return $this->response->setJSON(['error' => 1, 'errMess' => lang('Acp.no_permission')]);

        $rules = [
            'name'          => 'required|min_length[2]',
            'description'   => 'required|min_length[2]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['error' => 1, 'errMess' => $this->validator->getErrors()]);
        } else {
            $postData = $this->request->getPost(null, FILTER_SANITIZE_STRING);
            if (!empty($postData['id']) || $postData['id'] != null || $postData['id'] > 0 ) {
                $groupData = $this->_model->getWhere(['id' => $postData['id']])->getFirstRow();
                if (!isset($groupData->id)) {
                    $newGroup = $this->_addGroup();
                    $item = $this->_model->find($newGroup);
                    //log Action
                    $logData = [
                        'title' => 'Add Group',
                        'description' => "#{$this->user->username} đã thêm nhóm #{$item->name}",
                        'properties' => $item,
                        'subject_id' => $item->id,
                        'subject_type' => UsergModel::class,
                    ];
                    $this->logAction($logData);
                    return $this->response->setJSON(['error' => 0, 'data' => $item]);
                } else {
                    $groupData->name = $postData['name'];
                    $groupData->description = $postData['description'];
                    $this->_model->update($groupData->id, $groupData);
                    // log Action
                    $logData = [
                        'title' => 'Edit Group',
                        'description' => "#{$this->user->username} đã thay đổi nhóm #{$groupData->name}",
                        'properties' => $groupData,
                        'subject_id' => $groupData->id,
                        'subject_type' => UsergModel::class,
                    ];
                    $this->logAction($logData);
                    return $this->response->setJSON(['error' => 0, 'data' => $groupData]);
                }

            } else {
                $groupData = $this->_model->getWhere(['name' => $postData['name']])->getFirstRow(); //echo "<pre>"; print_r($groupData); exit;
                if (!empty($groupData)) return $this->response->setJSON(['error' => 1, 'errMess' => lang('User.userGroupName_is_exist')]);
                else {
                    $newGroup = $this->_addGroup();

                    $item = $this->_model->find($newGroup);
                    //log Action
                    $logData = [
                        'title' => 'Add Group',
                        'description' => "#{$this->user->username} đã thêm nhóm #{$item->name}",
                        'properties' => $item,
                        'subject_id' => $item->id,
                        'subject_type' => UsergModel::class,
                    ];
                    $this->logAction($logData);
                    return $this->response->setJSON(['error' => 0, 'data' => $item]);
                }
            }
        }
    }

    private function _addGroup()
    {
        $postData = $this->request->getPost(null, FILTER_SANITIZE_STRING);
        $insertData = [
            'name' => $postData['name'],
            'description' => (isset($postData['description'])) ? $postData['description'] : '',
            'permissions' => (isset($postData['permission'])) ? serialize($postData['permission']) : ''
        ];
        $id = $this->_model->insert($insertData);
        return $id;
    }

    public function deleteGroup($groupId)
    {
        $groupData = $this->_model->find($groupId);

        //check permission
        if ( !$this->user->can($this->currentAct) ) return $this->response->setJSON(['error' => 1, 'errMess' => lang('Acp.no_permission')]);

        if (empty($groupData)) return $this->response->setJSON(['error' => 1, 'errMess' => lang('Acp.invalid_request')]);
        //remove group
        if ($this->_model->delete($groupData->id)) {
            //log Action
            $logData = [
                'title' => 'Delete Group',
                'description' => "#{$this->user->username} đã xoá nhóm #{$groupData->name}",
                'properties' => $groupData,
                'subject_id' => $groupData->id,
                'subject_type' => UsergModel::class,
            ];
            $this->logAction($logData);

            return $this->response->setJSON(['error' => 0, 'text' => "Delete success"]);
        } else return $this->response->setJSON(['error' => 0, 'errMess' => "Delete problem please try again"]);
    }
}
