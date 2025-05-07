<?php if ( isset($products) && count($products) ) : ?>
    <div class="tf-col-content">
        <div class="tf-search-content-title fw-5"><?=lang('Home.search_inspiration_title')?></div>
        <div class="tf-search-hidden-inner">
        <?php 
            foreach ($products as $product) : 
                $featureImg = (isset($product->feature_image['thumbnail']) && $product->feature_image['thumbnail'] !== null) ? $product->feature_image['thumbnail'] : base_url($configs->no_img);
            ?>
            <div class="tf-loop-item">
                <div class="image">
                    <a href="<?= base_url(route_to('product_detail', $product->pd_slug, $product->id)) ?>">
                        <img src="<?=$featureImg?>" alt="<?=$product->pd_name?>">
                    </a>
                </div>
                <div class="content">
                    <a href="<?= base_url(route_to('product_detail', $product->pd_slug, $product->id)) ?>"><?=$product->pd_name?></a>
                    <div class="tf-product-info-price">
                        <?php if ( $product->price_discount > 0 && $product->price_discount < $product->price) :?>
                        <div class="compare-at-price"><?=format_currency($product->price, $currentLang->locale )?></div>
                        <div class="price-on-sale fw-6"><?=format_currency($product->price_discount, $currentLang->locale )?></div>
                        <?php else :?>
                        <div class="price-on-sale fw-6"><?=format_currency($product->price, $currentLang->locale )?></div>
                        <?php endif;?>
                    </div>
                </div>
            </div>
            <?php endforeach;?>

        </div>
    </div>

<?php endif;?>