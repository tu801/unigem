<?php
/**
 * Author: tmtuan
 * Created date: 11/12/2023
 * Project: Unigem
 **/

namespace App\Controllers\LuckyDraw;


use App\Controllers\BaseController;
use App\Entities\CusEntity;
use App\Libraries\BreadCrumb\BreadCrumbCell;
use App\Models\CusModel;
use App\Models\CustomerVoucherModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Modules\Acp\Entities\User;
use Modules\Acp\Enums\Store\Promotion\EVoucherStatus;
use Modules\Acp\Enums\Store\Promotion\LuckyDrawHistoryStatusEnum;
use Modules\Acp\Enums\Store\Promotion\LuckyDrawHistorySuccessTypeEnum;
use Modules\Acp\Enums\Store\Promotion\PromotionEnum;
use Modules\Acp\Enums\UserTypeEnum;
use Modules\Acp\Models\RecordTypeModel;
use Modules\Acp\Models\Store\Promotion\LuckyDrawHistoryModel;
use Modules\Acp\Models\Store\Promotion\PromotionModel;
use Modules\Acp\Models\Store\Promotion\PromotionVoucherModel;
use Modules\Acp\Models\User\UserModel;
use Modules\Auth\Config\Services;
use Modules\Auth\Password;

class LuckyDrawMiniGame extends BaseController
{
    private $_modelPromotionVoucher;
    private $_modelLuckyDrawHistoryModel;
    private $_modelCustomerVoucherModel;
    private $auth;
    private $_userModel;
    private $session;
    private $_modelCustomer;
    private $_recordTypeModel;

    public function __construct()
    {
        parent::__construct();
        $this->_model                      = model(PromotionModel::class);
        $this->_modelPromotionVoucher      = model(PromotionVoucherModel::class);
        $this->_modelLuckyDrawHistoryModel = model(LuckyDrawHistoryModel::class);
        $this->_modelCustomerVoucherModel  = model(CustomerVoucherModel::class);
        $this->session                     = Services::session();
        $this->auth                        = Services::authentication();
        $this->_modelCustomer              = model(CusModel::class);
        $this->_userModel                  = model(UserModel::class);
        $this->_recordTypeModel            = model(RecordTypeModel::class);
    }

    public function luckyDraw()
    {
        //set breadcrumb
        BreadCrumbCell::add('Home', base_url());
        BreadCrumbCell::add(lang('LuckyDraw.lucky_spin'), route_to('lucky_draw'));
        $dataSpin                = $this->_model->findAll();
        $this->_data['dataSpin'] = $dataSpin;
        return $this->_render('lucky-spin/index', $this->_data);
    }

