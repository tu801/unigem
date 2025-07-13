<?php 
if ( count($customer->shippingAddress) > 0 ): 
    $i = 0;
    foreach ($$customer->shippingAddress as $address):
        $i++;
?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-map-marked-alt"></i>
            <?=lang('Customer.shipping_address') . ' #' . $i?>
        </h3>
        <div class="card-tools">
            <span title="3 New Messages" class="badge badge-primary"><?=$i?></span>
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <blockquote>
            <h2 class="lead"><?=lang('Customer.ship_full_name')?> <b></b></h2>
            
            <ul class="ml-4 mb-0 fa-ul text-muted">
                <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> <?=lang('Customer.shipping_address')?>: <?=$address->full_address ?></li>
                <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> <?=lang('Customer.ship_phone')?> #: <?=$address->ship_telephone ?></li>
                <li class="small"><span class="fa-li"><i class="fas fa-lg fa-envelope"></i></i></span><?=lang('Customer.ship_email')?>: <?=$address->ship_email ?></li>
            </ul>
        </blockquote>
    </div>
    <!-- /.card-body -->
</div>
<?php 
    endforeach; 
else:
?>
<div class="alert alert-info">
    <?= lang('Customer.no_shipping_address') ?>
</div>
<?php endif; ?>