<?php
echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>
<!-- slider -->
<?= view($configs->view . '\components\home\slider') ?>
<!-- //slider -->

<!-- Jewel Categories -->
<?php if (get_theme_config('jewelry_cat_active')):?>
<?= view($configs->view . '\components\home\jewel_categories', ['currentLang' => $currentLang]) ?>
<?php endif;?>
<!-- //Jewel Categories -->

<!-- Products -->
<?php if ( !empty($productList) ) : ?>
<?= view($configs->view. '\components\home\products', ['productList' => $productList])?>
<?php endif; ?>
<!-- //Products -->

<!-- Gems Collection -->
<?php if (get_theme_config('gems_cat_active')):?>
<?= view($configs->view. '\components\home\gems-collection')?>
<?php endif;?>
<!-- //Gems Collection -->

<!-- Marquee -->
<?php if (get_theme_config('running_text_active')):?>
<?= view($configs->view. '\components\home\marquee')?>
<?php endif;?>
<!-- //Marquee -->

<!-- video-text -->
<?php if (get_theme_config('design_active')):?>
<?= view($configs->view. '\components\home\video-text')?>
<?php endif;?>
<!-- /video-text -->

<!-- Blogs post -->
<?php if (!empty($postList) ) :?>
<section class="flat-spacing-14">
    <div class="container">
        <div class="flat-title wow fadeInUp" data-wow-delay="0s">
            <span class="title"><?=lang('Home.top_news')?></span>
        </div>
        <div class="hover-sw-nav view-default hover-sw-3">
            <div dir="ltr" class="swiper tf-sw-recent" data-preview="3" data-tablet="2" data-mobile="1" data-space-lg="30" data-space-md="30" data-space="15" data-pagination="1" data-pagination-md="1" data-pagination-lg="1">
                <div class="swiper-wrapper">
                    <?php foreach ($postList as $key => $post) : ?>                    
                    <div class="swiper-slide" lazy="true">
                        <div class="blog-article-item wow fadeInUp" data-wow-delay="0s">
                            <div class="article-thumb rounded-0">
                                <a href="<?=$post->url?>">
                                    <img class="lazyload" data-src="<?=$post->images['thumbnail']?>" src="<?=$post->images['thumbnail']?>" alt="<?= $post->title ?? '' ?>">
                                </a>
                                <div class="article-label">
                                    <a href="<?=$post->priv_cat->url?>" class="tf-btn btn-sm btn-fill animate-hover-btn"><?=$post->priv_cat->title?></a>
                                </div>
                            </div>
                            <div class="article-content">
                                <div class="article-title">
                                    <a href="<?=$post->url?>" class=""><?= $post->title ?? '' ?></a>
                                </div>
                                <div class="article-btn">
                                    <a href="<?=$post->url?>" class="tf-btn btn-line fw-6"><?=lang('Home.read_more')?><i class="icon icon-arrow1-top-left"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach;?>

                </div>
            </div>
            <?php if ( count($postList) > 3) :?>
            <div class="nav-sw nav-next-slider nav-next-recent box-icon w_46 round"><span class="icon icon-arrow-left"></span></div>
            <div class="nav-sw nav-prev-slider nav-prev-recent box-icon w_46 round"><span class="icon icon-arrow-right"></span></div>
            <div class="sw-dots style-2 sw-pagination-recent justify-content-center"></div>
            <?php endif;?>
        </div>
    </div>
</section>
<?php endif;?>
<!-- /Blogs post -->

<!-- Icon box -->
<section class="flat-spacing-1 flat-iconbox wow fadeInUp" data-wow-delay="0s">
    <div class="container">
        <div class="wrap-carousel wrap-mobile">
            <div dir="ltr" class="swiper tf-sw-mobile" data-preview="1" data-space="15">
                <div class="swiper-wrapper wrap-iconbox">
                    <div class="swiper-slide">
                        <div class="tf-icon-box style-row">
                            <div class="icon">
                                <i class="icon-shipping"></i>
                            </div>
                            <div class="content ">
                                <div class="title fw-8 text-uppercase fs-14"><?=lang('Home.icon_box_free_ship_title')?></div>
                                <p><?=lang('Home.icon_box_free_ship_desc')?></p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="tf-icon-box style-row">
                            <div class="icon">
                                <i class="icon-payment fs-22"></i>
                            </div>
                            <div class="content ">
                                <div class="title fw-8 text-uppercase fs-14"><?=lang('Home.icon_box_payment_title')?></div>
                                <p><?=lang('Home.icon_box_payment_desc')?></p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="tf-icon-box style-row">
                            <div class="icon">
                                <i class="icon-return fs-20"></i>
                            </div>
                            <div class="content ">
                                <div class="title fw-8 text-uppercase fs-14"><?=lang('Home.icon_box_returns_title')?></div>
                                <p><?=lang('Home.icon_box_returns_desc')?></p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="tf-icon-box style-row">
                            <div class="icon">
                                <i class="icon-suport"></i>
                            </div>
                            <div class="content ">
                                <div class="title fw-8 text-uppercase fs-14"><?=lang('Home.icon_box_support_title')?></div>
                                <p><?=lang('Home.icon_box_support_desc')?></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="sw-dots style-2 sw-pagination-mb justify-content-center"></div>
        </div>
    </div>
</section>
<!-- /Icon box -->
<?= $this->endSection() ?>