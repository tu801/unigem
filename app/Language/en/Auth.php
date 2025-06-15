<?php

declare(strict_types=1);

return [
    // Exceptions
    'unknownAuthenticator'  => '{0} is not a valid authenticator.',
    'unknownUserProvider'   => 'Unable to determine the User Provider to use.',
    'invalidUser'           => 'The specified user could not be found.',
    'bannedUser'            => 'Cannot log in because you are currently banned.',
    'logOutBannedUser'      => 'You have been logged out because you have been banned.',
    'badAttempt'            => 'Unable to log in. Please check your login credentials.',
    'noPassword'            => 'Cannot authenticate user without a password.',
    'invalidPassword'       => 'Unable to log in. Please check your password.',
    'noToken'               => 'Each request must have a bearer token in the {0} header.',
    'badToken'              => 'Invalid access token.',
    'oldToken'              => 'Access token has expired.',
    'noUserEntity'          => 'User Entity must be provided to validate the password.',
    'emailRequired'         => 'Please enter an email.',
    'invalidEmail'          => 'Invalid email address.',
    'unableSendEmailToUser' => 'Sorry, there was a problem sending the email. We could not send an email to "{0}".',
    'throttled'             => 'Too many requests from this IP address. You can try again in {0} seconds.',
    'notEnoughPrivilege'    => 'You do not have the necessary privileges to perform the desired action.',
    // JWT Exceptions
    'invalidJWT'     => 'Invalid token.',
    'expiredJWT'     => 'Token has expired.',
    'beforeValidJWT' => 'Token is not yet valid.',

    'email'           => 'Email',
    'username'        => 'Username',
    'password'        => 'Password',
    'passwordConfirm' => 'Password (repeat)',
    'haveAccount'     => 'Already have an account? Login now!',
    'token'           => 'Token',

    // Buttons
    'confirm' => 'Confirm',
    'send'    => 'Send',

    // Registration
    'register'                  => 'Register',
    'registerDisabled'          => 'Registration is currently not allowed.',
    'registerSuccess'           => 'Welcome to us!',
    'newCustomerRegisterNow'    => 'Don\'t have an account? Register now!',

    // Login
    'login'              => 'Login',
    'needAccount'        => 'Need an account?',
    'rememberMe'         => 'Remember me?',
    'forgotPassword'     => 'Forgot password?',
    'forgotPasswordTitle' => 'Forgot Password',
    'forgotPasswordText'  => '<p>Enter your email address and we will send you a link to reset your password.</p>',
    'useMagicLink'       => 'Use Magic Login Link',
    'magicLinkSubject'   => 'Your Magic Login Link',
    'magicTokenNotFound' => 'Unable to verify the link.',
    'magicLinkExpired'   => 'Sorry, the link has expired.',
    'checkYourEmail'     => 'Check your email!',
    'magicLinkDetails'   => 'We have just sent you an email containing a Magic Login Link. The link is only valid for {0} minutes.',
    'magicLinkDisabled'  => 'The use of MagicLink is currently not allowed.',
    'successLogout'      => 'You have successfully logged out.',
    'backToLogin'        => 'Back to Login',
    'loginSuccess'       => 'Login successful',
    'already_logged_in'  => 'You are already logged in. Please log out before logging in again.',
    'logoutSuccess'      => 'Logout successful',

    // Passwords
    'passwordRequired'          => 'Please enter a password.',
    'errorPasswordLength'       => 'Password must be at least {0, number} characters.',
    'suggestPasswordLength'     => 'A passphrase - up to 255 characters - creates a safer and easier to remember password.',
    'errorPasswordCommon'       => 'Password must not be a common password.',
    'suggestPasswordCommon'     => 'The password has been checked against over 65 thousand common or compromised passwords.',
    'errorPasswordPersonal'     => 'Password must not contain re-encoded personal information.',
    'suggestPasswordPersonal'   => 'Variants of your email address or username should not be used as your password.',
    'errorPasswordTooSimilar'   => 'Password is too similar to the username.',
    'suggestPasswordTooSimilar' => 'Do not use parts of your username in your password.',
    'errorPasswordPwned'        => 'The password {0} has been exposed in a data breach and appeared {1, number} times in {2} compromised passwords.',
    'suggestPasswordPwned'      => '{0} should not be used as a password. If you are using it anywhere, change it immediately.',
    'errorPasswordEmpty'        => 'Password is required.',
    'errorPasswordTooLongBytes' => 'Password must not exceed {param} bytes.',
    'passwordChangeSuccess'     => 'Password changed successfully',
    'userDoesNotExist'          => 'Password not changed. User does not exist',
    'resetTokenExpired'         => 'Sorry. Your reset token has expired.',
    'forgotPasswordSuccess'     => 'We have sent an email with instructions to reset your password. Please check your inbox.',
    'emailForgotPasswordExtra'  => 'Check your email to recover your password',
    'recoverPasswordEmail'      => 'Please visit the following link to reset your password:',
    'recoverPasswordLink'       => 'Reset Password',
    'recoverPassword'           => 'Recover Password',
    'recoverPasswordBody'       => 'Please enter your new password in the field below to recover your password.',
    'passwordMaxLength'         => 'Password must not exceed 255 characters.',
    'passwordNotMatch'          => 'Password does not match. Please try again.',
    'recoverPasswordSuccess'    => 'Your password has been successfully reset. You can now log in with your new password.',

    // Email Globals
    'emailInfo'      => 'Some information about the user:',
    'emailIpAddress' => 'IP Address:',
    'emailDevice'    => 'Device:',
    'emailDate'      => 'Date:',
    'emailNotFound'  => 'No user found with this email address.',

    // 2FA
    'email2FATitle'       => 'Two-Factor Authentication',
    'confirmEmailAddress' => 'Confirm your email address.',
    'emailEnterCode'      => 'Confirm your Email',
    'emailConfirmCode'    => 'Enter the 6-digit code we just sent to your email address.',
    'email2FASubject'     => 'Your authentication code',
    'email2FAMailBody'    => 'Your authentication code is:',
    'invalid2FAToken'     => 'Incorrect code.',
    'need2FA'             => 'You must complete two-factor authentication.',
    'needVerification'    => 'Check your email to complete account activation.',

    // Activate
    'activateAccountTitle' => 'Activate Account',
    'emailActivateTitle'    => 'Email Activation',
    'emailActivateBody'     => 'We have just sent you an email with a code to confirm your email address. Copy that code and paste it below.',
    'emailActivateSubject'  => 'Your activation code',
    'emailActivateMailBody' => 'Please use the code below to activate your account and start using the website.',
    'invalidActivateToken'  => 'Incorrect code.',
    'needActivate'          => 'You must complete registration by confirming the code sent to your email address.',
    'activationBlocked'     => 'You must activate your account before logging in.',

    // Groups
    'unknownGroup' => '{0} is not a valid group.',
    'missingTitle' => 'Group must have a title.',

    // Permissions
    'unknownPermission' => '{0} is not a valid permission.',
];