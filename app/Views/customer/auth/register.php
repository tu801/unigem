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

<!--register wrapper-->
<div class="register_wrap section_padding_b">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-7 col-md-9">
                <?= view('customer/templates/_message_block') ?>
                <div class="register_form padding_default shadow_sm">
                    <h4 class="title_2"><?= lang('AuthCustomer.register_account') ?></h4>
                    <form method="post">
                        <?= csrf_field() ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="single_billing_inp">
                                    <label><?= lang('AuthCustomer.fullname') ?> <span>*</span></label>
                                    <input type="text" name="full_name" value="<?= old('full_name') ?>"  class="<?= session('errors.full_name') ? 'form-control is-invalid' : '' ?>" placeholder="<?= lang('AuthCustomer.fullname') ?>">
                                    <?php if (session('errors.full_name')): ?>
                                        <div class="invalid-feedback"><?= session('errors.full_name') ?></div>
                                    <?php endif;?>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="single_billing_inp">
                                    <label><?= lang('AuthCustomer.email') ?></label>
                                    <input type="email" name="email" value="<?= old('email') ?>"  class="<?= session('errors.email') ? 'form-control is-invalid' : '' ?>" placeholder="<?= lang('AuthCustomer.email') ?> ">
                                    <?php if (session('errors.email')): ?>
                                        <div class="invalid-feedback"><?= session('errors.email') ?></div>
                                    <?php endif;?>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="single_billing_inp">
                                    <label><?= lang('AuthCustomer.phone_number') ?>  <span>*</span></label>
                                    <input type="text" name="phone" value="<?= old('phone') ?>"  class="<?= session('errors.phone') ? 'form-control is-invalid' : '' ?>" placeholder="<?= lang('AuthCustomer.phone_number') ?> ">
                                    <?php if (session('errors.phone')): ?>
                                        <div class="invalid-feedback"><?= session('errors.phone') ?></div>
                                    <?php endif;?>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="single_billing_inp">
                                    <label><?= lang('AuthCustomer.username') ?> <span>*</span></label>
                                    <input type="text" name="username" value="<?= old('username') ?>"  class="<?= session('errors.username') ? 'form-control is-invalid' : '' ?>" placeholder="<?= lang('AuthCustomer.username') ?>">
                                    <?php if (session('errors.username')): ?>
                                        <div class="invalid-feedback"><?= session('errors.username') ?></div>
                                    <?php endif;?>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="single_billing_inp">
                                    <label><?= lang('AuthCustomer.password') ?>  <span>*</span></label>
                                    <input type="password" name="password" class="<?= session('errors.password') ? 'form-control is-invalid' : '' ?>" placeholder="<?= lang('AuthCustomer.password') ?> ">
                                    <?php if (session('errors.password')): ?>
                                        <div class="invalid-feedback"><?= session('errors.password') ?></div>
                                    <?php endif;?>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="single_billing_inp">
                                    <label><?= lang('AuthCustomer.repeat_password') ?> <span>*</span></label>
                                    <input type="password" name="pass_confirm" class="<?= session('errors.pass_confirm') ? 'form-control is-invalid' : '' ?>" placeholder="<?= lang('AuthCustomer.repeat_password') ?>">
                                    <?php if (session('errors.pass_confirm')): ?>
                                        <div class="invalid-feedback"><?= session('errors.pass_confirm') ?></div>
                                    <?php endif;?>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <button type="submit" class="default_btn xs_btn rounded px-4 d-block w-100"><?= lang('AuthCustomer.register_account') ?></button>
                            </div>

                        </div>
                    </form>
                    <p class="text-center mt-3 mb-0"><?= lang('AuthCustomer.alreadyRegistered') ?> <a href="<?= base_url(route_to('cus_login')) ?>" class="text-color"><?= lang('AuthCustomer.login')?></a></p>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>
