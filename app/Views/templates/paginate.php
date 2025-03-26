<?php $pager->setSurroundCount(4) ?>
<div class="pagination_wrp d-flex align-items-center justify-content-center mt-4">
    <?php if ($pager->hasPrevious()) : ?>
            <a href="<?= $pager->getPrevious() ?>">
                <div class="single_paginat">
                    <i class="las la-long-arrow-alt-left"></i>
                </div>
            </a>
    <?php endif ?>
    <?php foreach ($pager->links() as $link) : ?>
            <a href="<?= $link['uri'] ?>">
                <div class="single_paginat <?= $link['active'] ? 'active' : '' ?>">
                    <?= $link['title'] ?>
                </div>
            </a>
    <?php endforeach ?>

    <?php if ($pager->hasNext()) : ?>
        <a href="<?= $pager->getNext() ?>">
            <div class="single_paginat">
                <i class="las la-long-arrow-alt-right"></i>
            </div>
        </a>
    <?php endif ?>
</div>