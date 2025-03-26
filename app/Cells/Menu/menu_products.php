<?php
$_catModel = model(\Modules\Acp\Models\Blog\CategoryModel::class);
if ( isset($productMenu-> id) ) :
    foreach ($productMenu->menu_items as $menuItem ) :
        if ( count($menuItem->children) ) :
?>
        <div class="singlecats withsub">
<!--            <span class="img_wrp"><i class="las la-male"></i></span>-->
            <span class="txt"><?=$menuItem->title?></span>
            <span class="wsicon"><i class="las la-angle-right"></i></span>
            <div class="mega_menu">
                <?php
                foreach ($menuItem->children as $childItem) :
                    if ( $childItem->type == 'category' && $childItem->related_id > 0 ) {
                        $catData = $_catModel
                            ->select('category.*, category_content.title, category_content.slug')
                            ->join('category_content', 'category_content.cat_id = category.id')
                            ->where('lang_id', $locale->id)
                            ->find($childItem->related_id);

                        if ( $catData->cat_type == \Modules\Acp\Enums\CategoryEnum::CAT_TYPE_PRODUCT) {
                            $catUrl = base_url(route_to('product_category', $catData->slug));
                        } else {
                            $catUrl = base_url($catData->slug);
                        }

                        $catChildren = $_catModel
                            ->select('category.*, category_content.title, category_content.slug')
                            ->join('category_content', 'category_content.cat_id = category.id')
                            ->where('parent_id', $catData->id)
                            ->where('lang_id', $locale->id)
                            ->findAll();

                        if ( count($catChildren) ) {
                            echo <<<EOF
                <div class="single_mega_menu">
                    <div class="mega_menu_wrap">
                        <h4>{$catData->title}</h4>
                        <div class="mega_categories">
EOF;
                            foreach ($catChildren as $catChildrenItem) {
                                $catItemUrl = base_url(route_to('product_category', $catChildrenItem->slug));

                                echo <<<EOF
                            <a href="{$catItemUrl}">{$catChildrenItem->title}</a>
EOF;
                            }
                            echo <<<EOF
                        </div>
                    </div>
                </div>
EOF;

                        } else {
                            echo <<<EOF
                <a href="{$catUrl}">{$catData->title}</a>
EOF;
                        }
                    } else {
                        echo <<<EOF
                <a href="{$childItem->url}">{$childItem->title}</a>
EOF;
                    }
                ?>
                <?php endforeach; ?>

            </div>
        </div>
        <?php else : ?>
        <a href="<?=$menuItem->url?>" class="singlecats">
<!--                <span class="img_wrp"><i class="las la-shoe-prints"></i></span>-->
            <span class="txt"><?=$menuItem->title?></span>
        </a>
<?php
        endif;
    endforeach;
endif;
?>
