<?php

use Modules\Acp\Enums\BankingPaymentEnum;

echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>
    <!-- breadcrumbs -->
    <?php echo view_cell('App\Libraries\BreadCrumb\BreadCrumbCell') ?>
    <!-- payment methods -->
    <div class="cart_area section_padding_b">
        <div class="container">
            <div class="row">
                <div class="col-12 mb-4">
                    <h4 class="shop_cart_title ps-4"><?= lang('PaymentConfig.bank') ?></h4>

                    <div class="payment_method_options">
                        <?php foreach ($banks as $index => $bank): ?>
                        <div class="single_payment_method <?= $index == 0 ? 'active' : '' ?>" data-target=".bank-<?= $bank->bank ?? '' ?>">
                            <div class="sp_img">
                                <img loading="lazy" src="<?= base_url(BankingPaymentEnum::BANK_LIST[$bank->bank]['logo']) ?>" width="100" height="100" class="img-fluid" alt="credit card">
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="row">
                        <div class="col-12">
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
                                    <div class="single_check_order">
                                        <div class="checkorder_cont">
                                            <h5><?= lang('OrderShop.transfer_content') ?>:</h5>
                                        </div>
                                        <p class="checkorder_price text-danger ">DONHANG <?= $order->code ?? '' ?></p>
                                    </div>
                                    <div class="single_check_order total">
                                        <div class="checkorder_cont">
                                            <h5><?= lang('OrderShop.total_payment') ?>:</h5>
                                        </div>
                                        <p class="checkorder_price text-danger"><?= number_format($order->total) ?>đ</p>
                                    </div>

                                    <div class="single_check_order total">
                                        <div class="checkorder_cont">
                                            <h5><?= lang('OrderShop.deposit') ?>:</h5>
                                        </div>
                                        <p class="checkorder_price text-danger"><?= number_format(($order->sub_total * (10 /100)) + $order->shipping_amount) ?>đ</p>
                                    </div>
                                    <form method="post" enctype="multipart/form-data">
                                        <?= csrf_field( )?>
                                        <input type="hidden" name="bank" value="<?= $bank->bank ?>">
                                        <div class="single_check_order total">
                                            <div class="checkorder_cont">
                                                <h5><?= lang('OrderShop.image_payment') ?>:</h5>
                                            </div>
                                            <p class="checkorder_price py-5">
                                                <div class="img_uploading">
                                                    <input type="file" name="image" id="upload_img" hidden>
                                                    <label for="upload_img" class="upload_img">
                                                        <div class="upload_icn">
                                                            <img loading="lazy" class="image-review"  src="<?=base_url($configs->templatePath)?>/assets/images/upload-img.png">
                                                        </div>
                                                        <p><?= lang('OrderShop.image_select') ?></p>
                                                    </label>
                                                </div>
                                            </p>
                                        </div>

                                        <div class="single_check_order d-flex justify-content-center">
                                            <button type="submit" class="default_btn rounded text-center"><?= lang('OrderShop.payment_completed') ?></button>
                                        </div>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>
<?php echo $this->section('scripts'); ?>
<script>
    $(document).ready(() => {
        $('#upload_img').change(function () {
            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function (event) {
                    $('.image-review').attr('src', event.target.result);
                }
                reader.readAsDataURL(file);
            }
        });
    });
</script>
<?= $this->endSection() ?>
