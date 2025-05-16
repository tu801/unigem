<div class="offcanvas offcanvas-start canvas-filter" id="filterShop">
    <div class="canvas-wrapper">
        <header class="canvas-header">
            <div class="filter-icon">
                <span class="icon icon-filter"></span>
                <span><?= lang('Product.filter') ?></span>
            </div>
            <span class="icon-close icon-close-popup" data-bs-dismiss="offcanvas" aria-label="Close"></span>
        </header>
        <div class="canvas-body">
            <?php if (isset($categoryList) && count($categoryList) > 0) : ?>
                <div class="widget-facet wd-categories">
                    <div class="facet-title" data-bs-target="#categories" data-bs-toggle="collapse" aria-expanded="true"
                        aria-controls="categories">
                        <span><?= lang('Product.category') ?></span>
                        <span class="icon icon-arrow-up"></span>
                    </div>
                    <div id="categories" class="collapse show">
                        <ul class="list-categoris current-scrollbar mb_36">
                            <?php foreach ($categoryList as $category) : ?>
                                <li class="cate-item current">
                                    <a href="<?= $category->url ?>"><span><?= $category->title ?></span></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <form action="#" id="facet-filter-form" class="facet-filter-form">
                <div class="widget-facet">
                    <div class="facet-title" data-bs-target="#availability" data-bs-toggle="collapse"
                        aria-expanded="true" aria-controls="availability">
                        <span>Availability</span>
                        <span class="icon icon-arrow-up"></span>
                    </div>
                    <div id="availability" class="collapse show">
                        <ul class="tf-filter-group current-scrollbar mb_36">
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="availability" class="tf-check" id="inStock">
                                <label for="inStock"
                                    class="label"><span><?= lang('Product.in_stock') ?></span>&nbsp;<span>(14)</span></label>
                            </li>
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="radio" name="availability" class="tf-check" id="outStock">
                                <label for="outStock"
                                    class="label"><span><?= lang('Product.out_of_stock') ?></span>&nbsp;<span>(2)</span></label>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="widget-facet">
                    <div class="facet-title" data-bs-target="#price" data-bs-toggle="collapse" aria-expanded="true"
                        aria-controls="price">
                        <span><?= lang('Product.price') ?></span>
                        <span class="icon icon-arrow-up"></span>
                    </div>
                    <div id="price" class="collapse show">
                        <div class="widget-price filter-price">
                            <?php
                            switch ($currentLang->currency_code) {
                                case 'USD':
                                    $minPrice = 0;
                                    $maxPrice = 20000;
                                    break;
                                default:
                                    $minPrice = 0;
                                    $maxPrice = 500000000;
                                    break;
                            }
                            ?>
                            <div class="price-val-range" id="price-value-range" data-min="<?= $minPrice ?>"
                                data-max="<?= $maxPrice ?>" data-currency-code="<?= $currentLang->currency_code ?>">
                            </div>
                            <div class="box-title-price">
                                <div class="caption-price-vertical">
                                    <div class="price-row">
                                        <span class="title-price"><?= lang('Product.price_from') ?> :</span>
                                        <div class="price-val" id="price-min-value"
                                            data-currency="<?= $currentLang->currency_symbol ?>">
                                        </div>
                                    </div>
                                    <div class="price-row">
                                        <span class="title-price"><?= lang('Product.price_to') ?> :</span>
                                        <div class="price-val" id="price-max-value"
                                            data-currency="<?= $currentLang->currency_symbol ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>