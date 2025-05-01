<?php
echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>
<!-- page-title -->
<div class="tf-page-title">
    <div class="container-full">
        <div class="row">
            <div class="col-12">
                <div class="heading text-center"><?=$page_title?></div>
                <?php echo view_cell('App\Libraries\BreadCrumb\BreadCrumbCell') ?>
            </div>
        </div>
    </div>
</div>
<!-- /page-title -->

<!-- shop grid view -->
<section class="flat-spacing-1">
    <div class="container">
        <?=$this->include($configs->view. '\components\shop\shop-control-filter') ?>
        
        <div class="tf-row-flex">
            <?=$this->include($configs->view. '\components\shop\shop-sidebar', ['product_category' => $product_category]) ?>
            
            <div class="wrapper-control-shop tf-shop-content">
                <div class="meta-filter-shop">
                    <div id="product-count-grid" class="count-text"></div>
                    <div id="product-count-list" class="count-text"></div>
                    <div id="applied-filters"></div>
                    <button id="remove-all" class="remove-all-filters" style="display: none;">Remove All <i class="icon icon-close"></i></button>
                </div>
                
                <?php 
                    if ( !empty($data) && count($data) > 0):
                        $params = [
                            'configs' => $configs,
                            'currentLang' => $currentLang,
                            'productData' => $data,
                            'pager' => $pager,
                        ];
                        echo view($configs->view. '\components\shop\product-list-layout', $params);
                        echo view($configs->view. '\components\shop\product-grid-layout', $params);
                    else:
                ?>
                    <div class="text text-danger"><?=lang('Product.product_not_found')?></div>
                <?php endif;?>
                
            </div>
        </div>
        
    </div>
</section>
<div class="btn-sidebar-style2">
    <button data-bs-toggle="offcanvas" data-bs-target="#sidebarmobile" aria-controls="offcanvas"><i class="icon icon-sidebar-2"></i></button>
</div>

