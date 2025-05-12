<section class="flat-spacing-1 pt_0">
    <div class="container">
        <div class="flat-title">
            <span class="title"><?=$sectionTitle?></span>
        </div>
        <div class="hover-sw-nav hover-sw-2">
            <div dir="ltr" class="swiper tf-sw-product-sell wrap-sw-over" data-preview="4" data-tablet="3" data-mobile="2" data-space-lg="30" data-space-md="15" data-pagination="2" data-pagination-md="3" data-pagination-lg="3">
                <div class="swiper-wrapper">
                    <?php foreach ($productData as $product) : ?>
                    <div class="swiper-slide" lazy="true">
                        <div class="card-product">
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
                                <div class="list-product-btn">
                                    <a href="#quick_add" data-bs-toggle="modal" class="box-icon bg_white quick-add tf-btn-loading">
                                        <span class="icon icon-bag"></span>
                                        <span class="tooltip">Quick Add</span>
                                    </a>
                                    
                                    <a href="#quick_view" data-bs-toggle="modal" data-product-id="<?=$product->id?>" class="box-icon bg_white quickview tf-btn-loading">
                                        <span class="icon icon-view"></span>
                                        <span class="tooltip">Quick View</span>
                                    </a>
                                </div>
                                
                            </div>
                            <div class="card-product-info">
                                <a href="<?= base_url(route_to('product_detail', $product->pd_slug, $product->id)) ?>" class="title link"><?=$product->pd_name?></a>
                                <span class="price"><?=$product->display_price?></span>
                                
                            </div>
                        </div>
                    </div>
                    <?php endforeach;?>
                    
                </div>
            </div>
            <?php if ( count($productData) > 4 ) :?>
            <div class="nav-sw nav-next-slider nav-next-product box-icon w_46 round"><span class="icon icon-arrow-left"></span></div>
            <div class="nav-sw nav-prev-slider nav-prev-product box-icon w_46 round"><span class="icon icon-arrow-right"></span></div>
            <div class="sw-dots style-2 sw-pagination-product justify-content-center"></div>
            <?php endif;?>
        </div>
    </div>
</section>