    public function actionSpin()
    {
        $response = [];

        // check draw
        if (!logged_in() && $this->request->getCookie('luck_draw')) {
            $response['error']   = 1;
            $response['message'] = lang('LuckyDraw.has_lucky_spin');
            return $this->response->setJSON($response);
        }

        if (logged_in()) {
            $customerSpin = $this->_modelLuckyDrawHistoryModel->where('customer_id', $this->customer->id)
                ->where('created_at >= ', date('Y-m-d 00:00:00'))->first();
            if (isset($customerSpin->history_id)) {
                $response['error']   = 1;
                $response['message'] = lang('LuckyDraw.has_lucky_spin');
                return $this->response->setJSON($response);
            }
        }

        try {
            // get milestone id
            $milestone = $this->_recordTypeModel->where('developer_name', PromotionEnum::MILESTONE_KEY)->first();
            if(isset($milestone->id)) {
                $milestoneID =  $milestone->object_type;
            }else {
                $maxHistory = $this->_modelLuckyDrawHistoryModel->select('MAX(history_id) as max_id')->first();
                $milestoneID = $maxHistory->max_id ?? 0;
                $this->_recordTypeModel->insertOrUpdate([
                    'developer_name' => PromotionEnum::MILESTONE_KEY,
                    'object_type'    => $milestoneID,
                ]);
            }

            $histories = $this->_modelLuckyDrawHistoryModel
                ->join('promotion_voucher', 'lucky_draw_history.promo_voucher_id = promotion_voucher.voucher_id')
                ->where('lucky_draw_history.history_id >', $milestoneID)
                ->select('voucher_discount_type, voucher_discount_value, COUNT(voucher_discount_type) AS discount_type_count')
                ->groupBy('voucher_discount_type, voucher_discount_value')
                ->findAll();

            $totalFreeGift = 0;
            $totalVoucher  = 0;
            $totalCash     = 0;

            foreach ($histories as $item) {
                if ($item->voucher_discount_type == PromotionEnum::DISCOUNT_TYPE_FREE_GIFT) {
                    $totalFreeGift += $item->discount_type_count;
                }
                if (($item->voucher_discount_type == PromotionEnum::DISCOUNT_TYPE_PERCENT || $item->voucher_discount_type == PromotionEnum::DISCOUNT_TYPE_VALUE)
                    && $item->voucher_discount_value != PromotionEnum::AMOUNT_CASH
                ) {
                    $totalVoucher += $item->discount_type_count;
                }

                if ($item->voucher_discount_type == PromotionEnum::DISCOUNT_TYPE_VALUE
                    && $item->voucher_discount_value == PromotionEnum::AMOUNT_CASH) {
                    $totalCash += $item->discount_type_count;
                }
            }

            if ($totalFreeGift < PromotionEnum::COUNT_WIN_FREE_GIFT) {
                $this->_model->orGroupStart()
                                    ->orWhere('discount_type', PromotionEnum::DISCOUNT_TYPE_FREE_GIFT)
                                 ->groupEnd();
            }
            if ($totalVoucher < PromotionEnum::COUNT_WIN_VOUCHER) {
                $this->_model->orGroupStart()
                                    ->orWhere('discount_type', PromotionEnum::DISCOUNT_TYPE_PERCENT)
                                        ->orGroupStart()
                                         ->orWhere('discount_type', PromotionEnum::DISCOUNT_TYPE_VALUE)
                                         ->where('discount_value !=', PromotionEnum::AMOUNT_CASH)
                                        ->groupEnd()
                                    ->groupEnd();
            }
            if ($totalCash < PromotionEnum::COUNT_WIN_CASH) {
                $this->_model->orGroupStart()
                                        ->orWhere('discount_type', PromotionEnum::DISCOUNT_TYPE_VALUE)
                                        ->where('discount_value', PromotionEnum::AMOUNT_CASH)
                                    ->groupEnd();
            }
            $promotion = $this->_model->orderBy('rand()')->first();
            $promotion->discount_image = $promotion->image;

            // create promotion voucher

            $voucherCode        = $this->_modelPromotionVoucher->generateCode();
            $dataPromotion      = [
                'promotion_id'           => $promotion->promo_id,
                'voucher_code'           => $voucherCode,
                'voucher_title'          => $promotion->promo_name,
                'voucher_description'    => $promotion->promo_description,
                'voucher_discount_type'  => $promotion->discount_type,
                'voucher_discount_value' => $promotion->discount_value,
                'voucher_status'         => EVoucherStatus::UNUSED,
            ];
            $promotionVoucherID = $this->_modelPromotionVoucher->insert($dataPromotion);

            // customer voucher
            if (logged_in()) {
                $this->_modelCustomerVoucherModel->insert([
                    'voucher_id'  => $promotionVoucherID,
                    'customer_id' => $this->customer->id,
                ]);
            }


            // create history spin
            $dataLuckyDrawHistory = [
                'ip_address'       => $this->request->getIPAddress(),
                'user_agent'       => $this->request->getUserAgent(),
                'customer_id'      => logged_in() ? $this->customer->id : null,
                'promo_voucher_id' => $promotionVoucherID,
                'status'           => LuckyDrawHistoryStatusEnum::NEW,
                'success'          => LuckyDrawHistorySuccessTypeEnum::WIN,
            ];

            $luckyDrawHistoryID = $this->_modelLuckyDrawHistoryModel->insert($dataLuckyDrawHistory);

            // save milestone id
            if (($totalFreeGift + $totalVoucher + $totalCash + 1) == PromotionEnum::MAX_TURN) {
                $this->_recordTypeModel->insertOrUpdate([
                    'developer_name' => PromotionEnum::MILESTONE_KEY,
                    'object_type'    => $luckyDrawHistoryID,
                ]);
            }


            $response['error']  = 0;
            $response['data']   = [
                'promotion' => [
                    'promo_id'       => (int) $promotion->promo_id,
                    'promo_name'     => $promotion->promo_name,
                    'discount_type'  => $promotion->discount_type,
                    'discount_image' => $promotion->discount_image,
                ],
                'voucher_code'          => $voucherCode,
                'voucher_id'            => $promotionVoucherID,
                'lucky_draw_history_id' => $luckyDrawHistoryID,
            ];

        } catch (DatabaseException $e) {
            $response['error']   = 1;
            $response['message'] = $e->getMessage();
        }

        $this->response->setCookie('luck_draw', true, 60 * 60 * 24);
        return $this->response->setJSON($response);
    }


