<?php
echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>

<!-- blog-list -->
<div class="blog-detail">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="blog-list-main pt-1">
                    <div class="list-blog">
                        <div class="blog-detail-main">
                            
                            <div class="blog-detail-main-heading">
                                <?php if ( count($post->categories) ) : ?>
                                <ul class="tags-lists justify-content-center">
                                    <?php foreach ($post->categories as $category) : ?>
                                    <li>
                                        <a href="<?=base_url(route_to('category_page', $category['slug']))?>" class="tags-item"><?= $category['title']?></a>
                                    </li>
                                    <?php endforeach;?>
                                </ul>
                                <?php endif;?>
                                <div class="title"><?= $post->title ?? '' ?></div>
                                <div class="meta"><?=lang('Site.post_author_meta', [$post->author->username, $post->created_at->format('d/m/Y')])?></div>
                                <!-- <div class="image">
                                    <img class=" ls-is-cached lazyloaded" data-src="images/blog/blog-detail.jpg" src="images/blog/blog-detail.jpg" alt="">
                                </div> -->
                            </div>
                            <?=$post->content ?? '' ?>
                        </div>
                    </div>
        
                    <?= view($configs->view. '\components\blog_sidebar', ['post' => $post, 'configs' => $configs, 'currentLang' => $currentLang ])?>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="btn-sidebar-mobile">
    <button data-bs-toggle="offcanvas" data-bs-target="#sidebarmobile" aria-controls="offcanvasRight"><i class="icon-open"></i></button>
</div>
<!-- /blog-list -->


<!-- sidebarmobile -->
<div class="offcanvas offcanvas-end canvas-mb" id="sidebarmobile">
    <span class="icon-close icon-close-popup" data-bs-dismiss="offcanvas" aria-label="Close"></span>
    <div class="mb-canvas-content">
        <div class="sidebar-mobile-append tf-section-sidebar">

        </div>
    </div>       
</div>
<!-- /sidebarmobile -->
<?= $this->endSection() ?>