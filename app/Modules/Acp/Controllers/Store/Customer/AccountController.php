<?php
namespace App\Modules\Acp\Controllers\Store\Customer;


use CodeIgniter\Database\Exceptions\DatabaseException;
use App\Entities\User;
use Modules\Acp\Controllers\Store\Customer\CustomerController;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use CodeIgniter\Shield\Models\UserIdentityModel;
use CodeIgniter\Validation\Exceptions\ValidationException;
use App\Enums\Store\CustomerActiveEnum;
use App\Enums\UserTypeEnum;
use App\Models\Store\Customer\CustomerModel;
use App\Models\User\UserModel;

class AccountController extends CustomerController {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * show create customer account form
     */
    public function createCustomerAccount($cus_id)
    {
        $this->_data['title'] = lang('Customer.create_customer_title');

        $customer = $this->_model->find($cus_id);
        if (isset($customer->id)) {
            $this->_data['customer'] = $customer;
            $this->_render('\customer\create_customer_account', $this->_data);
        } else {
            return redirect()->route('customer')->with('error', lang('Customer.customer_not_exist'));
        }
    }

    /**
     * create customer account
     *
     * @param int $cus_id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function actionCustomerAccount($cus_id)
    {
        $customer = $this->_model->find($cus_id);
        if (!isset($customer->id) && isset($customer->user_id)) {
            return redirect()->route('customer')->with('error', lang('Customer.customer_not_exist'));
        }

        $postData = $this->request->getPost();
        // Validate here first, since some things,
        // like the password, can only be validated properly here.
        $rules   = [
            'username'     => 'required|min_length[4]|is_unique[users.username]',
            'email'        => 'required|valid_email',
            'password'     => 'required|min_length[4]|strong_password',
            'pass_confirm' => 'required|matches[password]',
        ];
        $errMess = [
            'username'     => [
                'required'            => lang('User.username_required'),
                'min_length'          => lang('User.min_length'),
                'is_unique'           => lang('User.user_is_exist'),
            ],
            'email'        => [
                'required'    => lang('User.email_required'),
                'valid_email' => lang('User.valid_email'),
            ],
            'password'     => [
                'required'   => lang('User.pw_required'),
                'min_length' => lang('User.pw_length'),
            ],
            'pass_confirm' => [
                'required' => lang('User.pwcf_required'),
                'matches'  => lang('User.pwcf_matches'),
            ],
        ];

        //validate the input
        if (!$this->validate($rules, $errMess)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        try {
            $this->db->transStart();
            //good then save the new customer account
            $cusAccount = new User($postData);
            $cusAccount->username = $postData['username'];
            $cusAccount->user_type = UserTypeEnum::CUSTOMER;

            try {
                $this->_userModel->save($cusAccount);
            } catch (ValidationException $e) {
                $this->db->transRollback();
                return redirect()->back()->withInput()->with('errors', $this->_userModel->errors());
            }

            // To get the complete user object with ID, we need to get from the database
            $user = $this->_userModel->findById($this->_userModel->getInsertID());

            // Add to default group
            $this->_userModel->addToDefaultGroup($user);
            $user->addGroup('customer');

            // update customer with user_id
            $this->_model->update($cus_id, ['user_id' => $user->id]);

            if (isset($postData['force_pass_reset'])) {
                $user->forcePasswordReset();
            }

            //log Action
            $logData = [
                'title'        => 'Create Customer Account',
                'description'  => "#{$this->user->username} đã tạo tài khoản khách hàng #{$user->username}",
                'properties'   => $user->toArray(),
                'subject_id'   => $user->id,
                'subject_type' => UserModel::class,
            ];
            $this->logAction($logData);

            $this->db->transCommit();
        } catch (DatabaseException $e) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('errors', $e->getMessage());
        }

        return redirect()->route('customer_detail', [$cus_id])->with('message', lang('User.addSuccess', [$user->username]));
    }

    /**
     * Activate customer account
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function active($id)
    {
        $cusData = $this->_model->find($id);
        $user = null;

        // check spam
        $this->checkSpam();

        if (!isset($cusData->id)) {
            return redirect()->back()->with('errors', lang('Acp.invalid_request'));
        }
        if (isset($cusData->user_id)) $user = $this->_userModel->find($cusData->user_id);
        try {
            $this->db->transStart();
            $this->_model->update($cusData->id, ['active' => CustomerActiveEnum::ACTIVE]);

            // also update the user active status
            if (isset($user->id)) {
                $user->activate();
                $this->_userModel->save($user);

                $identityModel = model(UserIdentityModel::class);

                $identity = $identityModel->getIdentityByType(
                    $user,
                    Session::ID_TYPE_EMAIL_ACTIVATE
                );

                if (isset($identity->id)) {
                    $identityModel->delete($identity->id);
                }
            }

            // log Action
            $logData = [
                'title' => 'Activate Customer',
                'description' => "#{$this->user->username} đã active customer #{$cusData->cus_full_name}",
                'subject_id' => $cusData->id,
                'subject_type' => CustomerModel::class,
            ];
            $this->logAction($logData);

            $this->db->transCommit();
        } catch (DatabaseException $e) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('errors', $e->getMessage());
        }


        //log Action
        $logData = [
            'title' => 'Active User',
            'description' => "#{$this->user->username} đã active user #{$user->username}",
            'subject_id' => $user->id,
            'subject_type' => $user->model_class,
        ];
        $this->logAction($logData);

        return redirect()->back()->with('message', lang('User.activate_success'));
    }
}