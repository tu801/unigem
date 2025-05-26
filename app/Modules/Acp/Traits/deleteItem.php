<?php
/**
 * @author tmtuan
 * created Date: 21-Jul-21
 */

namespace Modules\Acp\Traits;

trait deleteItem {

    /**
     * Delete item with POST or GET request
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function remove() {
        $inputData = $this->request->getPost() ?? $this->request->getGet();
        if (!isset($inputData['id']) || empty($inputData['id'])) {
            return redirect()->back()->with('error', lang('Acp.invalid_request'));
        }

        $item = $this->_model->find($inputData['id']);
        if (!isset($item->id) || empty($item)) {
            return redirect()->back()->with('error', lang('Acp.no_item'));
        }

        if ((isset($item->user_init) && $item->user_init == $this->user->id)
            || $this->user->inGroup('superadmin', 'admin')) {
            if ($this->_model->delete($item->id)) {
                //log Action
                if (method_exists(__CLASS__, 'logAction')) {
                    $prop = method_exists(get_class($item), 'toArray') ? $item->toArray() : (array)$item;
                    $logData = [
                        'title' => 'Delete',
                        'description' => lang('Acp.delete_success', [$item->id]),
                        'properties' => $prop,
                        'subject_id' => $item->id,
                        'subject_type' => get_class($this->_model),
                    ];
                    $this->logAction($logData);
                }
                return redirect()->back()->with('message', lang('Acp.delete_success', [$item->id]));
            } else {
                return redirect()->back()->with('error', lang('Acp.delete_fail'));
            }
        }
        return redirect()->back()->with('error', lang('Acp.no_permission'));
    }

    /**
     * Ajax delete item
     * @return mixed
     */
    public function ajxRemove() {
        $response = [];
        $postData = $this->request->getPost();
        if (!isset($postData['id']) || empty($postData['id'])) {
            return $this->response->setJSON(['error' => 1, 'message' => lang('Acp.invalid_request')]);
        }

        $item = $this->_model->find($postData['id']);
        if (!isset($item->id) || empty($item)) {
            return $this->response->setJSON(['error' => 1, 'message' => lang('Acp.no_item')]);
        }

        if ($this->_model->delete($item->id)) {
            if (method_exists(__CLASS__, 'logAction')) {
                $prop = method_exists(get_class($item), 'toArray') ? $item->toArray() : (array)$item;
                $logData = [
                    'title' => 'Delete',
                    'description' => lang('Acp.delete_success', [$item->id]),
                    'properties' => $prop,
                    'subject_id' => $item->id,
                    'subject_type' => get_class($this->_model),
                ];
                $this->logAction($logData);
            }
            return $this->response->setJSON(['error' => 0, 'message' => lang('Acp.delete_success', [$item->id])]);
        }
        return $this->response->setJSON(['error' => 1, 'message' => lang('Acp.delete_fail')]);
    }

    /**
     * Recover an item
     */
    public function recover($idItem){
        $item = $this->_model->withDeleted()->find($idItem);
        if (!isset($item->id)) {
            return redirect()->back()->with('error', lang('Acp.invalid_request'));
        }
        if ($this->_model->recover($item->id)) {
            if (method_exists(__CLASS__, 'logAction')) {
                $prop = method_exists(get_class($item), 'toArray') ? $item->toArray() : (array)$item;
                $logData = [
                    'title' => 'Recover',
                    'description' => lang('Acp.recover_success', [$item->id]),
                    'properties' => $prop,
                    'subject_id' => $item->id,
                    'subject_type' => get_class($this->_model),
                ];
                $this->logAction($logData);
            }
            return redirect()->back()->with('message', lang('Acp.recover_success', [$item->id]));
        }
        return redirect()->back()->with('error', lang('Acp.recover_fail'));
    }

}