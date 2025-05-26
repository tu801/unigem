<div class="modal fade modalDemo popup-quickview" id="quick_view">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="header" style="display: flex; align-items: center;">
                <span class="icon-close icon-close-popup" data-bs-dismiss="modal"></span>
            </div>
            <div class="tmt-spinner d-flex justify-content-center align-items-center w-100 py-5"
                style="min-height:300px; background:rgba(255,255,255,0.95); position:relative; z-index:10;">
                <img src="<?= base_url($configs->templatePath) ?>images/spinner.svg" alt="Loading...">
            </div>
            <div class="wrap tmt-wrap">
                <div class="tf-product-media-wrap">
                    <div dir="ltr" class="swiper tf-single-slide">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="item">
                                    <!-- <img src="images/products/orange-1.jpg" alt=""> -->
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="item">
                                    <!-- <img src="images/products/pink-1.jpg" alt=""> -->
                                </div>
                            </div>
                        </div>
                        <div class="swiper-button-next button-style-arrow single-slide-prev"></div>
                        <div class="swiper-button-prev button-style-arrow single-slide-next"></div>
                    </div>
                </div>
                <div class="tf-product-info-wrap position-relative">
                    <div class="tf-product-info-list">
                        <div class="tf-product-info-title">
                            <h5><a class="link" href="product-detail.html"></a></h5>
                        </div>
                        <!-- <div class="tf-product-info-badges">
                            <div class="badges text-uppercase">Best seller</div>
                            <div class="product-status-content">
                                <i class="icon-lightning"></i>
                                <p class="fw-6">Selling fast! 48 people have this in their carts.</p>
                            </div>
                        </div> -->
                        <div class="tf-product-info-price">
                            <div class="price"></div>
                        </div>
                        <div class="tf-product-description">
                            <p></p>
                        </div>
                        <div class="tf-product-size">
                            <p><?= lang('Product.pd_size') ?> : <span id="tmt_pd_size"></span></p>
                        </div>
                        <div class="tf-product-weight">
                            <p><?= lang('Product.pd_weight') ?> : <span id="tmt_pd_weight"></span></p>
                        </div>
                        <div class="tf-product-cut_angle">
                            <p><?= lang('Product.pd_cut_angle') ?> : <span id="tmt_pd_cut_angle"></span></p>
                        </div>

                        <div class="tf-product-info-buy-button">
                            <form class="">

                                <div class="w-100">
                                    <a href="#" class="btns-full">
                                        <span><?= lang('Product.buy_now') ?> -&nbsp;</span>
                                        <span class="tf-qty-price total-price"></span>

                                        <!-- <img
                                            src="<?= base_url($configs->templatePath . 'images/payments/paypal-mini.png') ?>"
                                            alt="<?= $configs->theme_name ?>">-->
                                    </a>
                                    <!-- <a href="#" class="payment-more-option">More payment options</a> -->
                                </div>
                            </form>
                        </div>
                        <div>
                            <a href="product-detail.html" class="tf-btn fw-6 btn-line">
                                <?= lang('Product.see_more')?>
                                <i class="icon icon-arrow1-top-left"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script type="text/javascript" src="<?= base_url($configs->templatePath) ?>js/product-quick-view.js"></script>
<?= $this->endSection() ?>