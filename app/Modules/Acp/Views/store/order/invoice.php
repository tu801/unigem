<?php

use App\Enums\Store\Order\EDeliveryType;

echo $this->extend($config->viewLayout);
echo $this->section('content');
?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Main content -->
                <div class="invoice p-3 mb-3">
                    <!-- title row -->
                    <div class="row">
                        <div class="col-12">
                            <h4>
                                <!-- <i class="fas fa-globe"></i>  -->
                                <img src="<?= base_url('themes/unigem/images/unigem-logo.png') ?>" alt="Logo" class="img-fluid" style="max-height: 50px; max-width: 150px;">
                                <?= $order->shop->name ?? '' ?>
                                <small class="float-right"><?= lang('Order.invoice_date') ?>: <?= $order->created_at->format('d/m/Y') ?> </small>
                            </h4>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- info row -->
                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                            <?= lang('Order.from') ?>
                            <address>
                                <strong><?= $order->shop->name ?? '' ?></strong><br>
                                <?= $order->shop->full_address ?? '' ?><br>
                                <?= lang('Order.phone') ?>: <?= $order->shop->phone ?? '' ?><br>
                            </address>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            <?= lang('Order.to') ?>
                            <address>
                                <strong><?= $order->cus_full_name ?? '' ?></strong><br>
                                <?php if ($order->delivery_type == EDeliveryType::HOME_DELIVERY): ?>
                                    <?= $order->full_delivery_address ?? '' ?><br>
                                    <?= lang('Order.ship_telephone') ?> : <?= $order->delivery_info->ship_telephone ?? '' ?><br>
                                    <?php if (!empty($order->delivery_info->ship_email)): ?>
                                        <?= lang('Order.ship_email') ?> : <?= $order->delivery_info->ship_email ?><br>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if ($order->delivery_type == EDeliveryType::PICK_UP): ?>
                                    <?= lang('Order.pick_up') ?><br>
                                <?php endif; ?>
                            </address>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            <b><?= lang('Order.code') ?>: <?= $order->code ?? '' ?></b><br>
                            <br>
                            <b><?= lang('Order.order') ?>:</b> #<?= $order->order_id ?? '' ?><br>
                            <b><?= lang('Order.customer_code') ?> :</b> <?= $order->cus_code ?? '' ?>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <!-- Table row -->
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th><?= lang('Order.quantity') ?></th>
                                        <th><?= lang('Order.product_name') ?></th>
                                        <th><?= lang('Order.product_sku') ?></th>
                                        <th><?= lang('Order.sub_total') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($order_items as $item): ?>
                                        <tr>
                                            <td><?= $item->quantity ?? '' ?></td>
                                            <td><?= $item->pd_name ?? '' ?></td>
                                            <td><?= $item->pd_sku ?? '' ?></td>
                                            <td><?= number_format($item->order_item_sub_total) ?? '' ?>đ</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <div class="row">
                        <!-- accepted payments column -->
                        <div class="col-6">
                            <p class="lead"><?= lang('Order.payment_method') ?>: <b><?= lang("Order.payment_method_{$order->payment_method}") ?></b></p>
                            <p class="lead"><?= lang('Order.note') ?>:</p>
                            <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                                <?= $order->note ?? '' ?>
                            </p>
                        </div>
                        <!-- /.col -->
                        <div class="col-6">
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <th style="width:50%"><?= lang('Order.sub_total') ?>:</th>
                                        <td><?= number_format($order->sub_total) ?? '' ?>đ</td>
                                    </tr>
                                    <?php if ($order->currency != config('Shop')->defaultCurrency): ?>
                                        <tr>
                                            <th><?= lang('Order.exchange_rate') ?>:</th>
                                            <td><?= number_format($order->exchange_rate, 2) ?? '' ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if ($order->discount_amount > 0): ?>
                                        <tr>
                                            <th><?= lang('Order.discount_amount') ?>:</th>
                                            <td><?= number_format($order->discount_amount) ?? '' ?>đ</td>
                                        </tr>
                                    <?php endif; ?>
                                    <!-- <tr>
                                        <th><?= lang('Order.shipping_fee') ?>:</th>
                                        <td><?= number_format($order->shipping_amount) ?? '' ?>đ</td>
                                    </tr> -->
                                    <tr>
                                        <th><?= lang('Order.total') ?>:</th>
                                        <td><?= number_format($order->total_amount_vnd) ?? '' ?>đ</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                    <!-- this row will not appear when printing -->
                    <div class="row no-print">
                        <div class="col-12">
                            <a href="<?= route_to('invoice_print', $order->order_id) ?>" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
                            <!-- <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submit
                            Payment
                        </button> -->
                            <!-- <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                            <i class="fas fa-download"></i> Generate PDF
                        </button> -->
                        </div>
                    </div>
                </div>
                <!-- /.invoice -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
<?= $this->endSection() ?>