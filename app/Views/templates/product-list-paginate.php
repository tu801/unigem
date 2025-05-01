<?php $pager->setSurroundCount(4) ?>
<ul class="wg-pagination tf-pagination-list justify-content-start">
<?php if ($pager->hasPrevious()) : ?>
    <li>
        <a href="<?= $pager->getPrevious() ?>" class="pagination-link animate-hover-btn"><span class="icon icon-arrow-left"></span></a>
    </li>
<?php endif ?>

<?php foreach ($pager->links() as $link) : ?>
    <?php if ($link['active']) :?>
    <li class="active">
        <div class="pagination-link"><?= $link['title'] ?></div>
    </li>
    <?php else: ?>
    <li>
        <a href="<?= $link['uri'] ?>" class="pagination-link animate-hover-btn"><?= $link['title'] ?></a>
    </li>
    <?php endif?>
<?php endforeach ?>

<?php if ($pager->hasNext()) : ?>
    <li>
        <a href="<?= $pager->getNext() ?>" class="pagination-link animate-hover-btn"><span class="icon icon-arrow-right"></span></a>
    </li>
<?php endif ?>
</ul>