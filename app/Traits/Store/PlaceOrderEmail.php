<?php

namespace App\Traits\Store;

use App\Models\ConfigModel;
use CodeIgniter\Shield\Traits\Viewable;

trait PlaceOrderEmail
{
    use Viewable;

    /**
     * Send order order information to Admin
     *
     * @param $order
     * @param $customer
     */
    public function sendOrderEmail($order, $customer)
    {
        $adminEmail = model(ConfigModel::class)->getConfigByKey('admin_order_email_receive');

        if (empty($adminEmail)) {
            log_message('error', "Admin email for order notifications is not set.");
            return;
        }

        helper('email');
        $email = emailer(['mailType' => 'html'])
            ->setFrom(setting('Email.fromEmail'), setting('Email.fromName') ?? '');
        $email->setTo($adminEmail);
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

        log_message('info', 'Order email sent to admin: ' . $adminEmail . ' for order: ' . $order->code);
        // Clear the email
        $email->clear();
    }
}
