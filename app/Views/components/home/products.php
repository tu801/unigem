<section class="flat-spacing-18 bg_brown-3">
    <div class="container">
        <div class="flat-title wow fadeInUp title-upper" data-wow-delay="0s">
            <span class="title fw-8  text-white"><?=lang('Home.best_sell_product')?></span>
            <div class="d-flex gap-16 align-items-center box-pagi-arr">
                <!-- <div class="nav-sw-arrow nav-next-slider type-white nav-next-product"><span class="icon icon-arrow1-left"></span></div> -->
                <a href="product-style-05.html" class="tf-btn btn-line fs-12 fw-6  btn-line-light"><?=lang('Home.best_sell_view_all_btn')?></a>
                <!-- <div class="nav-sw-arrow nav-prev-slider type-white nav-prev-product"><span class="icon icon-arrow1-right"></span></div> -->
            </div>   
        </div>
        <div class="hover-sw-nav hover-sw-2">
            <div dir="ltr" class="swiper tf-sw-product-sell wrap-sw-over" data-preview="4" data-tablet="3" data-mobile="2" data-space-lg="30" data-space-md="15" data-pagination="2" data-pagination-md="3" data-pagination-lg="3">
                <div class="swiper-wrapper">
                    <?php foreach ($productList as $product) : 
                        $price = ($product->price_discount > 0 && $product->price_discount < $product->price) ? $product->price_discount : $product->price;
                    ?>
                    <div class="swiper-slide" lazy="true">
                        <div class="card-product style-brown">
                            <div class="card-product-wrapper rounded-0">
                                <a href="product-detail.html" class="product-img ">
                                    <img class="lazyload img-product" data-src="<?=$product->feature_image['thumbnail']?>" src="<?=$product->feature_image['thumbnail']?>" alt="<?=$product->pd_name?>">
                                    <img class="lazyload img-hover" data-src="<?=$product->feature_image['thumbnail']?>" src="<?=$product->feature_image['thumbnail']?>" alt="<?=$product->pd_name?>">
                                </a>
                                <div class="list-product-btn absolute-2">
                                    <a href="#shoppingCart" data-bs-toggle="modal" class="box-icon quick-add tf-btn-loading">
                                        <span class="icon icon-bag"></span>
                                        <span class="tooltip">Add to cart</span>
                                    </a>
                                    
                                    <a href="#quick_view" data-bs-toggle="modal" class="box-icon quickview tf-btn-loading">
                                        <span class="icon icon-view"></span>
                                        <span class="tooltip">Quick View</span>
                                    </a>
                                </div>
                            </div>
                            <div class="card-product-info">
                                <a href="product-detail.html" class="title link  text-white"><?=$product->pd_name?></a>
                                <span class="price  text-white"><?=format_currency($price)?></span>
                                
                            </div>
                        </div>
                    </div>
                    <?php endforeach;?>
                </div>
            </div>
            <div class="nav-sw nav-next-slider nav-next-product box-icon w_46 round"><span class="icon icon-arrow-left"></span></div>
            <div class="nav-sw nav-prev-slider nav-prev-product box-icon w_46 round"><span class="icon icon-arrow-right"></span></div>
        </div>
    </div>
</section>