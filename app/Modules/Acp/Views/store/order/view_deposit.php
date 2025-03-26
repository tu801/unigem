<?php

use Modules\Acp\Enums\Store\Order\EDeliveryType;

echo $this->extend($config->viewLayout);
echo $this->section('content');
?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Main content -->
                <div class="invoice p-3 mb-3">
                    <!-- /.row -->
                    <?php if(isset($order_deposit)): ?>
                        <div class="table-responsive pt-5">
                            <table class="table">
                                <tr>
                                    <th style="width:50%"><?= lang('PaymentConfig.name')?>:</th>
                                    <td class="text-danger"><?= $order_deposit['bank']->name ?? '' ?></td>
                                </tr>
                                <tr>
                                    <th><?= lang('PaymentConfig.branch')?>:</th>
                                    <td class="text-danger"><?= $order_deposit['bank']->branch ?? '' ?></td>
                                </tr>
                                <tr>
                                    <th><?= lang('PaymentConfig.account_number')?>:</th>
                                    <td class="text-danger"><?= $order_deposit['bank']->account_number ?? '' ?></td>
                                </tr>
                            </table>
                        </div>
                        <?php if(isset($order_deposit['image'])): ?>
                            <h2 class="text-center"> <?= lang('Order.image_payment') ?></h2>
                            <hr>
                            <div class="d-flex justify-content-center">
                                <img class="img-fluid" src="<?= base_url('uploads/'.$order_deposit['image']) ?>" alt="">
                            </div>
                        <?php else: ?>
                            <h1 class="text-center"><?= lang('Order.no_image_payment') ?> </h1>
                        <?php endif; ?>
                    <?php else: ?>
                        <h1 class="text-center"><?= lang('Order.no_info') ?></h1>
                    <?php endif; ?>

                </div>
                <!-- /.invoice -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
<?= $this->endSection() ?>
