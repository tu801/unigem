<?php
echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>
<!-- breadcrumbs -->
<div class="tf-breadcrumb">
    <div class="container">
        <div class="tf-breadcrumb-wrap d-flex justify-content-between flex-wrap align-items-center">
            <?php echo view_cell('App\Libraries\BreadCrumb\BreadCrumbCell::product') ?>
            <div class="tf-breadcrumb-prev-next">
                <a href="#" class="tf-breadcrumb-prev hover-tooltip center">
                    <i class="icon icon-arrow-left"></i>
                    <!-- <span class="tooltip">Cotton jersey top</span> -->
                </a>
                <a href="<?=base_url(route_to('product_shop'))?>" class="tf-breadcrumb-back hover-tooltip center">
                    <i class="icon icon-shop"></i>
                    <!-- <span class="tooltip">Back to Women</span> -->
                </a>
                <a href="#" class="tf-breadcrumb-next hover-tooltip center">
                    <i class="icon icon-arrow-right"></i>
                    <!-- <span class="tooltip">Cotton jersey top</span> -->
                </a>
            </div>
        </div>
    </div>
</div>
<!-- /breadcrumb -->

<!-- default -->
<section class="flat-spacing-2 pt_0">
    <div class="tf-main-product section-image-zoom">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="tf-product-media-wrap sticky-top">
                        <div class="thumbs-slider">
                            <div dir="ltr" class="swiper tf-product-media-thumbs other-image-zoom" data-direction="vertical">
                                <div class="swiper-wrapper stagger-wrap">
                                    <!-- beige -->
                                    <?php if(isset($product->images->data)): foreach ($product->images->data as $item):  ?>
                                    <div class="swiper-slide stagger-item" data-color="beige">
                                        <div class="item">
                                            <img class="lazyload" data-src="<?= base_url($item->full_image) ?>" src="<?= base_url($item->full_image) ?>" alt="<?= $product->pd_name ?? '' ?>">
                                        </div>
                                    </div>
                                    <?php 
                                    endforeach; else: 
                                        $featureImg = (isset($product->feature_image['thumbnail']) && $product->feature_image['thumbnail'] !== null) ? $product->feature_image['thumbnail'] : base_url($configs->no_img);
                                    ?>
                                        <div class="item">
                                            <img class="lazyload" data-src="<?=$featureImg?>" src="<?=$featureImg?>" alt="<?= $product->pd_name ?? '' ?>">
                                        </div>
                                    <?php endif;?>
                                </div>
                            </div>
                            <div dir="ltr" class="swiper tf-product-media-main" id="gallery-swiper-started">
                                <div class="swiper-wrapper" >
                                    <!-- beige -->
                                    <?php if(isset($product->images->data)): foreach ($product->images->data as $item):  ?>
                                    <div class="swiper-slide" data-color="beige">
                                        <a href="<?= base_url($item->full_image) ?>" target="_blank" class="item" data-pswp-width="770px" data-pswp-height="770px">
                                            <img class="tf-image-zoom lazyload" data-zoom="<?= base_url($item->full_image) ?>" data-src="<?= base_url($item->full_image) ?>" src="<?= base_url($item->full_image) ?>" alt="<?= $product->pd_name ?? '' ?>">
                                        </a>
                                    </div>
                                    <?php endforeach; else: ?>
                                        <div class="swiper-slide" data-color="beige">
                                        <a href="<?=$featureImg?>" target="_blank" class="item" data-pswp-width="770px" data-pswp-height="770px">
                                            <img class="tf-image-zoom lazyload" data-zoom="<?=$featureImg?>" data-src="<?=$featureImg?>" src="<?=$featureImg?>" alt="<?= $product->pd_name ?? '' ?>">
                                        </a>
                                    </div>
                                    <?php endif;?>
                                    
                                </div>
                                <?php if ( isset($product->images->data) && count($product->images->data) > 0 ) : ?>
                                <div class="swiper-button-next button-style-arrow thumbs-next"></div>
                                <div class="swiper-button-prev button-style-arrow thumbs-prev"></div>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="tf-product-info-wrap position-relative">
                        <div class="tf-zoom-main"></div>
                        <div class="tf-product-info-list other-image-zoom">
                            <div class="tf-product-info-title">
                                <h5><?= $product->pd_name ?? '' ?></h5>
                            </div>
                            <!-- <div class="tf-product-info-badges">
                                <div class="badges">Best seller</div>
                                <div class="product-status-content">
                                    <i class="icon-lightning"></i>
                                    <p class="fw-6">Selling fast! 56 people have  this in their carts.</p>
                                </div>
                            </div> -->
                            <div class="tf-product-info-price">
                            <?php if ($product->price_discount > 0 && $product->price_discount < $product->price) : ?>
                                <div class="price-on-sale"><?=format_currency( $product->price_discount)?></div>
                                <div class="compare-at-price"><?=format_currency( $product->price)?></div>
                                <div class="badges-on-sale"><span><?= round((($product->price - $product->price_discount) / $product->price) * 100) ?></span>% OFF</div>
                            <?php elseif ($product->price == 0) :?>
                                <div class="price-on-sale"><?=lang('Product.contact_price_text')?></div>
                            <?php else:?>
                                <div class="price-on-sale"><?=format_currency( $product->price)?></div>
                            <?php endif;?>

                            </div>
                            <!-- <div class="tf-product-info-liveview">
                                <div class="liveview-count">20</div>
                                <p class="fw-6">People are viewing this right now</p>
                            </div> -->
                            
                            <?php if(!empty($product->pd_weight)): ?>
                            <div class="tf-product-weight">
                                <p class="fw-6 w-100"><?=lang('Product.pd_weight')?> : <?=$product->pd_weight?> &nbsp;<?=lang('Common.product_weight_unit')?></p>
                            </div>
                            <?php endif; ?>
                            <?php if(!empty($product->pd_size)): ?>
                            <div class="tf-product-size">
                                <p class="fw-6 w-100"><?=lang('Product.pd_size')?> : <?=$product->pd_size?></p>
                            </div>
                            <?php endif; ?>
                            <?php if(!empty($product->pd_cut_angle)): ?>
                            <div class="tf-product-cut-angle">
                                <p class="fw-6 w-100"><?=lang('Product.pd_cut_angle')?> : <?=$product->pd_cut_angle?></p>
                            </div>
                            <?php endif; ?>                            
                            
                            <div class="tf-product-info-buy-button">
                                <form class="">
                                <?php
                                $price = ($product->price_discount > 0 && $product->price_discount < $product->price) ? $product->price_discount : $product->price;
                                ?>
                                    <a href="javascript:void(0);" class="tf-btn btn-fill justify-content-center fw-6 fs-16 flex-grow-1 animate-hover-btn btn-add-to-cart">
                                        <span><?=lang('Product.buy_now')?> -&nbsp;</span>
                                        <span class="tf-qty-price total-price"><?=format_currency($price)?></span>
                                    </a>
                                    
                                    <!-- <div class="w-100">
                                        <a href="#" class="btns-full"><?=lang('Product.pay_now')?> <img src="<?=base_url($configs->templatePath.'images/payments/paypal-mini.png')?>" alt="<?=$configs->theme_name?>"></a>
                                        <a href="#" class="payment-more-option">More payment options</a>
                                    </div> -->
                                </form>
                            </div>
                            <div class="tf-product-info-extra-link">
                                
                                <a href="#ask_question" data-bs-toggle="modal" class="tf-product-extra-icon">
                                    <div class="icon">
                                        <i class="icon-question"></i>
                                    </div>
                                    <div class="text fw-6"><?=lang('Product.ask_a_question')?></div>
                                </a>
                                <a href="#delivery_return" data-bs-toggle="modal" class="tf-product-extra-icon">
                                    <div class="icon">
                                        <svg class="d-inline-block" viewBox="0 0 22 25" width="22" height="18" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M10.4608 1.50732C11.2607 0.539132 12.7446 0.539132 13.5445 1.50732L14.7445 2.95979C15.1677 3.472 15.8176 3.74061 16.4789 3.67664L18.3571 3.49495C19.6102 3.37372 20.6624 4.42666 20.5402 5.67974L20.3597 7.53129C20.2949 8.1958 20.566 8.84883 21.0823 9.2721L22.5232 10.4533C23.4993 11.2534 23.4993 12.7466 22.5232 13.5467L21.0823 14.7279C20.566 15.1512 20.2949 15.8042 20.3597 16.4687L20.5402 18.3203C20.6624 19.5733 19.6102 20.6263 18.3571 20.505L16.4789 20.3234C15.8176 20.2594 15.1677 20.528 14.7445 21.0402L13.5445 22.4927C12.7446 23.4609 11.2607 23.4609 10.4608 22.4927L9.26079 21.0402C8.83761 20.528 8.18769 20.2594 7.52637 20.3234L5.64824 20.505C4.39507 20.6263 3.34293 19.5733 3.4651 18.3203L3.64562 16.4687C3.71041 15.8042 3.43933 15.1512 2.92298 14.7279L1.48208 13.5467C0.505968 12.7466 0.505968 11.2534 1.48208 10.4533L2.92298 9.2721C3.43933 8.84883 3.71041 8.1958 3.64562 7.53129L3.4651 5.67974C3.34293 4.42666 4.39507 3.37372 5.64824 3.49495L7.52637 3.67664C8.18769 3.74061 8.83761 3.472 9.26079 2.95979L10.4608 1.50732Z" stroke="#000000" stroke-width="1.5"></path> <path d="M8.12549 12.7725L10.4136 15.0516C10.8437 15.48 11.5531 15.4296 11.9182 14.9446L15.8801 9.68274" stroke="#000000" stroke-width="1.5" stroke-linecap="round"></path> </g></svg>
                                        <!-- <svg class="d-inline-block" xmlns="http://www.w3.org/2000/svg" width="22" height="18" viewBox="0 0 22 18" fill="currentColor"><path d="M21.7872 10.4724C21.7872 9.73685 21.5432 9.00864 21.1002 8.4217L18.7221 5.27043C18.2421 4.63481 17.4804 4.25532 16.684 4.25532H14.9787V2.54885C14.9787 1.14111 13.8334 0 12.4255 0H9.95745V1.69779H12.4255C12.8948 1.69779 13.2766 2.07962 13.2766 2.54885V14.5957H8.15145C7.80021 13.6052 6.85421 12.8936 5.74468 12.8936C4.63515 12.8936 3.68915 13.6052 3.33792 14.5957H2.55319C2.08396 14.5957 1.70213 14.2139 1.70213 13.7447V2.54885C1.70213 2.07962 2.08396 1.69779 2.55319 1.69779H9.95745V0H2.55319C1.14528 0 0 1.14111 0 2.54885V13.7447C0 15.1526 1.14528 16.2979 2.55319 16.2979H3.33792C3.68915 17.2884 4.63515 18 5.74468 18C6.85421 18 7.80021 17.2884 8.15145 16.2979H13.423C13.7742 17.2884 14.7202 18 15.8297 18C16.9393 18 17.8853 17.2884 18.2365 16.2979H21.7872V10.4724ZM16.684 5.95745C16.9494 5.95745 17.2034 6.08396 17.3634 6.29574L19.5166 9.14894H14.9787V5.95745H16.684ZM5.74468 16.2979C5.27545 16.2979 4.89362 15.916 4.89362 15.4468C4.89362 14.9776 5.27545 14.5957 5.74468 14.5957C6.21392 14.5957 6.59575 14.9776 6.59575 15.4468C6.59575 15.916 6.21392 16.2979 5.74468 16.2979ZM15.8298 16.2979C15.3606 16.2979 14.9787 15.916 14.9787 15.4468C14.9787 14.9776 15.3606 14.5957 15.8298 14.5957C16.299 14.5957 16.6809 14.9776 16.6809 15.4468C16.6809 15.916 16.299 16.2979 15.8298 16.2979ZM18.2366 14.5957C17.8853 13.6052 16.9393 12.8936 15.8298 12.8936C15.5398 12.8935 15.252 12.943 14.9787 13.04V10.8511H20.0851V14.5957H18.2366Z"></path></svg> -->
                                    </div>
                                    <div class="text fw-6"><?=lang('Product.warranty_policy')?></div>
                                </a>
                                <a href="#share_social" data-bs-toggle="modal" class="tf-product-extra-icon">
                                    <div class="icon">
                                        <i class="icon-share"></i>
                                    </div>
                                    <div class="text fw-6"><?=lang('Product.social_share')?></div>
                                </a>
                            </div>
                            
                            <div class="tf-product-info-trust-seal">
                                <div class="tf-product-trust-mess">
                                    <i class="icon-safe"></i>
                                    <p class="fw-6"><?=lang('Product.guarantee_safe_checkout')?></p>
                                </div>
                                <div class="tf-payment">
                                    <img width="42px" src="<?=base_url($configs->templatePath)?>images/payments/visa.png" alt="<?=get_theme_config('general_site_title')?>">
                                    <img width="42px" src="<?=base_url($configs->templatePath)?>images/payments/img-1.png" alt="<?=get_theme_config('general_site_title')?>">
                                    <img width="42px" src="<?=base_url($configs->templatePath)?>images/payments/img-2.png" alt="<?=get_theme_config('general_site_title')?>">
                                    <img width="42px" src="<?=base_url($configs->templatePath)?>images/payments/img-3.png" alt="<?=get_theme_config('general_site_title')?>">
                                    <img width="42px" src="<?=base_url($configs->templatePath)?>images/payments/img-4.png" alt="<?=get_theme_config('general_site_title')?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tf-sticky-btn-atc">
        <div class="container">
            <div class="tf-height-observer w-100 d-flex align-items-center">
                <div class="tf-sticky-atc-product d-flex align-items-center">
                    <div class="tf-sticky-atc-img">
                    <?php
                    $featureImg = (isset($product->feature_image['thumbnail']) && $product->feature_image['thumbnail'] !== null) ? $product->feature_image['thumbnail'] : base_url($configs->no_img);
                    ?>
                        <img class="lazyloaded" data-src="<?=$featureImg?>" alt="<?= $product->pd_name ?? '' ?>" src="<?=$featureImg?>">
                    </div>
                    <div class="tf-sticky-atc-title fw-5 d-xl-block d-none"><?= $product->pd_name ?? '' ?></div>
                </div>
                <div class="tf-sticky-atc-infos">
                    <form class="">
                        <div class="tf-sticky-atc-btns">
                            <!-- <div class="tf-product-info-quantity">
                                <div class="wg-quantity">
                                    <span class="btn-quantity minus-btn">-</span>
                                    <input type="text" name="number" value="1">
                                    <span class="btn-quantity plus-btn">+</span>
                                </div>
                            </div> -->
                            <a href="javascript:void(0);" class="tf-btn btn-fill radius-3 justify-content-center fw-6 fs-14 flex-grow-1 animate-hover-btn btn-add-to-cart">
                                <span><?=lang('Product.add_to_cart')?>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /default -->

