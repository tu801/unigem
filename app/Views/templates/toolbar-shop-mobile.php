<?php

use App\Enums\CategoryEnum;
use App\Models\Blog\CategoryModel;

$productCategory = model(CategoryModel::class)->getCategories(CategoryEnum::CAT_TYPE_PRODUCT);
?>

<div class="offcanvas offcanvas-start canvas-mb toolbar-shop-mobile" id="toolbarShopmb">
    <span class="icon-close icon-close-popup" data-bs-dismiss="offcanvas" aria-label="Close"></span>
    <div class="mb-canvas-content">
        <?php if ( isset($productCategory) && count($productCategory) > 0 ): ?>
        <div class="mb-body">
            <ul class="nav-ul-mb" id="wrapper-menu-navigation">
                <?php foreach ($productCategory as $category) : ?>
                <li class="nav-mb-item">
                    <a href="<?=$category->url?>" class="tf-category-link mb-menu-link">
                        <div class="image">
                            <img src="<?=$category->cat_image['image']?>" alt="<?=$category->title?>">
                        </div>
                        <span><?=$category->title?></span>
                    </a>
                </li>
                <?php endforeach;?>
                
            </ul>
        </div>
        <?php endif;?>
        <div class="mb-bottom">
            <a href="<?=base_url(route_to('product_shop'))?>" class="tf-btn fw-5 btn-line"><?=lang('Home.read_more')?><i class="icon icon-arrow1-top-left"></i></a>
        </div>
    </div>       
</div>