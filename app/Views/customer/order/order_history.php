<?php

/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 11/12/2023
 */

echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>
<!-- page-title -->
<div class="tf-page-title">
    <div class="container-full">
        <div class="heading text-center"><?= lang('Order.order_history_title') ?></div>
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
                <?php echo view_cell('\App\Cells\Customer\RecentOrdersCell::listOrderHistory') ?>
            </div>
        </div>
    </div>
</section>
<!-- page-cart -->

<?= $this->endSection() ?>