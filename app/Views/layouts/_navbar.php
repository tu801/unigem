<?php
/**
 * @author tmtuan
 * created Date: 10/20/2023
 * Project: Unigem
 */

$otherPage = ( $controller != 'home' && $method != 'index' ) ? 'otherpage' : '';
?>
<nav class="home-2">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <a href="<?=base_url()?>" class="mobile_logo d-block d-lg-none">
                <img loading="lazy"  src="<?=get_logo_url($configs)?>" alt="<?= get_theme_config('general_site_title') ?>">
            </a>
            <div class="all_category <?=$otherPage?> d-none d-lg-block">
                <div class="bars text-white d-flex align-items-center justify-content-center">
                        <span class="icon">
                            <i class="las la-bars"></i>
                        </span>
                    <span class="icon_text" style="font-size: 14px"><?=lang('Home.all_product_categories')?></span>
                </div>
                <div class="sub_categories <?=$otherPage != '' ? '' : 'active' ?> ">
                    <h5 class="d-block position-relative d-lg-none subcats_title">
                        <?=lang('Home.all_categories')?>
                    </h5>
                    <?= view_cell('\App\Cells\Menu\MenuProductsCell', null, 300) ?>
                </div>
            </div>
            <div class="search_wrap d-none d-lg-block">
                <form method="get" action="<?=base_url(route_to('product_shop'))?>">
                <div class="search d-flex">
                    <!-- Search Category -->
                    <?php echo view_cell('\App\Cells\SearchCategoryCell') ?>

                    <div class="search_input">
                        <input type="text" name="query" placeholder="<?=lang('Home.search_box_placeholder')?>" id="show_suggest">
                    </div>
                    <div class="search_subimt">
                        <button type="submit">
                            <span class="d-none d-sm-inline-block"><?=lang('Home.search_btn')?></span>
                            <span class="d-sm-none d-inline-block"><i class="icon-search-left"></i></span>
                        </button>
                    </div>
                    <input type="hidden" id="cstoken" value="<?= csrf_hash() ?>">
                    <input type="hidden" id="csname" value="<?= csrf_token() ?>">
                </div>
                </form>

                <div class="search_suggest shadow-sm">
                    <div class="search_result_product" id="search_result_product"></div>
                </div>
            </div>
            <div class="header_icon d-flex align-items-center">
<!--                <a href="wish-list.html" class="icon_wrp text-center wishlist ms-0">-->
<!--                        <span class="icon">-->
<!--                            <i class="icon-heart"></i>-->
<!--                        </span>-->
<!--                    <span class="icon_text">Wish List</span>-->
<!--                    <span class="pops">6</span>-->
<!--                </a>-->
                <div class="shopcart">
                    <a href="<?= base_url(route_to('order_cart')) ?>" class="icon_wrp text-center d-none d-lg-block">
                            <span class="icon">
                                <i class="icon-cart"></i>
                            </span>
                        <span class="icon_text"><?= lang('OrderShop.cart') ?></span>
                        <span class="pops"> {{ carts.length }}</span>
                    </a>
                    <div class="shopcart_dropdown">
                        <div class="cart_droptitle">
                            <h4 class="text_lg">{{ carts.length }} <?= lang('OrderShop.product') ?></h4>
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
                        <div class="total_cartdrop">
                            <h4 class="text_lg text-uppercase mb-0"><?= lang('Order.sub_total') ?> :</h4>
                            <h4 class="text_lg mb-0 ms-2">{{ formatVnd(bill.sub_total) }}</h4>
                        </div>
                        <div class="cartdrop_footer d-flex mt-3" v-if="carts.length > 0">
                            <a href="<?= base_url(route_to('order_cart')) ?>" class="default_btn w-50 text_xs px-1"><?= lang('OrderShop.cart') ?></a>
                            <a href="<?= base_url(route_to('order_checkout')) ?>" class="default_btn second ms-3 w-50 text_xs px-1"><?= lang('OrderShop.checkout') ?></a>
                        </div>
                    </div>
                </div>

                <div class="position-relative myacwrap">
                    <?= view_cell('\App\Cells\Menu\MenuAccountCell') ?>
                </div>
            </div>

        </div>
    </div>
</nav>

