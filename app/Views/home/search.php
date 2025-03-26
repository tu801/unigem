<?php
echo $this->extend($configs->viewLayout);
echo $this->section('content');

$cat_bg_colors = ['bg-dribble','bg-blueviolet', 'bg-orange', 'bg-green', 'bg-aash-dark', 'bg-dodger-blue', 'bg-twitter2'];
?>
<!--=====================================-->
<!--=           Category Start        	=-->
<!--=====================================-->
<section class="inner-page-banner bg-common" data-bg-image="<?=base_url($configs->templatePath)?>/media/figure/banner1.jpg">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumbs-area">
                    <h1><?=$search_title?></h1>
                    <?=$breadcrum?>
                </div>
            </div>
        </div>
    </div>
</section>
<!--=====================================-->
<!--=           Post Start        		=-->
<!--=====================================-->
<section class="inner-section">
    <div class="container">
        <div class="row" id="no-equal-gallery">

        <?php
        if ( !empty(array_filter($posts)) ) :
            foreach ($posts as $post ):
                $postModel->convertData($post); //echo '<pre>'; print_r($post);
                $img = ( $post->thumb_image !== null ) ? base_url($post->full_image) :base_url($configs->noimg);
                ?>

            <div class="col-lg-4 col-md-6 no-equal-item">
                <div class="post-list-layout3 post-grid-layout5">
                    <div class="item-img">
                        <img src="<?=$img?>" alt="<?=$post->title?>">
                    </div>
                    <div class="item-content">
                        <div class="item-tag <?=array_rand($cat_bg_colors)?>"><?=$post->category['title']?></div>
                        <div class="post-meta">
                            <ul>
                                <li><i class="flaticon-clock"></i> <?=$post->created_view?></li>
                                <li class="item-author"><i class="flaticon-user bg-aash-dark"></i>
                                    <span>Bởi</span><a href="<?=$post->author['authorUrl']?>"><?=$post->author['username']?></a></li>
                            </ul>
                        </div>
                        <h3 class="item-title"><a href="<?=$post->detailUrl?>"><?=$post->title?></a></h3>
                    </div>
                </div>
            </div>
        <?php endforeach;
        else: ?>
            <div class="col-12">
                <p><?=lang('Ember.no-result-found')?></p>
                <a href="<?=base_url()?>" class="item-btn">TRỞ LẠI TRANG CHỦ</a>
            </div>
        <?php endif; ?>
        </div>
        <!--<div class="load-more-btn">
            <a href="#" class="item-btn">LOAD MORE</a>
        </div>-->
    </div>
</section>
<?= $this->endSection() ?>