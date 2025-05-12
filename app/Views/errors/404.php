<?php
echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>
<section class="page-404-wrap">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="image">
                    <img src="<?=base_url($configs->templatePath)?>images/item/404.svg" alt="">
                </div>
                <div class="title">
                    <?=lang('Home.404_title')?>
                </div>
                <p><?=lang('Home.404_desc')?></p>
                <a href="<?=base_url(route_to('product_shop'))?>" class="tf-btn btn-sm radius-3 btn-fill btn-icon animate-hover-btn">Shop now</a>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>