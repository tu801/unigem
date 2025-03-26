<?php
/**
 * Author: tmtuan
 * Created date: 11/13/2023
 * Project: Unigem
 **/

namespace App\Controllers\Customer;


use App\Controllers\BaseController;
use App\Libraries\BreadCrumb\BreadCrumbCell;
use App\Models\CustomerVoucherModel;
use Modules\Acp\Enums\BankingPaymentEnum;
use Modules\Acp\Enums\Store\Promotion\LuckyDrawHistoryStatusEnum;
use Modules\Acp\Enums\UserTypeEnum;
use Modules\Acp\Models\ConfigModel;
use Modules\Acp\Models\Store\Promotion\LuckyDrawHistoryModel;
use Modules\Acp\Models\Store\Promotion\PromotionVoucherModel;
use Modules\Auth\Config\Services;

class Voucher extends BaseController
{
    protected $_luckyDrawHistoryModel;

    public function __construct()
    {
        parent::__construct();
        $this->auth    = Services::authentication();
        $this->_model  = model(CustomerVoucherModel::class);
        $this->_luckyDrawHistoryModel = model(LuckyDrawHistoryModel::class);
    }

    public function list()
    {
        if (!$this->auth->check()) {
            return redirect()->route('cus_login');
        }

        if ( $this->user->user_type == UserTypeEnum::ADMIN ) {
            return redirect()->to(base_url());
        }

        //set breadcrumb
        BreadCrumbCell::add('Home', base_url());
        BreadCrumbCell::add(lang('CustomerProfile.cus_voucher_menu'), route_to('voucher_list'));

        $this->_data['user'] = $this->customer;
        $this->_data['voucherData'] = $this->_model->listCustomerVoucher($this->customer->id);
        return $this->_render('customer/voucher_list', $this->_data);
    }

    public function claimGift($voucher_id)
    {
        if (!$this->auth->check()) {
            return redirect()->route('cus_login');
        }

        if ( $this->user->user_type == UserTypeEnum::ADMIN ) {
            return redirect()->to(base_url());
        }
        $voucherData = model(PromotionVoucherModel::class)
            ->join('promotion', 'promotion.promo_id = promotion_voucher.promotion_id')
            ->select('promotion_voucher.*, promotion.discount_image')
            ->find($voucher_id);

        if ( !isset($voucherData->voucher_id) ) {
            return  redirect()->route('voucher_list')->with('error', lang('LuckyDraw.no_voucher_found'));
        }

        //set breadcrumb
        BreadCrumbCell::add('Home', base_url());
        BreadCrumbCell::add(lang('CustomerProfile.cus_voucher_menu'), route_to('voucher_list'));
        BreadCrumbCell::add(lang('CustomerProfile.cus_claim_gift'), route_to('claim_gift', $voucher_id));

        $bank                 = model(ConfigModel::class)->where('group_id', BankingPaymentEnum::CONFIG_GROUP)
            ->where('key', BankingPaymentEnum::CONFIG_KEY)
            ->findAll();
        if (!empty($bank)) {
            $banks = [];
            foreach ($bank as $item) {
                $value   = json_decode($item->value);
                $banks[] = $value;
            }
            $this->_data['banks'] = $banks;
        } else {
            $this->_data['banks'] = [];
        }

        if ( $this->request->getPost() ) {
            return $this->claimGiftAction($this->request->getPost(), $voucherData);
        }

        $this->_data['user'] = $this->customer;
        $this->_data['voucherData'] = $voucherData;
        return $this->_render('customer/claim_gift', $this->_data);
    }

    public function claimGiftAction($postData, $voucherData)
    {
        // validate input
        $rules     = [
            'ship_full_name'        => 'required',
            'ship_telephone'        => 'required',
            'province_id'           => 'required',
            'district_id'           => 'required',
            'ward_id'               => 'required',
            'ship_address'          => 'required',
        ];
        $errMess    = [
            'ship_full_name'      => [
                'required' => lang('Order.full_name_required'),
            ],
            'ship_telephone' => [
                'required' => lang('Order.phone_required'),
            ],
            'province_id'    => [
                'required' => lang('Order.province_id_required'),
            ],
            'district_id'    => [
                'required' => lang('Order.district_id_required'),
            ],
            'ward_id'        => [
                'required' => lang('Order.ward_id_required'),
            ],
            'ship_address' => [
                'required' => lang('Order.address_required'),
            ],
        ];

        if (!$this->validate($rules, $errMess)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $luckyDrawHistory = $this->_luckyDrawHistoryModel
            ->where('promo_voucher_id', $voucherData->voucher_id)
            ->first();
        if ( !isset($luckyDrawHistory->history_id) || $luckyDrawHistory->status != LuckyDrawHistoryStatusEnum::NEW ) {
            return redirect()->back()->withInput()->with('error', lang('LuckyDraw.claim_gift_error'));
        }

        // update request claim gift to history
        $luckyDrawHistory->status = LuckyDrawHistoryStatusEnum::REWARD_CLAIMED;
        $luckyDrawHistory->ship_full_name = $postData['ship_full_name'];
        $luckyDrawHistory->ship_telephone = $postData['ship_telephone'];
        $luckyDrawHistory->ship_email = $postData['ship_email'] ?? '';
        $luckyDrawHistory->ship_address = $postData['ship_address'];
        $luckyDrawHistory->ship_province_id = $postData['province_id'];
        $luckyDrawHistory->ship_district_id = $postData['district_id'];
        $luckyDrawHistory->ship_ward_id = $postData['ward_id'];

        if (! $this->_luckyDrawHistoryModel->update( $luckyDrawHistory->history_id, $luckyDrawHistory) ) {
            return redirect()->back()->withInput()->with('error', $this->_model->errors());
        }

        return redirect()->route('voucher_list')->with('message', lang('LuckyDraw.claim_gift_success'));
    }
}