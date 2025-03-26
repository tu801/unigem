<div>
    <a href="javascript:void(0)" class="icon_wrp text-center myacc">
                            <span class="icon">
                               <i class="icon-user-line"></i>
                            </span>
        <span class="icon_text">Account</span>
    </a>
    <div class="myacc_cont">
        <?php if ( !isset($cus_login) ) : ?>
        <div class="ac_join">
            <p><?=lang('Home.menu_acc_welcome_line', [get_theme_config('general_site_title')])?></p>
            <div class="account_btn d-flex justify-content-between">
                <a href="<?=route_to('cus_register')?>" class="default_btn" style="font-size: 12px;"><?=lang('Home.cus_register')?></a>
                <a href="<?=route_to('cus_login')?>" class="default_btn second" style="font-size: 10px;"><?=lang('Home.cus_login')?></a>
            </div>
        </div>
        <?php else: ?>
        <div class="ac_links">
            <a href="<?=route_to('cus_profile')?>" class="myac">
                <i class="lar la-id-card"></i>
                <?=lang('AuthCustomer.my_account')?>
            </a>
            <a href="<?=base_url(route_to('voucher_list'))?>">
                <i class="las la-gift"></i>
                <?=lang('AuthCustomer.acc_menu_my_voucher')?>
            </a>
<!--            <a href="wish-list.html">-->
<!--                <i class="lar la-heart"></i>-->
<!--                My Wishlist-->
<!--            </a>-->
            <a href="<?=base_url(route_to('order_history'))?>">
                <i class="icon-cart"></i>
                <?=lang('AuthCustomer.acc_menu_my_order')?>
            </a>
            <a href="<?=route_to('cus_logout')?>">
                <i class="las la-power-off"></i>
                <?=lang('AuthCustomer.logout')?>
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>
