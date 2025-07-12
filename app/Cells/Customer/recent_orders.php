<div class="my-account-content account-order">
    <?php if (count($orders)) : ?>
        <div class="wrap-account-order">
            <table>
                <thead>
                    <tr>
                        <th class="fw-6"><?= lang('Order.order_code') ?></th>
                        <th class="fw-6"><?= lang('Order.order_purchased_date') ?></th>
                        <th class="fw-6"><?= lang('Order.order_status') ?></th>
                        <th class="fw-6"><?= lang('Order.order_total') ?></th>
                        <th class="fw-6"><?= lang('Common.actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $item) : ?>
                        <tr class="tf-order-item">
                            <td>
                                <a href="<?= base_url(route_to('order_history_detail', $item->order_id)) ?>">
                                    <?= $item->code ?>
                                </a>
                            </td>
                            <td>
                                <?= $item->created_at->format('d-m-Y') ?>
                            </td>
                            <td>
                                <?php
                                $textColor = '';
                                switch ($item->status) {
                                    case \App\Enums\Store\Order\EOrderStatus::OPEN:
                                        $textColor = '';
                                        break;
                                    case \App\Enums\Store\Order\EOrderStatus::CONFIRMED:
                                    case \App\Enums\Store\Order\EOrderStatus::SHIPPED:
                                    case \App\Enums\Store\Order\EOrderStatus::PROCESSED:
                                        $textColor = 'text-yellow';
                                        break;
                                    case \App\Enums\Store\Order\EOrderStatus::CANCELLED:
                                        $textColor = 'text-color';
                                        break;
                                    case \App\Enums\Store\Order\EOrderStatus::COMPLETE:
                                        $textColor = 'text-green';
                                        break;
                                }
                                echo '<p class="' . $textColor . '">' . $item->getOrderStatusText() . '</p>'
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($item->lang_id != 1) {
                                    echo format_currency($item->total, $item->lang) . '<br>';
                                }
                                ?>
                                <?= vnd_encode($item->total_amount_vnd, true) ?>
                            </td>
                            <td>
                                <a href="<?= base_url(route_to('order_history_detail', $item->order_id)) ?>" class="tf-btn btn-fill animate-hover-btn rounded-0 justify-content-center">
                                    <?= lang('Order.view_order_history') ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p><?= lang('CustomerProfile.no_recent_order_text') ?></p>
    <?php endif; ?>
</div>