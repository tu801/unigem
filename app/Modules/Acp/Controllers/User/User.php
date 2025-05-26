<?php

namespace Modules\Acp\Controllers\User;

use App\Entities\User as EntitiesUser;
use App\Models\User\UserMetaModel;
use App\Models\User\UserModel;
use CodeIgniter\Database\Exceptions\DataException;
use CodeIgniter\HTTP\RedirectResponse;
use Config\Email;
use Modules\Acp\Controllers\Traits\UserAvatar;
use App\Enums\UserTypeEnum;
use App\Models\Store\Customer\CustomerModel;
use Config\Services;
use Modules\Acp\Controllers\AcpController;

class User extends AcpController
{
    use UserAvatar;

    protected $userMetaModel;
    protected $auth_config;

    protected $currentAct = 'user';

    public function __construct()
    {
        parent::__construct();
        if (empty($this->_model)) {
            $this->_model = model(UserModel::class);
        }
        $this->userMetaModel = model(UserMetaModel::class);
        $this->auth_config = config('Auth');
    }

    public function index()
    {
        $this->_data['title'] = lang("User.user_title");
        $inputData = $this->request->getPost();
        $query = $this->request->getGet();

        //get Users Data
        if (isset($inputData['search'])) {
            if ($inputData['username'] !== '') {
                $this->_model->like('username', $inputData['username']);
                $this->_data['search_title'] = $inputData['username'];
            }
        }

        if (isset($query['deleted']) && $query['deleted'] == 1) {
            $this->_model->onlyDeleted();
            $this->_data['action'] = 'deleted';
        } else $this->_data['action'] = 'all';

        $this->_model->where('user_type', UserTypeEnum::ADMIN);

        $this->_data['data'] = $this->_model->paginate();
        $this->_data['pager'] = $this->_model->pager;
        $this->_data['total'] = $this->_model->countAll();

        //echo "<pre>"; print_r($this->_data); exit;
        $this->_render('\user\index', $this->_data);
    }

    /**
     * Displays the profile page
     */
    public function profile()
    {
        $this->_data['title'] = lang('Acp.title_profile');

        $id = $this->request->getVar('user') ?? $this->user->id;

        $user = $this->_model->find($id);
        if (isset($user->id)) {
            // $this->_modelUsmeta->getMeta($user);
            $user->GroupInfo = $this->userMetaModel->find($user->gid);
            $this->_data['User'] = $user;
            $this->_render('\user\profile', $this->_data);
        } else {
            return redirect()->route('list_user')->with('error', lang('User.invalid_user'));
        }
    }

    /**
     * Display Add User Page
     */
    public function add()
    {
        $this->_data['title'] = lang('User.title_add');

        $this->_data['list_userg'] = $this->userMetaModel->findAll();
        $this->_render('\user\add', $this->_data);
    }

