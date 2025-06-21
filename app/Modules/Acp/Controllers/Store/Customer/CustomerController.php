<?php

/**
 * Author: tmtuan
 * Created date: 8/19/2023
 * Project: unigem
 **/

namespace Modules\Acp\Controllers\Store\Customer;

use App\Entities\Store\Customer\Customer;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\I18n\Time;
use CodeIgniter\Test\Fabricator;
use Config\Database;
use Libraries\Collection\Collection;
use Modules\Acp\Controllers\AcpController;
use Modules\Acp\Controllers\Traits\CustomerAvatar;
use App\Entities\User;
use App\Enums\Store\CustomerActiveEnum;
use App\Enums\UserTypeEnum;
use App\Models\Country;
use App\Models\Store\Customer\CustomerModel;
use App\Models\Store\DistrictModel;
use App\Models\Store\ProvinceModel;
use App\Models\Store\WardModel;
use App\Models\User\UserModel;
use App\Traits\SpamFilter;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use CodeIgniter\Shield\Models\UserIdentityModel;
use CodeIgniter\Validation\Exceptions\ValidationException;
use Modules\Acp\Traits\deleteItem;

class CustomerController extends AcpController
{
    use deleteItem, CustomerAvatar, SpamFilter;
    use \App\Traits\ActivationEmail;


    protected $db;
    protected $_userModel;
    protected $auth_config;

    public function __construct()
    {
        parent::__construct();
        if (empty($this->_model)) {
            $this->_model = model(CustomerModel::class);
        }
        $this->_userModel = model(UserModel::class);
        $this->db             = Database::connect(); //Load database connection
        $this->auth_config = config('Auth');
    }

    /**
     * List pages
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function index()
    {
        $this->_data['title'] = lang("Customer.page_title");
        $inputData = $this->request->getPost();

        //get Users Data
        if (isset($inputData['search'])) {
            if ($inputData['keyword'] != '') {
                $keyword = $inputData['keyword'];
                $this->_model->orLike('customer.cus_code', "%{$keyword}%");
                $this->_model->orLike('customer.cus_phone', "%{$keyword}%");
                $this->_data['search_title'] = $keyword;
            }
        }

        if (isset($postData) && !empty($postData)) {
            if (!empty($postData['sel'])) {
                $this->_model->delete($postData['sel']);
            } else return redirect()->back()->with('error', lang('Acp.no_item_to_delete'));
        }

        $this->_model->orderBy('customer.created_at', 'DESC');

        $this->_data['data'] = $this->_model->paginate();
        $this->_data['pager'] = $this->_model->pager;
        $this->_data['countAll'] = $this->_model->countAll();
        $this->_render('\customer\index', $this->_data);
    }

    /**
     * show add Customer form
     */
    public function addCustomer()
    {
        $this->_data['title'] = lang('Customer.add_title');

        $this->_data['countries'] = model(Country::class)->getCountries();
        $this->_render('\customer\add', $this->_data);
    }

    public function addAction()
    {
        // Validate here first, since some things can only be validated properly here.
        $rules = [
            'cus_phone'     => 'required|min_length[8]|is_unique[customer.cus_phone]',
            'cus_full_name' => 'required',
            'cus_email'        => 'permit_empty|valid_email|is_unique[customer.cus_email]',
            'cus_address'   => 'required',
            'password'      => 'required|min_length[6]',
            'password_confirm'      => 'matches[password]'
        ];
        $errMess = [
            'cus_phone' => [
                'required' => lang('Customer.phone_required'),
                'min_length' => lang('Customer.cus_phone_min_length'),
                'is_unique' => lang('Customer.phone_is_unique'),
            ],
            'cus_full_name' => [
                'required' => lang('Customer.full_name_required')
            ],
            'cus_email' => [
                'required' => lang('Customer.email_required'),
                'valid_email' => lang('Customer.valid_email'),
                'is_unique' => lang('Customer.email_exits')
            ],
            'cus_address' => [
                'required' => lang('Customer.cus_address_required')
            ],
            'password' => [
                'required' => lang('Customer.password_required'),
                'min_length' => lang('Customer.password_min_length'),
            ],
            'password_confirm' => [
                'matches' => lang('Customer.password_confirm_matches_password'),
            ],
        ];

        //validate the input
        if (! $this->validate($rules, $errMess)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $postData = $this->request->getPost();
        $customer = new Customer($postData);

        try {
            $this->db->transBegin();

            $customer->cus_code = $this->_model->generateCode();
            $customer->cus_birthday = !empty($postData['cus_birthday']) ? Time::parse($postData['cus_birthday'])->format('Y-m-d') : null;
            if ($postData['country_id'] != VIETNAM_COUNTRY_ID) {
                $customer->province_id  = 0;
                $customer->district_id  =  0;
                $customer->ward_id  = 0;
            }

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
            $this->_userModel->save($cusAccount);
            $newCusAccount = $this->_userModel->findById($this->_userModel->getInsertID());
            $this->_userModel->addToDefaultGroup($newCusAccount);
            $newCusAccount->addGroup('customer');

            // update customer with user_id
            $updateData = [
                'user_id' => $newCusAccount->id
            ];
            $this->_model->update($cusId, $updateData);

            //log Action
            $logData = [
                'title' => lang('Log.add_customer'),
                'description' => lang('Log.add_shop_desc', [$this->user->username, $customer->cus_email]),
                'properties' => $postData,
                'subject_id' => $cusId,
                'subject_type' => CustomerModel::class,
            ];
            $this->logAction($logData);

            $this->db->transCommit();

            // send activation email
            $this->sendActivationEmail($newCusAccount);
        } catch (DatabaseException $e) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('errors', $e->getMessage());
        }

        return redirect()->route('customer')->with('message', lang('Customer.addSuccess', [$customer->cus_email]));
    }

