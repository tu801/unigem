<?php
/**
 * @author tmtuan
 * created Date: 10/20/2023
 * Project: Unigem
 */
?>

<div class="mobile_search_bar">
    <div class="mobile_search_text">
        <p><?=lang('Home.mobile_search_text')?></p>
        <span class="close_mbsearch" id="close_mbsearch">
                <i class="las la-times"></i>
            </span>
    </div>
    <form method="get" action="<?=base_url(route_to('product_shop'))?>">
        <input name="query" type="text" placeholder="<?=lang('Home.search_box_placeholder')?>" id="mobile_search">
        <button>
            <i class="icon-search-left"></i>
        </button>
    </form>

    <div class="search_result_product" id="search_result_mobile"></div>
</div>
