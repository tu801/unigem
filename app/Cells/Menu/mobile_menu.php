<?php
/**
 * Author: tmtuan
 * Created date: 11/16/2023
 * Project: Unigem
 **/
?>
<ul>
    <?php
    foreach ($main_menu->menu_items as $menuItem) :
        if ( count($menuItem->children) ) :
    ?>
    <li class="withsub">
        <a href="javascript:void(0)">
            <?=$menuItem->title?>
        </a>
        <div class="submn">
            <?php foreach ($menuItem->children as $childItem) : ?>
            <a href="<?=$childItem->display_url?>"> <?=$childItem->title?> </a>
            <?php endforeach; ?>
        </div>
    </li>
        <?php else: ?>
            <li><a href="<?=$menuItem->url?>"><?=$menuItem->title?></a></li>
        <?php endif; ?>
    <?php endforeach; ?>

</ul>
