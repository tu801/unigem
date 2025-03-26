<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 11/12/2023
 */

use Modules\Acp\Enums\Store\Order\EDeliveryType;
use Modules\Acp\Enums\Store\Order\EOrderStatus;

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
                <div class="order_detail_wrapper shadow_sm">
                    <h4 class="od_title"><?= lang('OrderShop.order_detail') ?></h4>
                    <!-- order details -->
                    <div class="orderdet_info d-flex align-items-center justify-content-between flex-wrap">
                        <div class="single_orderdet">
                            <h5><?= lang('OrderShop.sold_by') ?></h5>
                            <p class="text-color"><?= $order->shop->name ?? '' ?></p>
                        </div>
                        <div class="single_orderdet">
                            <h5><?= lang('Order.code') ?></h5>
                            <p><?= $order->code ?? '' ?></p>
                        </div>
                        <div class="single_orderdet">
                            <h5><?= lang('Order.invoice_date') ?></h5>
                            <p><?= $order->created_at->format('d/m/Y') ?></p>
                        </div>
                    </div>

                    <!-- shipping address process -->
                    <div class="shipping_process">
                        <div class="sprocess_cont">
                            <div class="sprosbar">
                                <span class="sp_fill"></span>
                            </div>
                            <div class="single_sproc_cont ">
                                <div class="sproc_cont_dot"></div>
                                <p><?= lang('Order.order_status_1') ?></p>
                            </div>
                            <div class="single_sproc_cont ">
                                <div class="sproc_cont_dot gray"></div>
                                <p><?= lang('Order.order_status_2') ?></p>
                            </div>
                            <div class="single_sproc_cont ">
                                <div class="sproc_cont_dot gray"></div>
                                <p><?= lang('Order.order_status_3') ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- product details -->
                    <?php foreach ($orderItem as $item): ?>
                        <div class="order_prodetails d-flex align-items-center flex-wrap">
                            <div class="orderprod_img">
                                <?php
                                    $img = (isset($item->feature_image['thumbnail']) && $item->feature_image['thumbnail'] !== null) ? $item->feature_image['thumbnail'] : base_url($configs->noimg);
                                ?>
                                <img loading="lazy" src="<?= $img ?>" alt="product">
                            </div>
                            <div class="single_orderdet pdname">
                                <h5><?= $item->pd_name ?? '' ?></h5>
                            </div>
                            <div class="single_orderdet w-xs-33 ms-md-auto ms-0 mt-3 mt-md-0">
                                <h5><?= number_format($item->total)?>đ</h5>
                            </div>
                            <div class="single_orderdet w-xs-33 ms-auto mt-3 mt-md-0">
                                <h5>X<?= $item->quantity ?? '' ?></h5>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>

                <div class="profile_info_wrap mt-4">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="single_prof_info shadow_sm">
                                <div class="prof_info_title">
                                    <h4>Địa chỉ giao hàng</h4>
                                </div>
                                <div class="prfo_info_cont">
                                   <p>
                                       <?php if($order->delivery_type == EDeliveryType::HOME_DELIVERY ): ?>
                                           <?= $order->full_address_delivery ?? ''?><br>
                                       <?php else: ?>
                                        <?= lang('Order.pick_up') ?>
                                       <?php endif; ?>
                                   </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="single_prof_info shadow_sm mb-0">
                                <div class="prof_info_title">
                                    <h4>Tóm tắt đơn hàng</h4>
                                </div>
                                <div class="prfo_info_cont">
                                    <div class="d-flex justify-content-between">
                                        <p class="mb-0"><?= lang('Order.sub_total')?></p>
                                        <p class="text-semibold mb-0"><?= number_format($order->sub_total) ?? ''?>đ</p>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <p class=" mb-0"><?= lang('Order.shipping_fee')?></p>
                                        <p class="text-semibold mb-0"><?= number_format($order->shipping_amount) ?? '' ?>đ</p>
                                    </div>
                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between">
                                        <p class="text-semibold mb-0"><?= lang('Order.total')?></p>
                                        <p class="text-semibold mb-0"><?= number_format($order->total) ?? '' ?>đ</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
