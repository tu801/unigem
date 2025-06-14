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
                <div class="my-account-content account-dashboard">
                    <div class="mb_60">
                        Customer Profile Information
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- page-cart -->

<?= $this->endSection() ?>
