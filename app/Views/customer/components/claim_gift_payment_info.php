<?php
/**
 * Author: tmtuan
 * Created date: 11/16/2023
 * Project: Unigem
 **/

use Modules\Acp\Enums\BankingPaymentEnum;
?>
<div class="single_billing_inp">
    <label for="postInputTitle"><?= lang('LuckyDraw.ship_fee') ?> <span class="text-danger">*</span></label>
    <div class="input-group">
        <input disabled name="ship_fee" class="<?= session('errors.ship_fee') ? 'form-control is-invalid' : 'form-control' ?>" type="text"
               placeholder="<?= lang('LuckyDraw.ship_fee') ?>" id="shippingFee" value="0" >
        <!--                                        <span style="padding: 5px" class="input-group-text" >đ</span>-->
    </div>

</div>

<div class="single_billing_inp">
    <label><?= lang('PaymentConfig.bank') ?> </label>
    <div class="payment_method_options">
        <?php foreach ($banks as $index => $bank): ?>
            <div style="width: 90px; height: 70px" class="single_payment_method <?= $index == 0 ? 'active' : '' ?>" data-target=".bank-<?= $bank->bank ?? '' ?>">
                <div class="sp_img">
                    <img loading="lazy" src="<?= base_url(BankingPaymentEnum::BANK_LIST[$bank->bank]['logo']) ?>" width="100" height="100" class="img-fluid" alt="credit card">
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="single_billing_inp">
    <?php foreach ($banks as $index => $bank): ?>
        <div class="payment_methods bank-<?= $bank->bank ?? '' ?> <?= $index == 0 ? 'active' : '' ?>">
            <div class="single_check_order">
                <div class="checkorder_cont">
                    <h5><?= lang('PaymentConfig.name') ?>:</h5>
                </div>
                <p class="checkorder_price text-danger"><?= $bank->name ?? '' ?></p>
            </div>
            <div class="single_check_order">
                <div class="checkorder_cont">
                    <h5><?= lang('PaymentConfig.account_number') ?></h5>
                </div>
                <p class="checkorder_price text-danger"><?= $bank->account_number ?? '' ?></p>
            </div>
            <div class="single_check_order">
                <div class="checkorder_cont">
                    <h5><?= lang('PaymentConfig.branch') ?></h5>
                </div>
                <p class="checkorder_price text-danger"><?= $bank->branch ?? '' ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>
