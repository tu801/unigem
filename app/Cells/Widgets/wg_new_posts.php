<?php if ( !empty($posts) ) : ?>
<div class="sidebar-item sidebar-post">
    <div class="sidebar-title"><?=lang('Site.recent_posts_widget_title')?></div>
    <div class="sidebar-content">
        <ul>
            <?php foreach ($posts as $item): ?>
            <li>
                <div class="blog-article-item style-sidebar">
                    <div class="article-thumb">
                        <a href="blog-detail.html">
                            <img src="<?=$item->images['thumbnail']?>" alt="<?= $item->title ?>">
                        </a>
                    </div>
                    <div class="article-content">
                        <div class="article-label">
                            <a href="<?=$item->priv_cat->url?>" class="tf-btn btn-sm radius-3 btn-fill animate-hover-btn"><?=$item->priv_cat->title?></a>
                        </div>
                        <div class="article-title">
                            <a href="<?= base_url(route_to('post_detail', $item->slug)) ?>" class=""><?= $item->title ?></a>
                        </div>
                    </div>
                </div>
            </li>
            <?php endforeach;?>
        </ul>
    </div>
</div>
<?php endif;?>