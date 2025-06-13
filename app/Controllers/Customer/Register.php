<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Entities\Store\Customer\Customer;
use App\Entities\User;
use App\Enums\UserTypeEnum;
use App\Libraries\BreadCrumb\BreadCrumbCell;
use App\Libraries\SeoMeta\SeoMetaCell;
use App\Models\Country;
use App\Models\Store\Customer\CustomerModel;
use App\Models\User\UserModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Events\Events;

class Register extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        parent::__construct();

        $this->_model = model(CustomerModel::class);
        $this->userModel = model(UserModel::class);

        //SEOData config
        SeoMetaCell::setCanonical();
        SeoMetaCell::setOgType();
        SeoMetaCell::add('meta_desc', get_theme_config('general_seo_description'));
        SeoMetaCell::add('meta_keywords', get_theme_config('general_seo_keyword'));
        SeoMetaCell::add('og_title', get_theme_config('general_site_title'));
        SeoMetaCell::add('og_site_name', get_theme_config('general_site_title'));
        SeoMetaCell::add('og_description', get_theme_config('general_seo_description'));
        SeoMetaCell::add('og_url', base_url());
        $og_img_data = get_theme_config('general_seo_open_graph_image');
        if (isset($og_img_data->full_image)) {
            SeoMetaCell::add('og_image', base_url($og_img_data->full_image));
        }
    }

    public function register()
    {
        $authenticator = auth('session')->getAuthenticator();

        if ($authenticator->loggedIn()) {
            return redirect()->route('cus_logout');
        }

        //set breadcrumb
        BreadCrumbCell::add('Home', base_url());
        BreadCrumbCell::add(lang('Home.cus_register'), route_to('cus_register'));
        $this->page_title = lang('Home.cus_register');

        $this->_data['countries'] = model(Country::class)->getCountries();
        return $this->_render('customer/auth/register', $this->_data);
    }

    public function registerSubmit()
    {
        $postData = $this->request->getPost();
        $throttler = service('throttler');

        if ($throttler->check($this->request->getIPAddress(), 2, 5) === false) {
            return service('response')->setStatusCode(429)->setBody(lang('Auth.tooManyRequests', [$throttler->getTokentime()]));
        }

        $validRules = [
            'cus_full_name' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => lang('Customer.cus_full_name_required'),
                    'min_length' => lang('Customer.cus_full_name_min_length'),
                ]
            ],
            'cus_phone' => [
                'rules' => 'required|min_length[8]|is_unique[customer.cus_phone]',
                'errors' => [
                    'required' => lang('Customer.cus_phone_required'),
                    'min_length' => lang('Customer.cus_phone_min_length'),
                    'is_unique' => lang('Customer.cus_phone_unique'),
                ]
            ],
            'cus_email' => [
                'rules' => 'required|valid_email|is_unique[customer.cus_email]',
                'errors' => [
                    'required' => lang('Customer.cus_email_required'),
                    'valid_email' => lang('Customer.cus_email_valid_email'),
                    'is_unique' => lang('Customer.cus_email_unique'),
                ]
            ],
            'cus_address' => [
                'rules' => 'required',
                'errors' => [
                    'required' => lang('Customer.cus_address_required'),
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => lang('Customer.password_required'),
                    'min_length' => lang('Customer.password_min_length'),
                ]
            ],
            'password_confirm' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => lang('Customer.password_confirm_required'),
                    'matches' => lang('Customer.password_confirm_matches_password'),
                ]
            ],
        ];

        //validate the input
        if (! $this->validate($validRules)) {
            //return the errors
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // create new customer
        try {
            $this->db->transBegin();
            $customer = new Customer($postData);
            $customer->cus_code = $this->_model->generateCode();
            $customer->country_id = $postData['country'] ?? null;
            $customer->province_id  = $postData['province'] ?? null;
            $customer->district_id  = $postData['district'] ?? null;
            $customer->ward_id  = $postData['ward'] ?? null;

            $cusId = $this->_model->insert($customer);

            if (!$cusId) {
                $errors = $this->_model->errors();
                $this->db->transRollback();
                return redirect()->back()->withInput()->with('errors', $errors);
            }

            // create customer login account
            $newCustomer = $this->_model->find($cusId);
            $cusAccount = new User();
            $cusAccount->email = $newCustomer->cus_email;
            $cusAccount->username = $newCustomer->cus_email;
            $cusAccount->password = $postData['password'];
            $cusAccount->user_type = UserTypeEnum::CUSTOMER;
            $this->userModel->save($cusAccount);
            $newCusAccount = $this->userModel->findById($this->userModel->getInsertID());
            $this->userModel->addToDefaultGroup($newCusAccount);
            $newCusAccount->addGroup('customer');

            // update customer with user_id
            $updateData = [
                'user_id' => $newCusAccount->id
            ];
            $this->_model->update($cusId, $updateData);

            //log Action
            $logData = [
                'module' => 'site',
                'title' => 'Customer Register',
                'description' => "#{$postData['cus_full_name']} đã đăng ký",
                'properties' => $postData,
                'subject_id' => $cusId,
                'subject_type' => CustomerModel::class,

            ];
            $this->logAction($logData);

            $this->db->transCommit();

            Events::trigger('register', $newCusAccount);

            /** @var Session $authenticator */
            $authenticator = auth('session')->getAuthenticator();

            $authenticator->startLogin($newCusAccount);

            // If an action has been defined for register, start it up.
            $hasAction = $authenticator->startUpAction('register', $newCusAccount);
            if ($hasAction) {
                return redirect()->to(route_to('cus_activate_account'));
            }
        } catch (DatabaseException $e) {
            dd($e->getMessage());
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('errors', $e->getMessage());
        }
    }
}