<!-- tabs -->
<section class="flat-spacing-17 pt_0">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="widget-tabs style-has-border">
                    <ul class="widget-menu-tab">
                        <li class="item-title active">
                            <span class="inner"><?=lang('Product.product_info')?></span>
                        </li>
                        
                    </ul>
                    <div class="widget-content-tab">
                        <div class="widget-content-inner active">
                            <div class="product-detail">
                                <?= $product->product_info?? ''?>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /tabs -->

<!--related product -->
<?php
if ( isset($relatedProducts) && count($relatedProducts) > 0 ) {
    $relatedSectionData = [
        'sectionTitle' => lang('Product.related_products'),
        'productData' => $relatedProducts,
        'currentLang' => $currentLang,
    ];
    echo view($configs->view. '\components\product\related_products', $relatedSectionData);
} ?>
<!-- /related-product -->

<!-- recently view -->
<?php
if ( isset($recentlyViewedProducts) && count($recentlyViewedProducts) > 0 ) {
    // remove current product from list
    $recentlyViewedProducts = array_filter($recentlyViewedProducts, function($productItem) use ($product) {
        return $productItem->id != $product->id;
    });

    // render view component
    if ( isset($recentlyViewedProducts) && count($recentlyViewedProducts) > 0 ) {
        $recentlyViewSectionData = [
            'sectionTitle' => lang('Product.recently_view_products'),
            'productData' => $recentlyViewedProducts,
            'currentLang' => $currentLang,
        ];
        echo view($configs->view. '\components\product\related_products', $recentlyViewSectionData);
    }
} ?>
<!-- /recently view -->

