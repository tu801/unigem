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
use App\Models\Country;
use App\Models\CusModel;
use App\Models\Store\Customer\CustomerModel;
use App\Models\User\UserModel;
use App\Traits\SpamFilter;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\RedirectResponse;
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
        return $this->checkCustomerLoggedIn();
    }

    public function profile()
    {
        if (!auth()->loggedIn() || $this->user->user_type == UserTypeEnum::ADMIN) {
            return redirect()->route('/');
        }

        return $this->_render('customer/profile', $this->_data);
    }

    public function profileInfo()
    {
        if (!auth()->loggedIn() || $this->user->user_type == UserTypeEnum::ADMIN) {
            return redirect()->route('/');
        }

        $this->page_title = lang('Customer.account_detail');
        $this->_data['countries'] = model(Country::class)->getCountries();

        if ($this->request->getPost()) {
            $this->checkSpam();
            return $this->saveProfileAction();
        }

        return $this->_render('customer/profile_info', $this->_data);
    }

    private function saveProfileAction()
    {
        $postData = $this->request->getPost();
        if (empty($postData)) {
            return redirect()->back()->with('errors', lang('Acp.invalid_request'));
        }

        // Validate here first, since some things can only be validated properly here.
        $rules = [
            'cus_phone'        => 'required|valid_phone|is_unique[customer.cus_phone,id,' . $this->_data['customer']->id . ']',
            'cus_full_name'    => 'required',
            'cus_address'       => 'required',
        ];
        $errMess = [
            'mobile' => [
                'cus_phone' => lang('Customer.phone_required'),
                'is_unique' => lang('Customer.phone_is_unique')
            ],
            'cus_full_name' => [
                'required' => lang('Customer.full_name_required')
            ],
            'cus_address' => [
                'required' => lang('Customer.cus_address_required')
            ],
        ];
        //validate the input
        if (! $this->validate($rules, $errMess)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        // save data
        $postData['cus_birthday'] = !empty($postData['cus_birthday']) ? Time::parse($postData['cus_birthday'])->format('Y-m-d') : null;
        if ($postData['country_id'] != VIETNAM_COUNTRY_ID) {
            $postData['province_id']  = 0;
            $postData['district_id']  =  0;
            $postData['ward_id']  = 0;
        }
        
        try {
            $this->db->transBegin();
            $this->_model->update($this->_data['customer']->id, $postData);

            // log Action
            $logData = [
                'title' => 'Edit Customer Profile',
                'description' => lang('Customer.editCustomerProfileLog', [ $this->_data['customer']->cus_code . ' - ' . $this->_data['customer']->cus_full_name]),
                'properties' => $postData,
                'subject_id' => $this->_data['customer']->id,
                'subject_type' => CustomerModel::class,
            ];
            $this->logAction($logData);
            
            $this->db->transCommit();
        } catch (DatabaseException $e) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('errors', $e->getMessage());
        }

        return redirect()->route('edit_cus_profile')->with('message', lang('Customer.editProfileSuccess'));
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

    /**
     * Change password action
     *
     * @return RedirectResponse
     */
    private function changePasswordAction()
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
