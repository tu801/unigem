<?php

/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 11/5/2023
 */

namespace App\Controllers\Customer;


use App\Controllers\BaseController;
use App\Enums\UserTypeEnum;
use App\Libraries\BreadCrumb\BreadCrumbCell;
use App\Models\CusModel;
use App\Models\Store\Customer\CustomerModel;
use App\Models\User\UserModel;
use App\Traits\SpamFilter;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\I18n\Time;

class Profile extends BaseController
{
    use SpamFilter;

    /**
     * @var UserModel
     */
    protected $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->_model  = model(CustomerModel::class);
        $this->userModel = model(UserModel::class);

        // check customer logged in
        $this->checkCustomerLoggedIn();
    }

    public function profile()
    {
        if ($this->user->user_type == UserTypeEnum::ADMIN) {
            return redirect()->route('/');
        }

        return $this->_render('customer/profile', $this->_data);
    }

    public function profileInfo()
    {
        //set breadcrumb
        BreadCrumbCell::add('Home', base_url());
        BreadCrumbCell::add(lang('CustomerProfile.my_account'), route_to('cus_profile'));
        BreadCrumbCell::add(lang('CustomerProfile.cus_information'), route_to('edit_cus_profile'));

        if ($this->request->getPost()) {
            $this->checkSpam();
            return $this->saveProfileAction();
        }

        return $this->_render('customer/profile_info', $this->_data);
    }

    public function saveProfileAction($customer)
    {
        $postData = $this->request->getPost();
        if (empty($postData)) {
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
        if (! $this->validate($rules, $errMess)) {
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

    public function changePassword()
    {
        if (!auth()->loggedIn() || $this->user->user_type == UserTypeEnum::ADMIN) {
            return redirect()->route('/');
        }

        if ($this->request->getPost()) {
            $this->checkSpam();
            return $this->changePasswordAction();
        }

        return $this->_render('customer/cus_change_password', $this->_data);
    }

    public function changePasswordAction()
    {
        $postData = $this->request->getPost();
        if (empty($postData)) {
            return redirect()->back()->with('errors', lang('Acp.invalid_request'));
        }

        // Validate here first, since some things can only be validated properly here.
        $rules = [
            'password'          => 'required|min_length[6]',
            'password_confirm'  => 'required|matches[password]',
        ];
        $errMess = [
            'password' => [
                'required' => lang('Customer.password_required'),
                    'min_length' => lang('Customer.password_min_length'),
            ],
            'password_confirm' => [
                'required' => lang('Customer.password_confirm_required'),
                'matches' => lang('Customer.password_confirm_matches_password'),
            ],
        ];
        //validate the input
        if (! $this->validate($rules, $errMess)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $user = $this->user;
        
        // check old password
        $oldPassword = $this->request->getPost('old_password');
        /** @var Passwords $passwords */
        $passwords = service('passwords');

        // Now, try matching the passwords.
        if (! $passwords->verify($oldPassword, $user->password_hash)) {
            return redirect()->back()->withInput()->with('error', lang('Customer.old_password_not_match'));
        }
        
        // save new password 
        try {
            $this->db->transBegin();
            
            //record old password
            $oldData = [
                'user_id' => $user->id,
                'user_name' => $user->username,
                'old_password' => $user->password_hash
            ];

            $user->password = $postData['password'];
            $this->userModel->save($user);

            if ($user->requiresPasswordReset()) {
                $user->undoForcePasswordReset();
            }

            // log reset password
            $logData = [
                'title' => lang('Customer.change_password'),
                'description' => "Khách hàng #{$user->username} đã đổi mật khẩu",
                'properties' => $oldData,
                'subject_id' => $user->id,
                'subject_type' => UserModel::class,
            ];
            logAction($logData);

            $this->db->transCommit();
        } catch (DatabaseException $e) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('errors', $e->getMessage());
        }
        
        $authenticator = auth('session')->getAuthenticator();
        $authenticator->logout();

        return redirect()->route('cus_login')->with('message', lang('Customer.change_password_success'));
    }
}