<!-- modal ask_question -->
<div class="modal modalCentered fade modalDemo tf-product-modal modal-part-content" id="ask_question">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="header">
                <div class="demo-title"><?=lang('Product.ask_a_question')?></div>
                <span class="icon-close icon-close-popup" data-bs-dismiss="modal"></span>
            </div>
            <div class="overflow-y-auto">
                <form class="">
                    <fieldset class="">
                        <label for="">Name *</label>
                        <input type="text" placeholder="" class="" name="text" tabindex="2" value=""
                            aria-required="true" required="">
                    </fieldset>
                    <fieldset class="">
                        <label for="">Email *</label>
                        <input type="email" placeholder="" class="" name="text" tabindex="2" value=""
                            aria-required="true" required="">
                    </fieldset>
                    <fieldset class="">
                        <label for="">Phone number</label>
                        <input type="number" placeholder="" class="" name="text" tabindex="2" value=""
                            aria-required="true" required="">
                    </fieldset>
                    <fieldset class="">
                        <label for="">Message</label>
                        <textarea name="message" rows="4" placeholder="" class="" tabindex="2" aria-required="true"
                            required=""></textarea>
                    </fieldset>
                    <button type="submit"
                        class="tf-btn w-100 btn-fill justify-content-center fw-6 fs-16 flex-grow-1 animate-hover-btn"><span>Send</span></button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /modal ask_question -->

