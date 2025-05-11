<div class="modal fade modalDemo" id="quick_view">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="header">
                <span class="icon-close icon-close-popup" data-bs-dismiss="modal"></span>
            </div>
            <div class="wrap">
                <div class="tf-product-media-wrap">
                    <div dir="ltr" class="swiper tf-single-slide">
                        <div class="swiper-wrapper" >
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
                            <h5><a class="link" href="product-detail.html">Ribbed Tank Top</a></h5>
                        </div>
                        <!-- <div class="tf-product-info-badges">
                            <div class="badges text-uppercase">Best seller</div>
                            <div class="product-status-content">
                                <i class="icon-lightning"></i>
                                <p class="fw-6">Selling fast! 48 people have this in their carts.</p>
                            </div>
                        </div> -->
                        <div class="tf-product-info-price">
                            <div class="price">$18.00</div>
                        </div>
                        <div class="tf-product-description">
                            <p>Nunc arcu faucibus a et lorem eu a mauris adipiscing conubia ac aptent ligula facilisis a auctor habitant parturient a a.Interdum fermentum.</p>
                        </div>
                        <div class="tf-product-size">
                            <p><?=lang('Product.pd_size')?> : <span id="tmt_pd_size"></span></p>
                        </div>
                        <div class="tf-product-weight">
                            <p><?=lang('Product.pd_weight')?> : <span id="tmt_pd_weight"></span></p>
                        </div>
                        <div class="tf-product-cut_angle">
                            <p><?=lang('Product.pd_cut_angle')?> : <span id="tmt_pd_cut_angle"></span></p>
                        </div>

                        <div class="tf-product-info-buy-button">
                            <form class="">
                                
                                <div class="w-100">
                                    <a href="#" class="btns-full"><?=lang('Product.pay_now')?> <img src="<?=base_url($configs->templatePath.'assets/images/payments/paypal-mini.png')?>" alt="<?=$configs->theme_name?>"></a>
                                    <!-- <a href="#" class="payment-more-option">More payment options</a> -->
                                </div>
                            </form>
                        </div>
                        <div>
                            <a href="product-detail.html" class="tf-btn fw-6 btn-line">View full details<i class="icon icon-arrow1-top-left"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?=$this->section('scripts')?>
<script type="text/javascript" src="<?=base_url($configs->templatePath)?>assets/js/product-quick-view.js"></script>  
<?=$this->endSection()?>