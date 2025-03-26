<p><?= lang('AuthCustomer.activation_email_body') ?> <?= base_url() ?>.</p>

<p><?= lang('AuthCustomer.activation_email_link') ?></p>

<p><a href="<?= base_url(route_to('cus_activate_account')) . '?token=' . $hash ?>"><?= lang('AuthCustomer.activation_button_text') ?></a>.</p>

<br>

<p><?= lang('AuthCustomer.activation_email_footer') ?></p>