<!-- modal warranty_policy -->
<div class="modal modalCentered fade modalDemo tf-product-modal modal-part-content" id="delivery_return">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="header">
                <div class="demo-title"><?=lang('Product.warranty_and_return_policy_modal_title')?></div>
                <span class="icon-close icon-close-popup" data-bs-dismiss="modal"></span>
            </div>
            <div class="overflow-y-auto">
                <div class="tf-product-popup-delivery">
                    <div class="title"><?=lang('Product.warranty_policy')?></div>
                    <?=lang('Product.warranty_policy_des')?>
                </div>
                <div class="tf-product-popup-delivery">
                    <div class="title"><?=lang('Product.return_policy')?></div>
                    <?=lang('Product.return_policy_des')?>
                </div>
                <div class="tf-product-popup-delivery">
                    <div class="title"><?=lang('Product.support_title')?></div>
                    <?=lang('Product.support_des', [get_theme_config('general_email'), get_theme_config('general_hotline')])?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /modal warranty_policy -->

<!-- modal share social -->
<?=view($configs->view. '\components\product\social-share', ['shareUrl' => $product->url])?>
<!-- /modal share social -->

<?= $this->endSection() ?>

<?= $this->section('style')?>
<style>
.tf-product-weight, .tf-product-size, .tf-product-cut-angle {
    display: flex;
    gap: 10px;
    align-items: center;
}
.product-detail h1 {
    font-size: 30px !important;
}
.product-detail h2 {
    font-size: 25px !important;
}
.product-detail h3 {
    font-size: 20px !important;
}
</style>

<?= $this->endSection() ?>

<?= $this->section('scripts')?>
<script src="<?=base_url($configs->templatePath)?>js/photoswipe-lightbox.umd.min.js"></script>
<script src="<?=base_url($configs->templatePath)?>js/photoswipe.umd.min.js"></script>
<script type="module" src="<?=base_url($configs->templatePath)?>js/zoom.js"></script>
<?= $this->endSection() ?>