<div class="tf-list-layout wrapper-shop" id="listLayout">
    <?php
    if (isset($productData) && !empty($productData)) :
        $i = 0;
        foreach ($productData as $product) :
            $i++;
    ?>
    <!-- card product 1 -->
    <div class="card-product list-layout" data-availability="<?= $product->pd_status ?>">
        <div class="card-product-wrapper">
            <?php
                    $featureImg = (isset($product->feature_image['thumbnail']) && $product->feature_image['thumbnail'] !== null) ? $product->feature_image['thumbnail'] : base_url($configs->no_img);
                    if (isset($product->images) && !empty($product->images)) {
                        $hoverImg = !empty($product->images->data[0]->product_thumb) ? base_url($product->images->data[0]->product_thumb) : null;
                    }
                    ?>
            <a href="<?= base_url(route_to('product_detail', $product->pd_slug, $product->id)) ?>" class="product-img">
                <img class="lazyload img-product" data-src="<?= $featureImg ?>" src="<?= $featureImg ?>"
                    alt="<?= $product->pd_name ?>">
                <?php if (isset($hoverImg) && !empty($hoverImg)) : ?>
                <img class="lazyload img-hover" data-src="<?= $hoverImg ?>" src="<?= $hoverImg ?>"
                    alt="<?= $product->pd_name ?>">
                <?php endif ?>
            </a>
        </div>
        <div class="card-product-info">
            <a href="<?= base_url(route_to('product_detail', $product->pd_slug, $product->id)) ?>"
                class="title link"><?= $product->pd_name ?></a>
            <span class="price current-price"><?= $product->display_price ?></span>
            <span class="published-date d-none"><?= $product->publish_date ?></span>

            <p class="description"><?= strip_tags(word_limiter($product->pd_description ?? '', 30)) ?></p>

            <div class="list-product-btn">
                <a href="#quick_add" data-bs-toggle="modal" class="box-icon quick-add style-3 hover-tooltip">
                    <span class="icon icon-bag"></span><span
                        class="tooltip"><?= lang('Product.quick_add_to_cart') ?></span>
                </a>

                <a href="#quick_view" data-bs-toggle="modal" data-product-id="<?= $product->id ?>"
                    class="box-icon quickview style-3 hover-tooltip">
                    <span class="icon icon-view"></span><span class="tooltip"><?= lang('Product.quick_view') ?></span>
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- pagination -->
    <?php echo $pager->links('default', 'product_list') ?>
    <!-- /pagination -->
    <?php endif; ?>
</div>