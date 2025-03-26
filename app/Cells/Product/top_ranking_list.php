<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 11/7/2023
 */

?>
<h4 class="single_topr_title"><?= $category->title ?? ''?></h4>
<?php foreach ($products as $index => $item): ?>
<div class="single_top_ranking">
    <div class="topr_img">
        <a href="<?= base_url(route_to('product_detail', $item->pd_slug, $item->id)) ?>">
            <?php
            $img = (isset($item->feature_image['thumbnail']) && $item->feature_image['thumbnail'] !== null) ? $item->feature_image['thumbnail'] : base_url($configs->noimg);
            ?>
            <img loading="lazy"  src="<?= $img ?>" alt="<?= $item->pd_name ?? '' ?>">
        </a>
        <span class="tag"><?= $index + 1 ?></span>
    </div>
    <div class="topr_content">
        <a href="<?= base_url(route_to('product_detail', $item->pd_slug, $item->id)) ?>">
            <h4><?= $item->pd_name ?? '' ?></h4>
        </a>
        <div class="ratprice">
            <div class="price">
                <?php if(($item->price_discount > 0 && $item->price_discount < $item->price)): ?>
                    <span class="org_price"><?= number_format($item->price_discount) ?? '' ?>đ</span>
                <?php else: ?>
                    <span class="org_price"><?= number_format($item->price) ?? '' ?>đ</span>
                <?php endif; ?>
                <?php if($item->price_discount > 0): ?>
                    <span class="prev_price"><?= number_format($item->price) ?? '' ?>đ</span>
                <?php endif; ?>
            </div>
        </div>
<!--        <div class="rating">-->
<!--            <div class="d-flex align-items-center justify-content-start">-->
<!--                <div class="rating_star">-->
<!--                    <span><i class="las la-star"></i></span>-->
<!--                    <span><i class="las la-star"></i></span>-->
<!--                    <span><i class="las la-star"></i></span>-->
<!--                    <span><i class="las la-star"></i></span>-->
<!--                    <span><i class="las la-star"></i></span>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
    </div>
</div>
<?php endforeach; ?>
