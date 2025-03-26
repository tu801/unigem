<?php
/**
 * @author tmtuan
 * created Date: 10/20/2023
 * Project: Unigem
 */
?>

<div class="mobile_bottombar d-block d-lg-none">
    <div class="header_icon">
        <a href="javascript:void(0)" class="icon_wrp text-center open_menu">
                <span class="icon">
                    <i class="las la-bars"></i>
                </span>
            <span class="icon_text">Menu</span>
        </a>
        <a href="javascript:void(0)" class="icon_wrp text-center open_category">
                <span class="icon">
                    <i class="icon-list-ul"></i>
                </span>
            <span class="icon_text">Categories</span>
        </a>
        <a href="javascript:void(0)" class="icon_wrp text-center" id="src_icon">
                <span class="icon">
                   <i class="icon-search-left"></i>
                </span>
            <span class="icon_text">Search</span>
        </a>
        <a href="javascript:void(0)" class="icon_wrp crt text-center" id="openCart">
                <span class="icon">
                    <i class="icon-cart"></i>
                </span>
            <span class="icon_text"><?= lang('OrderShop.cart') ?></span>
            <span class="pops">{{ carts.length }}</span>
        </a>
    </div>
</div>
