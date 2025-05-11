<div class="tf-grid-layout wrapper-shop tf-col-3" id="gridLayout">
    <?php 
    if ( isset($productData) && !empty($productData) ) :
        $i = 0;
        foreach ($productData as $product) :
            $i++;
    ?>
    <!-- card product <?=$i?> -->
    <div class="card-product grid" data-availability="<?=$product->pd_status?>" >
        <div class="card-product-wrapper">
            <?php
            $featureImg = (isset($product->feature_image['thumbnail']) && $product->feature_image['thumbnail'] !== null) ? $product->feature_image['thumbnail'] : base_url($configs->no_img);
            if ( isset($product->images) &&!empty($product->images) ) {
                $hoverImg = !empty($product->images->data[0]->product_thumb) ? base_url($product->images->data[0]->product_thumb) : null;
            } else {
                $hoverImg = null;
            }
            ?>
            <a href="<?= base_url(route_to('product_detail', $product->pd_slug, $product->id)) ?>" class="product-img">
                <img class="lazyload img-product" data-src="<?=$featureImg?>" src="<?=$featureImg?>" alt="<?=$product->pd_name?>">
                <?php if ( isset($hoverImg) && !empty($hoverImg) ) :?>
                <img class="lazyload img-hover" data-src="<?=$hoverImg?>" src="<?=$hoverImg?>" alt="<?=$product->pd_name?>">
                <?php endif ?>
            </a>
            <div class="list-product-btn absolute-2">
                <a href="#quick_add" data-bs-toggle="modal" data-product-id="<?=$product->id?>" class="box-icon bg_white quick-add tf-btn-loading">
                    <span class="icon icon-bag"></span>
                    <span class="tooltip"><?=lang('Product.quick_add_to_cart')?></span>
                </a>
                <!-- <a href="javascript:void(0);" class="box-icon bg_white wishlist btn-icon-action">
                    <span class="icon icon-heart"></span>
                    <span class="tooltip">Add to Wishlist</span>
                    <span class="icon icon-delete"></span>
                </a>
                <a href="#compare" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft" class="box-icon bg_white compare btn-icon-action">
                    <span class="icon icon-compare"></span>
                    <span class="tooltip">Add to Compare</span>
                    <span class="icon icon-check"></span>
                </a> -->
                <a href="#quick_view" data-bs-toggle="modal" data-product-id="<?=$product->id?>" class="box-icon bg_white quickview tf-btn-loading">
                    <span class="icon icon-view"></span>
                    <span class="tooltip"><?=lang('Product.quick_view')?></span>
                </a>
            </div>
        </div>
        <div class="card-product-info">
            <a href="<?= base_url(route_to('product_detail', $product->pd_slug, $product->id)) ?>" class="title link"><?=$product->pd_name?></a>
            <?php
            $price = ($product->price_discount > 0 && $product->price_discount < $product->price) ? $product->price_discount : $product->price;
            ?>
            <span class="price current-price"><?=format_currency($price, $currentLang->locale )?></span>
            <span class="publish-date d-none"><?=$product->publish_date?></span>
        </div>
    </div>
    <?php endforeach;?>
    
    <!-- pagination -->
    <?php echo $pager->links('default', 'product_grid') ?>
    <!-- /pagination -->
    <?php endif;?>
</div>