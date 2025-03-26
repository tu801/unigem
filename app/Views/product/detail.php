<?php
echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>
<!-- breadcrumbs -->
<?php echo view_cell('App\Libraries\BreadCrumb\BreadCrumbCell') ?>

<!-- product view -->
<div class="product_view_wrap section_padding_b">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="product_view_slider">
                    <?php if(isset($product->images->images)): foreach ($product->images->images as $item):  ?>
                        <div class="single_viewslider">
                            <img loading="lazy"  src="<?= base_url($item->full_image) ?>" alt="product">
                        </div>
                    <?php endforeach; endif; ?>
                </div>
                <div class="product_viewslid_nav">
                    <?php if(isset($product->images->images)): foreach ($product->images->images as $item): ?>
                        <div class="single_viewslid_nav">
                            <img loading="lazy"  src="<?= base_url($item->thumb_image) ?>" alt="product">
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="product_info_wrapper">
                    <div class="product_base_info">
                        <h1><?= $product->pd_name ?? '' ?></h1>
                        <div class="product_other_info">
                            <p><span><?= lang('Product.brand') ?>:</span><?= $product->brand->manufacture_name ?? '' ?></p>
                            <p><span><?= lang('Product.category') ?>:</span><?= $product->category->title; ?></p>
                            <p><span><?= lang('Product.code') ?>:</span><?= $product->pd_sku ?? '' ?></p>
                        </div>
                        <div class="price mt-3 mb-3 d-flex align-items-center">
                            <?php if(($product->price_discount > 0 && $product->price_discount < $product->price)): ?>
                                <span class="org_price ms-2"><?= vnd_encode($product->price_discount, true) ?? '' ?></span>
                            <?php else: ?>
                                <span class="org_price ms-2"><?= vnd_encode($product->price, true) ?? '' ?></span>
                            <?php endif; ?>
                            <?php if($product->price_discount > 0): ?>
                                <span class="prev_price ms-0"><?= vnd_encode($product->price, true) ?? '' ?></span>
                            <?php endif; ?>

                        </div>
                        <div class="cart_qnty ms-md-auto">
                            <p><?= lang('Product.quantity') ?></p>
                            <div class="d-flex align-items-center">
                                <div class="cart_qnty_btn">
                                    <i class="las la-minus"></i>
                                </div>
                                <div class="cart_count">1</div>
                                <div class="cart_qnty_btn">
                                    <i class="las la-plus"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="product_buttons">
                        <button data-product-id="<?= $product->id ?>"  @click="addCart(<?= $product->id ?>)" class="default_btn add_product_to_card"><i class="icon-cart me-2"></i><?= lang('Product.add_cart') ?></button>
                    </div>
                    <div class="share_icons footer_icon d-flex">
                        <a href="#"><i class="lab la-facebook-f"></i></a>
                        <a href="#"><i class="lab la-twitter"></i></a>
                        <a href="#"><i class="lab la-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="product_view_tabs mt-4">
            <div class="pv_tab_buttons" class="spec_text">
                <div class="pbt_single_btn active" data-target=".info">Mô tả sản phẩm</div>
                <div class="pbt_single_btn" data-target=".qna">Thông số kỹ thuật</div>
            </div>
            <div class="pb_tab_content info active">
                <?= $product->product_meta['description'] ?? '' ?>
            </div>
            <div class="pb_tab_content qna">
                <?= $product->product_meta['product_info'] ?? '' ?>
            </div>
        </div>

        <!-- Related Products -->
        <?php echo view_cell('\App\Cells\Product\RelatedProductCell', ['cat_id' => $product->cat_id])?>

    </div>
</div>

<?= $this->endSection() ?>