<!-- Filter -->
<div class="offcanvas offcanvas-start canvas-filter" id="filterShop">
    <div class="canvas-wrapper">
        <header class="canvas-header">
            <div class="filter-icon">
                <span class="icon icon-filter"></span>
                <span>Filter</span>
            </div>
            <span class="icon-close icon-close-popup" data-bs-dismiss="offcanvas" aria-label="Close"></span>
        </header>
        <div class="canvas-body">
            <div class="widget-facet wd-categories">
                <div class="facet-title" data-bs-target="#categories" data-bs-toggle="collapse" aria-expanded="true" aria-controls="categories">
                    <span>Product categories</span>
                    <span class="icon icon-arrow-up"></span>
                </div>
                <div id="categories" class="collapse show">
                    <ul class="list-categoris current-scrollbar mb_36">
                        <li class="cate-item current"><a href="shop-default.html"><span>Fashion</span></a></li>
                        <li class="cate-item"><a href="shop-default.html"><span>Men</span></a></li>
                        <li class="cate-item"><a href="shop-default.html"><span>Women</span></a></li>
                        <li class="cate-item"><a href="shop-default.html"><span>Denim</span></a></li>
                        <li class="cate-item"><a href="shop-default.html"><span>Dress</span></a></li>
                    </ul>
                </div>
            </div>
            <form action="#" id="facet-filter-form" class="facet-filter-form">
                <div class="widget-facet">
                    <div class="facet-title" data-bs-target="#availability" data-bs-toggle="collapse" aria-expanded="true" aria-controls="availability">
                        <span>Availability</span>
                        <span class="icon icon-arrow-up"></span>
                    </div>
                    <div id="availability" class="collapse show">
                        <ul class="tf-filter-group current-scrollbar mb_36">
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="availability" class="tf-check" id="inStock">
                                <label for="inStock" class="label"><span>In stock</span>&nbsp;<span>(14)</span></label>
                            </li>
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="availability" class="tf-check" id="outStock">
                                <label for="outStock" class="label"><span>Out of stock</span>&nbsp;<span>(2)</span></label>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="widget-facet">
                    <div class="facet-title" data-bs-target="#price" data-bs-toggle="collapse" aria-expanded="true" aria-controls="price">
                        <span>Price</span>
                        <span class="icon icon-arrow-up"></span>
                    </div>
                    <div id="price" class="collapse show">
                        <div class="widget-price filter-price">
                            <div class="price-val-range" id="price-value-range" data-min="0" data-max="500"></div>
                            <div class="box-title-price">
                                <span class="title-price">Price :</span>
                                <div class="caption-price">
                                    <div class="price-val" id="price-min-value" data-currency="$"></div>
                                    <span>-</span>
                                    <div class="price-val" id="price-max-value" data-currency="$"></div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="widget-facet">
                    <div class="facet-title" data-bs-target="#brand" data-bs-toggle="collapse" aria-expanded="true" aria-controls="brand">
                        <span>Brand</span>
                        <span class="icon icon-arrow-up"></span>
                    </div>
                    <div id="brand" class="collapse show">
                        <ul class="tf-filter-group current-scrollbar mb_36">
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="brand" class="tf-check" id="Ecomus">
                                <label for="Ecomus" class="label"><span>Ecomus</span>&nbsp;<span>(8)</span></label>
                            </li>
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="brand" class="tf-check" id="M&H">
                                <label for="M&H" class="label"><span>M&H</span>&nbsp;<span>(8)</span></label>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="widget-facet">
                    <div class="facet-title" data-bs-target="#color" data-bs-toggle="collapse" aria-expanded="true" aria-controls="color">
                        <span>Color</span>
                        <span class="icon icon-arrow-up"></span>
                    </div>
                    <div id="color" class="collapse show">
                        <ul class="tf-filter-group filter-color current-scrollbar mb_36">
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="color" class="tf-check-color bg_beige" id="Beige" value="Beige">
                                <label for="Beige" class="label"><span>Beige</span>&nbsp;<span>(3)</span></label>
                            </li>
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="color" class="tf-check-color bg_dark" id="Black" value="Black">
                                <label for="Black" class="label"><span>Black</span>&nbsp;<span>(18)</span></label>
                            </li>
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="color" class="tf-check-color bg_blue-2" id="Blue" value="Blue">
                                <label for="Blue" class="label"><span>Blue</span>&nbsp;<span>(3)</span></label>
                            </li>
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="color" class="tf-check-color bg_brown" id="Brown" value="Brown">
                                <label for="Brown" class="label"><span>Brown</span>&nbsp;<span>(3)</span></label>
                            </li>
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="color" class="tf-check-color bg_cream" id="Cream" value="Cream">
                                <label for="Cream" class="label"><span>Cream</span>&nbsp;<span>(1)</span></label>
                            </li>
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="color" class="tf-check-color bg_dark-beige" id="Dark Beige" value="Dark Beige">
                                <label for="Dark Beige" class="label"><span>Dark Beige</span>&nbsp;<span>(1)</span></label>
                            </li>
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="color" class="tf-check-color bg_dark-blue" id="Dark Blue" value="Dark Blue">
                                <label for="Dark Blue" class="label"><span>Dark Blue</span>&nbsp;<span>(3)</span></label>
                            </li>
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="color" class="tf-check-color bg_dark-green" id="Dark Green" value="Dark Green">
                                <label for="Dark Green" class="label"><span>Dark Green</span>&nbsp;<span>(1)</span></label>
                            </li>
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="color" class="tf-check-color bg_dark-grey" id="Dark Grey" value="Dark Grey">
                                <label for="Dark Grey" class="label"><span>Dark Grey</span>&nbsp;<span>(1)</span></label>
                            </li>
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="color" class="tf-check-color bg_grey" id="Grey" value="Grey">
                                <label for="Grey" class="label"><span>Grey</span>&nbsp;<span>(2)</span></label>
                            </li>
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="color" class="tf-check-color bg_light-blue" id="Light Blue" value="Light Blue">
                                <label for="Light Blue" class="label"><span>Light Blue</span>&nbsp;<span>(5)</span></label>
                            </li>
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="color" class="tf-check-color bg_light-green" id="Light Green" value="Light Green">
                                <label for="Light Green" class="label"><span>Light Green</span>&nbsp;<span>(3)</span></label>
                            </li>
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="color" class="tf-check-color bg_light-grey" id="Light Grey" value="Light Grey">
                                <label for="Light Grey" class="label"><span>Light Grey</span>&nbsp;<span>(1)</span></label>
                            </li>
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="color" class="tf-check-color bg_light-pink" id="Light Pink" value="Light Pink">
                                <label for="Light Pink" class="label"><span>Light Pink</span>&nbsp;<span>(2)</span></label>
                            </li>
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="color" class="tf-check-color bg_purple" id="Light Purple" value="Light Purple">
                                <label for="Light Purple" class="label"><span>Light Purple</span>&nbsp;<span>(2)</span></label>
                            </li>
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="color" class="tf-check-color bg_light-yellow" id="Light Yellow" value="Light Yellow">
                                <label for="Light Yellow" class="label"><span>Light Yellow</span>&nbsp;<span>(1)</span></label>
                            </li>
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="color" class="tf-check-color bg_orange" id="Orange" value="Orange">
                                <label for="Orange" class="label"><span>Orange</span>&nbsp;<span>(1)</span></label>
                            </li>
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="color" class="tf-check-color bg_pink" id="Pink" value="Pink">
                                <label for="Pink" class="label"><span>Pink</span>&nbsp;<span>(2)</span></label>
                            </li>
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="color" class="tf-check-color bg_taupe" id="Taupe" value="Taupe">
                                <label for="Taupe" class="label"><span>Taupe</span>&nbsp;<span>(1)</span></label>
                            </li>
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="color" class="tf-check-color bg_white" id="White" value="White">
                                <label for="White" class="label"><span>White</span>&nbsp;<span>(14)</span></label>
                            </li>
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="color" class="tf-check-color bg_yellow" id="Yellow" value="Yellow">
                                <label for="Yellow" class="label"><span>Yellow</span>&nbsp;<span>(1)</span></label>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="widget-facet">
                    <div class="facet-title" data-bs-target="#size" data-bs-toggle="collapse" aria-expanded="true" aria-controls="size">
                        <span>Size</span>
                        <span class="icon icon-arrow-up"></span>
                    </div>
                    <div id="size" class="collapse show">
                        <ul class="tf-filter-group current-scrollbar">
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="size" class="tf-check tf-check-size" value="S" id="S">
                                <label for="S" class="label"><span>S</span>&nbsp;<span>(7)</span></label>
                            </li>
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="size" class="tf-check tf-check-size" value="M" id="M">
                                <label for="M" class="label"><span>M</span>&nbsp;<span>(8)</span></label>
                            </li>
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="size" class="tf-check tf-check-size" value="L" id="L">
                                <label for="L" class="label"><span>L</span>&nbsp;<span>(8)</span></label>
                            </li>
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="size" class="tf-check tf-check-size" value="XL" id="XL">
                                <label for="XL" class="label"><span>XL</span>&nbsp;<span>(6)</span></label>
                            </li>
                        </ul>
                    </div>
                </div>
            </form>    
        </div>
    </div>       
