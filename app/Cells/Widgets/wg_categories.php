<div class="py-3">
    <h3 class="text-center"> Danh mục </h3>
    <ul class="list-group list-group-flush">
        <?php foreach ($categories as $item): ?>
            <li class="list-group-item"><a
                        href="<?= base_url(route_to('category_list', $item->slug)) ?>"><?= $item->title ?></a></li>
        <?php endforeach; ?>
    </ul>
</div>