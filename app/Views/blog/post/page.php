<?php
echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>
<!-- breadcrumbs -->
<?php echo view_cell('App\Libraries\BreadCrumb\BreadCrumbCell') ?>
    <div class="shop_bycat section_padding_b">
        <div class="container">
            <h2 class="section_title_3 text-center"><?= $post->title ?? '' ?></h2>
            <div class="row gx-2 gy-2">
                <div class="col-12 post">
                    <?= $post->content ?? '' ?>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>
<?php
echo $this->section('style');
?>
<style>
    .post img {
        width: 100%;
    }
</style>
<?= $this->endSection() ?>
