<?php

namespace Modules\Acp\Controllers\Store;

use App\Models\Store\VoucherModel;
use Modules\Acp\Controllers\AcpController;

class VoucherController extends AcpController
{

    public function __construct()
    {
        parent::__construct();
        if (empty($this->_model)) {
            $this->_model = model(VoucherModel::class);
        }
    }

    public function index()
    {
        $this->_data['title'] = lang("Shop.voucher_page_title");
        $postData = $this->request->getPost();

        if (isset($postData) && !empty($postData)) {
            if (!empty($postData['sel'])) {
                $this->_model->delete($postData['sel']);
            }

            if (isset($postData['search_text']) && $postData['search_text'] !== '') {
                $this->_model->like('voucher_title', $postData['search_text']);
                $this->_data['search_title'] = $postData['search_text'];
            }
        }

        $this->_data['data'] = $this->_model->paginate();
        $this->_data['pager'] = $this->_model->pager;
        $this->_data['countAll'] = $this->_model->countAll();
        $this->_render('\store\voucher\index', $this->_data);
    }

    public function add()
    {
        $this->_data['title'] = lang("Shop.add_voucher_title");
        $postData = $this->request->getPost();

        if (isset($postData) && !empty($postData)) {
            return $this->saveVoucher($postData);
        }

        $this->_render('\store\voucher\add', $this->_data);
    }

