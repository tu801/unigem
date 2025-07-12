<?php
echo $this->extend($configs->viewLayout);
echo $this->section('content');
//dd($order);
?>
<!-- page-title -->
<div class="tf-page-title">
    <div class="container-full">
        <div class="row">
            <div class="col-12">
                <div class="heading text-center"><?= $page_title ?></div>
                <?php echo view_cell('App\Libraries\BreadCrumb\BreadCrumbCell') ?>
            </div>
        </div>
    </div>
</div>
<!-- /page-title -->

<section class="flat-spacing-11">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <h5 class="fw-5 mb_20"><?= lang('Order.order_information') ?></h5>

                <div class="tf-page-cart-checkout">
                    <div class="d-flex align-items-center justify-content-between mb_15">
                        <div class="fs-18"><?= lang('Order.order_purchased_date') ?></div>
                        <p><?= $order->created_at->format('d/m/Y') ?></p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb_15">
                        <div class="fs-18"><?= lang('Order.order_code') ?></div>
                        <p><b><?= $order->code ?></b></p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb_15">
                        <div class="fs-18"><?= lang('Order.customer_name') ?></div>
                        <p><?= $order->customer_info->name ?></p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb_15">
                        <div class="fs-18"><?= lang('Order.customer_phone') ?></div>
                        <p><?= $order->customer_info->phone ?></p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb_15">
                        <div class="fs-18"><?= lang('Order.customer_email') ?></div>
                        <p><?= $order->customer_info->email ?></p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb_15">
                        <div class="fs-18"><?= lang('Order.customer_phone') ?></div>
                        <p><?= $order->cus_phone ?></p>
                    </div>

                    <?php if ($order->lang_id > 1) : ?>
                        <div class="d-flex align-items-center justify-content-between mb_15">
                            <div class="fs-18"><?= lang('Order.exchange_rate') ?></div>
                            <p><?= format_currency($order->exchange_rate, $order->lang) ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex align-items-center justify-content-between mb_24">
                        <div class="fs-22 fw-6"><?= lang('Order.order_total') ?></div>
                        <span class="total-value"><?= format_currency($order->total_amount_vnd) ?></span>
                    </div>
                    <div class="d-flex gap-10">
                        <a href="<?= base_url() ?>" class="tf-btn w-100 btn-outline animate-hover-btn rounded-0 justify-content-center">
                            <span><?= lang('Order.continue_shopping') ?></span>
                        </a>
                        <a href="<?= base_url(route_to('order_history_detail', $order->order_id)) ?>" class="tf-btn w-100 btn-fill animate-hover-btn radius-3 justify-content-center">
                            <span><?= lang('Order.order_detail') ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>