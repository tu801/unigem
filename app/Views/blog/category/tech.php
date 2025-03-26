<?php
echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>

    <h1 class="d-none">Home Tech Blog</h1>
    <div class="axil-tech-post-banner pt--30 bg-color-grey">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-md-12 col-12">
                    <!-- Start Post Grid  -->
                    <div class="content-block post-grid post-grid-transparent">
                        <div class="post-thumbnail">
                            <a href="post-details.html">
                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tect-01.jpg" alt="Post Images">
                            </a>
                        </div>
                        <div class="post-grid-content">
                            <div class="post-content">
                                <div class="post-cat">
                                    <div class="post-cat-list">
                                        <a class="hover-flip-item-wrapper" href="#">
                                                <span class="hover-flip-item">
                                                    <span data-text="FEATURED POST">FEATURED POST</span>
                                                </span>
                                        </a>
                                    </div>
                                </div>
                                <h3 class="title"><a href="post-details.html">Beauty of deep space. Billions of
                                        galaxies in the universe.</a></h3>
                            </div>
                        </div>
                    </div>
                    <!-- Start Post Grid  -->
                </div>

                <div class="col-xl-3 col-md-6 mt_lg--30 mt_md--30 mt_sm--30 col-12">
                    <!-- Start Single Post  -->
                    <div class="content-block image-rounded">
                        <div class="post-thumbnail">
                            <a href="post-details.html">
                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tect-02.jpg" alt="Post Images">
                            </a>
                        </div>
                        <div class="post-content pt--20">
                            <div class="post-cat">
                                <div class="post-cat-list">
                                    <a class="hover-flip-item-wrapper" href="#">
                                            <span class="hover-flip-item">
                                                <span data-text="LEADERSHIP">LEADERSHIP</span>
                                            </span>
                                    </a>
                                </div>
                            </div>
                            <h5 class="title"><a href="post-details.html">Rocket Lab mission fails shortly after
                                    launch</a></h5>
                        </div>
                    </div>
                    <!-- End Single Post  -->
                    <!-- Start Single Post  -->
                    <div class="content-block image-rounded mt--30">
                        <div class="post-thumbnail">
                            <a href="post-details.html">
                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tect-03.jpg" alt="Post Images">
                            </a>
                        </div>
                        <div class="post-content pt--20">
                            <div class="post-cat">
                                <div class="post-cat-list">
                                    <a class="hover-flip-item-wrapper" href="#">
                                            <span class="hover-flip-item">
                                                <span data-text="TECHNOLOGY">TECHNOLOGY</span>
                                            </span>
                                    </a>
                                </div>
                            </div>
                            <h5 class="title"><a href="post-details.html">Virtual Reality or Artificial
                                    Intelligence Technology</a></h5>
                        </div>
                    </div>
                    <!-- End Single Post  -->
                </div>

                <div class="col-xl-3 col-md-6 mt_lg--30 mt_md--30 mt_sm--30 col-12">
                    <!-- Start Single Post  -->
                    <div class="content-block image-rounded">
                        <div class="post-thumbnail">
                            <a href="post-details.html">
                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tect-04.jpg" alt="Post Images">
                            </a>
                        </div>
                        <div class="post-content pt--20">
                            <div class="post-cat">
                                <div class="post-cat-list">
                                    <a class="hover-flip-item-wrapper" href="#">
                                            <span class="hover-flip-item">
                                                <span data-text="PRODUCT UPDATES">PRODUCT UPDATES</span>
                                            </span>
                                    </a>
                                </div>
                            </div>
                            <h5 class="title"><a href="post-details.html">The Morning After: Uber sets its
                                    sights on Postmates</a></h5>
                        </div>
                    </div>
                    <!-- End Single Post  -->
                    <!-- Start Single Post  -->
                    <div class="content-block image-rounded mt--30">
                        <div class="post-thumbnail">
                            <a href="post-details.html">
                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tect-05.jpg" alt="Post Images">
                            </a>
                        </div>
                        <div class="post-content pt--20">
                            <div class="post-cat">
                                <div class="post-cat-list">
                                    <a class="hover-flip-item-wrapper" href="#">
                                            <span class="hover-flip-item">
                                                <span data-text="GADGET">GADGET</span>
                                            </span>
                                    </a>
                                </div>
                            </div>
                            <h5 class="title"><a href="post-details.html">Air Pods Pro with Wireless Charging
                                    Case.</a></h5>
                        </div>
                    </div>
                    <!-- End Single Post  -->
                </div>
            </div>
        </div>
    </div>

    <!-- Start Categories List  -->
    <div class="axil-categories-list axil-section-gap bg-color-grey">
        <div class="container">
            <div class="row align-items-center mb--30">
                <div class="col-lg-6 col-md-8 col-sm-8 col-12">
                    <div class="section-title">
                        <h2 class="title">Trending Topics</h2>
                    </div>
                </div>
                <div class="col-lg-6 col-md-4 col-sm-4 col-12">
                    <div class="see-all-topics text-start text-sm-end mt_mobile--20">
                        <a class="axil-link-button" href="#">See All Topics</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <!-- Start List Wrapper  -->
                    <div class="list-categories categories-activation axil-slick-arrow arrow-between-side">

                        <!-- Start Single Category  -->
                        <div class="single-cat">
                            <div class="inner">
                                <a href="#">
                                    <div class="thumbnail">
                                        <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-sm-01.jpg" alt="post categories images">
                                    </div>
                                    <div class="content">
                                        <h5 class="title">Sports &#38; Fitness </h5>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!-- End Single Category  -->

                        <!-- Start Single Category  -->
                        <div class="single-cat">
                            <div class="inner">
                                <a href="#">
                                    <div class="thumbnail">
                                        <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-sm-02.jpg" alt="post categories images">
                                    </div>
                                    <div class="content">
                                        <h5 class="title">Travel</h5>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!-- End Single Category  -->

                        <!-- Start Single Category  -->
                        <div class="single-cat">
                            <div class="inner">
                                <a href="#">
                                    <div class="thumbnail">
                                        <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-sm-03.jpg" alt="post categories images">
                                    </div>
                                    <div class="content">
                                        <h5 class="title">lifestyle</h5>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!-- End Single Category  -->

                        <!-- Start Single Category  -->
                        <div class="single-cat">
                            <div class="inner">
                                <a href="#">
                                    <div class="thumbnail">
                                        <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-sm-04.jpg" alt="post categories images">
                                    </div>
                                    <div class="content">
                                        <h5 class="title">Health</h5>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!-- End Single Category  -->

                        <!-- Start Single Category  -->
                        <div class="single-cat">
                            <div class="inner">
                                <a href="#">
                                    <div class="thumbnail">
                                        <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-sm-05.jpg" alt="post categories images">
                                    </div>
                                    <div class="content">
                                        <h5 class="title">Animals</h5>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!-- End Single Category  -->

                        <!-- Start Single Category  -->
                        <div class="single-cat">
                            <div class="inner">
                                <a href="#">
                                    <div class="thumbnail">
                                        <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-sm-06.jpg" alt="post categories images">
                                    </div>
                                    <div class="content">
                                        <h5 class="title">Food &#38; Drink</h5>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!-- End Single Category  -->

                        <!-- Start Single Category  -->
                        <div class="single-cat">
                            <div class="inner">
                                <a href="#">
                                    <div class="thumbnail">
                                        <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-sm-06.jpg" alt="post categories images">
                                    </div>
                                    <div class="content">
                                        <h5 class="title">Food &#38; Drink</h5>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!-- End Single Category  -->

                        <!-- Start Single Category  -->
                        <div class="single-cat">
                            <div class="inner">
                                <a href="#">
                                    <div class="thumbnail">
                                        <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-sm-06.jpg" alt="post categories images">
                                    </div>
                                    <div class="content">
                                        <h5 class="title">Food &#38; Drink</h5>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!-- End Single Category  -->

                    </div>
                    <!-- Start List Wrapper  -->
                </div>
            </div>
        </div>
    </div>
    <!-- Start Categories List  -->


    <!-- Start Post Grid Area  -->
    <div class="axil-post-grid-area axil-section-gap bg-color-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-8 col-sm-8 col-12">
                    <div class="section-title">
                        <h2 class="title">Top Stories</h2>
                    </div>
                </div>
                <div class="col-lg-6 col-md-4 col-sm-4 col-12">
                    <div class="see-all-topics text-start text-sm-end mt_mobile--20">
                        <a class="axil-link-button" href="#">See All</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <ul class="axil-tab-button nav nav-tabs mt--20" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="grid-one" data-bs-toggle="tab" href="#gridone" role="tab" aria-controls="grid-one" aria-selected="true">Accessibility</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="grid-two" data-bs-toggle="tab" href="#gridtwo" role="tab" aria-controls="grid-two" aria-selected="false">Android Dev</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="grid-three" data-bs-toggle="tab" href="#gridthree" role="tab" aria-controls="grid-three" aria-selected="false">Blockchain</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="grid-four" data-bs-toggle="tab" href="#gridfour" role="tab" aria-controls="grid-four" aria-selected="false">Gadgets</a>
                        </li>
                    </ul>
                    <!-- Start Tab Content  -->
                    <div class="grid-tab-content tab-content">

                        <!-- Start Single Tab Content  -->
                        <div class="single-post-grid tab-pane fade show active" id="gridone" role="tabpanel">
                            <div class="row  mt--40">
                                <div class="col-xl-5 col-lg-6 col-md-12 col-12">
                                    <!-- Start Post Medium  -->
                                    <div class="content-block post-medium post-medium-border border-thin">
                                        <div class="post-thumbnail">
                                            <a href="post-details.html">
                                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-07.jpg" alt="Post Images">
                                            </a>
                                        </div>
                                        <div class="post-content">
                                            <div class="post-cat">
                                                <div class="post-cat-list">
                                                    <a class="hover-flip-item-wrapper" href="#">
                                                            <span class="hover-flip-item">
                                                                <span data-text="MOBILE">MOBILE</span>
                                                            </span>
                                                    </a>
                                                </div>
                                            </div>
                                            <h4 class="title"><a href="post-details.html">OnePlus Nord hands-on: Strong features at a tempting price</a></h4>
                                        </div>
                                    </div>
                                    <!-- End Post Medium  -->

                                    <!-- Start Post Medium  -->
                                    <div class="content-block post-medium post-medium-border border-thin">
                                        <div class="post-thumbnail">
                                            <a href="post-details.html">
                                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-09.jpg" alt="Post Images">
                                            </a>
                                        </div>
                                        <div class="post-content">
                                            <div class="post-cat">
                                                <div class="post-cat-list">
                                                    <a class="hover-flip-item-wrapper" href="#">
                                                            <span class="hover-flip-item">
                                                                <span data-text="ACCESSORIES">ACCESSORIES</span>
                                                            </span>
                                                    </a>
                                                </div>
                                            </div>
                                            <h4 class="title"><a href="post-details.html">The best accessories for your new iPad</a></h4>
                                        </div>
                                    </div>
                                    <!-- End Post Medium  -->

                                    <!-- Start Post Medium  -->
                                    <div class="content-block post-medium post-medium-border border-thin">
                                        <div class="post-thumbnail">
                                            <a href="post-details.html">
                                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-08.jpg" alt="Post Images">
                                            </a>
                                        </div>
                                        <div class="post-content">
                                            <div class="post-cat">
                                                <div class="post-cat-list">
                                                    <a class="hover-flip-item-wrapper" href="#">
                                                            <span class="hover-flip-item">
                                                                <span data-text="BUSINESS">BUSINESS</span>
                                                            </span>
                                                    </a>
                                                </div>
                                            </div>
                                            <h4 class="title"><a href="post-details.html">Chinese EV startups Byton and Nio received paycheck </a></h4>
                                        </div>
                                    </div>
                                    <!-- End Post Medium  -->

                                    <!-- Start Post Medium  -->
                                    <div class="content-block post-medium post-medium-border border-thin">
                                        <div class="post-thumbnail">
                                            <a href="post-details.html">
                                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-10.jpg" alt="Post Images">
                                            </a>
                                        </div>
                                        <div class="post-content">
                                            <div class="post-cat">
                                                <div class="post-cat-list">
                                                    <a class="hover-flip-item-wrapper" href="#">
                                                            <span class="hover-flip-item">
                                                                <span data-text="GOOGLE">GOOGLE</span>
                                                            </span>
                                                    </a>
                                                </div>
                                            </div>
                                            <h4 class="title"><a href="post-details.html">How to personalize your Google Chrome homepage with GIF</a></h4>
                                        </div>
                                    </div>
                                    <!-- End Post Medium  -->

                                </div>
                                <div class="col-xl-7 col-lg-6 col-md-12 col-12 mt_md--40 mt_sm--40">
                                    <!-- Start Post Grid  -->
                                    <div class="content-block content-block post-grid post-grid-transparent">
                                        <div class="post-thumbnail">
                                            <a href="post-details.html">
                                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-06.jpg" alt="Post Images">
                                            </a>
                                        </div>
                                        <div class="post-grid-content">
                                            <div class="post-content">
                                                <div class="post-cat">
                                                    <div class="post-cat-list">
                                                        <a class="hover-flip-item-wrapper" href="#">
                                                                <span class="hover-flip-item">
                                                                    <span data-text="APPLE">APPLE</span>
                                                                </span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <h3 class="title"><a href="post-details.html">iPhone SE is discontinued, but these retailers are still selling it</a></h3>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Start Post Grid  -->
                                </div>
                            </div>
                        </div>
                        <!-- End Single Tab Content  -->

                        <!-- Start Single Tab Content  -->
                        <div class="single-post-grid tab-pane fade" id="gridtwo" role="tabpanel">
                            <div class="row  mt--40">
                                <div class="col-xl-5 col-lg-6 col-md-12 col-12">
                                    <!-- Start Post Medium  -->
                                    <div class="content-block post-medium post-medium-border border-thin">
                                        <div class="post-thumbnail">
                                            <a href="post-details.html">
                                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-07.jpg" alt="Post Images">
                                            </a>
                                        </div>
                                        <div class="post-content">
                                            <div class="post-cat">
                                                <div class="post-cat-list">
                                                    <a class="hover-flip-item-wrapper" href="#">
                                                            <span class="hover-flip-item">
                                                                <span data-text="MOBILE">MOBILE</span>
                                                            </span>
                                                    </a>
                                                </div>
                                            </div>
                                            <h4 class="title"><a href="post-details.html">OnePlus Nord hands-on: Strong features at a tempting price</a></h4>
                                        </div>
                                    </div>
                                    <!-- End Post Medium  -->

                                    <!-- Start Post Medium  -->
                                    <div class="content-block post-medium post-medium-border border-thin">
                                        <div class="post-thumbnail">
                                            <a href="post-details.html">
                                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-09.jpg" alt="Post Images">
                                            </a>
                                        </div>
                                        <div class="post-content">
                                            <div class="post-cat">
                                                <div class="post-cat-list">
                                                    <a class="hover-flip-item-wrapper" href="#">
                                                            <span class="hover-flip-item">
                                                                <span data-text="ACCESSORIES">ACCESSORIES</span>
                                                            </span>
                                                    </a>
                                                </div>
                                            </div>
                                            <h4 class="title"><a href="post-details.html">The best accessories for your new iPad</a></h4>
                                        </div>
                                    </div>
                                    <!-- End Post Medium  -->

                                    <!-- Start Post Medium  -->
                                    <div class="content-block post-medium post-medium-border border-thin">
                                        <div class="post-thumbnail">
                                            <a href="post-details.html">
                                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-08.jpg" alt="Post Images">
                                            </a>
                                        </div>
                                        <div class="post-content">
                                            <div class="post-cat">
                                                <div class="post-cat-list">
                                                    <a class="hover-flip-item-wrapper" href="#">
                                                            <span class="hover-flip-item">
                                                                <span data-text="BUSINESS">BUSINESS</span>
                                                            </span>
                                                    </a>
                                                </div>
                                            </div>
                                            <h4 class="title"><a href="post-details.html">Chinese EV startups Byton and Nio received paycheck </a></h4>
                                        </div>
                                    </div>
                                    <!-- End Post Medium  -->

                                    <!-- Start Post Medium  -->
                                    <div class="content-block post-medium post-medium-border border-thin">
                                        <div class="post-thumbnail">
                                            <a href="post-details.html">
                                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-10.jpg" alt="Post Images">
                                            </a>
                                        </div>
                                        <div class="post-content">
                                            <div class="post-cat">
                                                <div class="post-cat-list">
                                                    <a class="hover-flip-item-wrapper" href="#">
                                                            <span class="hover-flip-item">
                                                                <span data-text="GOOGLE">GOOGLE</span>
                                                            </span>
                                                    </a>
                                                </div>
                                            </div>
                                            <h4 class="title"><a href="post-details.html">How to personalize your Google Chrome homepage with GIF</a></h4>
                                        </div>
                                    </div>
                                    <!-- End Post Medium  -->

                                </div>
                                <div class="col-xl-7 col-lg-6 col-md-12 col-12 mt_md--40 mt_sm--40">
                                    <!-- Start Post Grid  -->
                                    <div class="content-block content-block post-grid post-grid-transparent">
                                        <div class="post-thumbnail">
                                            <a href="post-details.html">
                                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-06.jpg" alt="Post Images">
                                            </a>
                                        </div>
                                        <div class="post-grid-content">
                                            <div class="post-content">
                                                <div class="post-cat">
                                                    <div class="post-cat-list">
                                                        <a class="hover-flip-item-wrapper" href="#">
                                                                <span class="hover-flip-item">
                                                                    <span data-text="APPLE">APPLE</span>
                                                                </span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <h3 class="title"><a href="post-details.html">iPhone SE is discontinued, but these retailers are still selling it</a></h3>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Start Post Grid  -->
                                </div>
                            </div>
                        </div>
                        <!-- End Single Tab Content  -->

                        <!-- Start Single Tab Content  -->
                        <div class="single-post-grid tab-pane fade" id="gridthree" role="tabpanel">
                            <div class="row  mt--40">
                                <div class="col-xl-5 col-lg-6 col-md-12 col-12">
                                    <!-- Start Post Medium  -->
                                    <div class="content-block post-medium post-medium-border border-thin">
                                        <div class="post-thumbnail">
                                            <a href="post-details.html">
                                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-07.jpg" alt="Post Images">
                                            </a>
                                        </div>
                                        <div class="post-content">
                                            <div class="post-cat">
                                                <div class="post-cat-list">
                                                    <a class="hover-flip-item-wrapper" href="#">
                                                            <span class="hover-flip-item">
                                                                <span data-text="MOBILE">MOBILE</span>
                                                            </span>
                                                    </a>
                                                </div>
                                            </div>
                                            <h4 class="title"><a href="post-details.html">OnePlus Nord hands-on: Strong features at a tempting price</a></h4>
                                        </div>
                                    </div>
                                    <!-- End Post Medium  -->

                                    <!-- Start Post Medium  -->
                                    <div class="content-block post-medium post-medium-border border-thin">
                                        <div class="post-thumbnail">
                                            <a href="post-details.html">
                                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-09.jpg" alt="Post Images">
                                            </a>
                                        </div>
                                        <div class="post-content">
                                            <div class="post-cat">
                                                <div class="post-cat-list">
                                                    <a class="hover-flip-item-wrapper" href="#">
                                                            <span class="hover-flip-item">
                                                                <span data-text="ACCESSORIES">ACCESSORIES</span>
                                                            </span>
                                                    </a>
                                                </div>
                                            </div>
                                            <h4 class="title"><a href="post-details.html">The best accessories for your new iPad</a></h4>
                                        </div>
                                    </div>
                                    <!-- End Post Medium  -->

                                    <!-- Start Post Medium  -->
                                    <div class="content-block post-medium post-medium-border border-thin">
                                        <div class="post-thumbnail">
                                            <a href="post-details.html">
                                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-08.jpg" alt="Post Images">
                                            </a>
                                        </div>
                                        <div class="post-content">
                                            <div class="post-cat">
                                                <div class="post-cat-list">
                                                    <a class="hover-flip-item-wrapper" href="#">
                                                            <span class="hover-flip-item">
                                                                <span data-text="BUSINESS">BUSINESS</span>
                                                            </span>
                                                    </a>
                                                </div>
                                            </div>
                                            <h4 class="title"><a href="post-details.html">Chinese EV startups Byton and Nio received paycheck </a></h4>
                                        </div>
                                    </div>
                                    <!-- End Post Medium  -->

                                    <!-- Start Post Medium  -->
                                    <div class="content-block post-medium post-medium-border border-thin">
                                        <div class="post-thumbnail">
                                            <a href="post-details.html">
                                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-10.jpg" alt="Post Images">
                                            </a>
                                        </div>
                                        <div class="post-content">
                                            <div class="post-cat">
                                                <div class="post-cat-list">
                                                    <a class="hover-flip-item-wrapper" href="#">
                                                            <span class="hover-flip-item">
                                                                <span data-text="GOOGLE">GOOGLE</span>
                                                            </span>
                                                    </a>
                                                </div>
                                            </div>
                                            <h4 class="title"><a href="post-details.html">How to personalize your Google Chrome homepage with GIF</a></h4>
                                        </div>
                                    </div>
                                    <!-- End Post Medium  -->

                                </div>
                                <div class="col-xl-7 col-lg-6 col-md-12 col-12 mt_md--40 mt_sm--40">
                                    <!-- Start Post Grid  -->
                                    <div class="content-block content-block post-grid post-grid-transparent">
                                        <div class="post-thumbnail">
                                            <a href="post-details.html">
                                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-06.jpg" alt="Post Images">
                                            </a>
                                        </div>
                                        <div class="post-grid-content">
                                            <div class="post-content">
                                                <div class="post-cat">
                                                    <div class="post-cat-list">
                                                        <a class="hover-flip-item-wrapper" href="#">
                                                                <span class="hover-flip-item">
                                                                    <span data-text="APPLE">APPLE</span>
                                                                </span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <h3 class="title"><a href="post-details.html">iPhone SE is discontinued, but these retailers are still selling it</a></h3>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Start Post Grid  -->
                                </div>
                            </div>
                        </div>
                        <!-- End Single Tab Content  -->

                        <!-- Start Single Tab Content  -->
                        <div class="single-post-grid tab-pane fade" id="gridfour" role="tabpanel">
                            <div class="row  mt--40">
                                <div class="col-xl-5 col-lg-6 col-md-12 col-12">
                                    <!-- Start Post Medium  -->
                                    <div class="content-block post-medium post-medium-border border-thin">
                                        <div class="post-thumbnail">
                                            <a href="post-details.html">
                                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-07.jpg" alt="Post Images">
                                            </a>
                                        </div>
                                        <div class="post-content">
                                            <div class="post-cat">
                                                <div class="post-cat-list">
                                                    <a class="hover-flip-item-wrapper" href="#">
                                                            <span class="hover-flip-item">
                                                                <span data-text="MOBILE">MOBILE</span>
                                                            </span>
                                                    </a>
                                                </div>
                                            </div>
                                            <h4 class="title"><a href="post-details.html">OnePlus Nord hands-on: Strong features at a tempting price</a></h4>
                                        </div>
                                    </div>
                                    <!-- End Post Medium  -->

                                    <!-- Start Post Medium  -->
                                    <div class="content-block post-medium post-medium-border border-thin">
                                        <div class="post-thumbnail">
                                            <a href="post-details.html">
                                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-09.jpg" alt="Post Images">
                                            </a>
                                        </div>
                                        <div class="post-content">
                                            <div class="post-cat">
                                                <div class="post-cat-list">
                                                    <a class="hover-flip-item-wrapper" href="#">
                                                            <span class="hover-flip-item">
                                                                <span data-text="ACCESSORIES">ACCESSORIES</span>
                                                            </span>
                                                    </a>
                                                </div>
                                            </div>
                                            <h4 class="title"><a href="post-details.html">The best accessories for your new iPad</a></h4>
                                        </div>
                                    </div>
                                    <!-- End Post Medium  -->

                                    <!-- Start Post Medium  -->
                                    <div class="content-block post-medium post-medium-border border-thin">
                                        <div class="post-thumbnail">
                                            <a href="post-details.html">
                                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-08.jpg" alt="Post Images">
                                            </a>
                                        </div>
                                        <div class="post-content">
                                            <div class="post-cat">
                                                <div class="post-cat-list">
                                                    <a class="hover-flip-item-wrapper" href="#">
                                                            <span class="hover-flip-item">
                                                                <span data-text="BUSINESS">BUSINESS</span>
                                                            </span>
                                                    </a>
                                                </div>
                                            </div>
                                            <h4 class="title"><a href="post-details.html">Chinese EV startups Byton and Nio received paycheck </a></h4>
                                        </div>
                                    </div>
                                    <!-- End Post Medium  -->

                                    <!-- Start Post Medium  -->
                                    <div class="content-block post-medium post-medium-border border-thin">
                                        <div class="post-thumbnail">
                                            <a href="post-details.html">
                                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-10.jpg" alt="Post Images">
                                            </a>
                                        </div>
                                        <div class="post-content">
                                            <div class="post-cat">
                                                <div class="post-cat-list">
                                                    <a class="hover-flip-item-wrapper" href="#">
                                                            <span class="hover-flip-item">
                                                                <span data-text="GOOGLE">GOOGLE</span>
                                                            </span>
                                                    </a>
                                                </div>
                                            </div>
                                            <h4 class="title"><a href="post-details.html">How to personalize your Google Chrome homepage with GIF</a></h4>
                                        </div>
                                    </div>
                                    <!-- End Post Medium  -->

                                </div>
                                <div class="col-xl-7 col-lg-6 col-md-12 col-12 mt_md--40 mt_sm--40">
                                    <!-- Start Post Grid  -->
                                    <div class="content-block content-block post-grid post-grid-transparent">
                                        <div class="post-thumbnail">
                                            <a href="post-details.html">
                                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-06.jpg" alt="Post Images">
                                            </a>
                                        </div>
                                        <div class="post-grid-content">
                                            <div class="post-content">
                                                <div class="post-cat">
                                                    <div class="post-cat-list">
                                                        <a class="hover-flip-item-wrapper" href="#">
                                                                <span class="hover-flip-item">
                                                                    <span data-text="APPLE">APPLE</span>
                                                                </span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <h3 class="title"><a href="post-details.html">iPhone SE is discontinued, but these retailers are still selling it</a></h3>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Start Post Grid  -->
                                </div>
                            </div>
                        </div>
                        <!-- End Single Tab Content  -->

                    </div>
                    <!-- End Tab Content  -->
                </div>
            </div>

        </div>
    </div>
    <!-- End Post Grid Area  -->

    <!-- Start Video Area  -->
    <div class="axil-video-post-area axil-section-gap bg-color-black">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2 class="title">Featured Video</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-12 col-md-6 col-12">
                    <!-- Start Post List  -->
                    <div class="content-block post-default image-rounded mt--30">
                        <div class="post-thumbnail">
                            <a href="post-details.html">
                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-dark-01.jpg" alt="Post Images">
                            </a>
                            <a class="video-popup position-top-center" href="post-details.html"><span
                                        class="play-icon"></span></a>
                        </div>
                        <div class="post-content">
                            <div class="post-cat">
                                <div class="post-cat-list">
                                    <a class="hover-flip-item-wrapper" href="#">
                                            <span class="hover-flip-item">
                                                <span data-text="CAREERS">CAREERS</span>
                                            </span>
                                    </a>
                                </div>
                            </div>
                            <h3 class="title"><a href="post-details.html">Security isn’t just a technology problem
                                    it’s about design, too</a></h3>
                            <div class="post-meta-wrapper">
                                <div class="post-meta">
                                    <div class="content">
                                        <h6 class="post-author-name">
                                            <a class="hover-flip-item-wrapper" href="author.html">
                                                    <span class="hover-flip-item">
                                                        <span data-text="Rafayel Hasan">Rafayel Hasan</span>
                                                    </span>
                                            </a>
                                        </h6>
                                        <ul class="post-meta-list">
                                            <li>Feb 17, 2019</li>
                                            <li>3 min read</li>
                                        </ul>
                                    </div>
                                </div>
                                <ul class="social-share-transparent justify-content-end">
                                    <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                    <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="#"><i class="fas fa-link"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- End Post List  -->
                </div>
                <div class="col-xl-6 col-lg-6 col-md-12 col-md-6 col-12">
                    <div class="row">
                        <!-- Start Post List  -->
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="content-block post-default image-rounded mt--30">
                                <div class="post-thumbnail">
                                    <a href="post-details.html">
                                        <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-dark-04.jpg" alt="Post Images">
                                    </a>
                                    <a class="video-popup size-medium position-top-center" href="post-details.html"><span class="play-icon"></span></a>
                                </div>
                                <div class="post-content">
                                    <div class="post-cat">
                                        <div class="post-cat-list">
                                            <a class="hover-flip-item-wrapper" href="#">
                                                    <span class="hover-flip-item">
                                                        <span data-text="COLLABORATION">COLLABORATION</span>
                                                    </span>
                                            </a>
                                        </div>
                                    </div>
                                    <h5 class="title"><a href="post-details.html">The 1 tool that helps remote teams
                                            collaborate better</a></h5>
                                </div>
                            </div>
                        </div>
                        <!-- End Post List  -->

                        <!-- Start Post List  -->
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="content-block post-default  image-rounded mt--30">
                                <div class="post-thumbnail">
                                    <a href="post-details.html">
                                        <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-dark-03.jpg" alt="Post Images">
                                    </a>
                                    <a class="video-popup size-medium position-top-center" href="post-details.html"><span class="play-icon"></span></a>
                                </div>
                                <div class="post-content">
                                    <div class="post-cat">
                                        <div class="post-cat-list">
                                            <a class="hover-flip-item-wrapper" href="#">
                                                    <span class="hover-flip-item">
                                                        <span data-text="DESIGN">DESIGN</span>
                                                    </span>
                                            </a>
                                        </div>
                                    </div>
                                    <h5 class="title"><a href="post-details.html">Get Ready To Up Your Creative Game With The</a></h5>
                                </div>
                            </div>
                        </div>
                        <!-- End Post List  -->

                        <!-- Start Post List  -->
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="content-block post-default image-rounded mt--30">
                                <div class="post-thumbnail">
                                    <a href="post-details.html">
                                        <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-dark-04.jpg" alt="Post Images">
                                    </a>
                                    <a class="video-popup size-medium position-top-center" href="post-details.html"><span class="play-icon"></span></a>
                                </div>
                                <div class="post-content">
                                    <div class="post-cat">
                                        <div class="post-cat-list">
                                            <a class="hover-flip-item-wrapper" href="#">
                                                    <span class="hover-flip-item">
                                                        <span data-text="LEADERSHIP">LEADERSHIP</span>
                                                    </span>
                                            </a>
                                        </div>
                                    </div>
                                    <h5 class="title"><a href="post-details.html">Traditional design won’t save us in the COVID-19 era</a></h5>
                                </div>
                            </div>
                        </div>
                        <!-- End Post List  -->

                        <!-- Start Post List  -->
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="content-block post-default image-rounded mt--30">
                                <div class="post-thumbnail">
                                    <a href="post-details.html">
                                        <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-dark-05.jpg" alt="Post Images">
                                    </a>
                                    <a class="video-popup size-medium position-top-center" href="post-details.html"><span class="play-icon"></span></a>
                                </div>
                                <div class="post-content">
                                    <div class="post-cat">
                                        <div class="post-cat-list">
                                            <a class="hover-flip-item-wrapper" href="#">
                                                    <span class="hover-flip-item">
                                                        <span data-text="PRODUCT UPDATES">PRODUCT UPDATES</span>
                                                    </span>
                                            </a>
                                        </div>
                                    </div>
                                    <h5 class="title"><a href="post-details.html">New: Freehand Templates, built for the whole team</a></h5>
                                </div>
                            </div>
                        </div>
                        <!-- End Post List  -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Video Area  -->

    <!-- Start Post List Wrapper  -->
    <div class="axil-post-list-area post-listview-visible-color axil-section-gap bg-color-white">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-xl-8">
                    <div class="axil-banner">
                        <div class="thumbnail">
                            <a href="#">
                                <img class="w-100" src="<?=base_url($configs->templatePath)?>/images/add-banner/banner-01.png" alt="Banner Images">
                            </a>
                        </div>
                    </div>
                    <!-- Start Post List  -->
                    <div class="content-block post-list-view is-active mt--30">
                        <div class="post-thumbnail">
                            <a href="post-details.html">
                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-test-tech-01.jpg" alt="Post Images">
                            </a>
                        </div>
                        <div class="post-content">
                            <div class="post-cat">
                                <div class="post-cat-list">
                                    <a class="hover-flip-item-wrapper" href="#">
                                            <span class="hover-flip-item">
                                                <span data-text="DESIGN">DESIGN</span>
                                            </span>
                                    </a>
                                </div>
                            </div>
                            <h4 class="title"><a href="post-details.html">New: Freehand Templates, built for the whole team</a></h4>
                            <div class="post-meta-wrapper">
                                <div class="post-meta">
                                    <div class="content">
                                        <h6 class="post-author-name">
                                            <a class="hover-flip-item-wrapper" href="author.html">
                                                    <span class="hover-flip-item">
                                                        <span data-text="Nusrat Ara">Nusrat Ara</span>
                                                    </span>
                                            </a>
                                        </h6>
                                        <ul class="post-meta-list">
                                            <li>Feb 17, 2019</li>
                                            <li>3 min read</li>
                                        </ul>
                                    </div>
                                </div>
                                <ul class="social-share-transparent justify-content-end">
                                    <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                    <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="#"><i class="fas fa-link"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- End Post List  -->

                    <!-- Start Post List  -->
                    <div class="content-block post-list-view axil-control mt--30">
                        <div class="post-thumbnail">
                            <a href="post-details.html">
                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-test-tech-02.jpg" alt="Post Images">
                            </a>
                        </div>
                        <div class="post-content">
                            <div class="post-cat">
                                <div class="post-cat-list">
                                    <a class="hover-flip-item-wrapper" href="#">
                                            <span class="hover-flip-item">
                                                <span data-text="CREATIVE">CREATIVE</span>
                                            </span>
                                    </a>
                                </div>
                            </div>
                            <h4 class="title"><a href="post-details.html">Get Ready To Up Your Creative Game With The New DJI Mavic Air 2</a></h4>
                            <div class="post-meta-wrapper">
                                <div class="post-meta">
                                    <div class="content">
                                        <h6 class="post-author-name">
                                            <a class="hover-flip-item-wrapper" href="author.html">
                                                    <span class="hover-flip-item">
                                                        <span data-text="Cameron Williamson">Cameron Williamson</span>
                                                    </span>
                                            </a>
                                        </h6>
                                        <ul class="post-meta-list">
                                            <li>Feb 17, 2019</li>
                                            <li>3 min read</li>
                                        </ul>
                                    </div>
                                </div>
                                <ul class="social-share-transparent justify-content-end">
                                    <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                    <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="#"><i class="fas fa-link"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- End Post List  -->

                    <!-- Start Post List  -->
                    <div class="content-block post-list-view axil-control mt--30">
                        <div class="post-thumbnail">
                            <a href="post-details.html">
                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-test-tech-03.jpg" alt="Post Images">
                            </a>
                        </div>
                        <div class="post-content">
                            <div class="post-cat">
                                <div class="post-cat-list">
                                    <a class="hover-flip-item-wrapper" href="#">
                                            <span class="hover-flip-item">
                                                <span data-text="TRAVEL">TRAVEL</span>
                                            </span>
                                    </a>
                                </div>
                            </div>
                            <h4 class="title"><a href="post-details.html">Traditional design won’t save us in the COVID-19 era</a></h4>
                            <div class="post-meta-wrapper">
                                <div class="post-meta">
                                    <div class="content">
                                        <h6 class="post-author-name">
                                            <a class="hover-flip-item-wrapper" href="author.html">
                                                    <span class="hover-flip-item">
                                                        <span data-text="Esther Howard">Esther Howard</span>
                                                    </span>
                                            </a>
                                        </h6>
                                        <ul class="post-meta-list">
                                            <li>Feb 17, 2019</li>
                                            <li>3 min read</li>
                                        </ul>
                                    </div>
                                </div>
                                <ul class="social-share-transparent justify-content-end">
                                    <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                    <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="#"><i class="fas fa-link"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- End Post List  -->

                    <!-- Start Post List  -->
                    <div class="content-block post-list-view axil-control mt--30">
                        <div class="post-thumbnail">
                            <a href="post-details.html">
                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-test-tech-04.jpg" alt="Post Images">
                            </a>
                        </div>
                        <div class="post-content">
                            <div class="post-cat">
                                <div class="post-cat-list">
                                    <a class="hover-flip-item-wrapper" href="#">
                                            <span class="hover-flip-item">
                                                <span data-text="GADGET">GADGET</span>
                                            </span>
                                    </a>
                                </div>
                            </div>
                            <h4 class="title"><a href="post-details.html">One of the most portable drones in the market.</a></h4>
                            <div class="post-meta-wrapper">
                                <div class="post-meta">
                                    <div class="content">
                                        <h6 class="post-author-name">
                                            <a class="hover-flip-item-wrapper" href="author.html">
                                                    <span class="hover-flip-item">
                                                        <span data-text="Jane Cooper">Jane Cooper</span>
                                                    </span>
                                            </a>
                                        </h6>
                                        <ul class="post-meta-list">
                                            <li>Feb 17, 2019</li>
                                            <li>3 min read</li>
                                        </ul>
                                    </div>
                                </div>
                                <ul class="social-share-transparent justify-content-end">
                                    <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                    <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="#"><i class="fas fa-link"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- End Post List  -->

                    <!-- Start Post List  -->
                    <div class="content-block post-list-view axil-control mt--30">
                        <div class="post-thumbnail">
                            <a href="post-details.html">
                                <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-test-tech-05.jpg" alt="Post Images">
                            </a>
                        </div>
                        <div class="post-content">
                            <div class="post-cat">
                                <div class="post-cat-list">
                                    <a class="hover-flip-item-wrapper" href="#">
                                            <span class="hover-flip-item">
                                                <span data-text="LIFESTYLE">LIFESTYLE</span>
                                            </span>
                                    </a>
                                </div>
                            </div>
                            <h4 class="title"><a href="post-details.html">The 1 tool that helps remote teams collaborate better</a></h4>
                            <div class="post-meta-wrapper">
                                <div class="post-meta">
                                    <div class="content">
                                        <h6 class="post-author-name">
                                            <a class="hover-flip-item-wrapper" href="author.html">
                                                    <span class="hover-flip-item">
                                                        <span data-text="Wade Warren">Wade Warren</span>
                                                    </span>
                                            </a>
                                        </h6>
                                        <ul class="post-meta-list">
                                            <li>Feb 17, 2019</li>
                                            <li>3 min read</li>
                                        </ul>
                                    </div>
                                </div>
                                <ul class="social-share-transparent justify-content-end">
                                    <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                    <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="#"><i class="fas fa-link"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- End Post List  -->

                </div>
                <div class="col-lg-4 col-xl-4 mt_md--40 mt_sm--40">
                    <!-- Start Sidebar Area  -->
                    <div class="sidebar-inner">

                        <!-- Start Single Widget  -->
                        <div class="axil-single-widget widget widget_categories mb--30">
                            <ul>
                                <li class="cat-item">
                                    <a href="#" class="inner">
                                        <div class="thumbnail">
                                            <img src="<?=base_url($configs->templatePath)?>/images/post-images/category-image-01.jpg" alt="">
                                        </div>
                                        <div class="content">
                                            <h5 class="title">Tech</h5>
                                        </div>
                                    </a>
                                </li>
                                <li class="cat-item">
                                    <a href="#" class="inner">
                                        <div class="thumbnail">
                                            <img src="<?=base_url($configs->templatePath)?>/images/post-images/category-image-02.jpg" alt="">
                                        </div>
                                        <div class="content">
                                            <h5 class="title">Style</h5>
                                        </div>
                                    </a>
                                </li>
                                <li class="cat-item">
                                    <a href="#" class="inner">
                                        <div class="thumbnail">
                                            <img src="<?=base_url($configs->templatePath)?>/images/post-images/category-image-03.jpg" alt="">
                                        </div>
                                        <div class="content">
                                            <h5 class="title">Travel</h5>
                                        </div>
                                    </a>
                                </li>
                                <li class="cat-item">
                                    <a href="#" class="inner">
                                        <div class="thumbnail">
                                            <img src="<?=base_url($configs->templatePath)?>/images/post-images/category-image-04.jpg" alt="">
                                        </div>
                                        <div class="content">
                                            <h5 class="title">Food</h5>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- End Single Widget  -->

                        <!-- Start Single Widget  -->
                        <div class="axil-single-widget widget widget_search mb--30">
                            <h5 class="widget-title">Search</h5>
                            <form action="#">
                                <div class="axil-search form-group">
                                    <button type="submit" class="search-button"><i class="fal fa-search"></i></button>
                                    <input type="text" class="form-control" placeholder="Search">
                                </div>
                            </form>
                        </div>
                        <!-- End Single Widget  -->

                        <!-- Start Single Widget  -->
                        <div class="axil-single-widget widget widget_postlist mb--30">
                            <h5 class="widget-title">Popular on Blogar</h5>
                            <!-- Start Post List  -->
                            <div class="post-medium-block">

                                <!-- Start Single Post  -->
                                <div class="content-block post-medium mb--20">
                                    <div class="post-thumbnail">
                                        <a href="post-details.html">
                                            <img src="<?=base_url($configs->templatePath)?>/images/small-images/blog-sm-01.jpg" alt="Post Images">
                                        </a>
                                    </div>
                                    <div class="post-content">
                                        <h6 class="title"><a href="post-details.html">The underrated design book that transformed the way I
                                                work </a></h6>
                                        <div class="post-meta">
                                            <ul class="post-meta-list">
                                                <li>Feb 17, 2019</li>
                                                <li>300k Views</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Single Post  -->

                                <!-- Start Single Post  -->
                                <div class="content-block post-medium mb--20">
                                    <div class="post-thumbnail">
                                        <a href="post-details.html">
                                            <img src="<?=base_url($configs->templatePath)?>/images/small-images/blog-sm-02.jpg" alt="Post Images">
                                        </a>
                                    </div>
                                    <div class="post-content">
                                        <h6 class="title"><a href="post-details.html">Here’s what you should (and shouldn’t) do when</a>
                                        </h6>
                                        <div class="post-meta">
                                            <ul class="post-meta-list">
                                                <li>Feb 17, 2019</li>
                                                <li>300k Views</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Single Post  -->

                                <!-- Start Single Post  -->
                                <div class="content-block post-medium mb--20">
                                    <div class="post-thumbnail">
                                        <a href="post-details.html">
                                            <img src="<?=base_url($configs->templatePath)?>/images/small-images/blog-sm-03.jpg" alt="Post Images">
                                        </a>
                                    </div>
                                    <div class="post-content">
                                        <h6 class="title"><a href="post-details.html">How a developer and designer duo at Deutsche Bank keep
                                                remote</a></h6>
                                        <div class="post-meta">
                                            <ul class="post-meta-list">
                                                <li>Feb 17, 2019</li>
                                                <li>300k Views</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Single Post  -->

                            </div>
                            <!-- End Post List  -->

                        </div>
                        <!-- End Single Widget  -->

                        <!-- Start Single Widget  -->
                        <div class="axil-single-widget widget widget_social mb--30">
                            <h5 class="widget-title">Stay In Touch</h5>
                            <!-- Start Post List  -->
                            <ul class="social-icon md-size justify-content-center">
                                <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fab fa-slack"></i></a></li>
                                <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                            </ul>
                            <!-- End Post List  -->
                        </div>
                        <!-- End Single Widget  -->

                        <!-- Start Single Widget  -->
                        <div class="axil-single-widget widget widget_instagram mb--30">
                            <h5 class="widget-title">Instagram</h5>
                            <!-- Start Post List  -->
                            <ul class="instagram-post-list-wrapper">
                                <li class="instagram-post-list">
                                    <a href="#">
                                        <img src="<?=base_url($configs->templatePath)?>/images/small-images/instagram-01.jpg" alt="Instagram Images">
                                    </a>
                                </li>
                                <li class="instagram-post-list">
                                    <a href="#">
                                        <img src="<?=base_url($configs->templatePath)?>/images/small-images/instagram-02.jpg" alt="Instagram Images">
                                    </a>
                                </li>
                                <li class="instagram-post-list">
                                    <a href="#">
                                        <img src="<?=base_url($configs->templatePath)?>/images/small-images/instagram-03.jpg" alt="Instagram Images">
                                    </a>
                                </li>
                                <li class="instagram-post-list">
                                    <a href="#">
                                        <img src="<?=base_url($configs->templatePath)?>/images/small-images/instagram-04.jpg" alt="Instagram Images">
                                    </a>
                                </li>
                                <li class="instagram-post-list">
                                    <a href="#">
                                        <img src="<?=base_url($configs->templatePath)?>/images/small-images/instagram-05.jpg" alt="Instagram Images">
                                    </a>
                                </li>
                                <li class="instagram-post-list">
                                    <a href="#">
                                        <img src="<?=base_url($configs->templatePath)?>/images/small-images/instagram-06.jpg" alt="Instagram Images">
                                    </a>
                                </li>
                            </ul>
                            <!-- End Post List  -->
                        </div>
                        <!-- End Single Widget  -->
                    </div>
                    <!-- End Sidebar Area  -->



                </div>
            </div>
        </div>
    </div>
    <!-- End Post List Wrapper  -->


    <!-- Start Review Wrapper  -->
    <div class="axil-review-area post-listview-visible-color axil-section-gap bg-color-grey">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2 class="title">Reviews</h2>
                    </div>
                </div>
                <div class="col-lg-12">
                    <ul class="axil-tab-button nav nav-tabs mt--20" id="reviewTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="review-one" data-bs-toggle="tab" href="#reviewone" role="tab" aria-controls="review-one" aria-selected="true">Phone</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="review-two" data-bs-toggle="tab" href="#reviewtwo" role="tab" aria-controls="review-two" aria-selected="false">Laptops</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="review-three" data-bs-toggle="tab" href="#reviewthree" role="tab" aria-controls="review-three" aria-selected="false">Headphones</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="row tab-content">
                <div class="col-lg-12 tab-pane fade show active" id="reviewone" role="tabpanel" aria-labelledby="review-one">
                    <div class="row">
                        <!-- Start Featured Post  -->
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="featured-post mt--30">
                                        <div class="content-block">
                                            <div class="post-content">
                                                <div class="post-cat">
                                                    <div class="post-cat-list">
                                                        <a class="hover-flip-item-wrapper" href="#">
                                                                <span class="hover-flip-item">
                                                                    <span data-text="FEATURED POST">FEATURED POST</span>
                                                                </span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <h4 class="title"><a href="post-details.html">Oppo Find X2 Pro Review: Supercar Smartphone</a></h4>
                                            </div>
                                            <div class="post-thumbnail">
                                                <a href="#">
                                                    <img src="<?=base_url($configs->templatePath)?>/images/post-single/featured-post-01.jpg" alt="Post Images">
                                                    <div class="review-count">
                                                        <span>8.1</span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="featured-post mt--30">
                                        <div class="content-block">
                                            <div class="post-content">
                                                <div class="post-cat">
                                                    <div class="post-cat-list">
                                                        <a class="hover-flip-item-wrapper" href="#">
                                                                <span class="hover-flip-item">
                                                                    <span data-text="FEATURED POST">FEATURED POST</span>
                                                                </span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <h4 class="title"><a href="post-details.html">The new Moto G Stylus and G Power are surprisingly adept cameraphones</a></h4>
                                            </div>
                                            <div class="post-thumbnail">
                                                <a href="#">
                                                    <img src="<?=base_url($configs->templatePath)?>/images/post-single/featured-post-02.jpg" alt="Post Images">
                                                    <div class="review-count">
                                                        <span>8</span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Featured Post  -->

                        <div class="col-lg-8 col-xl-8 mt--30">
                            <!-- Start Post List  -->
                            <div class="content-block post-list-view with-bg-solid mt--30">
                                <div class="post-thumbnail">
                                    <a href="post-details.html">
                                        <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-11.jpg" alt="Post Images">
                                        <div class="review-count">
                                            <span>7.4</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="post-content">
                                    <div class="post-cat">
                                        <div class="post-cat-list">
                                            <a class="hover-flip-item-wrapper" href="#">
                                                    <span class="hover-flip-item">
                                                        <span data-text="MOBILE">MOBILE</span>
                                                    </span>
                                            </a>
                                        </div>
                                    </div>
                                    <h4 class="title"><a href="post-details.html">Motorola Moto E (2020) and Moto G Fast review: smartphone basics</a></h4>
                                    <div class="post-meta-wrapper">
                                        <div class="post-meta">
                                            <div class="content">
                                                <h6 class="post-author-name">
                                                    <a class="hover-flip-item-wrapper" href="author.html">
                                                            <span class="hover-flip-item">
                                                                <span data-text="Nusrat Isra">Nusrat Isra</span>
                                                            </span>
                                                    </a>
                                                </h6>
                                                <ul class="post-meta-list">
                                                    <li>Feb 17, 2019</li>
                                                    <li>3 min read</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <ul class="social-share-transparent justify-content-end">
                                            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                            <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                            <li><a href="#"><i class="fas fa-link"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- End Post List  -->

                            <!-- Start Post List  -->
                            <div class="content-block post-list-view with-bg-solid mt--30">
                                <div class="post-thumbnail">
                                    <a href="post-details.html">
                                        <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-12.jpg" alt="Post Images">
                                        <div class="review-count">
                                            <span>5</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="post-content">
                                    <div class="post-cat">
                                        <div class="post-cat-list">
                                            <a class="hover-flip-item-wrapper" href="#">
                                                    <span class="hover-flip-item">
                                                        <span data-text="MOBILE">MOBILE</span>
                                                    </span>
                                            </a>
                                        </div>
                                    </div>
                                    <h4 class="title"><a href="post-details.html">Dell XPS 15 (2020) Review: New Design, Familiar Problems</a></h4>
                                    <div class="post-meta-wrapper">
                                        <div class="post-meta">
                                            <div class="content">
                                                <h6 class="post-author-name">
                                                    <a class="hover-flip-item-wrapper" href="author.html">
                                                            <span class="hover-flip-item">
                                                                <span data-text="Irin Pervin">Irin Pervin</span>
                                                            </span>
                                                    </a>
                                                </h6>
                                                <ul class="post-meta-list">
                                                    <li>Feb 17, 2019</li>
                                                    <li>3 min read</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <ul class="social-share-transparent justify-content-end">
                                            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                            <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                            <li><a href="#"><i class="fas fa-link"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- End Post List  -->

                            <!-- Start Post List  -->
                            <div class="content-block post-list-view with-bg-solid mt--30">
                                <div class="post-thumbnail">
                                    <a href="post-details.html">
                                        <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-13.jpg" alt="Post Images">
                                        <div class="review-count">
                                            <span>5</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="post-content">
                                    <div class="post-cat">
                                        <div class="post-cat-list">
                                            <a class="hover-flip-item-wrapper" href="#">
                                                    <span class="hover-flip-item">
                                                        <span data-text="MOBILE">MOBILE</span>
                                                    </span>
                                            </a>
                                        </div>
                                    </div>
                                    <h4 class="title"><a href="post-details.html">Sony’s Wf-sp800n Earbuds Are A Noise-canceling Alternative.</a></h4>
                                    <div class="post-meta-wrapper">
                                        <div class="post-meta">
                                            <div class="content">
                                                <h6 class="post-author-name">
                                                    <a class="hover-flip-item-wrapper" href="author.html">
                                                            <span class="hover-flip-item">
                                                                <span data-text="Rfa Islam">Rfa Islam</span>
                                                            </span>
                                                    </a>
                                                </h6>
                                                <ul class="post-meta-list">
                                                    <li>Feb 17, 2019</li>
                                                    <li>3 min read</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <ul class="social-share-transparent justify-content-end">
                                            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                            <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                            <li><a href="#"><i class="fas fa-link"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- End Post List  -->

                            <!-- Start Post List  -->
                            <div class="content-block post-list-view with-bg-solid mt--30">
                                <div class="post-thumbnail">
                                    <a href="post-details.html">
                                        <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-14.jpg" alt="Post Images">
                                        <div class="review-count">
                                            <span>5.5</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="post-content">
                                    <div class="post-cat">
                                        <div class="post-cat-list">
                                            <a class="hover-flip-item-wrapper" href="#">
                                                    <span class="hover-flip-item">
                                                        <span data-text="MOBILE">MOBILE</span>
                                                    </span>
                                            </a>
                                        </div>
                                    </div>
                                    <h4 class="title"><a href="post-details.html">Fujifilm’s Instax Mini LiPlay brings audio to the instant camera </a></h4>
                                    <div class="post-meta-wrapper">
                                        <div class="post-meta">
                                            <div class="content">
                                                <h6 class="post-author-name">
                                                    <a class="hover-flip-item-wrapper" href="author.html">
                                                            <span class="hover-flip-item">
                                                                <span data-text="Kanak Islam">Kanak Islam</span>
                                                            </span>
                                                    </a>
                                                </h6>
                                                <ul class="post-meta-list">
                                                    <li>Feb 17, 2019</li>
                                                    <li>3 min read</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <ul class="social-share-transparent justify-content-end">
                                            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                            <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                            <li><a href="#"><i class="fas fa-link"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- End Post List  -->


                        </div>

                        <!-- Start Sidebar  -->
                        <div class="col-lg-4 col-xl-4 mt--30 mt_md--40 mt_sm--40">
                            <div class="sidebar-inner">
                                <!-- Start Single Widget  -->
                                <div class="axil-single-widget widget-style-2 widget widget_categories">
                                    <ul>
                                        <li class="cat-item">
                                            <a href="#" class="inner">
                                                <div class="thumbnail">
                                                    <img src="<?=base_url($configs->templatePath)?>/images/post-images/category-image-01.jpg" alt="">
                                                </div>
                                                <div class="content">
                                                    <h5 class="title">Tech</h5>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="cat-item">
                                            <a href="#" class="inner">
                                                <div class="thumbnail">
                                                    <img src="<?=base_url($configs->templatePath)?>/images/post-images/category-image-02.jpg" alt="">
                                                </div>
                                                <div class="content">
                                                    <h5 class="title">Style</h5>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="cat-item">
                                            <a href="#" class="inner">
                                                <div class="thumbnail">
                                                    <img src="<?=base_url($configs->templatePath)?>/images/post-images/category-image-03.jpg" alt="">
                                                </div>
                                                <div class="content">
                                                    <h5 class="title">Travel</h5>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="cat-item">
                                            <a href="#" class="inner">
                                                <div class="thumbnail">
                                                    <img src="<?=base_url($configs->templatePath)?>/images/post-images/category-image-04.jpg" alt="">
                                                </div>
                                                <div class="content">
                                                    <h5 class="title">Food</h5>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <!-- End Single Widget  -->

                                <!-- Start Single Widget  -->
                                <div class="axil-single-widget widget-style-2 widget widget_post mt--30">
                                    <h5 class="widget-title">Featured Videos</h5>
                                    <div class="video-post-wrapepr">

                                        <!-- Start Single Post  -->
                                        <div class="content-block image-rounded">
                                            <div class="post-thumbnail">
                                                <a href="post-details.html">
                                                    <img src="<?=base_url($configs->templatePath)?>/images/post-single/post-md-01.jpg" alt="Post Images">
                                                </a>
                                                <a class="video-popup size-medium position-top-center icon-color-secondary" href="post-details.html"><span class="play-icon"></span></a>
                                            </div>
                                            <div class="post-content">
                                                <h6 class="title"><a href="post-details.html">With iOS 14, Apple is finally letting the iPhone home screen get complicated</a></h6>
                                            </div>
                                        </div>
                                        <!-- End Single Post  -->

                                        <!-- Start Single Post  -->
                                        <div class="content-block image-rounded mt--20">
                                            <div class="post-thumbnail">
                                                <a href="post-details.html">
                                                    <img src="<?=base_url($configs->templatePath)?>/images/post-single/post-md-02.jpg" alt="Post Images">
                                                </a>
                                                <a class="video-popup size-medium position-top-center icon-color-secondary" href="post-details.html"><span class="play-icon"></span></a>
                                            </div>
                                            <div class="post-content">
                                                <h6 class="title"><a href="post-details.html">A rocket launch can’t unite us until the space world acknowledges our divisions</a></h6>
                                            </div>
                                        </div>
                                        <!-- End Single Post  -->

                                        <!-- Start Single Post  -->
                                        <div class="content-block image-rounded mt--20">
                                            <div class="post-thumbnail">
                                                <a href="post-details.html">
                                                    <img src="<?=base_url($configs->templatePath)?>/images/post-single/post-md-03.jpg" alt="Post Images">
                                                </a>
                                                <a class="video-popup size-medium position-top-center icon-color-secondary" href="post-details.html"><span class="play-icon"></span></a>
                                            </div>
                                            <div class="post-content">
                                                <h6 class="title"><a href="post-details.html">I built my own camera with a Raspberry Pi 4</a></h6>
                                            </div>
                                        </div>
                                        <!-- End Single Post  -->

                                    </div>


                                </div>
                                <!-- End Single Widget  -->

                            </div>
                        </div>
                        <!-- End Sidebar  -->
                    </div>
                </div>

                <div class="col-lg-12 tab-pane fade" id="reviewtwo" role="tabpanel" aria-labelledby="review-two">
                    <div class="row">
                        <!-- Start Featured Post  -->
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="featured-post mt--30">
                                        <div class="content-block">
                                            <div class="post-content">
                                                <div class="post-cat">
                                                    <div class="post-cat-list">
                                                        <a class="hover-flip-item-wrapper" href="#">
                                                                <span class="hover-flip-item">
                                                                    <span data-text="FEATURED POST">FEATURED POST</span>
                                                                </span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <h4 class="title"><a href="post-details.html">Oppo Find X2 Pro Review: Supercar Smartphone</a></h4>
                                            </div>
                                            <div class="post-thumbnail">
                                                <a href="#">
                                                    <img src="<?=base_url($configs->templatePath)?>/images/post-single/featured-post-01.jpg" alt="Post Images">
                                                    <div class="review-count">
                                                        <span>8.1</span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="featured-post mt--30">
                                        <div class="content-block">
                                            <div class="post-content">
                                                <div class="post-cat">
                                                    <div class="post-cat-list">
                                                        <a class="hover-flip-item-wrapper" href="#">
                                                                <span class="hover-flip-item">
                                                                    <span data-text="FEATURED POST">FEATURED POST</span>
                                                                </span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <h4 class="title"><a href="post-details.html">The new Moto G Stylus and G Power are surprisingly adept cameraphones</a></h4>
                                            </div>
                                            <div class="post-thumbnail">
                                                <a href="#">
                                                    <img src="<?=base_url($configs->templatePath)?>/images/post-single/featured-post-02.jpg" alt="Post Images">
                                                    <div class="review-count">
                                                        <span>8</span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Featured Post  -->

                        <div class="col-lg-8 col-xl-8 mt--30">
                            <!-- Start Post List  -->
                            <div class="content-block post-list-view with-bg-solid mt--30">
                                <div class="post-thumbnail">
                                    <a href="post-details.html">
                                        <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-11.jpg" alt="Post Images">
                                        <div class="review-count">
                                            <span>7.4</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="post-content">
                                    <div class="post-cat">
                                        <div class="post-cat-list">
                                            <a class="hover-flip-item-wrapper" href="#">
                                                    <span class="hover-flip-item">
                                                        <span data-text="MOBILE">MOBILE</span>
                                                    </span>
                                            </a>
                                        </div>
                                    </div>
                                    <h4 class="title"><a href="post-details.html">Motorola Moto E (2020) and Moto G Fast review: smartphone basics</a></h4>
                                    <div class="post-meta-wrapper">
                                        <div class="post-meta">
                                            <div class="content">
                                                <h6 class="post-author-name">
                                                    <a class="hover-flip-item-wrapper" href="author.html">
                                                            <span class="hover-flip-item">
                                                                <span data-text="Nusrat Isra">Nusrat Isra</span>
                                                            </span>
                                                    </a>
                                                </h6>
                                                <ul class="post-meta-list">
                                                    <li>Feb 17, 2019</li>
                                                    <li>3 min read</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <ul class="social-share-transparent justify-content-end">
                                            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                            <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                            <li><a href="#"><i class="fas fa-link"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- End Post List  -->

                            <!-- Start Post List  -->
                            <div class="content-block post-list-view with-bg-solid mt--30">
                                <div class="post-thumbnail">
                                    <a href="post-details.html">
                                        <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-12.jpg" alt="Post Images">
                                        <div class="review-count">
                                            <span>5</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="post-content">
                                    <div class="post-cat">
                                        <div class="post-cat-list">
                                            <a class="hover-flip-item-wrapper" href="#">
                                                    <span class="hover-flip-item">
                                                        <span data-text="MOBILE">MOBILE</span>
                                                    </span>
                                            </a>
                                        </div>
                                    </div>
                                    <h4 class="title"><a href="post-details.html">Dell XPS 15 (2020) Review: New Design, Familiar Problems</a></h4>
                                    <div class="post-meta-wrapper">
                                        <div class="post-meta">
                                            <div class="content">
                                                <h6 class="post-author-name">
                                                    <a class="hover-flip-item-wrapper" href="author.html">
                                                            <span class="hover-flip-item">
                                                                <span data-text="Irin Pervin">Irin Pervin</span>
                                                            </span>
                                                    </a>
                                                </h6>
                                                <ul class="post-meta-list">
                                                    <li>Feb 17, 2019</li>
                                                    <li>3 min read</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <ul class="social-share-transparent justify-content-end">
                                            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                            <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                            <li><a href="#"><i class="fas fa-link"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- End Post List  -->

                            <!-- Start Post List  -->
                            <div class="content-block post-list-view with-bg-solid mt--30">
                                <div class="post-thumbnail">
                                    <a href="post-details.html">
                                        <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-13.jpg" alt="Post Images">
                                        <div class="review-count">
                                            <span>5</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="post-content">
                                    <div class="post-cat">
                                        <div class="post-cat-list">
                                            <a class="hover-flip-item-wrapper" href="#">
                                                    <span class="hover-flip-item">
                                                        <span data-text="MOBILE">MOBILE</span>
                                                    </span>
                                            </a>
                                        </div>
                                    </div>
                                    <h4 class="title"><a href="post-details.html">Sony’s Wf-sp800n Earbuds Are A Noise-canceling Alternative.</a></h4>
                                    <div class="post-meta-wrapper">
                                        <div class="post-meta">
                                            <div class="content">
                                                <h6 class="post-author-name">
                                                    <a class="hover-flip-item-wrapper" href="author.html">
                                                            <span class="hover-flip-item">
                                                                <span data-text="Rfa Islam">Rfa Islam</span>
                                                            </span>
                                                    </a>
                                                </h6>
                                                <ul class="post-meta-list">
                                                    <li>Feb 17, 2019</li>
                                                    <li>3 min read</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <ul class="social-share-transparent justify-content-end">
                                            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                            <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                            <li><a href="#"><i class="fas fa-link"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- End Post List  -->

                            <!-- Start Post List  -->
                            <div class="content-block post-list-view with-bg-solid mt--30">
                                <div class="post-thumbnail">
                                    <a href="post-details.html">
                                        <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-14.jpg" alt="Post Images">
                                        <div class="review-count">
                                            <span>5.5</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="post-content">
                                    <div class="post-cat">
                                        <div class="post-cat-list">
                                            <a class="hover-flip-item-wrapper" href="#">
                                                    <span class="hover-flip-item">
                                                        <span data-text="MOBILE">MOBILE</span>
                                                    </span>
                                            </a>
                                        </div>
                                    </div>
                                    <h4 class="title"><a href="post-details.html">Fujifilm’s Instax Mini LiPlay brings audio to the instant camera </a></h4>
                                    <div class="post-meta-wrapper">
                                        <div class="post-meta">
                                            <div class="content">
                                                <h6 class="post-author-name">
                                                    <a class="hover-flip-item-wrapper" href="author.html">
                                                            <span class="hover-flip-item">
                                                                <span data-text="Kanak Islam">Kanak Islam</span>
                                                            </span>
                                                    </a>
                                                </h6>
                                                <ul class="post-meta-list">
                                                    <li>Feb 17, 2019</li>
                                                    <li>3 min read</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <ul class="social-share-transparent justify-content-end">
                                            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                            <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                            <li><a href="#"><i class="fas fa-link"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- End Post List  -->


                        </div>

                        <!-- Start Sidebar  -->
                        <div class="col-lg-4 col-xl-4 mt--30 mt_md--40 mt_sm--40">
                            <div class="sidebar-inner">
                                <!-- Start Single Widget  -->
                                <div class="axil-single-widget widget-style-2 widget widget_categories">
                                    <ul>
                                        <li class="cat-item">
                                            <a href="#" class="inner">
                                                <div class="thumbnail">
                                                    <img src="<?=base_url($configs->templatePath)?>/images/post-images/category-image-01.jpg" alt="">
                                                </div>
                                                <div class="content">
                                                    <h5 class="title">Tech</h5>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="cat-item">
                                            <a href="#" class="inner">
                                                <div class="thumbnail">
                                                    <img src="<?=base_url($configs->templatePath)?>/images/post-images/category-image-02.jpg" alt="">
                                                </div>
                                                <div class="content">
                                                    <h5 class="title">Style</h5>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="cat-item">
                                            <a href="#" class="inner">
                                                <div class="thumbnail">
                                                    <img src="<?=base_url($configs->templatePath)?>/images/post-images/category-image-03.jpg" alt="">
                                                </div>
                                                <div class="content">
                                                    <h5 class="title">Travel</h5>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="cat-item">
                                            <a href="#" class="inner">
                                                <div class="thumbnail">
                                                    <img src="<?=base_url($configs->templatePath)?>/images/post-images/category-image-04.jpg" alt="">
                                                </div>
                                                <div class="content">
                                                    <h5 class="title">Food</h5>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <!-- End Single Widget  -->

                                <!-- Start Single Widget  -->
                                <div class="axil-single-widget widget-style-2 widget widget_post mt--30">
                                    <h5 class="widget-title">Featured Videos</h5>
                                    <div class="video-post-wrapepr">

                                        <!-- Start Single Post  -->
                                        <div class="content-block image-rounded">
                                            <div class="post-thumbnail">
                                                <a href="post-details.html">
                                                    <img src="<?=base_url($configs->templatePath)?>/images/post-single/post-md-01.jpg" alt="Post Images">
                                                </a>
                                                <a class="video-popup size-medium position-top-center icon-color-secondary" href="post-details.html"><span class="play-icon"></span></a>
                                            </div>
                                            <div class="post-content">
                                                <h6 class="title"><a href="post-details.html">With iOS 14, Apple is finally letting the iPhone home screen get complicated</a></h6>
                                            </div>
                                        </div>
                                        <!-- End Single Post  -->

                                        <!-- Start Single Post  -->
                                        <div class="content-block image-rounded mt--20">
                                            <div class="post-thumbnail">
                                                <a href="post-details.html">
                                                    <img src="<?=base_url($configs->templatePath)?>/images/post-single/post-md-02.jpg" alt="Post Images">
                                                </a>
                                                <a class="video-popup size-medium position-top-center icon-color-secondary" href="post-details.html"><span class="play-icon"></span></a>
                                            </div>
                                            <div class="post-content">
                                                <h6 class="title"><a href="post-details.html">A rocket launch can’t unite us until the space world acknowledges our divisions</a></h6>
                                            </div>
                                        </div>
                                        <!-- End Single Post  -->

                                        <!-- Start Single Post  -->
                                        <div class="content-block image-rounded mt--20">
                                            <div class="post-thumbnail">
                                                <a href="post-details.html">
                                                    <img src="<?=base_url($configs->templatePath)?>/images/post-single/post-md-03.jpg" alt="Post Images">
                                                </a>
                                                <a class="video-popup size-medium position-top-center icon-color-secondary" href="post-details.html"><span class="play-icon"></span></a>
                                            </div>
                                            <div class="post-content">
                                                <h6 class="title"><a href="post-details.html">I built my own camera with a Raspberry Pi 4</a></h6>
                                            </div>
                                        </div>
                                        <!-- End Single Post  -->

                                    </div>


                                </div>
                                <!-- End Single Widget  -->

                            </div>
                        </div>
                        <!-- End Sidebar  -->
                    </div>
                </div>

                <div class="col-lg-12 tab-pane fade" id="reviewthree" role="tabpanel" aria-labelledby="review-three">
                    <div class="row">
                        <!-- Start Featured Post  -->
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="featured-post mt--30">
                                        <div class="content-block">
                                            <div class="post-content">
                                                <div class="post-cat">
                                                    <div class="post-cat-list">
                                                        <a class="hover-flip-item-wrapper" href="#">
                                                                <span class="hover-flip-item">
                                                                    <span data-text="FEATURED POST">FEATURED POST</span>
                                                                </span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <h4 class="title"><a href="post-details.html">Oppo Find X2 Pro Review: Supercar Smartphone</a></h4>
                                            </div>
                                            <div class="post-thumbnail">
                                                <a href="#">
                                                    <img src="<?=base_url($configs->templatePath)?>/images/post-single/featured-post-01.jpg" alt="Post Images">
                                                    <div class="review-count">
                                                        <span>8.1</span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="featured-post mt--30">
                                        <div class="content-block">
                                            <div class="post-content">
                                                <div class="post-cat">
                                                    <div class="post-cat-list">
                                                        <a class="hover-flip-item-wrapper" href="#">
                                                                <span class="hover-flip-item">
                                                                    <span data-text="FEATURED POST">FEATURED POST</span>
                                                                </span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <h4 class="title"><a href="post-details.html">The new Moto G Stylus and G Power are surprisingly adept cameraphones</a></h4>
                                            </div>
                                            <div class="post-thumbnail">
                                                <a href="#">
                                                    <img src="<?=base_url($configs->templatePath)?>/images/post-single/featured-post-02.jpg" alt="Post Images">
                                                    <div class="review-count">
                                                        <span>8</span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Featured Post  -->

                        <div class="col-lg-8 col-xl-8 mt--30">
                            <!-- Start Post List  -->
                            <div class="content-block post-list-view with-bg-solid mt--30">
                                <div class="post-thumbnail">
                                    <a href="post-details.html">
                                        <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-11.jpg" alt="Post Images">
                                        <div class="review-count">
                                            <span>7.4</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="post-content">
                                    <div class="post-cat">
                                        <div class="post-cat-list">
                                            <a class="hover-flip-item-wrapper" href="#">
                                                    <span class="hover-flip-item">
                                                        <span data-text="MOBILE">MOBILE</span>
                                                    </span>
                                            </a>
                                        </div>
                                    </div>
                                    <h4 class="title"><a href="post-details.html">Motorola Moto E (2020) and Moto G Fast review: smartphone basics</a></h4>
                                    <div class="post-meta-wrapper">
                                        <div class="post-meta">
                                            <div class="content">
                                                <h6 class="post-author-name">
                                                    <a class="hover-flip-item-wrapper" href="author.html">
                                                            <span class="hover-flip-item">
                                                                <span data-text="Nusrat Isra">Nusrat Isra</span>
                                                            </span>
                                                    </a>
                                                </h6>
                                                <ul class="post-meta-list">
                                                    <li>Feb 17, 2019</li>
                                                    <li>3 min read</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <ul class="social-share-transparent justify-content-end">
                                            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                            <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                            <li><a href="#"><i class="fas fa-link"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- End Post List  -->

                            <!-- Start Post List  -->
                            <div class="content-block post-list-view with-bg-solid mt--30">
                                <div class="post-thumbnail">
                                    <a href="post-details.html">
                                        <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-12.jpg" alt="Post Images">
                                        <div class="review-count">
                                            <span>5</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="post-content">
                                    <div class="post-cat">
                                        <div class="post-cat-list">
                                            <a class="hover-flip-item-wrapper" href="#">
                                                    <span class="hover-flip-item">
                                                        <span data-text="MOBILE">MOBILE</span>
                                                    </span>
                                            </a>
                                        </div>
                                    </div>
                                    <h4 class="title"><a href="post-details.html">Dell XPS 15 (2020) Review: New Design, Familiar Problems</a></h4>
                                    <div class="post-meta-wrapper">
                                        <div class="post-meta">
                                            <div class="content">
                                                <h6 class="post-author-name">
                                                    <a class="hover-flip-item-wrapper" href="author.html">
                                                            <span class="hover-flip-item">
                                                                <span data-text="Irin Pervin">Irin Pervin</span>
                                                            </span>
                                                    </a>
                                                </h6>
                                                <ul class="post-meta-list">
                                                    <li>Feb 17, 2019</li>
                                                    <li>3 min read</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <ul class="social-share-transparent justify-content-end">
                                            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                            <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                            <li><a href="#"><i class="fas fa-link"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- End Post List  -->

                            <!-- Start Post List  -->
                            <div class="content-block post-list-view with-bg-solid mt--30">
                                <div class="post-thumbnail">
                                    <a href="post-details.html">
                                        <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-13.jpg" alt="Post Images">
                                        <div class="review-count">
                                            <span>5</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="post-content">
                                    <div class="post-cat">
                                        <div class="post-cat-list">
                                            <a class="hover-flip-item-wrapper" href="#">
                                                    <span class="hover-flip-item">
                                                        <span data-text="MOBILE">MOBILE</span>
                                                    </span>
                                            </a>
                                        </div>
                                    </div>
                                    <h4 class="title"><a href="post-details.html">Sony’s Wf-sp800n Earbuds Are A Noise-canceling Alternative.</a></h4>
                                    <div class="post-meta-wrapper">
                                        <div class="post-meta">
                                            <div class="content">
                                                <h6 class="post-author-name">
                                                    <a class="hover-flip-item-wrapper" href="author.html">
                                                            <span class="hover-flip-item">
                                                                <span data-text="Rfa Islam">Rfa Islam</span>
                                                            </span>
                                                    </a>
                                                </h6>
                                                <ul class="post-meta-list">
                                                    <li>Feb 17, 2019</li>
                                                    <li>3 min read</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <ul class="social-share-transparent justify-content-end">
                                            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                            <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                            <li><a href="#"><i class="fas fa-link"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- End Post List  -->

                            <!-- Start Post List  -->
                            <div class="content-block post-list-view with-bg-solid mt--30">
                                <div class="post-thumbnail">
                                    <a href="post-details.html">
                                        <img src="<?=base_url($configs->templatePath)?>/images/post-images/post-tech-14.jpg" alt="Post Images">
                                        <div class="review-count">
                                            <span>5.5</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="post-content">
                                    <div class="post-cat">
                                        <div class="post-cat-list">
                                            <a class="hover-flip-item-wrapper" href="#">
                                                    <span class="hover-flip-item">
                                                        <span data-text="MOBILE">MOBILE</span>
                                                    </span>
                                            </a>
                                        </div>
                                    </div>
                                    <h4 class="title"><a href="post-details.html">Fujifilm’s Instax Mini LiPlay brings audio to the instant camera </a></h4>
                                    <div class="post-meta-wrapper">
                                        <div class="post-meta">
                                            <div class="content">
                                                <h6 class="post-author-name">
                                                    <a class="hover-flip-item-wrapper" href="author.html">
                                                            <span class="hover-flip-item">
                                                                <span data-text="Kanak Islam">Kanak Islam</span>
                                                            </span>
                                                    </a>
                                                </h6>
                                                <ul class="post-meta-list">
                                                    <li>Feb 17, 2019</li>
                                                    <li>3 min read</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <ul class="social-share-transparent justify-content-end">
                                            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                            <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                            <li><a href="#"><i class="fas fa-link"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- End Post List  -->


                        </div>

                        <!-- Start Sidebar  -->
                        <div class="col-lg-4 col-xl-4 mt--30 mt_md--40 mt_sm--40">
                            <div class="sidebar-inner">
                                <!-- Start Single Widget  -->
                                <div class="axil-single-widget widget-style-2 widget widget_categories">
                                    <ul>
                                        <li class="cat-item">
                                            <a href="#" class="inner">
                                                <div class="thumbnail">
                                                    <img src="<?=base_url($configs->templatePath)?>/images/post-images/category-image-01.jpg" alt="">
                                                </div>
                                                <div class="content">
                                                    <h5 class="title">Tech</h5>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="cat-item">
                                            <a href="#" class="inner">
                                                <div class="thumbnail">
                                                    <img src="<?=base_url($configs->templatePath)?>/images/post-images/category-image-02.jpg" alt="">
                                                </div>
                                                <div class="content">
                                                    <h5 class="title">Style</h5>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="cat-item">
                                            <a href="#" class="inner">
                                                <div class="thumbnail">
                                                    <img src="<?=base_url($configs->templatePath)?>/images/post-images/category-image-03.jpg" alt="">
                                                </div>
                                                <div class="content">
                                                    <h5 class="title">Travel</h5>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="cat-item">
                                            <a href="#" class="inner">
                                                <div class="thumbnail">
                                                    <img src="<?=base_url($configs->templatePath)?>/images/post-images/category-image-04.jpg" alt="">
                                                </div>
                                                <div class="content">
                                                    <h5 class="title">Food</h5>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <!-- End Single Widget  -->

                                <!-- Start Single Widget  -->
                                <div class="axil-single-widget widget-style-2 widget widget_post mt--30">
                                    <h5 class="widget-title">Featured Videos</h5>
                                    <div class="video-post-wrapepr">

                                        <!-- Start Single Post  -->
                                        <div class="content-block image-rounded">
                                            <div class="post-thumbnail">
                                                <a href="post-details.html">
                                                    <img src="<?=base_url($configs->templatePath)?>/images/post-single/post-md-01.jpg" alt="Post Images">
                                                </a>
                                                <a class="video-popup size-medium position-top-center icon-color-secondary" href="post-details.html"><span class="play-icon"></span></a>
                                            </div>
                                            <div class="post-content">
                                                <h6 class="title"><a href="post-details.html">With iOS 14, Apple is finally letting the iPhone home screen get complicated</a></h6>
                                            </div>
                                        </div>
                                        <!-- End Single Post  -->

                                        <!-- Start Single Post  -->
                                        <div class="content-block image-rounded mt--20">
                                            <div class="post-thumbnail">
                                                <a href="post-details.html">
                                                    <img src="<?=base_url($configs->templatePath)?>/images/post-single/post-md-02.jpg" alt="Post Images">
                                                </a>
                                                <a class="video-popup size-medium position-top-center icon-color-secondary" href="post-details.html"><span class="play-icon"></span></a>
                                            </div>
                                            <div class="post-content">
                                                <h6 class="title"><a href="post-details.html">A rocket launch can’t unite us until the space world acknowledges our divisions</a></h6>
                                            </div>
                                        </div>
                                        <!-- End Single Post  -->

                                        <!-- Start Single Post  -->
                                        <div class="content-block image-rounded mt--20">
                                            <div class="post-thumbnail">
                                                <a href="post-details.html">
                                                    <img src="<?=base_url($configs->templatePath)?>/images/post-single/post-md-03.jpg" alt="Post Images">
                                                </a>
                                                <a class="video-popup size-medium position-top-center icon-color-secondary" href="post-details.html"><span class="play-icon"></span></a>
                                            </div>
                                            <div class="post-content">
                                                <h6 class="title"><a href="post-details.html">I built my own camera with a Raspberry Pi 4</a></h6>
                                            </div>
                                        </div>
                                        <!-- End Single Post  -->

                                    </div>


                                </div>
                                <!-- End Single Widget  -->

                            </div>
                        </div>
                        <!-- End Sidebar  -->
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="axil-banner mt--30">
                        <div class="thumbnail">
                            <a href="#">
                                <img class="w-100" src="<?=base_url($configs->templatePath)?>/images/add-banner/banner-03.png" alt="Banner Images">
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- End Review Wrapper  -->

<?= $this->endSection() ?>