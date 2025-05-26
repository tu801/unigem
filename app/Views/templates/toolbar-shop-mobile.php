<?php

use App\Enums\CategoryEnum;
use App\Models\Blog\CategoryModel;

// $productCategory = model(CategoryModel::class)->getCategories(CategoryEnum::CAT_TYPE_PRODUCT);
$productCategory = get_menu('product_toolbar_menu');
?>

<div class="offcanvas offcanvas-start canvas-mb toolbar-shop-mobile" id="toolbarShopmb">
    <span class="icon-close icon-close-popup" data-bs-dismiss="offcanvas" aria-label="Close"></span>
    <div class="mb-canvas-content">
        <?php if ( isset($productCategory->id) && count($productCategory->menu_items) > 0 ): ?>
        <div class="mb-body">
            <ul class="nav-ul-mb" id="wrapper-menu-navigation">
                <?php foreach ($productCategory->menu_items as $item) : 
                    if ( $item->type != 'category' ) continue;
                    
                    $category = model(CategoryModel::class)->getById($item->related_id, $currentLang->id);
                ?>
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