    /**
     * show edit Customer form
     */
    public function editCustomer($cus_id)
    {
        $this->_data['title'] = lang('Customer.edit_title');

        $customer = $this->_model->find($cus_id);
        if (isset($customer->id)) {
            $this->_data['customer'] = $customer;
            $this->_render('\customer\edit', $this->_data);
        } else {
            return redirect()->route('customer')->with('error', lang('Customer.customer_not_exist'));
        }
    }

    public function editAction($cus_id)
    {
        if (!$this->user->inGroup('superadmin', 'admin')) {
            return redirect()->route('customer')->with('error', lang('Acp.no_permission'));
        }

        $item = $this->_model->find($cus_id);

        if (is_null($item)) {
            return redirect()->route('customer')->with('error', lang('Acp.no_item_found'));
        }

        // Validate here first, since some things can only be validated properly here.
        $rules = [
            'cus_phone'        => 'required|valid_phone|is_unique[customer.cus_phone,id,' . $cus_id . ']',
            'cus_full_name'      => 'required',
            'cus_email'            => 'permit_empty|valid_email|is_unique[customer.cus_email,id, ' . $cus_id . ']',
        ];
        $errMess = [
            'mobile' => [
                'cus_phone' => lang('Customer.phone_required'),
                'is_unique' => lang('Customer.phone_is_unique')
            ],
            'cus_full_name' => [
                'required' => lang('Customer.full_name_required')
            ],
            'cus_email' => [
                'required' => lang('Customer.email_required'),
                'valid_email' => lang('Customer.valid_email'),
                'is_unique' => lang('Customer.email_exits')
            ],
        ];

        //validate the input
        if (! $this->validate($rules, $errMess)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        // good then save the item
        $postData = $this->request->getPost();
        $postData['cus_birthday'] = !empty($postData['cus_birthday']) ? Time::parse($postData['cus_birthday'])->format('Y-m-d') : null;
        try {
            $this->db->transBegin();
            $this->_model->update($cus_id, $postData);
            $this->db->transCommit();
        } catch (DatabaseException $e) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('errors', $e->getMessage());
        }

        // log Action
        $logData = [
            'title' => 'Edit Customer',
            'description' => lang('Customer.editCustomerLog', [$this->user->username, $item->cus_email]),
            'properties' => $item,
            'subject_id' => $item->id,
            'subject_type' => CustomerModel::class,
        ];
        $this->logAction($logData);

        return redirect()->route('customer')->with('message', lang('Customer.editSuccess', [$this->user->username, $item->cus_email]));
    }

    public function detail($cus_id)
    {
        $this->_data['title'] = lang('Customer.detail_title');

        $customer = $this->_model->find($cus_id);

        if (isset($customer->id)) {
            $this->_data['customer'] = $customer;
            $this->_render('\customer\profile', $this->_data);
        } else {
            return redirect()->route('customer')->with('error', lang('Customer.customer_not_exist'));
        }
    }

    public function ajaxSearchCustomer()
    {
        $inputData = $this->request->getPost();
        $response = [];
        if (isset($inputData['keyword_search']) && $inputData['keyword_search'] !== '') {
            $querySearch = esc($inputData['keyword_search']);
            $data = $this->_model->like('cus_full_name', $querySearch)
                ->orLike('cus_email', $querySearch)
                ->orLike('cus_phone', $querySearch)
                ->orLike('cus_code', $querySearch)
                ->findAll();
            $response['error'] = 0;
            $response['data'] = $data;
        } else {
            $response['error'] = 1;
            $response['message'] = lang('Vui lòng nhập Từ khóa');
        }
        return $this->response->setJson($response);
    }

    /**
     * generate customer for testing purpose
     * @return RedirectResponse
     * @throws \Exception
     */
    public function generateCustomer()
    {
        //check permission
        if (!$this->user->inGroup('superadmin')) return redirect()->route('dashboard')->with('error', lang('Acp.no_permission'));

        $total = $this->_model->countAll();
        $provinces = model(ProvinceModel::class)->findAll();
        $districts = model(DistrictModel::class)->findAll();
        $wards = model(WardModel::class)->findAll();

        $provinceData = new Collection($provinces);
        $districtData = new Collection($districts);
        $wardData = new Collection($wards);

        if ($total < 20) {
            $faker = new Fabricator(CustomerModel::class);
            $generator = $faker->getFaker();
            $override = [
                'active' => 1,
                'user_id' => null
            ];
            $faker->setOverrides($override);
            $customers = $faker->make(10);

            foreach ($customers as $cus) {
                $randId = random_int(1, count($provinces));
                $provinceItem = $provinceData->find(function ($item, $key) use ($randId) {
                    if ($randId == $key) return $item;
                });
                $districtItem = $districtData->find(function ($item) use ($provinceItem) {
                    if ($item['province_id'] == $provinceItem['id']) return $item;
                });
                $wardItem = $wardData->find(function ($item) use ($districtItem) {
                    if ($item['district_id'] == $districtItem['id']) return $item;
                });

                $cus->cus_address = $generator->address();
                $cus->cus_code = $this->_model->generateCode();
                $cus->province_id = $provinceItem['id'];
                $cus->district_id = $districtItem['id'];
                $cus->ward_id = $wardItem['id'];

                $this->_model->insert($cus);
            }
        }

        return redirect()->route('customer')->with('message', 'Generate complete!');
    }

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
