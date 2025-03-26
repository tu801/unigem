<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 11/6/2023
 */

echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>

<!-- breadcrumbs -->
<?php echo view_cell('App\Libraries\BreadCrumb\BreadCrumbCell') ?>

<!--Login wrap-->
<div class="register_wrap section_padding_b">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-7 col-md-9">
                <?= view('customer/templates/_message_block') ?>
                <div class="register_form padding_default shadow_sm">
                    <h4 class="title_2"><?= lang('AuthCustomer.login')?></h4>
                    <form method="post">
                        <?= csrf_field() ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="single_billing_inp">
                                    <label><?= lang('AuthCustomer.username')?> <span>*</span></label>
                                    <input type="text" name="username" value="<?= old('username') ?>"  class="<?= session('errors.username') ? 'form-control is-invalid' : '' ?>" placeholder="<?= lang('AuthCustomer.username')?>">
                                    <?php if (session('errors.username')): ?>
                                        <div class="invalid-feedback"><?= session('errors.username') ?></div>
                                    <?php endif;?>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="single_billing_inp">
                                    <label><?= lang('AuthCustomer.password')?> <span>*</span></label>
                                    <input type="password" name="password" class="<?= session('errors.password') ? 'form-control is-invalid' : '' ?>" placeholder="<?= lang('AuthCustomer.password')?>">
                                    <?php if (session('errors.password')): ?>
                                        <div class="invalid-feedback"><?= session('errors.password') ?></div>
                                    <?php endif;?>
                                </div>
                            </div>
                            <div class="col-12 mt-2 d-flex justify-content-between align-items-center">
                                <div class="custom_check check_2 d-flex align-items-center">
                                    <input type="checkbox" name="remember" <?= old('remember') ? 'checked' : '' ?> class="check_inp" hidden id="save-default">
                                    <label for="save-default"><?= lang('Authcustomer.rememberMe') ?></label>
                                </div>

                                <a href="<?= base_url(route_to('cus_forgot_password')) ?>" class="text-color"><?= lang('AuthCustomer.forgotPassword')?></a>
                            </div>
                            <div class="col-12 mt-3">
                                <button type="submit" class="default_btn xs_btn rounded px-4 d-block w-100"><?= lang('AuthCustomer.login')?></button>
                            </div>

                        </div>
                    </form>
                    <p class="text-center mt-3 mb-0"><?= lang('Authcustomer.noAccount') ?> <a href="<?= base_url(route_to('cus_register')) ?>" class="text-color"><?= lang('AuthCustomer.register_account')?></a></p>

                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
