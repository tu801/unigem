<?php

declare(strict_types=1);

return [
    // Exceptions
    'unknownAuthenticator'  => '{0} không phải là một authenticator hợp lệ.',
    'unknownUserProvider'   => 'Không thể xác định User Provider để sử dụng.',
    'invalidUser'           => 'Không thể tìm thấy người dùng đã chỉ định.',
    'bannedUser'            => 'Không thể đăng nhập vì bạn hiện đang bị cấm.',
    'logOutBannedUser'      => 'Bạn đã bị đăng xuất vì bạn đã bị cấm.',
    'badAttempt'            => 'Không thể đăng nhập. Vui lòng kiểm tra thông tin đăng nhập của bạn.',
    'noPassword'            => 'Không thể xác thực người dùng khi không có mật khẩu.',
    'invalidPassword'       => 'Không thể đăng nhập. Vui lòng kiểm tra mật khẩu của bạn.',
    'noToken'               => 'Mỗi yêu cầu phải có một bearer token trong header {0}.',
    'badToken'              => 'Access token không hợp lệ.',
    'oldToken'              => 'Access token đã hết hạn.',
    'noUserEntity'          => 'Phải cung cấp User Entity để xác thực mật khẩu.',
    'emailRequired'         => 'Vui lòng nhập email.',
    'invalidEmail'          => 'Email không hợp lệ.',
    'unableSendEmailToUser' => 'Xin lỗi, đã xảy ra sự cố khi gửi email. Chúng tôi không thể gửi email đến "{0}".',
    'throttled'             => 'Quá nhiều yêu cầu từ địa chỉ IP này. Bạn có thể thử lại sau {0} giây.',
    'notEnoughPrivilege'    => 'Bạn không có quyền cần thiết để thực hiện thao tác mong muốn.',
    // JWT Exceptions
    'invalidJWT'     => 'Token không hợp lệ.',
    'expiredJWT'     => 'Token đã hết hạn.',
    'beforeValidJWT' => 'Token chưa có hiệu lực.',

    'email'           => 'Email',
    'username'        => 'Tên đăng nhập',
    'password'        => 'Mật khẩu',
    'passwordConfirm' => 'Mật khẩu (nhập lại)',
    'haveAccount'     => 'Đã có tài khoản? Đăng nhập ngay!',
    'token'           => 'Token',

    // Buttons
    'confirm' => 'Xác nhận',
    'send'    => 'Gửi',

    // Registration
    'register'                  => 'Đăng ký',
    'registerDisabled'          => 'Đăng ký hiện không được cho phép.',
    'registerSuccess'           => 'Chào mừng bạn đến với chúng tôi!',
    'newCustomerRegisterNow'    => 'Chưa có tài khoản? Đăng ký ngay!',

    // Login
    'login'              => 'Đăng nhập',
    'needAccount'        => 'Cần một tài khoản?',
    'rememberMe'         => 'Ghi nhớ đăng nhập?',
    'forgotPassword'     => 'Quên mật khẩu?',
    'useMagicLink'       => 'Sử dụng Liên kết Đăng nhập',
    'magicLinkSubject'   => 'Liên kết Đăng nhập của bạn',
    'magicTokenNotFound' => 'Không thể xác minh liên kết.',
    'magicLinkExpired'   => 'Xin lỗi, liên kết đã hết hạn.',
    'checkYourEmail'     => 'Kiểm tra email của bạn!',
    'magicLinkDetails'   => 'Chúng tôi vừa gửi cho bạn một email có chứa Liên kết Đăng nhập. Liên kết chỉ có hiệu lực trong {0} phút.',
    'magicLinkDisabled'  => 'Việc sử dụng MagicLink hiện không được cho phép.',
    'successLogout'      => 'Bạn đã đăng xuất thành công.',
    'backToLogin'        => 'Quay lại Đăng nhập',
    'loginSuccess'       => 'Đăng nhập thành công',
    'already_logged_in'  => 'Bạn đã đăng nhập rồi. Vui lòng đăng xuất trước khi đăng nhập lại.',
    'logoutSuccess'      => 'Đăng xuất thành công',

    // Passwords
    'passwordRequired'          => 'Vui lòng điền mật khẩu.',
    'errorPasswordLength'       => 'Mật khẩu phải có ít nhất {0, number} ký tự.',
    'suggestPasswordLength'     => 'Cụm mật khẩu - dài tới 255 ký tự - tạo ra mật khẩu an toàn hơn và dễ nhớ.',
    'errorPasswordCommon'       => 'Mật khẩu không được là mật khẩu thông dụng.',
    'suggestPasswordCommon'     => 'Mật khẩu đã được kiểm tra với hơn 65 nghìn mật khẩu thường dùng hoặc mật khẩu đã bị lộ qua các vụ hack.',
    'errorPasswordPersonal'     => 'Mật khẩu không được chứa thông tin cá nhân đã được mã hóa lại.',
    'suggestPasswordPersonal'   => 'Các biến thể của địa chỉ email hoặc tên người dùng không nên được sử dụng làm mật khẩu.',
    'errorPasswordTooSimilar'   => 'Mật khẩu quá giống với tên người dùng.',
    'suggestPasswordTooSimilar' => 'Không sử dụng các phần của tên người dùng trong mật khẩu của bạn.',
    'errorPasswordPwned'        => 'Mật khẩu {0} đã bị lộ do vi phạm dữ liệu và đã xuất hiện {1, number} lần trong {2} mật khẩu bị xâm phạm.',
    'suggestPasswordPwned'      => '{0} không nên được sử dụng làm mật khẩu. Nếu bạn đang sử dụng nó ở bất kỳ đâu, hãy thay đổi ngay lập tức.',
    'errorPasswordEmpty'        => 'Mật khẩu là bắt buộc.',
    'errorPasswordTooLongBytes' => 'Mật khẩu không được vượt quá {param} byte.',
    'passwordChangeSuccess'     => 'Đổi mật khẩu thành công',
    'userDoesNotExist'          => 'Mật khẩu không được thay đổi. Người dùng không tồn tại',
    'resetTokenExpired'         => 'Xin lỗi. Token đặt lại của bạn đã hết hạn.',

    // Email Globals
    'emailInfo'      => 'Một số thông tin về người dùng:',
    'emailIpAddress' => 'Địa chỉ IP:',
    'emailDevice'    => 'Thiết bị:',
    'emailDate'      => 'Ngày:',

    // 2FA
    'email2FATitle'       => 'Xác thực Hai yếu tố',
    'confirmEmailAddress' => 'Xác nhận địa chỉ email của bạn.',
    'emailEnterCode'      => 'Xác nhận Email của bạn',
    'emailConfirmCode'    => 'Nhập mã 6 chữ số mà chúng tôi vừa gửi đến địa chỉ email của bạn.',
    'email2FASubject'     => 'Mã xác thực của bạn',
    'email2FAMailBody'    => 'Mã xác thực của bạn là:',
    'invalid2FAToken'     => 'Mã không chính xác.',
    'need2FA'             => 'Bạn phải hoàn thành xác thực hai yếu tố.',
    'needVerification'    => 'Kiểm tra email của bạn để hoàn tất kích hoạt tài khoản.',

    // Activate
    'emailActivateTitle'    => 'Kích hoạt Email',
    'emailActivateBody'     => 'Chúng tôi vừa gửi cho bạn một email có mã để xác nhận địa chỉ email của bạn. Sao chép mã đó và dán vào bên dưới để kích hoạt tài khoản của bạn.',
    'emailActivateSubject'  => 'Mã kích hoạt của bạn',
    'emailActivateMailBody' => 'Vui lòng sử dụng mã dưới đây để kích hoạt tài khoản của bạn và bắt đầu sử dụng trang web.',
    'invalidActivateToken'  => 'Mã kích hoạt không chính xác.',
    'needActivate'          => 'Bạn phải hoàn thành đăng ký bằng cách xác nhận mã đã gửi đến địa chỉ email của bạn.',
    'activationBlocked'     => 'Bạn phải kích hoạt tài khoản trước khi đăng nhập.',

    // Groups
    'unknownGroup' => '{0} không phải là một nhóm hợp lệ.',
    'missingTitle' => 'Nhóm phải có tiêu đề.',

    // Permissions
    'unknownPermission' => '{0} không phải là một quyền hợp lệ.',
];
