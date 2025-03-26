<?php
echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>
<!-- order complete -->
<div class="cart_area section_padding_b">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <div class="order_complete">
                            <div class="complete_icon">
                                <img loading="lazy"  src="<?=base_url($configs->templatePath)?>/assets/images/complete.png" alt="success">
                            </div>
                            <div class="order_complete_content">
                                <h4><?= lang('OrderShop.order') ?> #<?= $order->order_id ?? '' ?> <?= lang('OrderShop.order_completed') ?></h4>
                                <p><?= lang('OrderShop.order_completed_message') ?></p>
                                <div class="order_complete_btn">
                                    <a href="<?= base_url(route_to('product_shop')) ?>" class="default_btn rounded"><?= lang('OrderShop.continue_shopping') ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    $(document).ready(function () {
        localStorage.removeItem('carts');
    })
</script>
<?= $this->endSection() ?>