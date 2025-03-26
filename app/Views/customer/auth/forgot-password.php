<?php
echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>

<!-- breadcrumbs -->
<?php echo view_cell('App\Libraries\BreadCrumb\BreadCrumbCell') ?>
<div class="section_padding mb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-7">
                <div class="padding_default shadow_sm">
                    <h2 class="title_2"><?= lang('AuthCustomer.reset_password') ?></h2>
                    <p class="text_md mb-4 text-danger"><?= lang('AuthCustomer.contact_reset_password') ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