</div>
<!-- End Filter -->

<!-- modal quick_add -->
<div class="modal fade modalDemo" id="quick_add">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="header">
                <span class="icon-close icon-close-popup" data-bs-dismiss="modal"></span>
            </div>
            <div class="wrap">
                <div class="tf-product-info-item">
                    <div class="image">
                        <img src="images/products/orange-1.jpg" alt="">
                    </div>
                    <div class="content">
                        <a href="product-detail.html">Ribbed Tank Top</a>
                        <div class="tf-product-info-price">
                            <!-- <div class="price-on-sale">$8.00</div>
                            <div class="compare-at-price">$10.00</div>
                            <div class="badges-on-sale"><span>20</span>% OFF</div> -->
                            <div class="price">$18.00</div>
                        </div>
                    </div>
                </div>
                <div class="tf-product-info-variant-picker mb_15">
                    <div class="variant-picker-item">
                        <div class="variant-picker-label">
                            Color: <span class="fw-6 variant-picker-label-value">Orange</span>
                        </div>
                        <div class="variant-picker-values">
                            <input id="values-orange" type="radio" name="color" checked>
                            <label class="hover-tooltip radius-60" for="values-orange" data-value="Orange">
                                <span class="btn-checkbox bg-color-orange"></span>
                                <span class="tooltip">Orange</span>
                            </label>
                            <input id="values-black" type="radio" name="color">
                            <label class=" hover-tooltip radius-60" for="values-black" data-value="Black">
                                <span class="btn-checkbox bg-color-black"></span>
                                <span class="tooltip">Black</span>
                            </label>
                            <input id="values-white" type="radio" name="color">
                            <label class="hover-tooltip radius-60" for="values-white" data-value="White">
                                <span class="btn-checkbox bg-color-white"></span>
                                <span class="tooltip">White</span>
                            </label>
                        </div>
                    </div>
                    <div class="variant-picker-item">
                        <div class="variant-picker-label">
                            Size: <span class="fw-6 variant-picker-label-value">S</span>
                        </div>
                        <div class="variant-picker-values">
                            <input type="radio" name="size" id="values-s" checked>
                            <label class="style-text" for="values-s" data-value="S">
                                <p>S</p>
                            </label>
                            <input type="radio" name="size" id="values-m">
                            <label class="style-text" for="values-m" data-value="M">
                                <p>M</p>
                            </label>
                            <input type="radio" name="size" id="values-l">
                            <label class="style-text" for="values-l" data-value="L">
                                <p>L</p>
                            </label>
                            <input type="radio" name="size" id="values-xl">
                            <label class="style-text" for="values-xl" data-value="XL">
                                <p>XL</p>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="tf-product-info-quantity mb_15">
                    <div class="quantity-title fw-6">Quantity</div>
                    <div class="wg-quantity">
                        <span class="btn-quantity minus-btn">-</span>
                        <input type="text" name="number" value="1">
                        <span class="btn-quantity plus-btn">+</span>
                    </div>
                </div>
                <div class="tf-product-info-buy-button">
                    <form class="">
                        <a href="javascript:void(0);" class="tf-btn btn-fill justify-content-center fw-6 fs-16 flex-grow-1 animate-hover-btn btn-add-to-cart"><span>Add to cart -&nbsp;</span><span class="tf-qty-price">$18.00</span></a>
                        <div class="tf-product-btn-wishlist btn-icon-action">
                            <i class="icon-heart"></i>
                            <i class="icon-delete"></i>
                        </div>
                        <a href="#compare" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft" class="tf-product-btn-wishlist box-icon bg_white compare btn-icon-action">
                            <span class="icon icon-compare"></span>
                            <span class="icon icon-check"></span>
                        </a>
                        <div class="w-100">
                            <a href="#" class="btns-full">Buy with <img src="images/payments/paypal.png" alt=""></a>
                            <a href="#" class="payment-more-option">More payment options</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /modal quick_add -->

