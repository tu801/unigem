<?php
echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>
<!-- breadcrumbs -->
<?php echo view_cell('App\Libraries\BreadCrumb\BreadCrumbCell') ?>

<!-- shop grid view -->
<div class="shop_wrap section_padding_b">
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-4 position-relative">
                <form>
                    <div class="filter_box py-3 px-3 shadow_sm">
                        <div class="close_filter d-block d-lg-none"><i class="las la-times"></i></div>
                        <div class="shop_filter">
                            <h4 class="filter_title"><?= lang('Product.category') ?></h4>
                            <div class="filter_list">
                                <?php foreach ($product_category as $item): ?>
                                    <div class="custom_check d-flex align-items-center">
                                        <input type="checkbox" name="category" class="check_inp" value="<?= $item->id ?>" class="check_inp" hidden id="cat-<?= $item->id ?>" <?= isset($select_cat) && $item->id == $select_cat ? 'checked' : '' ?>>
                                        <label for="cat-<?= $item->id ?>"><?= $item->title ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="shop_filter">
                            <h4 class="filter_title"><?= lang('Product.brand') ?></h4>
                            <div class="filter_list">
                                <?php foreach ($product_manufacturer as $item): ?>
                                    <div class="custom_check d-flex align-items-center">
                                        <input type="checkbox" name="manufacturer" class="check_inp" value="<?= $item->manufacturer_id ?>" hidden id="bnd-<?= $item->manufacturer_id ?>" <?= isset($select_manufacturer) && $item->manufacturer_id == $select_manufacturer ? 'checked' : '' ?>>
                                        <label for="bnd-<?= $item->manufacturer_id ?>"><?= $item->manufacture_name ?></label>
                                    </div>
                                <?php endforeach; ?>

                            </div>
                        </div>
                        <div class="shop_filter border-bottom-0 pb-0 mb-0">
                            <button type="submit" class="default_btn py-2 me-3 rounded"><?= lang('Product.filter') ?></button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-xl-9 col-lg-8">
                <div class="d-flex align-items-center">
                    <div class="d-block d-lg-none">
                        <button class="default_btn py-2 me-3 rounded" id="mobile_filter_btn"><?= lang('Product.filter') ?></button>
                    </div>
                </div>
                <div class="shop_products">
                    <div class="row gy-4">
                        <?php if (count($data) > 0):
                            foreach ($data as $item): ?>
                        <div class="col-md-4 col-sm-6">
                            <div class="single_new_arrive">
                                <div class="sna_img">
                                    <?php
                                    $img = (isset($item->feature_image['thumbnail']) && $item->feature_image['thumbnail'] !== null) ? $item->feature_image['thumbnail'] : base_url($configs->noimg);
                                    ?>
                                    <img loading="lazy"  class="prd_img" src="<?= $img ?>" alt="<?= $item->pd_name ?? '' ?>">
                                </div>
                                <div class="sna_content">
                                    <a href="<?= base_url(route_to('product_detail', $item->pd_slug, $item->id)) ?>">
                                        <h4><?= $item->pd_name ?? '' ?></h4>
                                    </a>
                                    <div class="ratprice">
                                        <div class="price">
                                            <?php if(($item->price_discount > 0 && $item->price_discount < $item->price)): ?>
                                                <span class="org_price"><?= number_format($item->price_discount) ?? '' ?>đ</span>
                                            <?php else: ?>
                                                <span class="org_price"><?= number_format($item->price) ?? '' ?>đ</span>
                                            <?php endif; ?>
                                            <?php if($item->price_discount > 0): ?>
                                                <span class="prev_price"><?= number_format($item->price) ?? '' ?>đ</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="product_adcart">
                                        <button data-product-id="<?= $item->id ?>" class="default_btn add_product_to_card"><?= lang('Product.add_cart') ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach;
                            else:
                        ?>
                            <p class="text-center">
                                <?= lang('Acp.item_not_found') ?>
                            </p>
                        <?php
                        endif;
                        ?>
                    </div>
                    <?= $pager->links(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
