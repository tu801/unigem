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
<?= view($configs->view. '\components\home\marquee')?>
<!-- //Marquee -->

<!-- video-text -->
<?= view($configs->view. '\components\home\video-text')?>
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
            <div class="nav-sw nav-next-slider nav-next-recent box-icon w_46 round"><span class="icon icon-arrow-left"></span></div>
            <div class="nav-sw nav-prev-slider nav-prev-recent box-icon w_46 round"><span class="icon icon-arrow-right"></span></div>
            <div class="sw-dots style-2 sw-pagination-recent justify-content-center"></div>
        </div>
    </div>
</section>
<?php endif;?>
<!-- /Blogs post -->

<?= $this->endSection() ?>