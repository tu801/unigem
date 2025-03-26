<?php
/**
 * @author tmtuan
 * created Date: 10/20/2023
 * Project: Unigem
 */
?>

<div class="mobile_menwrap d-lg-none" id="mobileCart">
    <div class="mobile_cart_wrap d-flex flex-column">
        <h5 class="mobile_title">
            <?= lang('OrderShop.cart') ?>
            <span class="sidebarclose" id="mobileCartClose">
                    <i class="las la-times"></i>
                </span>
        </h5>
        <div class="px-3 py-3 flex-grow-1 d-flex flex-column">
            <div class="cart_droptitle">
                <h4 class="text_lg">{{ carts.length }} Sản phẩm</h4>
            </div>
            <div class="cartsdrop_wrap" v-if="carts.length > 0">
                <a class="single_cartdrop mb-3" v-for="(cart, index) in carts">
                    <span class="remove_cart" @click="removeProduct(index)"><i class="las la-times"></i></span>
                    <div class="cartdrop_img">
                        <img loading="lazy"  :src="cart.image" alt="product">
                    </div>
                    <div class="cartdrop_cont">
                        <h5 class="text_lg mb-0 default_link">{{ cart.pd_name }}</h5>
                        <p class="mb-0 text_xs text_p">x{{ cart.quantity }} <span class="ms-2">{{ formatVnd((cart.price_discount > 0 && cart.price_discount < cart.price) ? cart.price_discount : cart.price) }}</span></p>
                    </div>
                </a>
            </div>
            <div class="cartsdrop_wrap" v-else>
                <?= lang('OrderShop.product_empty') ?>
            </div>

            <div class="mt-auto">
                <div class="total_cartdrop">
                    <h4 class="text_lg text-uppercase mb-0"><?= lang('Order.sub_total') ?>:</h4>
                    <h4 class="text_lg mb-0 ms-2">{{ formatVnd(bill.sub_total) }}</h4>
                </div>
                <div class="cartdrop_footer mt-3 d-flex" v-if="carts.length > 0">
                    <a href="<?= base_url(route_to('order_cart')) ?>" class="default_btn w-50 text_xs px-1"><?= lang('OrderShop.cart') ?></a>
                    <a href="<?= base_url(route_to('order_checkout')) ?>" class="default_btn second ms-3 w-50 text_xs px-1"><?= lang('OrderShop.checkout') ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
