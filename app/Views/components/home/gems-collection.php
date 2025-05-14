<?php

use App\Models\Blog\CategoryModel;

$categories = get_theme_config('gems_cat_list');
$catTotal = count($categories);
$catSlideData = [];
foreach ($categories as $catId) {
    $catSlideData[] = model(CategoryModel::class)->getById($catId, $currentLang->id);
}

$catTitle = get_theme_config('gems_cat_title');
?>
<section class="flat-spacing-12 bg_grey-3">
    <div class="container">
        <div class="flat-title flex-row justify-content-between align-items-center px-0 wow fadeInUp" data-wow-delay="0s" style="visibility: visible; animation-delay: 0s; animation-name: fadeInUp;">
            <h3 class="title"><?=$catTitle?></h3>
            <a href="<?=route_to('product_shop')?>" class="tf-btn btn-line"><?=lang('Home.shop_collection')?><i class="icon icon-arrow1-top-left"></i></a>
        </div>
        <div class="hover-sw-nav hover-sw-2">
            <div dir="ltr" class="swiper tf-sw-collection swiper-initialized swiper-horizontal swiper-pointer-events swiper-backface-hidden" data-preview="6" data-tablet="3" data-mobile="2" data-space-lg="50" data-space-md="30" data-space="15" data-loop="false" data-auto-play="false">
                <div class="swiper-wrapper" id="swiper-wrapper-5f6aadc72d430102a" aria-live="polite" style="transform: translate3d(0px, 0px, 0px); transition-duration: 0ms;">
                    <?php
                    $i=0;
                    foreach ($catSlideData as $catSlide) :
                        $i++;

                        if ( $i == 1 ) {
                            $class = 'swiper-slide-active';                  
                        } else if ($i == 2) {
                            $class ='swiper-slide-next';
                        } else {
                            $class = '';
                        }
                    ?>
                    <div class="swiper-slide <?=$class?>" lazy="true" role="group" aria-label="<?=$i?> / <?=$catTotal?>" style="width: 198.333px; margin-right: 50px;">
                        <div class="collection-item-circle hover-img">
                            <a href="<?=$catSlide->url?>" class="collection-image img-style">
                                <img class=" ls-is-cached lazyloaded" data-src="<?=$catSlide->cat_image['image']?>" src="<?=$catSlide->cat_image['image']?>" alt="<?=$catSlide->title?>">
                            </a>
                            <div class="collection-content text-center">
                                <a href="<?=$catSlide->url?>" class="link title fw-5"><?=$catSlide->title?></a>
                                <!-- <div class="count">23 items</div> -->
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <div class="sw-dots style-2 sw-pagination-collection justify-content-center swiper-pagination-clickable swiper-pagination-bullets swiper-pagination-horizontal"><span class="swiper-pagination-bullet swiper-pagination-bullet-active" tabindex="0" role="button" aria-label="Go to slide 1" aria-current="true"></span><span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 2"></span></div>
            <div class="nav-sw nav-next-slider nav-next-collection swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-5f6aadc72d430102a" aria-disabled="true"><span class="icon icon-arrow-left"></span></div>
            <div class="nav-sw nav-prev-slider nav-prev-collection" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-5f6aadc72d430102a" aria-disabled="false"><span class="icon icon-arrow-right"></span></div>
        </div>
    </div>
</section>