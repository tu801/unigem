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


<!-- blog-list -->
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="blog-list-main pt-1">
            <?php if(count($post_category) > 0): ?>
                <div class="list-blog">
                    <?php foreach ($post_category as $item): ?>
                    <div class="blog-article-item style-row">
                        <div class="article-thumb">
                            <a href="<?=$item->url?>">
                                <img class="lazyload" 
                                    data-src="<?= (!empty($item->images)) ? $item->images['thumbnail'] : '' ?>" 
                                    src="<?= (!empty($item->images)) ? $item->images['thumbnail'] : '' ?>" 
                                    alt="<?= $item->title ?? '' ?>">
                            </a>
                        </div>
                        <div class="article-content">
                            <div class="article-label">
                                <a href="<?=$category->url?>" class="tf-btn btn-sm radius-3 btn-fill animate-hover-btn"><?=$category->title?></a>
                            </div>
                            <div class="article-title">
                                <a href="<?=$item->url?>" class=""><h4><?= $item->title ?? '' ?></h4></a>
                            </div>
                            <div class="desc">
                                <?= $item->description ?? '' ?>
                            </div>
                            <div class="article-btn">
                                <a href="<?=$item->url?>" class="tf-btn btn-line fw-6">Read more<i class="icon icon-arrow1-top-left"></i></a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach;?>
                    
                    <?= $pager->links(); ?>
                </div>

                <?= view($configs->view. '\components\blog_sidebar')?>
            <?php else : ?>
                <p class="text-center">
                    <?= lang('Acp.item_not_found')?>
                </p>
            <?php endif;?>
            </div>
        </div>
    </div>
</div>
<div class="btn-sidebar-mobile">
    <button data-bs-toggle="offcanvas" data-bs-target="#sidebarmobile" aria-controls="offcanvasRight"><i class="icon-open"></i></button>
</div>
<!-- /blog-list -->
<?= $this->endSection() ?>