<?php
/**
 * Author: tmtuan
 * Created date: 11/16/2023
 * Project: Unigem
 **/

 if ( isset($main_menu->id) && count($main_menu->menu_items) ) :
?>
<ul class="nav-ul-mb" id="wrapper-menu-navigation">
    <?php
    foreach ($main_menu->menu_items as $menuItem) :
        if ( count($menuItem->children) ) :
    ?>
    <li class="nav-mb-item">
        <a href="#dropdown-menu-<?=$menuItem->id?>" class="collapsed mb-menu-link current" data-bs-toggle="collapse" aria-expanded="true" aria-controls="dropdown-menu-<?=$menuItem->id?>">
            <span><?=$menuItem->title?></span>
            <span class="btn-open-sub"></span>
        </a>
        <div id="dropdown-menu-<?=$menuItem->id?>" class="collapse">
            <ul class="sub-nav-menu" >
                <?php foreach ($menuItem->children as $childItem) : ?>
                <li><a class="sub-nav-link" href="<?=$childItem->display_url?>"> <?=$childItem->title?> </a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </li>
    <?php else: ?>
        <li class="nav-mb-item"><a class="mb-menu-link" href="<?=$menuItem->url?>"><?=$menuItem->title?></a></li>
    <?php endif; ?>
    <?php endforeach; ?>
</ul>
<?php endif;?>