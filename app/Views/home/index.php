<?php
echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>
<!-- slider -->
<?= view($configs->view . '\templates\slider') ?>
<!-- //slider -->

<!-- Categories -->
<?= view($configs->view . '\components\home\categories') ?>
<!-- //Categories -->

<!-- Products -->
<?= view($configs->view. '\components\home\products')?>
<!-- //Products -->

<!-- Collection -->
<?= view($configs->view. '\components\home\collection')?>
<!-- //Collection -->

<!-- Marquee -->
<?= view($configs->view. '\components\home\marquee')?>
<!-- //Marquee -->

<!-- video-text -->
<?= view($configs->view. '\components\home\video-text')?>
<!-- /video-text -->

<!-- Blogs post -->
<section class="flat-spacing-14">
    <div class="container">
        <div class="flat-title wow fadeInUp" data-wow-delay="0s">
            <span class="title">Blogs post</span>
        </div>
        <div class="hover-sw-nav view-default hover-sw-3">
            <div dir="ltr" class="swiper tf-sw-recent" data-preview="3" data-tablet="2" data-mobile="1" data-space-lg="30" data-space-md="30" data-space="15" data-pagination="1" data-pagination-md="1" data-pagination-lg="1">
                <div class="swiper-wrapper">
                    <div class="swiper-slide" lazy="true">
                        <div class="blog-article-item wow fadeInUp" data-wow-delay="0s">
                            <div class="article-thumb h-460 rounded-0">
                                <a href="blog-detail.html">
                                    <img class="lazyload" data-src="images/blog/blog-1.jpg" src="images/blog/blog-1.jpg" alt="img-blog">
                                </a>
                                <div class="article-label">
                                    <a href="blog-grid.html" class="tf-btn btn-sm btn-fill animate-hover-btn">Accessories</a>
                                </div>
                            </div>
                            <div class="article-content">
                                <div class="article-title">
                                    <a href="blog-detail.html" class="">Pop-punk is back in fashion</a>
                                </div>
                                <div class="article-btn">
                                    <a href="blog-detail.html" class="tf-btn btn-line fw-6">Read more<i class="icon icon-arrow1-top-left"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" lazy="true">
                        <div class="blog-article-item wow fadeInUp" data-wow-delay=".1s">
                            <div class="article-thumb h-460 rounded-0">
                                <a href="blog-detail.html">
                                    <img class="lazyload" data-src="images/blog/blog-2.jpg" src="images/blog/blog-2.jpg" alt="img-blog">
                                </a>
                                <div class="article-label">
                                    <a href="blog-grid.html" class="tf-btn btn-sm btn-fill animate-hover-btn">Accessories</a>
                                </div>
                            </div>
                            <div class="article-content">
                                <div class="article-title">
                                    <a href="blog-detail.html" class="">The next generation of leather alternatives</a>
                                </div>
                                <div class="article-btn">
                                    <a href="blog-detail.html" class="tf-btn btn-line fw-6">Read more<i class="icon icon-arrow1-top-left"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" lazy="true">
                        <div class="blog-article-item wow fadeInUp" data-wow-delay=".2s">
                            <div class="article-thumb h-460 rounded-0">
                                <a href="blog-detail.html">
                                    <img class="lazyload" data-src="images/blog/blog-3.jpg" src="images/blog/blog-3.jpg" alt="img-blog">
                                </a>
                                <div class="article-label">
                                    <a href="blog-grid.html" class="tf-btn btn-sm btn-fill animate-hover-btn">Accessories</a>
                                </div>
                            </div>
                            <div class="article-content">
                                <div class="article-title">
                                    <a href="blog-detail.html" class="">An Exclusive Clothing Collaboration</a>
                                </div>
                                <div class="article-btn">
                                    <a href="blog-detail.html" class="tf-btn btn-line fw-6">Read more<i class="icon icon-arrow1-top-left"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" lazy="true">
                        <div class="blog-article-item wow fadeInUp" data-wow-delay=".3s">
                            <div class="article-thumb h-460 rounded-0">
                                <a href="blog-detail.html">
                                    <img class="lazyload" data-src="images/blog/blog-4.jpg" src="images/blog/blog-4.jpg" alt="img-blog">
                                </a>
                                <div class="article-label">
                                    <a href="blog-grid.html" class="tf-btn btn-sm btn-fill animate-hover-btn">Accessories</a>
                                </div>
                            </div>
                            <div class="article-content">
                                <div class="article-title">
                                    <a href="blog-detail.html" class="">The next generation of leather alternatives</a>
                                </div>
                                <div class="article-btn">
                                    <a href="blog-detail.html" class="tf-btn btn-line fw-6">Read more<i class="icon icon-arrow1-top-left"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" lazy="true">
                        <div class="blog-article-item wow fadeInUp" data-wow-delay=".4s">
                            <div class="article-thumb h-460 rounded-0">
                                <a href="blog-detail.html">
                                    <img class="lazyload" data-src="images/blog/blog-5.jpg" src="images/blog/blog-5.jpg" alt="img-blog">
                                </a>
                                <div class="article-label">
                                    <a href="blog-grid.html" class="tf-btn btn-sm btn-fill animate-hover-btn">Accessories</a>
                                </div>
                            </div>
                            <div class="article-content">
                                <div class="article-title">
                                    <a href="blog-detail.html" class="">The next generation of leather alternatives</a>
                                </div>
                                <div class="article-btn">
                                    <a href="blog-detail.html" class="tf-btn btn-line fw-6">Read more<i class="icon icon-arrow1-top-left"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" lazy="true">
                        <div class="blog-article-item wow fadeInUp" data-wow-delay=".5s">
                            <div class="article-thumb h-460 rounded-0">
                                <a href="blog-detail.html">
                                    <img class="lazyload" data-src="images/blog/blog-6.jpg" src="images/blog/blog-6.jpg" alt="img-blog">
                                </a>
                                <div class="article-label">
                                    <a href="blog-grid.html" class="tf-btn btn-sm btn-fill animate-hover-btn">Accessories</a>
                                </div>
                            </div>
                            <div class="article-content">
                                <div class="article-title">
                                    <a href="blog-detail.html" class="">The next generation of leather alternatives</a>
                                </div>
                                <div class="article-btn">
                                    <a href="blog-detail.html" class="tf-btn btn-line fw-6">Read more<i class="icon icon-arrow1-top-left"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nav-sw nav-next-slider nav-next-recent box-icon w_46 round"><span class="icon icon-arrow-left"></span></div>
            <div class="nav-sw nav-prev-slider nav-prev-recent box-icon w_46 round"><span class="icon icon-arrow-right"></span></div>
            <div class="sw-dots style-2 sw-pagination-recent justify-content-center"></div>
        </div>
    </div>
</section>
<!-- /Blogs post -->

<?= $this->endSection() ?>