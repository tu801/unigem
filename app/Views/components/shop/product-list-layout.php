<div class="tf-list-layout wrapper-shop" id="listLayout">
    <?php 
    if ( isset($productData) && !empty($productData) ) :
        $i = 0;
        foreach ($productData as $product) :
            $i++;
    ?>
    <!-- card product 1 -->
    <div class="card-product list-layout" data-availability="<?=$product->pd_status?>" >
        <div class="card-product-wrapper">
            <?php
            $featureImg = (isset($product->feature_image['thumbnail']) && $product->feature_image['thumbnail'] !== null) ? $product->feature_image['thumbnail'] : base_url($configs->no_img);
            if ( isset($product->images) &&!empty($product->images) ) {
                $hoverImg = !empty($product->images->data[0]->product_thumb) ? base_url($product->images->data[0]->product_thumb) : null;
            }
            ?>
            <a href="<?= base_url(route_to('product_detail', $product->pd_slug, $product->id)) ?>" class="product-img">
                <img class="lazyload img-product" data-src="<?=$featureImg?>" src="<?=$featureImg?>" alt="<?=$product->pd_name?>">
                <?php if ( isset($hoverImg) && !empty($hoverImg) ) :?>
                <img class="lazyload img-hover" data-src="<?=$hoverImg?>" src="<?=$hoverImg?>" alt="<?=$product->pd_name?>">
                <?php endif ?>
            </a>
        </div>
        <div class="card-product-info">
            <a href="<?= base_url(route_to('product_detail', $product->pd_slug, $product->id)) ?>" class="title link"><?=$product->pd_name?></a>
            <span class="price current-price"><?=$product->display_price?></span>
            <span class="published-date d-none"><?=$product->publish_date?></span>

            <p class="description"><?=htmlentities(word_limiter($product->pd_description ?? '', 30))?></p>
            <!-- <ul class="list-color-product">
                <li class="list-color-item hover-tooltip color-swatch active">
                    <span class="tooltip tooltip-bottom">Orange</span>
                    <span class="swatch-value bg_orange-3"></span>
                    <img class="lazyload" data-src="images/products/orange-1.jpg" src="images/products/orange-1.jpg" alt="image-product">
                </li>
                <li class="list-color-item color-swatch">
                    <span class="tooltip">Black</span>
                    <span class="swatch-value bg_dark"></span>
                    <img class="lazyload" data-src="images/products/black-1.jpg" src="images/products/black-1.jpg" alt="image-product">
                </li>
                <li class="list-color-item color-swatch">
                    <span class="tooltip">White</span>
                    <span class="swatch-value bg_white"></span>
                    <img class="lazyload" data-src="images/products/white-1.jpg" src="images/products/white-1.jpg" alt="image-product">
                </li>
            </ul> -->
            <!-- <div class="size-list">
                <span class="size-item">S</span>
                <span class="size-item">M</span>
                <span class="size-item">L</span>
                <span class="size-item">XL</span>
            </div> -->
            <div class="list-product-btn">
                <a href="#quick_add" data-bs-toggle="modal" class="box-icon quick-add style-3 hover-tooltip">
                    <span class="icon icon-bag"></span><span class="tooltip"><?=lang('Product.quick_add_to_cart')?></span>
                </a>
                <!-- <a href="#" class="box-icon wishlist style-3 hover-tooltip"><span class="icon icon-heart"></span> <span class="tooltip">Add to Wishlist</span></a>
                <a href="#compare" data-bs-toggle="offcanvas" class="box-icon compare style-3 hover-tooltip"><span class="icon icon-compare"></span> <span class="tooltip">Add to Compare</span></a> -->
                <a href="#quick_view" data-bs-toggle="modal" data-product-id="<?=$product->id?>" class="box-icon quickview style-3 hover-tooltip">
                    <span class="icon icon-view"></span><span class="tooltip"><?=lang('Product.quick_view')?></span>
                </a>
            </div>
        </div>
    </div>
    <?php endforeach;?>

    <!-- pagination -->
    <?php echo $pager->links('default', 'product_list') ?>
    <!-- /pagination -->
    <?php endif;?>  
</div>