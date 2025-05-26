<?php
if ( isset($main_menu->id) && count($main_menu->menu_items) ) :
?>
<ul class="box-nav-ul d-flex align-items-center justify-content-center gap-30">
    <?php
    foreach ($main_menu->menu_items as $menuItem) :
        if ( count($menuItem->children) ) :
    ?>
            <li class="menu-item position-relative">
                <a class="item-link  fw-6 fs-14" href="<?=$menuItem->display_url?>"><?=$menuItem->title?> </a>
                <div class="sub-menu submenu-default">
                    <ul class="menu-list">
                        <?php foreach ($menuItem->children as $childItem) :?>
                            <li><a class="menu-link-text link text_black-2" href="<?=$childItem->display_url?>"> <?=$childItem->title?> </a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </li>
        <?php else: ?>
            <li class="menu-item"><a class="item-link  fw-6 fs-14" href="<?=$menuItem->display_url?>"><?=$menuItem->title?></a></li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>
<?php endif; ?>