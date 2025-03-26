<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 11/5/2023
 */

namespace App\Controllers\Customer;


use App\Controllers\BaseController;
use App\Libraries\BreadCrumb\BreadCrumbCell;
use App\Models\CusModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\I18n\Time;
use Modules\Acp\Enums\UserTypeEnum;
use Modules\Acp\Models\Store\Customer\CustomerModel;
use Modules\Auth\Config\Services;

class Profile extends BaseController
{
    protected $session;

    public function __construct()
    {
        parent::__construct();
        $this->session = Services::session();
        $this->auth    = Services::authentication();
        $this->_model  = model(CusModel::class);

    }

    public function profile()
    {
        if (!$this->auth->check()) {
            return redirect()->route('cus_login');
        }

        if ( $this->user->user_type == UserTypeEnum::ADMIN ) {
            return redirect()->to(base_url());
        }

        //set breadcrumb
        BreadCrumbCell::add('Home', base_url());
        BreadCrumbCell::add(lang('CustomerProfile.my_account'), route_to('cus_profile'));

        $this->_data['user'] = $this->customer;

        return $this->_render('customer/profile', $this->_data);
    }

    public function profileInfo()
    {
        if (!$this->auth->check()) {
            return redirect()->route('cus_login');
        }

        if ( $this->user->user_type == UserTypeEnum::ADMIN ) {
            return redirect()->to(base_url());
        }

        //set breadcrumb
        BreadCrumbCell::add('Home', base_url());
        BreadCrumbCell::add(lang('CustomerProfile.my_account'), route_to('cus_profile'));
        BreadCrumbCell::add(lang('CustomerProfile.cus_information'), route_to('edit_cus_profile'));

        if ( $this->request->getPost() ) {
            $this->_checkThrottler();
            return $this->saveProfileAction($this->customer);
        }

        $this->_data['user'] = $this->customer;
        return $this->_render('customer/profile_info', $this->_data);
    }

    public function saveProfileAction($customer)
    {
        $postData = $this->request->getPost();
        if ( empty($postData) ) {
            return redirect()->back()->with('errors', lang('Acp.invalid_request'));
        }

        // Validate here first, since some things can only be validated properly here.
        $rules = [
            'full_name'     => 'required',
            'province_id'   => 'required',
            'district_id'   => 'required',
            'ward_id'       => 'required',
            'address'       => 'required',
        ];
        $errMess = [
            'full_name' => [
                'required' => lang('Customer.full_name_required')
            ],
            'province_id' => [
                'required' => lang('CustomerProfile.province_id_required')
            ],
            'district_id' => [
                'required' => lang('CustomerProfile.district_id_required')
            ],
            'ward_id' => [
                'required' => lang('CustomerProfile.ward_id_required')
            ],
            'address' => [
                'required' => lang('CustomerProfile.address_required')
            ],
        ];
        //validate the input
        if (! $this->validate($rules, $errMess))
        {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        // save data
        $updateData = [
            'cus_birthday'  => !empty($postData['cus_birthday']) ? Time::parse($postData['cus_birthday'])->format('Y-m-d') : null,
            'cus_full_name' => $postData['full_name'],
            'province_id'   => $postData['province_id'],
            'district_id'   => $postData['district_id'],
            'ward_id'       => $postData['ward_id'],
            'cus_address'   => $postData['address'],
        ];

        try {
            $this->db->transBegin();
            $this->_model->update($customer->id, $updateData);
            $this->db->transCommit();
        } catch (DatabaseException $e) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('errors', $e->getMessage());
        }
        $item = $this->_model->find($customer->id);
        // log Action
        $logData = [
            'title' => 'Edit Customer',
            'description' => lang('CustomerProfile.editCustomerLog', [$item->cus_code]),
            'properties' => $item,
            'subject_id' => $item->id,
            'subject_type' => CusModel::class,
        ];
        $this->logAction($logData);

        return redirect()->route('edit_cus_profile')->with('message', lang('CustomerProfile.editProfileSuccess'));
    }
}