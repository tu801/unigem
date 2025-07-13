<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<head>
    <meta name="x-apple-disable-message-reformatting">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?= lang('Auth.emailActivateSubject') ?></title>
</head>

<body>
    <p style="line-height: 20px; font-size: 20px;">
        <?= getenv('CI_ENVIRONMENT') == 'development' ? '[Demo] ' : '' ?>Xin chào, có đơn hàng mới vừa được đặt với mã là # <?= $order->code ?>
    </p>

    <p style="line-height: 20px; font-size: 20px;"><b>Một số thông tin về đơn hàng</b></p>

    <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
        <tbody>
            <tr>
                <td style="line-height: 20px; font-size: 20px; ">
                    Mã đơn hàng:
                </td>
                <td style="line-height: 20px; font-size: 20px; ">
                    <a href="<?= base_url('acp/order/edit/' . $order->order_id) ?>" target="_blank" rel="noopener noreferrer">
                        <?= $order->code ?>
                    </a>
                </td>
            </tr>
            <tr>
                <td style="line-height: 20px; font-size: 20px; ">
                    Tên Khách Hàng:
                </td>
                <td style="line-height: 20px; font-size: 20px; ">
                    <?= $customer->cus_full_name ?>
                </td>
            </tr>
            <tr>
                <td style="line-height: 20px; font-size: 20px; ">
                    Số điện thoại:
                </td>
                <td style="line-height: 20px; font-size: 20px; ">
                    <?= $customer->cus_phone ?>
                </td>
            </tr>
            <tr>
                <td style="line-height: 20px; font-size: 20px; ">
                    Tổng tiền:
                </td>
                <td style="line-height: 20px; font-size: 20px; ">
                    <?= number_format($order->total_amount_vnd) ?> đ
                </td>
            </tr>
            <tr>
                <td style="line-height: 20px; font-size: 20px; ">
                    Phương thức giao hàng:
                </td>
                <td style="line-height: 20px; font-size: 20px; ">
                    <?php

                    use App\Enums\Store\Order\EDeliveryType;

                    switch ($order->delivery_type) {
                        case EDeliveryType::HOME_DELIVERY:
                            echo 'Giao hàng tận nhà';
                            break;
                        case EDeliveryType::PICK_UP:
                            echo 'Nhận tại cửa hàng';
                            break;
                        default:
                            echo 'Không xác định';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td style="line-height: 20px; font-size: 20px; ">
                    Ngày đặt hàng:
                </td>
                <td style="line-height: 20px; font-size: 20px; ">
                    <?= $order->created_at ?>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>