    /**
     * Attempt to add a new user.
     */
    public function addAction()
    {
        $postData = $this->request->getPost();
        // Validate here first, since some things,
        // like the password, can only be validated properly here.
        $rules = [
            'username'      => 'required|alpha_numeric_space|min_length[4]|is_unique[users.username]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'fullname'      => 'required',
            'password'         => 'required|min_length[4]|strong_password',
            'pass_confirm'     => 'required|matches[password]',
        ];
        $errMess = [
            'username' => [
                'required' => lang('User.username_required'),
                'alpha_numeric_space' => lang('User.alpha_numeric_space'),
                'min_length' => lang('User.min_length'),
                'is_unique' => lang('User.user_is_exist')
            ],
            'email' => [
                'required' => lang('User.email_required'),
                'valid_email' => lang('User.valid_email'),
                'is_unique' => lang('User.email_exits')
            ],
            'fullname' => [
                'required' => lang('User.fullname_required')
            ],
            'password' => [
                'required' => lang('User.pw_required'),
                'min_length' => lang('User.pw_length')
            ],
            'pass_confirm' => [
                'required' => lang('User.pwcf_required'),
                'matches'  => lang('User.pwcf_matches')
            ]
        ];

        //validate the input
        if (! $this->validate($rules, $errMess)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        //upload user avata first
        $avatar   = $this->request->getFile('avatar');
        if ($avatar->getName()) {
            $response = $this->uploadAvatar($postData, $avatar);
            if ($response instanceof RedirectResponse) return $response;
            $postData['avatar'] = $response;
        }

        //good then save the new user
        $user = new EntitiesUser($postData);

        $user_id = $this->_model->insert($user);
        if (! $user_id) {
            return redirect()->back()->withInput()->with('errors', $this->_model->errors());
        }
        // Success!
        $item = $this->_model->find($user_id);

        if (isset($postData['force_pass_reset'])) $item->forcePasswordReset();

        //add user meta
        $this->insertUserMeta($this->request->getPost(), $user_id);

        //log Action
        $logData = [
            'title' => 'Add User',
            'description' => "#{$this->user->username} đã thêm user #{$item->username}",
            'properties' => $item->toArray(),
            'subject_id' => $item->id,
            'subject_type' => $item->model_class,
        ];
        $this->logAction($logData);

        //        if ($this->auth_config->requireActivation !== false)
        //        {
        //            $activator = service('activator');
        //            $sent = $activator->send($user);
        //
        //            if (! $sent)
        //            {
        //                return redirect()->back()->withInput()->with('error', $activator->error() ?? lang('Auth.unknownError'));
        //            }
        //
        //            // Success!
        //            return redirect()->route('login')->with('message', lang('Auth.activationSuccess'));
        //        }

        return redirect()->route('list_user')->with('message', lang('User.addSuccess', [$postData['username']]));
    }

    /**
     * Display Edit User Page
     */
    public function edit($userID)
    {
        $this->_data['title'] = lang('User.edit_title');
        $user = $this->_model->withDeleted()
            ->find($userID);

        if (isset($user->id)) {
            if ($user->id != $this->user->id) {
                //check permission
                if (!$this->user->inGroup('superadmin', 'admin')) return redirect()->route('dashboard')->with('error', lang('Acp.no_permission'));
            }

            //$this->_modelUsmeta->getMeta($user); //echo "<pre>"; print_r($user);exit;
            $this->_data['userData'] = $user;
            $this->_data['list_userg'] = $this->userMetaModel->findAll();

            $this->_render('\user\edit', $this->_data);
        } else {
            return redirect()->route('list_user')->with('error', lang('User.invalid_user'));
        }
    }

    /**
     * Attempt to edit a user.
     */
    public function editAction($userID = 0)
    {
        $this->_data['title'] = lang('User.edit_title');

        $user = $this->_model->find($userID);
        $this->_model->skipValidation(true);

        if (isset($user->id)) {
            $inputData = $this->request->getPost();
            //validate the data
            $rules = [
                'email'            => 'required|valid_email',
                'fullname'      => 'required',
            ];

            $errMess = [
                'username' => [
                    'required' => lang('User.username_required'),
                    'alpha_numeric_space' => lang('User.alpha_numeric_space'),
                    'min_length' => lang('User.min_length'),
                    'is_unique' => lang('User.user_is_exist')
                ],
                'email' => [
                    'required' => lang('User.email_required'),
                    'valid_email' => lang('User.valid_email'),
                ],
                'fullname' => [
                    'required' => lang('User.fullname_required')
                ]
            ];

            if (isset($inputData['password']) && $inputData['password'] !== '') {
                $rules['password'] = 'required|min_length[4]|strong_password';
                $rules['pass_confirm'] = 'required|matches[password]';

                $errMess['password'] = [
                    'required' => lang('User.pw_required'),
                    'min_length' => lang('User.pw_length')
                ];
                $errMess['pass_confirm'] = [
                    'required' => lang('User.pwcf_required'),
                    'matches'  => lang('User.pwcf_matches')
                ];
            }
            if (isset($inputData['email']) && $inputData['email'] !== $user->email) {
                $rules['email'] .= '|is_unique[users.email]';
                $errMess['email']['is_unique'] = lang('User.email_exits');
            }
            //validate the input
            if (! $this->validate($rules, $errMess)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            //upload user avata first
            $avatar   = $this->request->getFile('avatar');
            if ($avatar->getName()) {
                $this->editAvatar($user, $avatar);
            }
            //good then save the new user
            $user->fill($inputData);

            try {
                if (!$this->_model->save($user)) {
                    return redirect()->back()->withInput()->with('errors', $this->_model->errors());
                }
            } catch (DataException $e) {
            }

            if (isset($inputData['sync_permission']) && $inputData['sync_permission'] == 1) {
                $userGroup = $this->userMetaModel->find($user->gid);
                $user->permissions = json_decode($userGroup->permissions);
            }
            // Success!
            //add user meta
            $this->updateUserMeta($inputData, $userID);

            //log Action
            $logData = [
                'title' => 'Edit User',
                'description' => "#{$this->user->username} đã sửa user #{$user->username}",
                'properties' => $user->toArray(),
                'subject_id' => $user->id,
                'subject_type' => $user->model_class,
            ];
            $this->logAction($logData);

            if (isset($inputData['save'])) return redirect()->to(route_to('list_user'))->with('message', lang('User.editSuccess', [$user->username]));
            else if (isset($inputData['save_exit'])) return redirect()->route('list_user')->with('message', lang('User.editSuccess', [$user->username]));
            else if (isset($inputData['save_addnew'])) return redirect()->route('add_user')->with('message', lang('User.editSuccess', [$user->username]));
        } else return redirect()->route('list_user')->with('error', lang('User.invalid_user'));
    }

    /**
     * display edit password form
     * @param $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function editPassword($id)
    {
        $this->_data['title'] = lang('User.edit_password');
        $user = $this->_model->find($id);

        if (isset($user->id)) {
            $this->_data['userData'] = $user;
            $redirect_url = base_url(route_to('list_user'));
            if (previous_url() != current_url()) {
                $redirect_url = previous_url();
            } else {
                if ($user->user_type == UserTypeEnum::CUSTOMER) {
                    $cusData = model(CustomerModel::class)
                        ->where('user_id', $user->id)
                        ->first();
                    $redirect_url = base_url(route_to('customer_detail', $cusData->id));
                } else $redirect_url = base_url("acp/user/profile?user={$user->id}");
            }

            $this->_data['cancelUrl'] = $redirect_url;
            $this->_render('\user\changePassword', $this->_data);
        } else {
            return redirect()->route('list_user')->with('error', lang('User.invalid_user'));
        }
    }

    /**
     * try to reset password
     * @param $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     * @throws \ReflectionException
     */
    public function attempEditPassword($id)
    {
        $user = $this->_model->find($id);
        $postData = $this->request->getPost();

        if (isset($user->id)) {
            $rules['password'] = 'required|min_length[6]|alpha_numeric';
            $rules['pass_confirm'] = 'required|matches[password]';

            $errMess['password'] = [
                'required' => lang('User.pw_required'),
                'min_length' => lang('User.pw_length')
            ];
            $errMess['pass_confirm'] = [
                'required' => lang('User.pwcf_required'),
                'matches'  => lang('User.pwcf_matches')
            ];

            //validate the input
            if (! $this->validate($rules, $errMess)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
            // Success! Save the new password.
            $user->password         = $postData['password'];
            $user->reset_at         = date('Y-m-d H:i:s');

            $this->_model->save($user);

            if (isset($postData['force_pass_reset'])) $user->forcePasswordReset();

            //log Action
            $logData = [
                'title' => 'Change Password',
                'description' => "#{$this->user->username} đã đổi mật khẩu user #{$user->username}",
                'subject_id' => $user->id,
                'subject_type' => $user->model_class,
            ];
            $this->logAction($logData);

            //email inform
            $checkSendMail = $this->request->getPost('send_email');
            $emailErr = [];
            $data = [
                'username' => $user->username,
                'password' => $this->request->getPost('password')
            ];
            //send email
            $email = Services::email();
            $config = new Email();
            if ($checkSendMail) {
                $sent = $email->setFrom($config->fromEmail, $config->fromName)
                    ->setTo($user->email)
                    ->setSubject("Your password was reset")
                    ->setMessage(view($this->config->view . '\user\email\editPassword', $data))
                    ->setMailType('html')
                    ->send();
                if (! $sent) {
                    $emailErr = ['error' => 'Fail to send email please try again!'];
                }
            }

            if ($user->user_type == UserTypeEnum::CUSTOMER) {
                $cusData = model(CustomerModel::class)
                    ->where('user_id', $user->id)
                    ->first();
                $routeTo = base_url(route_to('customer_detail', $cusData->id));
            } else {
                $routeTo = base_url("acp/user/profile?user={$user->id}");
            }
            if (!empty($emailErr)) return redirect()->to($routeTo)->with('message', $emailErr['error']);
            return redirect()->to($routeTo)->with('message', lang('User.edit_pass_success', [$user->username]));
        } else return redirect()->route('list_user')->with('error', lang('User.invalid_user'));
    }

    /**
     * Remove a user
     */
    public function remove($id)
    {
        $user = $this->_model->find($id);

        if (isset($user->id)) {
            if ($user->id == $this->user->id) {
                return redirect()->route('list_user')->with('error', lang('User.invalid_delete_user'));
            }

            if ($this->_model->delete($user->id)) {
                //log Action
                $logData = [
                    'title' => 'Delete User',
                    'description' => "#{$this->user->username} đã xoá user #{$user->username}",
                    'subject_id' => $user->id,
                    'subject_type' => $user->model_class,
                ];
                $this->logAction($logData);

                return redirect()->route('list_user')->with('message', lang('User.delete_success', [$user->id]));
            } else return redirect()->route('list_user')->with('error', lang('User.delete_fail'));
        } else return redirect()->route('list_user')->with('error', lang('Acp.invalid_request'));
    }

    /**
     * Recover deleted user
     * @param $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function recover($id)
    {
        $user = $this->_model->withDeleted()->find($id);

        if (isset($user->id)) {
            if ($user->id == $this->user->id) {
                return redirect()->route('list_user')->with('error', lang('User.invalid_delete_user'));
            }
            
            $this->_model->validate(false);
            if ($this->_model->recover($user->id)) {
                //log Action
                $logData = [
                    'title' => 'Recover User',
                    'description' => "#{$this->user->username} đã khôi phục user #{$user->username}",
                    'subject_id' => $user->id,
                    'subject_type' => $user->model_class,
                ];
                $this->logAction($logData);

                return redirect()->route('list_user')->with('message', lang('User.recover_success', [$user->id]));
            } else return redirect()->route('list_user')->with('error', lang('User.recover_fail'));
        } else return redirect()->route('list_user')->with('error', lang('Acp.invalid_request'));
    }

    public function active($id)
    {
        $throttler = service('throttler');

        if ($throttler->check($this->request->getIPAddress(), 2, MINUTE) === false) {
            return service('response')->setStatusCode(429)->setBody(lang('Auth.tooManyRequests', [$throttler->getTokentime()]));
        }
        $user = $this->_model->find($id);

        if (!isset($user->id) || $user->isActivated()) {
            return redirect()->back()->with('error', lang('User.invalid_user'));
        }

        $user->activate();
        $this->_model->save($user);

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

    //insert user meta
    public function insertUserMeta($inputData, $userID)
    {

        if (!empty($this->config->user_meta)) {
            foreach ($this->config->user_meta as $metaKey => $val) {
                if (isset($inputData[$metaKey]) && !empty($inputData[$metaKey])) {
                    $insertData['meta_key'] = $metaKey;
                    $insertData['meta_value'] = $inputData[$metaKey];
                    $insertData['user_id'] = $userID;
                    $this->userMetaModel->insert($insertData);
                }
            }
        }
    }

    /**
     * Update user meta
     * @param $inputData
     */
    public function updateUserMeta($inputData, $userID)
    {

        if (!empty($this->config->user_meta)) {
            foreach ($this->config->user_meta as $metaKey => $val) {
                if (isset($inputData[$metaKey]) && !empty($inputData[$metaKey])) {
                    //check user meta
                    $where = array("meta_key" => $metaKey, "user_id" => $userID);
                    $userMeta = $this->userMetaModel->getWhere($where)->getFirstRow('array'); //echo $this->_modelUsmeta->getlastQuery();
                    //echo "<pre>";  print_r($userMeta);exit;
                    if (isset($userMeta['id']) && $userMeta['id'] > 0) {
                        $updateData['meta_value'] = $inputData[$metaKey];
                        $this->userMetaModel->update($userMeta['id'], $updateData);
                    } else {
                        $insertData['meta_key'] = $metaKey;
                        $insertData['meta_value'] = $inputData[$metaKey];
                        $insertData['user_id'] = $userID;
                        $this->userMetaModel->insert($insertData);
                    }
                }
            }
        }
    }
}