<!-- modal quick_view -->
<div class="modal fade modalDemo" id="quick_view">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="header">
                <span class="icon-close icon-close-popup" data-bs-dismiss="modal"></span>
            </div>
            <div class="wrap">
                <div class="tf-product-media-wrap">
                    <div dir="ltr" class="swiper tf-single-slide">
                        <div class="swiper-wrapper" >
                            <div class="swiper-slide">
                                <div class="item">
                                    <img src="images/products/orange-1.jpg" alt="">
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="item">
                                    <img src="images/products/pink-1.jpg" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="swiper-button-next button-style-arrow single-slide-prev"></div>
                        <div class="swiper-button-prev button-style-arrow single-slide-next"></div>
                    </div>
                </div>
                <div class="tf-product-info-wrap position-relative">
                    <div class="tf-product-info-list">
                        <div class="tf-product-info-title">
                            <h5><a class="link" href="product-detail.html">Ribbed Tank Top</a></h5>
                        </div>
                        <div class="tf-product-info-badges">
                            <div class="badges text-uppercase">Best seller</div>
                            <div class="product-status-content">
                                <i class="icon-lightning"></i>
                                <p class="fw-6">Selling fast! 48 people have this in their carts.</p>
                            </div>
                        </div>
                        <div class="tf-product-info-price">
                            <div class="price">$18.00</div>
                        </div>
                        <div class="tf-product-description">
                            <p>Nunc arcu faucibus a et lorem eu a mauris adipiscing conubia ac aptent ligula facilisis a auctor habitant parturient a a.Interdum fermentum.</p>
                        </div>
                        <div class="tf-product-info-variant-picker">
                            <div class="variant-picker-item">
                                <div class="variant-picker-label">
                                    Color: <span class="fw-6 variant-picker-label-value">Orange</span>
                                </div>
                                <div class="variant-picker-values">
                                    <input id="values-orange-1" type="radio" name="color-1" checked>
                                    <label class="hover-tooltip radius-60" for="values-orange-1" data-value="Orange">
                                        <span class="btn-checkbox bg-color-orange"></span>
                                        <span class="tooltip">Orange</span>
                                    </label>
                                    <input id="values-black-1" type="radio" name="color-1">
                                    <label class=" hover-tooltip radius-60" for="values-black-1" data-value="Black">
                                        <span class="btn-checkbox bg-color-black"></span>
                                        <span class="tooltip">Black</span>
                                    </label>
                                    <input id="values-white-1" type="radio" name="color-1">
                                    <label class="hover-tooltip radius-60" for="values-white-1" data-value="White">
                                        <span class="btn-checkbox bg-color-white"></span>
                                        <span class="tooltip">White</span>
                                    </label>
                                </div>
                            </div>
                            <div class="variant-picker-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="variant-picker-label">
                                        Size: <span class="fw-6 variant-picker-label-value">S</span>
                                    </div>
                                    <div class="find-size btn-choose-size fw-6">Find your size</div>
                                </div>
                                <div class="variant-picker-values">
                                    <input type="radio" name="size-1" id="values-s-1" checked>
                                    <label class="style-text" for="values-s-1" data-value="S">
                                        <p>S</p>
                                    </label>
                                    <input type="radio" name="size-1" id="values-m-1">
                                    <label class="style-text" for="values-m-1" data-value="M">
                                        <p>M</p>
                                    </label>
                                    <input type="radio" name="size-1" id="values-l-1">
                                    <label class="style-text" for="values-l-1" data-value="L">
                                        <p>L</p>
                                    </label>
                                    <input type="radio" name="size-1" id="values-xl-1">
                                    <label class="style-text" for="values-xl-1" data-value="XL">
                                        <p>XL</p>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="tf-product-info-quantity">
                            <div class="quantity-title fw-6">Quantity</div>
                            <div class="wg-quantity">
                                <span class="btn-quantity minus-btn">-</span>
                                <input type="text" name="number" value="1">
                                <span class="btn-quantity plus-btn">+</span>
                            </div>
                        </div>
                        <div class="tf-product-info-buy-button">
                            <form class="">
                                <a href="javascript:void(0);" class="tf-btn btn-fill justify-content-center fw-6 fs-16 flex-grow-1 animate-hover-btn btn-add-to-cart"><span>Add to cart -&nbsp;</span><span class="tf-qty-price">$8.00</span></a>
                                <a href="javascript:void(0);" class="tf-product-btn-wishlist hover-tooltip box-icon bg_white wishlist btn-icon-action">
                                    <span class="icon icon-heart"></span>
                                    <span class="tooltip">Add to Wishlist</span>
                                    <span class="icon icon-delete"></span>
                                </a>
                                <a href="#compare" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft" class="tf-product-btn-wishlist hover-tooltip box-icon bg_white compare btn-icon-action">
                                    <span class="icon icon-compare"></span>
                                    <span class="tooltip">Add to Compare</span>
                                    <span class="icon icon-check"></span>
                                </a>
                                <div class="w-100">
                                    <a href="#" class="btns-full">Buy with <img src="images/payments/paypal.png" alt=""></a>
                                    <a href="#" class="payment-more-option">More payment options</a>
                                </div>
                            </form>
                        </div>
                        <div>
                            <a href="product-detail.html" class="tf-btn fw-6 btn-line">View full details<i class="icon icon-arrow1-top-left"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /modal quick_view -->

