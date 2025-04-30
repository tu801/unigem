<?php if ( !empty($tagsData) ) : ?>
<div class="sidebar-item sidebar-tags">
    <div class="sidebar-title"><?=lang('Site.tags_widget_title')?></div>
    <div class="sidebar-content">
        <ul class="tags-lists">
            <?php foreach ($tagsData as $tag): ?>
            <li>
                <a href="<?= base_url("tag/{$tag->slug}") ?>" class="tags-item"><?= $tag->title ?></a>
            </li>
            <?php endforeach;?>
        </ul>
    </div>
</div>
<?php endif;?>