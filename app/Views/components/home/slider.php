<?php
$sliders = get_slider_config();
?>
<div class="tf-slideshow slider-effect-fade slider-skincare position-relative">
    <div dir="ltr" class="swiper tf-sw-slideshow" data-preview="1" data-tablet="1" data-mobile="1" data-centered="false" data-space="0" data-loop="true" data-auto-play="false" data-delay="2000" data-speed="1000">
        <div class="swiper-wrapper">
            <?php foreach ($sliders as $slider): ?>
            <div class="swiper-slide" lazy="true">
                <div class="wrap-slider">
                    <img class="lazyload" 
                        data-src="<?=base_url($slider['image']['full_image']) ?>" 
                        src="<?=base_url($slider['image']['full_image']) ?>" alt="<?=$slider['title_big']?>" loading="lazy">
                    <div class="box-content text-center">
                        <div class="container">
                            <?php if (!empty($slider['subtitle'])):?>
                            <h1 class="fade-item fade-item-1 text-white heading  fw-8"><?=$slider['title_big']?></h1>
                            <?php endif;?>
                            <?php if ( !empty($slider['slider_url']) ) : ?>
                            <div class="fade-item fade-item-2">
                                <a href="<?= $slider['slider_url'] ?>" class=" tf-btn btn-outline-light rounded-0 fw-6 fs-14 link justify-content-center letter-spacing-2 wow fadeInUp" data-wow-delay="0s">SHOP NOW</a>
                            </div>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach;?>

        </div>
    </div>
    <div class="wrap-pagination sw-absolute-3">
        <div class="sw-dots style-2 dots-white sw-pagination-slider justify-content-center"></div>
    </div>
</div>