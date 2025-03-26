<?php
if ( isset($main_menu->id) && count($main_menu->menu_items) ) :
    ?>
    <ul class="nav_bar flex-grow-1">
        <?php
        foreach ($main_menu->menu_items as $menuItem) :
            if ( count($menuItem->children) ) :
                ?>
                <li class="withsubs">
                    <a href="<?=$menuItem->url?>"><?=$menuItem->title?> <span><i class="las la-angle-down"></i></span></a>
                    <ul class="subnav">
                        <?php foreach ($menuItem->children as $childItem) : ?>
                            <li><a href="<?=$childItem->display_url?>"> <?=$childItem->title?> </a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php else: ?>
                <li><a href="<?=$menuItem->url?>"><?=$menuItem->title?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>