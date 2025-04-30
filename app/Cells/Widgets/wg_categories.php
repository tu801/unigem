<?php if ( !empty($categories) ) : ?>
<div class="sidebar-item sidebar-categories">
    <div class="sidebar-title"><?=lang('Site.categories_widget_title')?></div>
    <div class="sidebar-content">
        <ul>
            <?php foreach ($categories as $item): ?>
            <li>
                <a href="<?= base_url(route_to('category_page', $item->slug)) ?>"><?= $item->title ?></a>
            </li>
            <?php endforeach;?>
        </ul>
    </div>
</div>
<?php endif;?>