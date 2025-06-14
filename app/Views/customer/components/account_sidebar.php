<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 */

$currentUri = '/' . uri_string();
?>
<div class="wrap-sidebar-account">
    <ul class="my-account-nav">
        <li>
            <?php if ($currentUri === route_to('cus_profile')) : ?>
                <span class="my-account-nav-item active"><?= lang('Customer.dashboard') ?></span>
            <?php else : ?>
                <a href="<?= route_to('cus_profile') ?>" class="my-account-nav-item"><?= lang('Customer.dashboard') ?></a>
            <?php endif; ?>
        </li>
        <li>
            <?php if ($currentUri === route_to('order_history')) : ?>
                <span class="my-account-nav-item active"><?= lang('Customer.orders') ?></span>
            <?php else : ?>
                <a href="my-account-orders.html" class="my-account-nav-item"><?= lang('Customer.orders') ?></a>
            <?php endif; ?>
        </li>
        <li>
            <?php if ($currentUri === route_to('my_account_address')) : ?>
                <span class="my-account-nav-item active"><?= lang('Customer.shipping_address') ?></span>
            <?php else : ?>
                <a href="my-account-address.html" class="my-account-nav-item"><?= lang('Customer.shipping_address') ?></a>
            <?php endif; ?>
        </li>
        <li>
            <?php if ($currentUri === route_to('edit_cus_profile')) : ?>
                <span class="my-account-nav-item active"><?= lang('Customer.account_detail') ?></span>
            <?php else : ?>
                <a href="<?=route_to('edit_cus_profile')?>" class="my-account-nav-item"><?= lang('Customer.account_detail') ?></a>
            <?php endif; ?>
        </li>
        <li>
            <?php if ($currentUri === route_to('cus_change_password')) : ?>
                <span class="my-account-nav-item active"><?= lang('Customer.change_password') ?></span>
            <?php else : ?>
                <a href="<?=route_to('cus_change_password')?>" class="my-account-nav-item"><?= lang('Customer.change_password') ?></a>
            <?php endif; ?>
        </li>
        <li><a href="<?= route_to('cus_logout') ?>" class="my-account-nav-item"><?= lang('Customer.logout') ?></a></li>
    </ul>
</div>