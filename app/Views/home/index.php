<?php
echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>
    <!-- hero area -->
    <?= $this->include($configs->view . '\layouts\_hero_area') ?>

    <?php if (get_theme_config('active')):?>
    <!-- features area -->
    <section class="features_area  section_padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="row justify-content-center gx-2 gx-md-4">
                        <div class="col-sm-4 mb-3 mb-sm-0">
                            <div
                                    class="single_feature d-flex flex-column flex-sm-row align-items-center justify-content-center">
                                <div class="feature_icon">
                                    <img loading="lazy"  src="<?=base_url($configs->templatePath)?>/assets/images/svg/delivery-van.svg" alt="icon">

                                </div>
                                <div class="feature_content">
                                    <h4><?= get_theme_config('ship_title') ?? '' ?></h4>
                                    <p><?= get_theme_config('ship_text') ?? '' ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 mb-3 mb-sm-0">
                            <div
                                    class="single_feature d-flex flex-column flex-sm-row align-items-center justify-content-center">
                                <div class="feature_icon">
                                    <img loading="lazy"  src="<?=base_url($configs->templatePath)?>/assets/images/svg/money-back.svg" alt="icon">
                                </div>
                                <div class="feature_content">
                                    <h4><?= get_theme_config('money_back_title') ?? '' ?></h4>
                                    <p><?= get_theme_config('money_back_text') ?? '' ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div
                                    class="single_feature d-flex flex-column flex-sm-row align-items-center justify-content-center">
                                <div class="feature_icon">
                                    <img loading="lazy"  src="<?=base_url($configs->templatePath)?>/assets/images/svg/service-hours.svg" alt="icon">
                                </div>
                                <div class="feature_content">
                                    <h4><?= get_theme_config('support_text') ?? '' ?></h4>
                                    <p><?= get_theme_config('support_text') ?? '' ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if (get_theme_config('top_ranking_active')):?>
    <!-- top ranking -->
    <section class="top_ranking section_padding_b">
        <div class="container">
            <h2 class="section_title_2 mb-0"><?= lang('Product.top_ranking') ?></h2>
            <div class="row">
                <div class="col-xl-3 col-lg-4 col-6">
                    <?= view_cell('\App\Cells\Product\TopRankingCell', 'column_key=first_col', 300) ?>
                </div>
                <div class="col-xl-3 col-lg-4 col-6">
                    <?= view_cell('\App\Cells\Product\TopRankingCell', 'column_key=second_col', 300) ?>
                </div>
                <div class="col-xl-3 col-lg-4 col-6">
                    <?= view_cell('\App\Cells\Product\TopRankingCell', 'column_key=third_col', 300) ?>
                </div>
                <div class="col-xl-3 col-lg-4 col-6 d-lg-none d-block d-xl-block">
                    <?= view_cell('\App\Cells\Product\TopRankingCell', 'column_key=fourth_col', 300) ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if (get_theme_config('new_arrivals_active')): ?>
    <!-- new arrive -->
    <section class="new_arrive section_padding_b">
        <div class="container">
            <div class="d-flex align-items-start justify-content-between">
                <h2 class="section_title_2"><?= lang('Product.new_product') ?></h2>
                <div class="seemore_2 pt-2">
                    <a href="<?= base_url(route_to('product_shop')) ?>"><?= lang('Product.see_more') ?> <span><i class="las la-angle-right"></i></span></a>
                </div>
            </div>
            <div class="row gy-4">
                <?= view_cell('\App\Cells\Product\ListingByCell', 'type=new_arrivals', 300) ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if (get_theme_config('active') && $ads = get_theme_config('ads_image')):?>
    <!-- ad banner -->
    <div class="ad_banner_area section_padding_b">
        <div class="container">
            <picture>
                <img loading="lazy"  class="w-100" src="<?=base_url($ads->full_image)?>" alt="ad">
            </picture>
        </div>
    </div>
    <?php endif; ?>

    <?php if (get_theme_config('recommended_active')):?>
        <!-- recomended -->
        <section class="new_arrive section_padding_b">
            <div class="container">
                <div class="d-flex align-items-start justify-content-between">
                    <h2 class="section_title_2"><?= lang('product.recommended') ?></h2>
                    <div class="seemore_2 pt-2">
                        <a href="<?= base_url(route_to('product_shop')) ?>"><?= lang('Product.see_more') ?> <span><i class="las la-angle-right"></i></span></a>
                    </div>
                </div>
                <div class="row gy-4">
                    <?= view_cell('\App\Cells\Product\ListingByCell', 'type=recommended', 300) ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

<?= $this->endSection() ?>