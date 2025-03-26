<p><?= lang('AuthCustomer.reset_password_body') ?> <?= base_url() ?>.</p>

<p><?= lang('AuthCustomer.reset_password_link') ?></p>

<p><?= lang('AuthCustomer.reset_password_code') ?>: <?= $hash ?></p>

<p><?= lang('AuthCustomer.reset_password_visit') ?> <a href="<?= base_url(route_to('customer-reset-password')) . '?token=' . $hash ?>"><?= lang('AuthCustomer.reset_password_form') ?></a>.</p>

<br>

<p><?= lang('AuthCustomer.reset_password_footer') ?></p>
