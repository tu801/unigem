<?php $pager->setSurroundCount(4) ?>
<ul class="wg-pagination">
<?php if ($pager->hasPrevious()) : ?>
    <li>
        <a href="<?= $pager->getPrevious() ?>" class="pagination-item animate-hover-btn"><i class="icon-arrow-left"></i></a>
    </li>
<?php endif ?>
<?php foreach ($pager->links() as $link) : ?>
    <?php if ($link['active']) :?>
    <li class="active">
        <div class="pagination-item"><?= $link['title'] ?></div>
    </li>
    <?php else: ?>
    <li>
        <a href="<?= $link['uri'] ?>" class="pagination-item animate-hover-btn"><?= $link['title'] ?></a>
    </li>
    <?php endif?>
<?php endforeach ?>

<?php if ($pager->hasNext()) : ?>
    <li>
        <a href="<?= $pager->getNext() ?>" class="pagination-item animate-hover-btn"><i class="icon-arrow-right"></i></a>
    </li>
<?php endif ?>
</ul>