<?php
echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>
<!-- page-title -->
<div class="tf-page-title">
    <div class="container-full">
        <div class="row">
            <div class="col-12">
                <div class="heading text-center"><?= $post->title ?? '' ?></div>
            </div>
        </div>
    </div>
</div>
<!-- /page-title -->

<!-- main page-->
<section class="flat-spacing-25">
    <div class="container">
        <div class="tf-main-area-page">
        <?= $post->content ?? '' ?>
        </div>
    </div>
</section>
<!-- /main page-->

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
