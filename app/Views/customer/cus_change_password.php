<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 11/6/2023
 */

echo $this->extend($configs->viewLayout);
echo $this->section('content');

?>
<!-- page-title -->
<div class="tf-page-title">
    <div class="container-full">
        <div class="heading text-center"><?=lang('Customer.my_account')?></div>
    </div>
</div>
<!-- /page-title -->

<!-- page-cart -->
<section class="flat-spacing-11">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <?= view('customer/components/account_sidebar', ['customer' => $customer]) ?>
            </div>
            <div class="col-lg-9">
                <div class="my-account-content account-edit">
                    <?=view('customer/components/customer_alert_block')?>

                    <div class="mb_60">
                        <form class="" id="form-password-change" action="<?= route_to('cus_change_password') ?>" method="post">
                            <?= csrf_field() ?>
                            <h6 class="mb_20"><?=lang('Customer.change_password')?></h6>
                            <div class="tf-field style-1 mb_30">
                                <input class="tf-field-input tf-input" placeholder=" " type="password" id="property4" name="old_password">
                                <label class="tf-field-label fw-4 text_black-2" for="property4"><?=lang('Customer.current_password')?></label>
                            </div>
                            <div class="tf-field style-1 mb_30">
                                <input class="tf-field-input tf-input" placeholder=" " type="password" id="property5" name="password">
                                <label class="tf-field-label fw-4 text_black-2" for="property5"><?=lang('Customer.password')?></label>
                            </div>
                            <div class="tf-field style-1 mb_30">
                                <input class="tf-field-input tf-input" placeholder=" " type="password" id="property6" name="password_confirm">
                                <label class="tf-field-label fw-4 text_black-2" for="property6"><?=lang('Customer.passwordConfirm')?></label>
                            </div>
                            <div class="mb_20">
                                <button type="submit" class="tf-btn w-100 radius-3 btn-fill animate-hover-btn justify-content-center"><?=lang('Common.save_changes')?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- page-cart -->

<?= $this->endSection() ?>
