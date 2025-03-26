<?php
/**
 * @author tmtuan
 * created Date: 10/20/2023
 * Project: Unigem
 */
?>

<div class="mobile_menwrap d-lg-none" id="mobile_catwrap">
    <div class="sub_categories">
        <h5 class="mobile_title">
            <?=lang('Home.all_product_categories')?>
            <span class="sidebarclose" id="catclose">
                    <i class="las la-times"></i>
                </span>
        </h5>

        <?= view_cell('\App\Cells\Menu\MenuProductsCell::mobileMenu') ?>

    </div>
</div>
