<div class="prof_recent_order shadow_sm mt-0">
    <h4><?=lang('CustomerProfile.recent_order_title')?></h4>
    <?php if ( count($orders) ) :
    foreach ($orders as $item) : ?>
    <div class="single_prof_recorder">
<!--        <div class="prorder_img">-->
<!--            <img loading="lazy"  src="assets/images/tv.png" alt="product">-->
<!--            <img loading="lazy"  src="assets/images/tv.png" alt="product">-->
<!--        </div>-->
        <div class="prorder_btn">
            <a href="<?=base_url(route_to('order_history_detail', $item->order_id))?>">
                <?=lang('OrderShop.view_order_history')?>
            </a>
        </div>
        <div class="prorder_txt prorder_odnumber">
            <h5><?=lang('OrderShop.order_code')?></h5>
            <p><?=$item->code?></p>
        </div>
        <div class="prorder_txt prorder_purchased">
            <h5><?=lang('OrderShop.order_purchased_date')?></h5>
            <p><?=$item->created_at->format('d-m-Y')?></p>
        </div>
        <div class="prorder_txt prorder_qnty d-none d-sm-block">
            <h5><?=lang('OrderShop.order_item_quantity')?></h5>
            <p><?=$item->count_product?></p>
        </div>
        <div class="prorder_txt prorder_total">
            <h5><?=lang('OrderShop.order_total')?></h5>
            <p><?=vnd_encode($item->total, true)?></p>
        </div>
        <div class="prorder_txt prorder_status">
            <h5 class="d-none d-md-block"><?=lang('OrderShop.order_status')?></h5>
            <h5 class="d-block d-md-none"><span class="me-2 d-inline-block d-sm-none font-normal text_xs">x3</span> $120</h5>
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
            echo '<p class="'.$textColor.'">'.$item->getOrderStatusText().'</p>'
            ?>
        </div>
    </div>
    <?php endforeach; else: ?>
        <p><?=lang('CustomerProfile.no_recent_order_text')?></p>
    <?php endif; ?>
</div>