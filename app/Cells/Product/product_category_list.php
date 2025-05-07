<?php if ( count($product_category) ) :?>
<div class="widget-facet wd-categories">
    <div class="facet-title" data-bs-target="#categories" data-bs-toggle="collapse" aria-expanded="true" aria-controls="categories">
        <span><?=lang('Product.category')?></span>
        <span class="icon icon-arrow-up"></span>
    </div>
    
    <div id="categories" class="collapse show">
        <ul class="list-categories current-scrollbar mb_36">
            <?php 
            foreach ($product_category as $item):
                $currentCatSelected = ( isset($currentCat) && $category->slug == $currentCat ) ? 'current' : '';
            ?>
            <li class="cate-item <?=$currentCatSelected?> ">
                <a href="<?=$item->url?>"><span><?=$item->title?></span>&nbsp;<span>(<?=$item->count?>)</span></a>
            </li>
            <?php endforeach;?>
        </ul>
    </div>
    
</div>
<?php endif;?>