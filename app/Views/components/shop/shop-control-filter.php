<div class="tf-shop-control grid-3 align-items-center">
    <div class="tf-control-filter">
        <a href="#filterShop" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft" class="tf-btn-filter">
            <span class="icon icon-filter"></span><span class="text"><?=lang('Product.filter')?></span>
        </a>
    </div>
    <ul class="tf-control-layout d-flex justify-content-center">
        <li class="tf-view-layout-switch sw-layout-list list-layout" data-value-layout="list">
            <div class="item"><span class="icon icon-list"></span></div>
        </li>
        <li class="tf-view-layout-switch sw-layout-2" data-value-layout="tf-col-2">
            <div class="item"><span class="icon icon-grid-2"></span></div>
        </li>
        <li class="tf-view-layout-switch sw-layout-3 active" data-value-layout="tf-col-3">
            <div class="item"><span class="icon icon-grid-3"></span></div>
        </li>
        <li class="tf-view-layout-switch sw-layout-4" data-value-layout="tf-col-4">
            <div class="item"><span class="icon icon-grid-4"></span></div>
        </li>
    </ul>
    <div class="tf-control-sorting d-flex justify-content-end">
        <div class="tf-dropdown-sort" data-bs-toggle="dropdown">
            <div class="btn-select">
                <span class="text-sort-value">Featured</span>
                <span class="icon icon-arrow-down"></span>
            </div>
            <div class="dropdown-menu">
                <div class="select-item active">
                    <span class="text-value-item">Featured</span>
                </div>
                <!-- <div class="select-item">
                    <span class="text-value-item">Best selling</span>
                </div> -->
                <div class="select-item" data-sort-value="a-z">
                    <span class="text-value-item"><?=lang('Product.order_by_a_z')?></span>
                </div>
                <div class="select-item" data-sort-value="z-a">
                    <span class="text-value-item"><?=lang('Product.order_by_z_a')?></span>
                </div>
                <div class="select-item" data-sort-value="price-low-high">
                    <span class="text-value-item"><?=lang('Product.order_by_price_low_to_high')?></span>
                </div>
                <div class="select-item" data-sort-value="price-high-low">
                    <span class="text-value-item"><?=lang('Product.order_by_price_high_to_low')?></span>
                </div>
                <div class="select-item">
                    <span class="text-value-item"><?=lang('Product.order_by_date_low_to_high')?></span>
                </div>
                <div class="select-item">
                    <span class="text-value-item"><?=lang('Product.order_by_date_high_to_low')?></span>
                </div>
            </div>
        </div>
    </div>
</div>