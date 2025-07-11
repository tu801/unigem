<?php

namespace App\Traits\Store;

use App\Models\ConfigModel;
use RuntimeException;

trait PlaceOrderEmail
{

    /**
     * Send order order information to Admin
     *
     * @param $order
     * @param $customer
     */
    public function sendOrderEmail($order, $customer)
    {
        $adminEmail = model(ConfigModel::class)->getConfigByKey('admin_order_email_receive');
        if (empty($adminEmail) || !isset($adminEmail->id)) {
            return;
        }

        helper('email');
        $email = emailer(['mailType' => 'html'])
            ->setFrom(setting('Email.fromEmail'), setting('Email.fromName') ?? '');
        $email->setTo($adminEmail->value);
        $email->setSubject(lang('Order.newOrderPlaced', [$order->order_code]));
        $email->setMessage($this->view(
            '\App\Views\Email\email_admin_new_order_placed',
            ['order' => $order, 'customer' => $customer],
            ['debug' => false]
        ));

        if ($email->send(false) === false) {
            // Log the error message
            log_message('error', 'Failed to send order email: ' . $email->printDebugger(['headers']));
        }

        // Clear the email
        $email->clear();
    }
}
