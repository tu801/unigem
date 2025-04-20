<div class="py-3">
    <h3 class="text-center"> <?=lang('Home.wiget_tags_title')?> </h3>
    <?php foreach ($tagsData as $tag): ?>
        <a href="<?= base_url("tag/{$tag->slug}") ?>"><span class="badge bg-secondary"><?= $tag->title ?></span></a>
    <?php endforeach; ?>
</div>