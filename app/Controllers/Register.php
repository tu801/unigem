<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 11/6/2023
 */

namespace App\Controllers;


use App\Entities\CusEntity;
use App\Libraries\BreadCrumb\BreadCrumbCell;
use App\Models\CusModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Modules\Acp\Entities\User;
use Modules\Acp\Enums\UserTypeEnum;
use Modules\Acp\Models\User\UserModel;
use Modules\Auth\Config\Services;
use Modules\Auth\Password;

class Register extends BaseController
{
    protected $auth;
    protected $session;
    protected $_userModel;

    public function __construct()
    {
        parent::__construct();
        $this->session      = Services::session();
        $this->auth         = Services::authentication();
        $this->_model       = model(CusModel::class);
        $this->_userModel   = model(UserModel::class);
    }

    public function register()
    {
        if ( logged_in() ) {
            return redirect()->route('cus_logout');
        }

        //set breadcrumb
        BreadCrumbCell::add('Home', base_url());
        BreadCrumbCell::add(lang('Home.cus_register'), route_to('cus_register'));

        return $this->_render('customer/auth/register', $this->_data);
    }

    public function actionRegister()
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
        $postData['cus_code'] = $this->_model->generateCode();

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

            $this->_model->createCustomer($newCustomer);
            $this->db->transCommit();
        } catch (DatabaseException $e) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('errors', $e->getMessage());
        }


//        $activator = service('activator');
//        $sent      = $activator->send($user);
//
//        if (! $sent) {
//            return redirect()
//                ->back()
//                ->withInput()
//                ->with('error', $activator->error() ?? lang('AuthCustomer.unknownError'));
//        }

        // Success!
        return redirect()
            ->route('cus_login')
            ->with('message', lang('AuthCustomer.registerSuccess'));
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