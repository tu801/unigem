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

<!-- blog-grid-main -->
<div class="blog-grid-main">
    <div class="container">
        <?php if(count($postTags) > 0): ?>
            <div class="row">
            <?php foreach ($postTags as $item): ?>
            <div class="col-xl-4 col-md-6 col-12">
                <div class="blog-article-item">
                    <div class="article-thumb">
                        <a href="<?=$item->url?>">
                            <img class="lazyload" 
                                data-src="<?= (!empty($item->images)) ? $item->images['thumbnail'] : '' ?>" 
                                src="<?= (!empty($item->images)) ? $item->images['thumbnail'] : '' ?>" 
                                alt="<?= $item->title ?? '' ?>">
                        </a>
                        <div class="article-label">
                            <a href="<?=$item->priv_cat->url?>" class="tf-btn btn-sm radius-3 btn-fill animate-hover-btn"><?=$item->priv_cat->title?></a>
                        </div>
                    </div>
                    <div class="article-content">
                        <div class="article-title">
                            <a href="<?=$item->url?>" class=""><?= $item->title ?? '' ?></a>
                        </div>
                        <div class="article-btn">
                            <a href="<?=$item->url?>" class="tf-btn btn-line fw-6"><?=lang('Home.read_more')?><i class="icon icon-arrow1-top-left"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach;?>
            </div>
            <div class="row">
                <div class="col-12">
                    <?= $pager->links();?>
                </div>
            </div>
            <?php else : ?>
            <div class="row">
                <div class="col-12">
                    <p class="text-center">
                        <?= lang('Acp.item_not_found')?>
                    </p>
                </div>
            </div>
            <?php endif;?>
    </div>
</div>
<!-- /blog-grid-main -->


<?= $this->endSection() ?>