<!-- modal find_size -->
<div class="modal fade modalDemo tf-product-modal" id="find_size">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="header">
                <div class="demo-title">Size chart</div>
                <span class="icon-close icon-close-popup" data-bs-dismiss="modal"></span>
            </div>
            <div class="tf-rte">
                <div class="tf-table-res-df">
                    <h6>Size guide</h6>
                    <table class="tf-sizeguide-table">
                        <thead>
                            <tr>
                                <th>Size</th>
                                <th>US</th>
                                <th>Bust</th>
                                <th>Waist</th>
                                <th>Low Hip</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>XS</td>
                                <td>2</td>
                                <td>32</td>
                                <td>24 - 25</td>
                                <td>33 - 34</td>
                            </tr>
                            <tr>
                                <td>S</td>
                                <td>4</td>
                                <td>34 - 35</td>
                                <td>26 - 27</td>
                                <td>35 - 26</td>
                            </tr>
                            <tr>
                                <td>M</td>
                                <td>6</td>
                                <td>36 - 37</td>
                                <td>28 - 29</td>
                                <td>38 - 40</td>
                            </tr>
                            <tr>
                                <td>L</td>
                                <td>8</td>
                                <td>38 - 29</td>
                                <td>30 - 31</td>
                                <td>42 - 44</td>
                            </tr>
                            <tr>
                                <td>XL</td>
                                <td>10</td>
                                <td>40 - 41</td>
                                <td>32 - 33</td>
                                <td>45 - 47</td>
                            </tr>
                            <tr>
                                <td>XXL</td>
                                <td>12</td>
                                <td>42 - 43</td>
                                <td>34 - 35</td>
                                <td>48 - 50</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="tf-page-size-chart-content">
                    <div>
                        <h6>Measuring Tips</h6>
                        <div class="title">Bust</div>
                        <p>Measure around the fullest part of your bust.</p>
                        <div class="title">Waist</div>
                        <p>Measure around the narrowest part of your torso.</p>
                        <div class="title">Low Hip</div>
                        <p class="mb-0">With your feet together measure around the fullest part of your hips/rear.
                        </p>
                    </div>
                    <div>
                        <img class="sizechart lazyload" data-src="images/shop/products/size_chart2.jpg" src="images/shop/products/size_chart2.jpg" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /modal find_size -->

<!-- Filter sidebar-->
<div class="offcanvas offcanvas-start canvas-filter canvas-sidebar" id="sidebarmobile">
    <div class="canvas-wrapper">
        <header class="canvas-header">
            <span class="title">SIDEBAR PRODUCT</span>
            <span class="icon-close icon-close-popup" data-bs-dismiss="offcanvas" aria-label="Close"></span>
        </header>
        <div class="canvas-body sidebar-mobile-append">
                
        </div>
        
    </div>       
</div>
<!-- End Filter sidebar -->

<?= $this->endSection() ?>

<?=$this->section('scripts')?>
<script type="module" src="<?=base_url($configs->templatePath)?>assets/js/nouislider.min.js"></script>
<script type="module" src="<?=base_url($configs->templatePath)?>assets/js/shop.js"></script>
<?= $this->endSection() ?>