    public function luckyDrawRegisterInfo($id)
    {
        if ( logged_in() ) {
            return redirect()->route('cus_logout');
        }

        //set breadcrumb
        BreadCrumbCell::add('Home', base_url());
        BreadCrumbCell::add(lang('LuckyDraw.register_info_title'), route_to('lucky_draw'));

        $historyLuckyDraw = $this->_modelLuckyDrawHistoryModel->where('history_id', $id)->first();
        if(!isset($historyLuckyDraw->history_id) || isset($historyLuckyDraw->customer_id)) {
            return $this->_render('errors/404', $this->_data);
        }

        return $this->_render('lucky-spin/register_info', $this->_data);
    }

    public function actionLuckyDrawRegisterInfo($id)
    {
        // throttler
        $this->_checkThrottler();

        // Validate here first, since some things,
        // like the password, can only be validated properly here.
        $rules = [
            'full_name'  	=> 'required|min_length[2]',
            'username'  	=> 'required|alpha_numeric_space|min_length[2]|is_unique[users.username]',
            'email'			=> 'permit_empty|valid_email|is_unique[customer.cus_email]',
            'phone'			=> 'required|valid_phone|is_unique[customer.cus_phone]',
        ];

        if (! $this->validate($rules, $this->messageValidate()))
        {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Validate passwords since they can only be validated properly here
        $rules = [
            'password'     => 'required|min_length[6]|alpha_numeric',
            'pass_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules, $this->messageValidate())) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }
        $postData = $this->request->getPost();
        $postData['cus_code'] = $this->_modelCustomer->generateCode();

        try {
            $this->db->transBegin();
            // Save the user first
            $insertData = [
                'full_name'     => $postData['full_name'],
                'username'      => $postData['username'],
                'password_hash' => Password::hash($this->request->getPost('password')),
                'email'         => $postData['email'],
                'user_type'     => UserTypeEnum::CUSTOMER,
            ];
            $user              = new User($insertData);
            $user->activate();

            $user_id = $this->_userModel->insert($user);

            $newCustomer = new CusEntity($postData);
            $newCustomer->user_id = $user_id;
            $newCustomer->cus_full_name = $postData['full_name'];
            $newCustomer->cus_email = $postData['email'];
            $newCustomer->cus_phone = $postData['phone'];

            $customerID = $this->_modelCustomer->createCustomer($newCustomer);


            $this->_modelLuckyDrawHistoryModel->where('history_id', $id)->update(null, [
                'customer_id'    => $customerID,
                'user_full_name' => $postData['full_name'],
                'user_email'     => $postData['email'],
                'user_phone'     => $postData['phone'],
            ]);

            $luckyDraw = $this->_modelLuckyDrawHistoryModel->where('history_id', $id)->first();
            $this->_modelCustomerVoucherModel->insert([
                'voucher_id'  => $luckyDraw->promo_voucher_id,
                'customer_id' => $customerID
            ]);

            $this->db->transCommit();
        } catch (DatabaseException $e) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('errors', $e->getMessage());
        }
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $this->auth->attempt(['username' => $username, 'password' => $password]);

        // Success!
        return redirect()
            ->to(base_url(route_to('voucher_list')))
            ->withCookies()
            ->with('message', lang('AuthCustomer.loginSuccess'));
    }

    public function _checkThrottler()
    {
        $throttler = service('throttler');

        if ($throttler->check(md5($this->request->getIPAddress()), 4, MINUTE) === false) {
            return service('response')
                ->setStatusCode(429)
                ->setBody(lang('AuthCustomer.tooManyRequests', [$throttler->getTokentime()]));
        }
    }

    private function messageValidate()
    {
        return [
            'email'        => [
                'required'    => lang('AuthCustomer.email_required'),
                'valid_email' => lang('AuthCustomer.email_valid_email'),
                'is_unique'   => lang('AuthCustomer.email_is_unique'),
            ],
            'phone'    => [
                'required'      => lang('AuthCustomer.phone_required'),
                'is_unique'     => lang('AuthCustomer.phone_number_exist'),
            ],
            'full_name'    => [
                'required'   => lang('AuthCustomer.full_name_required'),
                'min_length' => lang('AuthCustomer.full_name_min_length'),
            ],
            'password'     => [
                'required'          => lang('AuthCustomer.password_required'),
                'min_length'        => lang('AuthCustomer.password_min_length'),
                'alpha_numeric'     => lang('AuthCustomer.password_invalid'),
            ],
            'pass_confirm' => [
                'required' => lang('AuthCustomer.pass_confirm_required'),
                'matches'  => lang('AuthCustomer.password_matches'),
            ],
            'username'     => [
                'required'            => lang('AuthCustomer.username_required'),
                'alpha_numeric_space' => lang('AuthCustomer.username_alpha_numeric_space'),
                'min_length'          => lang('AuthCustomer.username_min_length'),
                'is_unique'           => lang('AuthCustomer.username_is_unique'),
            ],
        ];
    }

}