<div class="py-3">
    <h3 class="text-center"> Bài viết mới </h3>
    <ul class="list-group list-group-flush">
        <?php foreach ($posts as $item): ?>
            <li class="list-group-item"><a
                        href="<?= base_url(route_to('post_detail', $item->slug)) ?>"><?= $item->title ?></a></li>
        <?php endforeach; ?>
    </ul>
</div>