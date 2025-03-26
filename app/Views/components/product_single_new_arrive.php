<?php
/**
 * Author: tmtuan
 * Created date: 11/10/2023
 * Project: Unigem
 **/

$img = (isset($product->feature_image['thumbnail']) && $product->feature_image['thumbnail'] !== null)
    ? $product->feature_image['thumbnail']
    : base_url($configs->noimg);
?>
<div class="single_new_arrive">
    <div class="sna_img">
        <a href="<?= base_url(route_to('product_detail', $product->pd_slug, $product->id)) ?>">
            <img loading="lazy" class="prd_img" src="<?=$img?>" alt="<?=$product->pd_name?>">
        </a>
    </div>
    <div class="sna_content">
        <a href="<?= base_url(route_to('product_detail', $product->pd_slug, $product->id)) ?>">
            <h4><?=$product->pd_name?></h4>
        </a>
        <div class="ratprice">
            <div class="price">
                <?php if(($product->price_discount > 0 && $product->price_discount < $product->price)): ?>
                    <span class="org_price"><?= vnd_encode($product->price_discount, true) ?? '' ?></span>
                <?php else: ?>
                    <span class="org_price"><?= vnd_encode($product->price, true) ?? '' ?></span>
                <?php endif; ?>
                <?php if($product->price_discount > 0): ?>
                    <span class="prev_price"><?= vnd_encode($product->price, true) ?? '' ?></span>
                <?php endif; ?>
            </div>

        </div>
        <div class="product_adcart">
            <button data-product-id="<?= $product->id ?>"  @click="addCart(<?= $product->id ?>)" class="default_btn add_product_to_card"><?= lang('Product.add_cart') ?></button>
        </div>
    </div>
</div>
