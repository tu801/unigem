<?php
echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>
<!-- breadcrumbs -->
<?php echo view_cell('App\Libraries\BreadCrumb\BreadCrumbCell') ?>
<!-- categories -->
<div class="shop_bycat section_padding_b">
    <div class="container">
        <div class="row gx-2 gy-2">
            <?php if(count($post_category) > 0): foreach ($post_category as $item): ?>
                <div class="col-lg-4 col-6">
                <a href="<?= base_url(route_to('post_detail', $item->slug)) ?>" class="single_shopbycat bg_1"
                   style="background-image: url(<?= (!empty($item->images)) ? $item->images['thumbnail'] : '' ?>);">
                    <div class="shopcat_cont">
                        <h4><?= $item->title ?? '' ?></h4>
                        <div class="icon">
                            <i class="las la-long-arrow-alt-right"></i>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; else: ?>
                <p class="text-center">
                    <?= lang('Acp.item_not_found') ?>
                </p>
            <?php endif; ?>
        </div>
        <?= $pager->links(); ?>
    </div>
</div>
<?= $this->endSection() ?>