    private function saveVoucher($data)
    {
        // validate voucher data
        [$rules, $errMess] = $this->_getValidateRules(null);
        if (! $this->validate($rules, $errMess)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data['voucher_code'] = isset($data['voucher_code']) && !empty($data['voucher_code']) ? strtoupper($data['voucher_code']) : $this->_model->generateVoucherCode();

        if (isset($data['voucher_start_date']) && !empty($data['voucher_start_date'])) {
            $data['voucher_start_date'] = date('Y-m-d', strtotime($data['voucher_start_date']));
        } else {
            $data['voucher_start_date'] = null;
        }

        if (isset($data['voucher_end_date']) && !empty($data['voucher_end_date'])) {
            $data['voucher_end_date'] = date('Y-m-d', strtotime($data['voucher_end_date']));
        } else {
            $data['voucher_end_date'] = null;
        }

        $id = $this->_model->insert($data);

        if (!$id) {
            return redirect()->back()->withInput()->with('errors', $this->_model->errors());
        }

        // Success!
        $item = $this->_model->find($id);

        //log Action
        $logData = [
            'title'        => lang('Log.add_voucher'),
            'description'  => lang('Log.add_voucher_desc', [$this->user->username, $item->voucher_code]),
            'properties'   => $data,
            'subject_id'   => $item->voucher_id,
            'subject_type' => VoucherModel::class,
        ];
        $this->logAction($logData);

        if (isset($data['save'])) return redirect()->route('edit_voucher', [$item->voucher_id])->with('message', lang('Shop.addSuccess', [$item->voucher_code]));
        else if (isset($data['save_exit'])) return redirect()->route('list_voucher')->with('message', lang('Shop.addSuccess', [$item->voucher_code]));
        else if (isset($data['save_addnew'])) return redirect()->route('add_voucher')->with('message', lang('Shop.addSuccess', [$item->voucher_code]));
    }

    public function edit($id)
    {
        $this->_data['title'] = lang("Shop.edit_voucher_title");
        $voucher = $this->_model->find($id);
        $postData = $this->request->getPost();

        if (!isset($voucher) || empty($voucher)) {
            return redirect()->route('list_voucher')->with('error', lang('Acp.no_item_found'));
        }

        if (isset($postData) && !empty($postData)) {
            return $this->updateVoucher($postData, $voucher);
        }

        $this->_data['voucher'] = $this->_model->find($id);
        $this->_render('\store\voucher\edit', $this->_data);
    }

    private function updateVoucher($data, $oldVoucher)
    {
        // validate voucher data
        [$rules, $errMess] = $this->_getValidateRules($oldVoucher);
        if (! $this->validate($rules, $errMess)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if (isset($data['voucher_start_date']) && !empty($data['voucher_start_date'])) {
            $data['voucher_start_date'] = date('Y-m-d', strtotime($data['voucher_start_date']));
        } else {
            $data['voucher_start_date'] = null;
        }

        if (isset($data['voucher_end_date']) && !empty($data['voucher_end_date'])) {
            $data['voucher_end_date'] = date('Y-m-d', strtotime($data['voucher_end_date']));
        } else {
            $data['voucher_end_date'] = null;
        }

        if ($this->_model->update($oldVoucher->voucher_id, $data)) {
            $recordLog = [
                'old_data' => $oldVoucher,
                'new_data' => $data,
            ];
            //log Action
            $logData = [
                'title'        => lang('Log.edit_voucher', [$oldVoucher->voucher_code]),
                'description'  => lang('Log.edit_voucher_desc', [$this->user->username, $oldVoucher->voucher_code]),
                'properties'   => $recordLog,
                'subject_id'   => $oldVoucher->voucher_id,
                'subject_type' => VoucherModel::class,
            ];
            $this->logAction($logData);

            if (isset($data['save'])) return redirect()->route('edit_voucher', [$oldVoucher->voucher_id])->with('message', lang('Shop.editSuccess', [$oldVoucher->voucher_code]));
            else if (isset($data['save_exit'])) return redirect()->route('list_voucher')->with('message', lang('Shop.editSuccess', [$oldVoucher->voucher_code]));
            else if (isset($data['save_addnew'])) return redirect()->route('add_voucher')->with('message', lang('Shop.editSuccess', [$oldVoucher->voucher_code]));
        } else {
            return redirect()->back()->withInput()->with('errors', $this->_model->errors());
        }
    }

    /**
     * generate validate rules for voucher
     * @param object|null $old_item
     */
    private function _getValidateRules($old_item)
    {
        $rules = [
            'voucher_title'             => 'required',
            'voucher_discount_type'     => 'required',
            'voucher_discount_value'    => 'required',
        ];
        if (isset($old_item) && $old_item->voucher_id) {
            $rules['voucher_code'] = "permit_empty|is_unique[voucher.voucher_code,voucher_id,{$old_item->voucher_id}]";
        } else {
            $rules['voucher_code'] = 'permit_empty|is_unique[voucher.voucher_code]';
        }

        $errMess = [
            'voucher_code' => [
                'is_unique' => lang('Shop.voucher_code_is_exist')
            ],
            'voucher_title' => [
                'required' => lang('Shop.voucher_title_required'),
            ],
            'voucher_discount_type' => [
                'required' => lang('Shop.voucher_discount_type_required'),
            ],
            'voucher_discount_value' => [
                'required' => lang('Shop.voucher_discount_value_required'),
            ],
        ];

        return [$rules, $errMess];
    }

    /**
     * Ajax soft delete item
     * @return mixed
     */
    public function ajxRemove()
    {
        $response = [];
        $postData = $this->request->getPost();
        if (!isset($postData['id']) || empty($postData['id'])) return $this->response->setJSON(['error' => 1, 'message' => lang('Acp.invalid_request')]);

        $item = $this->_model->find($postData['id']);
        if (!isset($item->voucher_id) || empty($item)) {
            $response['error'] = 1;
            $response['message'] = lang('Acp.item_not_found');
        } else {
            if ($this->_model->delete($item->voucher_id)) {
                //log Action
                if (method_exists(__CLASS__, 'logAction')) {
                    $prop = method_exists(get_class($item), 'toArray') ? $item->toArray() : (array)$item;
                    $logData = [
                        'title' => 'Delete Voucher #' . $item->voucher_code,
                        'description' => lang('Acp.delete_success', [$item->voucher_code]),
                        'properties' => $prop,
                        'subject_id' => $item->voucher_id,
                        'subject_type' => get_class($this->_model),
                    ];
                    $this->logAction($logData);
                }
                $response['error'] = 0;
                $response['message'] = lang('Acp.delete_success', [$item->voucher_code]);
            } else {
                $response['error'] = 1;
                $response['message'] = lang('Acp.delete_fail');
            }
        }
        return $this->response->setJson($response);
    }
}
