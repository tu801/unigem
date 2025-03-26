<?php
return [
    'acc_menu_my_order'       => 'Lịch sử mua hàng',
    'acc_menu_my_voucher'     => 'Voucher của bạn',

    // login
    'my_account'              => 'Tài khoản của tôi',
    'login'                   => 'Đăng nhập',
    'remember_me'             => 'Ghi nhớ tôi',
    'lost_password'           => 'Quên mật khẩu của bạn?',
    'email'                   => 'Email',
    'password'                => 'Mật Khẩu',
    'needAnAccount'           => 'Đăng ký tài khoản',
    // register
    'register_account'        => 'Đăng Ký Tài khoản',
    'register'                => 'Đăng Ký',
    'repeat_password'         => 'Nhập lại mật khẩu',
    'username'                => 'Tên Tài Khoản',
    'phone_number'            => 'Số điện thoại',

    // forgot password
    'lost_your_password_decs' => 'Mất mật khẩu của bạn? Vui lòng nhập địa chỉ email của bạn. Bạn sẽ nhận được một liên kết để tạo mật khẩu mới qua email.',
    'reset_password'          => 'Đặt lại mật khẩu',
    'new_password'            => 'Mật khẩu mới',
    'hash_change_password'    => 'Mã Bảo Mật',

    'invalidModel'              => 'Model {0} phải được tải trước khi sử dụng.',
    'userNotFound'              => 'Không tìm thấy người dùng có ID = {0, number}.',
    'noUserEntity'              => 'Dữ liệu người dùng phải được cung cấp để kiểm tra mật khẩu.',
    'tooManyCredentials'        => 'Bạn chỉ có thể xác minh 1 thông tin xác thực ngoài mật khẩu.',
    'invalidFields'             => 'Trường "{0}" không thể được sử dụng để xác minh thông tin xác thực.',
    'unsetPasswordLength'       => 'Bạn phải thiết lập giá trị `minimumPasswordLength` trong tệp cấu hình Auth.',
    'unknownError'              => 'Xin lỗi, chúng tôi gặp sự cố khi gửi email cho bạn. Vui lòng thử lại sau.',
    'notLoggedIn'               => 'Bạn phải đăng nhập để truy cập trang đó.',
    'notEnoughPrivilege'        => 'Bạn không có đủ quyền truy cập trang đó.',
    'loginRequired'             => 'Bạn cần đăng nhập trước!',
    'invalid_request'           => 'Xin lỗi, chúng tôi không thể xử lý yêu cầu của bạn ngay bây giờ! Vui lòng thử lại',

    // Đăng ký
    'registerDisabled'          => 'Xin lỗi, tài khoản người dùng mới không được phép tại thời điểm này.',
    'registerSuccess'           => 'Chào mừng bạn! Vui lòng đăng nhập bằng thông tin đăng nhập mới của bạn.',
    'registerCLI'               => 'Người dùng mới được tạo: {0}, #{1}',
    'register_title'            => 'Đăng ký',

    // Kích hoạt
    'activationNoUser'          => 'Không thể tìm thấy người dùng với mã kích hoạt đó.',
    'activationSubject'         => 'Kích hoạt tài khoản của bạn',
    'activationSuccess'         => 'Vui lòng xác nhận tài khoản của bạn bằng cách kiểm tra liên kết kích hoạt trong email chúng tôi đã gửi.',
    'activationResend'          => 'Gửi lại thông báo kích hoạt một lần nữa.',
    'notActivated'              => 'Tài khoản người dùng này chưa được kích hoạt.',
    'errorSendingActivation'    => 'Gửi thông báo kích hoạt đến: {0} không thành công',

    // Đăng nhập
    'badAttempt'                => 'Không thể đăng nhập. Vui lòng kiểm tra thông tin đăng nhập của bạn.',
    'loginSuccess'              => 'Chào mừng trở lại!',
    'invalidPassword'           => 'Không thể đăng nhập. Vui lòng kiểm tra thông tin đăng nhập của bạn.',

    // Quên mật khẩu
    'forgotNoUser'              => 'Không thể tìm thấy người dùng với email đó.',
    'forgotSubject'             => 'Hướng dẫn đặt lại mật khẩu',
    'resetSuccess'              => 'Mật khẩu của bạn đã được đặt lại thành công. Vui lòng đăng nhập bằng mật khẩu mới.',
    'forgotEmailSent'           => 'Một mã bảo mật đã được gửi đến email của bạn. Nhập nó vào ô bên dưới để tiếp tục.',

    // Mật khẩu
    'errorPasswordLength'       => 'Mật khẩu phải có ít nhất {0, number} ký tự.',
    'suggestPasswordLength'     => 'Câu mật khẩu - lên đến 255 ký tự - tạo ra các mật khẩu an toàn hơn và dễ nhớ.',
    'errorPasswordCommon'       => 'Mật khẩu này là mật khẩu phổ biến. Vui lòng chọn mật khẩu khác an toàn hơn.',
    'suggestPasswordCommon'     => 'Mật khẩu này đã được kiểm tra với hơn 65k mật khẩu thông dụng hoặc mật khẩu đã bị rò rỉ thông qua các cuộc tấn công.',
    'errorPasswordPersonal'     => 'Mật khẩu không thể chứa thông tin cá nhân đã được mã hóa lại.',
    'suggestPasswordPersonal'   => 'Không nên sử dụng biến thể của địa chỉ email hoặc tên người dùng của bạn cho mật khẩu.',
    'errorPasswordTooSimilar'   => 'Mật khẩu quá giống với tên người dùng.',
    'suggestPasswordTooSimilar' => 'Không nên sử dụng phần của tên người dùng trong mật khẩu.',
    'errorPasswordPwned'        => 'Mật khẩu {0} đã bị tiết lộ do một vụ vi phạm dữ liệu và đã xuất hiện {1, number} lần trong {2} mật khẩu đã bị đánh cắp.',
    'suggestPasswordPwned'      => '{0} không nên được sử dụng làm mật khẩu. Nếu bạn đang sử dụng nó ở bất kỳ đâu, hãy thay đổi ngay lập tức.',
    'errorPasswordEmpty'        => 'Mật khẩu là bắt buộc.',
    'passwordChangeSuccess'     => 'Thay đổi mật khẩu thành công',
    'userDoesNotExist'          => 'Mật khẩu không thay đổi. Người dùng không tồn tại',
    'resetTokenExpired'         => 'Xin lỗi. Mã đặt lại của bạn đã hết hạn.',

    // Nhóm
    'groupNotFound'             => 'Không thể tìm thấy nhóm: {0}.',

    // Quyền
    'permissionNotFound'        => 'Không thể tìm thấy quyền: {0}',

    // Bị cấm
    'userIsBanned'              => 'Người dùng đã bị cấm. Liên hệ với quản trị viên',

    // Quá nhiều yêu cầu
    'tooManyRequests'           => 'Quá nhiều yêu cầu. Vui lòng đợi {0, number} giây.',

    // Giao diện đăng nhập
    'login_title'               => 'Đăng Nhập',
    'home'                      => 'Trang chủ',
    'current'                   => 'Hiện tại',
    'forgotPassword'            => 'Quên Mật khẩu?',
    'enterEmailForInstructions' => 'Không vấn đề gì! Nhập email của bạn bên dưới và chúng tôi sẽ gửi hướng dẫn để đặt lại mật khẩu của bạn.',
    'emailAddress'              => 'Địa chỉ Email',
    'sendInstructions'          => 'Gửi Hướng dẫn',
    'loginTitle'                => 'Đăng Nhập',
    'loginAction'               => 'Đăng Nhập',
    'rememberMe'                => 'Ghi nhớ tôi',
    'forgotYourPassword'        => 'Quên mật khẩu?',
    'repeatPassword'            => 'Nhập lại Mật khẩu',
    'emailOrUsername'           => 'Email hoặc tên đăng nhập',
    'signIn'                    => 'Đăng Nhập',
    'alreadyRegistered'         => 'Đã có tài khoản?',
    'noAccount'                 => 'Chưa có tài khoản?',
    'weNeverShare'              => 'Chúng tôi sẽ không bao giờ chia sẻ email của bạn với bất kỳ ai khác.',
    'resetYourPassword'         => 'Đặt Lại Mật khẩu Của Bạn',
    'enterCodeEmailPassword'    => 'Nhập mã bạn nhận được qua email, địa chỉ email của bạn và mật khẩu mới của bạn.',
    'token'                     => 'Mã',
    'newPassword'               => 'Mật khẩu Mới',
    'newPasswordRepeat'         => 'Nhập lại Mật khẩu Mới',
    'resetPassword'             => 'Đặt Lại Mật khẩu',
    'forceChangePass'           => 'Bạn cần phải đổi mật khẩu!<br> Vui lòng tạo mật khẩu mới của bạn.',

    // Giao diện đổi mật khẩu
    'changePassword_title'      => 'Đổi Mật khẩu',
    'logout'                    => 'Đăng Xuất',
    'fullname'                  => 'Họ Và Tên',
    // email
    'activation_email_body'    => 'Đây là email kích hoạt tài khoản cho tài khoản của bạn trên :base_url.',
    'activation_email_link'    => 'Để kích hoạt tài khoản, vui lòng sử dụng liên kết sau:',
    'activation_button_text'   => 'Kích hoạt tài khoản',
    'activation_email_footer'  => 'Nếu bạn không đăng ký trên trang web này, bạn có thể bỏ qua email này.',

    'reset_password_body'        => 'Ai đó đã yêu cầu đặt lại mật khẩu tại địa chỉ email này trên .',
    'reset_password_code'        => 'Mã xác thực của bạn: ',
    'reset_password_link'        => 'Để đặt lại mật khẩu, vui lòng sử dụng mã hoặc truy cập vào liên kết sau:',
    'reset_password_button_text' => 'Đặt lại mật khẩu',
    'reset_password_footer'      => 'Nếu bạn không yêu cầu đặt lại mật khẩu, bạn có thể bỏ qua email này.',
    'reset_password_visit'       => 'Đến',
    'reset_password_form'        => 'Form Đặt lại Mật khẩu',

    // validate
    'email_required'               => 'Vui lòng nhập email',
    'email_valid_email'            => 'Vui lòng nhập email hợp lệ',
    'email_is_unique'              => 'Email này đã được đăng kí tài khoản trước đó',
    'full_name_required'           => 'Vui lòng nhập họ và tên',
    'full_name_min_length'         => 'Vui lòng nhập Tối thiểu 2 kí tự',
    'password_required'            => 'Vui lòng nhập mật khẩu',
    'pass_confirm_required'        => 'Vui lòng xác nhận mật khẩu',
    'password_strong_password'     => 'Vui lòng nhập mật khẩu mạnh hơn',
    'password_min_length'          => 'Mật khẩu phải có ít nhất 6 ký tự',
    'password_invalid'             => 'Mật khẩu phải bao gồm ký tự chữ và số.',
    'password_matches'             => 'Mật khẩu xác nhận không khớp',
    'username_required'            => 'Vui lòng nhập Tên đăng nhập',
    'username_alpha_numeric_space' => 'Tên đăng nhập không được có khoảng trắng và kí tự đặc biệt',
    'username_min_length'          => 'Vui lòng nhập Tối thiểu 2 kí tự',
    'username_is_unique'           => 'Tên tài khoản đã được sử dụng',
    'phone_required'               => 'Vui lòng điền số điện thoại',
    'phone_number_exist'           => 'Số điện thoại này đã tồn tại',
    'contact_reset_password'       => 'Vui lòng liên hệ với bộ phận chăm sóc khách hàng của chúng tôi để lấy lại mật khẩu.'
];