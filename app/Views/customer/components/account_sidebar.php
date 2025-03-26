<div class="account_sidebar">
    <div class="account_profile position-relative shadow_sm">
        <div class="acprof_img">
            <a href="<?=current_url()?>">
                <img loading="lazy"  src="<?=$user->avatar?>" alt="<?=$user->cus_full_name?>">
            </a>
        </div>
        <div class="acprof_cont">
            <p><?=lang('CustomerProfile.hello_text')?></p>
            <h4><?=$user->cus_full_name?></h4>
        </div>
        <div class="profile_hambarg d-lg-none d-block">
            <i class="las la-bars"></i>
        </div>
    </div>
    <div class="acprof_wrap shadow_sm">
        <div class="acprof_links">
            <a href="<?=base_url(route_to('cus_profile'))?>" class="active">
                <h4 class="acprof_link_title">
                    <i class="lar la-id-card"></i>
                    <?=lang('CustomerProfile.account_management')?>
                </h4>
            </a>
            <a href="<?=base_url(route_to('edit_cus_profile'))?>"><?=lang('CustomerProfile.cus_information')?></a>
<!--            <a href="account-manage-address.html">Manage Address</a>-->
            <a href="<?=base_url(route_to('cus_change_password'))?>"><?=lang('CustomerProfile.cus_change_pass')?></a>
        </div>
        <div class="acprof_links">
            <a href="<?=base_url(route_to('order_history'))?>">
                <h4 class="acprof_link_title">
                    <i class="lab la-opencart"></i>
                    <?=lang('CustomerProfile.recent_order_menu')?>
                </h4>
            </a>
<!--            <a href="account-return-order.html">My Returns</a>-->
<!--            <a href="account-order-cancel.html">My Cancellations</a>-->
<!--            <a href="account-my-reviews.html">My Reviews</a>-->
        </div>
        <div class="acprof_links">
<!--            <a href="account-payment-methods.html">-->
<!--                <h4 class="acprof_link_title">-->
<!--                    <i class="las la-credit-card"></i>-->
<!--                    Payments Methods-->
<!--                </h4>-->
<!--            </a>-->
            <a href="<?=base_url(route_to('voucher_list'))?>">
                <h4 class="acprof_link_title">
                <i class="las la-gift"></i> <?=lang('CustomerProfile.cus_voucher_menu')?>
                </h4>
            </a>
        </div>
<!--        <div class="acprof_links">-->
<!--            <a href="wish-list.html">-->
<!--                <h4 class="ac_link_title">-->
<!--                    <i class="lar la-heart"></i>-->
<!--                    My Wishlist-->
<!--                </h4>-->
<!--            </a>-->
<!--        </div>-->
        <div class="acprof_links border-0">
            <a href="<?=base_url(route_to('cus_logout'))?>">
                <h4 class="acprof_link_title">
                    <i class="las la-power-off"></i>
                    Log Out
                </h4>
            </a>
        </div>
    </div>
</div>
