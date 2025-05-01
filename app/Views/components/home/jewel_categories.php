<?php
use App\Models\Blog\CategoryModel;

// Add helper function to convert slug to camelCase
function slugToCamelCase($slug) {
    // Replace hyphens with spaces, then capitalize each word
    $words = str_replace(['-', '_'], ' ', $slug);
    $words = ucwords($words);
    // Remove spaces and make first character lowercase
    $camelCase = str_replace(' ', '', $words);
    return lcfirst($camelCase);
}

$categories = get_theme_config('jewelry_cat_list');
$catSlideData = [];
foreach ($categories as $catId) {
    $catSlideData[] = model(CategoryModel::class)->getById($catId, $currentLang->id);
}
?>
<section class="flat-spacing-18 wow fadeInUp" data-wow-delay="0s">
    <div class="container">
        <div class="tf-grid-layout-v2 flat-animate-tab">
            <ul class="widget-tab-4 rounded-0 scroll-snap" role="tablist">
                <?php 
                $i = 0;
                foreach ($catSlideData as $catSlide) : 
                    $catTargetId = slugToCamelCase($catSlide->slug);
                    $i++;
                ?> 
                <li class="nav-tab-item" role="presentation">   
                    <div data-bs-target="#<?=$catTargetId?>" class="<?=$i==1?'active':''?> nav-tab-link" data-bs-toggle="tab">
                        <span class="text fw-8 "><?=$catSlide->title?></span>
                        <a href="<?=base_url(route_to('product_category', $catSlide->slug))?>" class="icon icon-arrow1-top-left"></a>
                    </div>
                </li>
                <?php endforeach ?> 
            </ul>
            <div class="scroll-process d-md-none" id="scroll-process">
                <div class="value-process"></div>
            </div>
            
            <div class="tab-content">
                <?php 
                $i = 0;
                foreach ($catSlideData as $catSlide) : 
                    $catTargetId = slugToCamelCase($catSlide->slug);
                    $i++;
                ?> 
                    <div class="tab-pane <?=$i==1?'active show':''?>" id="<?=$catTargetId?>" role="tabpanel">
                        <a href="<?=$catSlide->url?>" class="fullwidth o-hidden">
                            <img class="lazyload" data-src="<?=$catSlide->cat_image['image']?>" src="<?=$catSlide->cat_image['image']?>" alt="<?=$catSlide->title?>">
                        </a>
                    </div>
                <?php endforeach ?> 
                
            </div>
        </div>
    </div>
</section>