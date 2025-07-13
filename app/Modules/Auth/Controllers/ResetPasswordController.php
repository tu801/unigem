<?php
namespace App\Modules\Auth\Controllers;

use App\Controllers\BaseController;
use App\Models\User\UserModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Shield\Authentication\Passwords\DictionaryValidator;
use CodeIgniter\Shield\Traits\Viewable;
use Psr\Log\LoggerInterface;

class ResetPasswordController extends BaseController {
    use Viewable;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ): void {
        parent::initController(
            $request,
            $response,
            $logger
        );
        helper('global');
    }

    public function resetPasswordView() {
        if (!auth()->loggedIn()) {
            return redirect()->to('/');
        }

        $user = auth()->user();

        if (!$user->requiresPasswordReset()) {
            return redirect()->to('/');
        }

        return $this->view(setting('Auth.views')['reset-password'], [
            'username' => $user->username, 
            'email' => $user->email
        ]);
    }

    public function resetPasswordAction() {
        if (!auth()->loggedIn()) {
            return redirect()->to('/');
        }
        $userModel = auth()->getProvider();
        $user = auth()->user();

        if (!$user->requiresPasswordReset()) {
            return redirect()->to('/');
        }

        // check for common password
        $passwordChecker = new DictionaryValidator(config('Auth'));
        $result = $passwordChecker->check($this->request->getPost('password'), $user);
        if (! $result->isOk()) {
            return redirect()->back()->with('errors', $result->reason())->withInput();
        }
        
        $rules = [
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ];
        $errors = [
            'min_length' => 'Common.errorPasswordTooShort',
        ];
        if (! $this->validate($rules, $errors))
        {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // check old password
        $oldPassword = $this->request->getPost('old_password');
        /** @var Passwords $passwords */
        $passwords = service('passwords');

        // Now, try matching the passwords.
        if (! $passwords->verify($oldPassword, $user->password_hash)) {
            return redirect()->back()->withInput()->with('error', lang('Customer.old_password_not_match'));
        }

        //record old password
        $oldData = [
            'user_id' => $user->id,
            'user_name' => $user->username,
            'old_password' => $user->password_hash
        ];

        $user->password = $this->request->getPost('password');
        $userModel->save($user);
        $user->undoForcePasswordReset();

        // log reset password
        $logData = [
            'title' => lang('Common.cmsResetPassword'),
            'description' => "User {$user->username} đã đổi mật khẩu",
            'properties' => $oldData,
            'subject_id' => $user->id,
            'subject_type' => UserModel::class,
        ];
        logAction($logData);

        auth()->logout();

        return redirect()->to('login')->with('message', lang('Common.passwordResetSuccess'));
    }
}