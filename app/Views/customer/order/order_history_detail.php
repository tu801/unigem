<?php

/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 11/12/2023
 */

use App\Enums\Store\Order\EDeliveryType;

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
                <div class="wd-form-order">
                    <div class="order-head">
                        <!-- <figure class="img-product">
                            <img src="images/products/brown.jpg" alt="product">
                        </figure> -->
                        <div class="content">
                            <div class="badge">
                                <?= $order->getOrderStatusText() ?>
                            </div>
                            <h6 class="mt-8 fw-5"><?= lang('Order.order_code') . " #" . $order->code ?></h6>
                        </div>
                    </div>
                    <div class="tf-grid-layout md-col-2 gap-15">
                        <div class="item">
                            <div class="text-2 text_black-2"><?= lang('Order.customer_name') ?></div>
                            <div class="text-2 mt_4 fw-6"><?= $order->customer_info->name ?></div>
                        </div>
                        <div class="item">
                            <div class="text-2 text_black-2"><?= lang('Order.customer_phone') ?></div>
                            <div class="text-2 mt_4 fw-6"><?= $order->customer_info->phone ?></div>
                        </div>
                        <div class="item">
                            <div class="text-2 text_black-2"><?= lang('Order.order_purchased_date') ?></div>
                            <div class="text-2 mt_4 fw-6"><?= $order->created_at->format('d/m/Y') ?></div>
                        </div>
                        <div class="item">
                            <div class="text-2 text_black-2"><?= lang('Order.payment_method') ?></div>
                            <div class="text-2 mt_4 fw-6"><?= lang("Order.payment_method_{$order->payment_method}") ?></div>
                        </div>
                    </div>
                    <div class="widget-tabs style-has-border widget-order-tab">
                        <ul class="widget-menu-tab">
                            <li class="item-title active">
                                <span class="inner"><?= lang('Order.order_detail') ?></span>
                            </li>
                            <li class="item-title">
                                <span class="inner"><?= lang('Order.delivery_method') ?></span>
                            </li>
                            <li class="item-title">
                        </ul>
                        <div class="widget-content-tab">
                            <div class="widget-content-inner active">
                                <?php foreach ($order->order_items as $item): ?>
                                    <div class="order-head">
                                        <?php
                                        $img = (isset($item->feature_image['thumbnail']) && $item->feature_image['thumbnail'] !== null) ? $item->feature_image['thumbnail'] : base_url($configs->noimg);
                                        ?>
                                        <figure class="img-product">
                                            <img src="<?= $img ?>" alt="<?= $item->pd_name ?? '' ?>">
                                        </figure>
                                        <div class="content">
                                            <div class="text-2 fw-6"><?= $item->pd_name ?? '' ?></div>
                                            <div class="mt_4"><span class="fw-6"><?= lang('Order.unit_price') ?> :</span> <?= format_currency($item->unit_price, $order->lang) ?></div>
                                            <div class="mt_4"><span class="fw-6"><?= lang('Order.quantity') ?> :</span> <?= $item->quantity ?? 0 ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>


                                <ul>
                                    <li class="d-flex justify-content-between text-2">
                                        <span><?= lang('Order.unit_price') ?></span>
                                        <span class="fw-6"><?= format_currency($order->sub_total, $order->lang) ?></span>
                                    </li>
                                    <?php if ($order->lang_id != 1): ?>
                                        <li class="d-flex justify-content-between text-2 mt_4">
                                            <span><?= lang('Order.exchange_rate') ?></span>
                                            <span class="fw-6"><?= vnd_encode($order->exchange_rate) ?></span>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($order->discount_amount > 0) : ?>
                                        <li class="d-flex justify-content-between text-2 mt_4 pb_8 line">
                                            <span><?= lang('Order.discount') ?></span>
                                            <span class="fw-6"><?= format_currency($order->discount_amount, $order->lang) ?></span>
                                        </li>
                                    <?php endif; ?>
                                    <li class="d-flex justify-content-between text-2 mt_8">
                                        <span><?= lang('Order.total') ?></span>
                                        <span class="fw-6"><?= vnd_encode($order->total_amount_vnd) ?></span>
                                    </li>
                                </ul>
                            </div>
                            <div class="widget-content-inner">
                                <?php
                                if ($order->delivery_type == EDeliveryType::HOME_DELIVERY) {
                                    echo '<p class="text-2">' . $order->full_address_delivery . '</p>';
                                } else {
                                    echo '<p class="text-2">' . lang('Order.pick_up') . '</p>';
                                }
                                ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- page-cart -->

<?= $this->endSection() ?>