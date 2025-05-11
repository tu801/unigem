<div class="footer-col <?=$position_class?> footer-col-block">
    <div class="footer-heading footer-heading-desktop">
        <h6 class="fs-14 text-uppercase fw-8"><?=$menuData->name?></h6>
    </div>
    <div class="footer-heading footer-heading-moblie">
        <h6 class="fs-14 text-uppercase fw-8"><?=$menuData->name?></h6>
    </div>
    <ul class="footer-menu-list tf-collapse-content">
    <?php foreach ($menuData->menu_items as $item) : ?>
        <li>
            <a href="<?=$item->display_url?>" class="footer-menu_item"><?=$item->title?></a>
        </li>
    <?php endforeach; ?>
    </ul>
</div>