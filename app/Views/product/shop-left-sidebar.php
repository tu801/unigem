<?php
echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>
<!-- page-title -->
<div class="tf-page-title">
    <div class="container-full">
        <div class="row">
            <div class="col-12">
                <div class="heading text-center"><?= $page_title ?></div>
                <?php echo view_cell('App\Libraries\BreadCrumb\BreadCrumbCell') ?>
            </div>
        </div>
    </div>
</div>
<!-- /page-title -->

<!-- shop grid view -->
<section class="flat-spacing-1">
    <div class="container">
        <?= $this->include($configs->view . '\components\shop\shop-control-filter') ?>

        <div class="tf-row-flex">
            <?= $this->include($configs->view . '\components\shop\shop-sidebar') ?>

            <div class="wrapper-control-shop tf-shop-content">
                <div class="meta-filter-shop">
                    <div id="product-count-grid" class="count-text"></div>
                    <div id="product-count-list" class="count-text"></div>
                    <div id="applied-filters"></div>
                    <button id="remove-all" class="remove-all-filters" style="display: none;">Remove All <i
                            class="icon icon-close"></i></button>
                </div>

                <?php
                if (!empty($data) && count($data) > 0):
                    $params = [
                        'configs' => $configs,
                        'currentLang' => $currentLang,
                        'productData' => $data,
                        'pager' => $pager,
                    ];
                    echo view($configs->view . '\components\shop\product-list-layout', $params);
                    echo view($configs->view . '\components\shop\product-grid-layout', $params);
                else:
                ?>
                    <div class="text text-danger"><?= lang('Product.product_not_found') ?></div>
                <?php endif; ?>

            </div>
        </div>

    </div>
</section>
<div class="btn-sidebar-style2">
    <button data-bs-toggle="offcanvas" data-bs-target="#sidebarmobile" aria-controls="offcanvas"><i
            class="icon icon-sidebar-2"></i></button>
</div>

<!-- Off Canvas Filter -->
<?= view($configs->view . '\components\shop\off-canvas-filter', ['categoryList' => $product_category, 'currentLang' => $currentLang]) ?>
<!-- End Off Canvas Filter -->

<!-- modal quick_add -->
<div class="modal fade modalDemo" id="quick_add">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="header">
                <span class="icon-close icon-close-popup" data-bs-dismiss="modal"></span>
            </div>
            <div class="wrap">
                <div class="tf-product-info-item">
                    <div class="image">
                        <img src="" alt="">
                    </div>
                    <div class="content">
                        <a href="product-detail.html">Ribbed Tank Top</a>
                        <div class="tf-product-info-price">
                            <!-- <div class="price-on-sale">$8.00</div>
                            <div class="compare-at-price">$10.00</div>
                            <div class="badges-on-sale"><span>20</span>% OFF</div> -->
                            <div class="price">$18.00</div>
                        </div>
                    </div>
                </div>

                <div class="tf-product-info-buy-button">
                    <form class="">
                        <a href="javascript:void(0);"
                            class="tf-btn btn-fill justify-content-center fw-6 fs-16 flex-grow-1 animate-hover-btn btn-add-to-cart">
                            <span><?= lang('product.quick_add_to_cart') ?> -&nbsp;</span><span
                                class="tf-qty-price">$18.00</span>
                        </a>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /modal quick_add -->

<!-- Filter sidebar-->
<div class="offcanvas offcanvas-start canvas-filter canvas-sidebar" id="sidebarmobile">
    <div class="canvas-wrapper">
        <header class="canvas-header">
            <span class="title">SIDEBAR PRODUCT</span>
            <span class="icon-close icon-close-popup" data-bs-dismiss="offcanvas" aria-label="Close"></span>
        </header>
        <div class="canvas-body sidebar-mobile-append">

        </div>

    </div>
</div>
<!-- End Filter sidebar -->

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script type="module" src="<?= base_url($configs->templatePath) ?>js/nouislider.min.js"></script>
<script type="module" src="<?= base_url($configs->templatePath) ?>js/shop.js"></script>
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<link rel="stylesheet" type="text/css" href="<?= base_url($configs->templatePath) ?>css/shop-custom.css" />
<?= $this->endSection() ?>