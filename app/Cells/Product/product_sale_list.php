<?php if ( isset($products) && count($products) ) : ?>
<div class="widget-facet">
    <div class="facet-title" data-bs-target="#sale-products" data-bs-toggle="collapse" aria-expanded="true" aria-controls="sale-products">
        <span><?=lang('Product.sale_off_products')?></span>
        <span class="icon icon-arrow-up"></span>
    </div>
    <div id="sale-products" class="collapse show">
        <div class="widget-featured-products mb_36">
            <?php 
            foreach ($products as $product) : 
                $featureImg = (isset($product->feature_image['thumbnail']) && $product->feature_image['thumbnail'] !== null) ? $product->feature_image['thumbnail'] : base_url($configs->no_img);
            ?>
            <div class="featured-product-item">
                <a href="product-detail.html" class="card-product-wrapper">
                    <img class="lazyload img-product" data-src="<?=$featureImg?>" alt="<?=$product->pd_name?>">
                </a>
                <div class="card-product-info">
                    <a href="<?= base_url(route_to('product_detail', $product->pd_slug, $product->id)) ?>" class="title link"><?=$product->pd_name?></a>
                    <span class="price"><?=format_currency($product->price_discount, $currentLang->locale )?></span>
                </div>
            </div>
            <?php endforeach;?>

        </div>
    </div>
</div>
<?php endif;?>