<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 11/12/2023
 */

echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>

<!-- breadcrumbs -->
<?php echo view_cell('App\Libraries\BreadCrumb\BreadCrumbCell') ?>


<!-- account -->
<div class="my_account_wrap section_padding_b">
    <div class="container">
        <div class="row">
            <!--  account sidebar  -->
            <div class="col-lg-3">
                <?= view('customer/components/account_sidebar', ['user' => $user]) ?>
            </div>
            <!-- account content -->
            <div class="col-lg-9">
                <div class="account_cont_wrap">
                    <?= view('customer/templates/_message_block') ?>

                    <div class="acprof_wrap shadow_sm">
                        <h4><?=lang('CustomerProfile.cus_change_password')?></h4>
                        <form action="<?=current_url()?>" method="post">
                            <?= csrf_field() ?>
                            <div class="row">
                                <div class="col-12">
                                    <div class="single_billing_inp">
                                        <label for="old_password"><?=lang('CustomerProfile.oldPassword')?></label>
                                        <input type="password" class="form-control <?php if(session('errors.old_password')) : ?>is-invalid<?php endif ?>"
                                               name="old_password">
                                        <div class="invalid-feedback"> <?= session('errors.old_password') ?> </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="single_billing_inp">
                                        <label for="password"><?=lang('CustomerProfile.newPassword')?></label>
                                        <input type="password" class="form-control <?php if(session('errors.password')) : ?>is-invalid<?php endif ?>"
                                               name="password">
                                        <div class="invalid-feedback"> <?= session('errors.password') ?> </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="single_billing_inp">
                                        <label for="pass_confirm"><?=lang('CustomerProfile.newPasswordRepeat')?></label>
                                        <input type="password" class="form-control <?php if(session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>" name="pass_confirm">
                                        <div class="invalid-feedback"><?= session('errors.pass_confirm') ?> </div>
                                    </div>
                                </div>

                                <div class="col-12 mt-3 acprof_subbtn">
                                    <button type="submit" class="default_btn xs_btn rounded px-4 d-block w-100"><?=lang('Home.save_action')?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>