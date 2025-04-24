<?php
echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>

<!-- blog-list -->
<div class="blog-detail">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="blog-list-main">
                    <div class="list-blog">
                        <div class="blog-detail-main">
                            
                            <div class="blog-detail-main-heading">
                                <ul class="tags-lists justify-content-center">
                                    <li>
                                        <a href="#" class="tags-item">ACCESSORIES</a>
                                    </li>
                                    <li>
                                        <a href="#" class="tags-item">BAGS</a>
                                    </li>
                                </ul>
                                <div class="title"><?= $post->title ?? '' ?></div>
                                <div class="meta">by <span>admin</span> on <span>Oct 02</span></div>
                                <div class="image">
                                    <img class=" ls-is-cached lazyloaded" data-src="images/blog/blog-detail.jpg" src="images/blog/blog-detail.jpg" alt="">
                                </div>
                            </div>
                            <?= $post->content ?? '' ?>
                        </div>
                    </div>
        
                    <?= view($configs->view. '\components\blog_sidebar')?>

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