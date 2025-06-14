<?php
namespace Modules\Ajax\Controllers;

use App\Models\Store\Customer\CustomerModel;
use CodeIgniter\Shield\Validation\ValidationRules;

class CustomerController extends AjaxBaseController {
    public function __construct()
    {
        parent::__construct();

        $this->_model = model(CustomerModel::class);
    }

    public function logout() {
        $this->checkSpam();

        if (!auth()->loggedIn()) {
            return $this->respond([
                'status' => '400',
                'message' => lang('Auth.not_logged_in')
            ]);
        }

        auth()->logout();
        return $this->respond([
            'status' => '200',
            'message' => lang('Auth.logoutSuccess')
        ]);
    }

    public function login() {
        $this->checkSpam();

        if (auth()->loggedIn()) {
            return $this->respond([
                'code' => '400',
                'message' => lang('Auth.already_logged_in')
            ]);
        }

        // Validate here first, since some things,
        // like the password, can only be validated properly here.
        $rules = $this->getValidationRules();

        if (! $this->validateData($this->request->getPost(), $rules, [], config('Auth')->DBGroup)) {
            return $this->respond([
                'code' => '401',
                'message' => $this->validator->getErrors()
            ]);
        }

        /** @var array $credentials */
        $credentials             = $this->request->getPost(setting('Auth.validFields')) ?? [];
        $credentials             = array_filter($credentials);
        $credentials['password'] = $this->request->getPost('password');
        $remember                = (bool) $this->request->getPost('remember');

        /** @var Session $authenticator */
        $authenticator = auth('session')->getAuthenticator();

        // Attempt to login
        $result = $authenticator->remember($remember)->attempt($credentials);
        if (! $result->isOK()) {
            return $this->respond([
                'code' => '401',
                'message' => $result->reason()
            ]);
        }

        $user = $authenticator->getUser();
        $customer = $this->_model->queryCustomerByUserId($user->id)->first();
        return $this->respond([
            'code' => '200',
            'message' => lang('Auth.loginSuccess'),
            'customer' => [
                'code' => $customer->cus_code,
                'email' => $customer->cus_email,
                'full_name' => $customer->cus_full_name,
                'phone' => $customer->cus_phone,
                'address' => $customer->cus_address,
                'country_id' => $customer->country_id,
                'province_id' => $customer->province_id,
                'district_id' => $customer->district_id,
                'ward_id' => $customer->ward_id
            ]
        ]);
    }

    /**
     * Returns the rules that should be used for customer validation.
     *
     * @return array<string, array<string, list<string>|string>>
     */
    protected function getValidationRules(): array
    {
        return [
            'username' => [
                'label' => 'Auth.username',
                'rules' => [
                    'required',
                    'max_length[30]',
                    'min_length[3]',
                ],
            ],
            'password' => [
                'label' => 'Auth.password',
                    'rules' => [
                        'required',
                        'max_byte[72]',
                    ],
                'errors' => [
                    'max_byte' => 'Auth.errorPasswordTooLongBytes',
                ]
            ],
        ];
    }
}