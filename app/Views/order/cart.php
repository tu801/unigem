<?php
echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>
<!-- breadcrumbs -->
<?php echo view_cell('App\Libraries\BreadCrumb\BreadCrumbCell') ?>
<!-- shopping cart -->
<div class="cart_area section_padding_b">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <h4 class="shop_cart_title sopcart_ttl d-none d-lg-flex">
                    <span><?= lang('Order.product_name') ?></span>
                    <span><?= lang('Order.quantity') ?></span>
                    <span><?= lang('Order.total') ?></span>
                </h4>
                <div class="shop_cart_wrap" v-if="carts.length > 0">
                    <div class="single_shop_cart d-flex align-items-center flex-wrap" v-for="(cart, index) in carts">
                        <div class="cart_img mb-4 mb-md-0">
                            <img loading="lazy"  :src="cart.image" alt="product">
                        </div>
                        <div class="cart_cont">
                            <h5>{{ cart.pd_name }}</h5>
                            <p class="price">{{ formatVnd((cart.price_discount > 0 && cart.price_discount < cart.price) ? cart.price_discount : cart.price) }}</p>
                        </div>
                        <div class="cart_qnty d-flex align-items-center ms-md-auto">
                            <div class="cart_qnty_btn" @click="minusQuantityProduct(index)">
                                <i class="las la-minus"></i>
                            </div>
                            <div class="cart_count">{{ cart.quantity }}</div>
                            <div class="cart_qnty_btn" @click="plusQuantityProduct(index)">
                                <i class="las la-plus"></i>
                            </div>
                        </div>
                        <div class="cart_price ms-auto">
                            <p>{{ formatVnd(((cart.price_discount > 0 && cart.price_discount < cart.price) ? cart.price_discount : cart.price) * cart.quantity) }}</p>
                        </div>
                        <div class="cart_remove ms-auto" @click="removeProduct(index)">
                            <i class="icon-trash"></i>
                        </div>
                    </div>
                </div>
                <div class="shop_cart_wrap text-center py-5" v-else>
                    <?= lang('OrderShop.product_empty') ?>
                </div>
            </div>
            <div class="col-lg-3 mt-4 mt-lg-0" v-if="carts.length > 0">
                <div class="cart_summary">
                    <h4><?= lang('OrderShop.order') ?></h4>
                    <div class="cartsum_text d-flex justify-content-between">
                        <p class="text-semibold"><?= lang('Order.sub_total') ?></p>
                        <p class="text-semibold">{{ formatVnd(bill.sub_total) }}</p>
                    </div>
                    <div class="cartsum_text d-flex justify-content-between">
                        <p><?= lang('Order.shipping_fee') ?></p>
                        <p>{{ formatVnd(bill.shipping_fee) }}</p>
                    </div>
                    <div class="cart_sum_total d-flex justify-content-between">
                        <p><?= lang('Order.order_total') ?></p>
                        <p>{{ formatVnd(bill.total) }}</p>
                    </div>
                    <div class="cart_sum_pros">
                        <a class="default_btn w-100 text_xs px-1" href="<?= base_url(route_to('order_checkout')) ?>"><?= lang('OrderShop.process_checkout') ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>