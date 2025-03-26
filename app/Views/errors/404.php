<?php
echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>
    <!-- breadcrumbs -->
    <div class="container">
        <div class="breadcrumbs">
            <a href="<?= base_url() ?>"><i class="las la-home"></i></a>
            <a href="#" class="active">404</a>
        </div>
    </div>

    <!-- 404 page -->
    <div class="section_padding_b">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-9">
                    <div class="page_nfwrap">
                        <div class="page_nfimg">
                            <img loading="lazy"  src="<?=base_url($configs->templatePath)?>/assets/images/svg/404.svg" class="w-100" alt="page not found">
                        </div>
                        <div class="page_nfcont text-center mt-5">
                            <h4 class="mb-4"><?= lang('Home.page_not_available') ?></h4>
                            <a href="<?= base_url() ?>" class="default_btn small rounded"><?= lang('Home.back_to_home') ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>