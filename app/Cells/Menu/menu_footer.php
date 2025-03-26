<div class="footer_menu">
    <h4 class="footer_title"><?=$menuData->name?></h4>
    <?php foreach ($menuData->menu_items as $item) : ?>
    <a href="<?=$item->url?>"><?=$item->title?></a>
    <?php endforeach; ?>
</div>