<div class="modal modalCentered fade form-sign-in modal-part-content" id="login">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="header">
                <div class="demo-title"><?= lang('Auth.login') ?></div>
                <span class="icon-close icon-close-popup" data-bs-dismiss="modal"></span>
            </div>
            <div class="tf-login-form">
                <form class="" accept-charset="utf-8" id="loginForm" >
                    <div class="tf-field style-1">
                        <input class="tf-field-input tf-input" placeholder=" " id="login-email" type="email" name="email">
                        <label class="tf-field-label" for=""><?= lang('Auth.email') ?> *</label>
                    </div>
                    <div class="tf-field style-1">
                        <input class="tf-field-input tf-input" placeholder=" " id="login-password" type="password" name="password">
                        <label class="tf-field-label" for=""><?= lang('Auth.password') ?> *</label>
                    </div>
                    <div>
                        <a href="#forgotPassword" data-bs-toggle="modal"
                            class="btn-link link"><?= lang('Auth.forgotPassword') ?></a>
                    </div>
                    <div class="bottom">
                        <div class="w-100">
                            <button type="submit" id="login-submit"
                                class="tf-btn btn-fill animate-hover-btn radius-3 w-100 justify-content-center"><span><?= lang('Auth.login') ?></span></button>
                        </div>
                        <div class="w-100">
                            <a href="<?= base_url(route_to('cus_register')) ?>" class="btn-link fw-6 w-100 link">
                                <?= lang('Auth.newCustomerRegisterNow') ?>
                                <i class="icon icon-arrow1-top-left"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal modalCentered fade form-sign-in modal-part-content" id="forgotPassword">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="header">
                <div class="demo-title">Reset your password</div>
                <span class="icon-close icon-close-popup" data-bs-dismiss="modal"></span>
            </div>
            <div class="tf-login-form">
                <form class="">
                    <div>
                        <p>Sign up for early Sale access plus tailored new arrivals, trends and promotions. To opt out,
                            click unsubscribe in our emails</p>
                    </div>
                    <div class="tf-field style-1">
                        <input class="tf-field-input tf-input" placeholder=" " type="email" name="">
                        <label class="tf-field-label" for="">Email *</label>
                    </div>
                    <div>
                        <a href="#login" data-bs-toggle="modal" class="btn-link link">Cancel</a>
                    </div>
                    <div class="bottom">
                        <div class="w-100">
                            <button type="submit"
                                class="tf-btn btn-fill animate-hover-btn radius-3 w-100 justify-content-center"><span>Reset
                                    password</span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const loginValidateMessage = {
        emailInvalid: '<?= lang('Auth.invalidEmail') ?>',
        emailRequired: '<?= lang('Auth.emailRequired') ?>',
        passwordRequired: '<?= lang('Auth.passwordRequired') ?>',
        somethingWentWrong: '<?= lang('Common.somethingWentWrong') ?>',
        loginSuccess: '<?= lang('Auth.loginSuccess') ?>',
    }
</script>