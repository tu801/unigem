<?php
echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>
<!-- page-title -->
<div class="tf-page-title">
    <div class="container-full">
        <div class="row">
            <div class="col-12">
                <div class="heading text-center"><?= $page_title ?? '' ?></div>
            </div>
        </div>
    </div>
</div>
<!-- /page-title -->

<!-- main page-->
<section class="mt-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <?php if (session()->has('errors')) : ?>
                <ul class="alert alert-danger alert-dismissible text-danger">
                    <?php foreach (session('errors') as $error) : ?>
                    <li><?= $error ?></li>
                    <?php endforeach ?>
                </ul>
                <?php endif ?>
                <?php if (session()->has('message')) : ?>
                <div class="alert alert-success">
                    <?= session('message') ?>
                </div>
                <?php endif ?>

                <div class="my-account-content account-edit">
                    <div class="">
                        <form class="" id="registerForm" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="tf-field style-1 mb_15">
                                <input class="tf-field-input tf-input" placeholder=" " type="text" name="cus_full_name">
                                <label class="tf-field-label" for=""><?= lang('Customer.fullName') ?> *</label>
                            </div>
                            <div class="tf-field style-1 mb_15">
                                <input class="tf-field-input tf-input" placeholder=" " type="text" name="cus_phone">
                                <label class="tf-field-label" for=""><?= lang('Customer.phoneNumber') ?> *</label>
                            </div>
                            <div class="tf-field style-1 mb_15">
                                <input class="tf-field-input tf-input" placeholder=" " type="email" name="cus_email">
                                <label class="tf-field-label" for=""><?= lang('Customer.email') ?> *</label>
                            </div>

                            <div class="tf-field style-1 mb_15">
                                <input class="tf-field-input tf-input" placeholder=" " type="password" name="password">
                                <label class="tf-field-label" for=""><?= lang('Customer.password') ?> *</label>
                            </div>
                            <div class="tf-field style-1 mb_15">
                                <input class="tf-field-input tf-input" placeholder=" " type="password"
                                    name="password_confirm">
                                <label class="tf-field-label" for=""><?= lang('Customer.passwordConfirm') ?> *</label>
                            </div>

                            <div class="tf-field style-1 mb_15">
                                <input class="tf-field-input tf-input" placeholder=" " type="password"
                                    name="cus_address">
                                <label class="tf-field-label fw-4 text_black-2"><?= lang('Customer.address') ?></label>
                            </div>
                            <fieldset class="box fieldset mb_15">
                                <label for="country">Country/Region</label>
                                <div class="select-custom">
                                    <select class="tf-select w-100" id="country" name="" data-default="">
                                        <option value="---" data-provinces="[]">---</option>
                                        <option value="Austria" data-provinces="[]">Austria</option>
                                    </select>
                                </div>
                            </fieldset>
                            <div class="mb_20">
                                <button type="submit"
                                    class="tf-btn w-100 radius-3 btn-fill animate-hover-btn justify-content-center">Save
                                    Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /main page-->

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('#country').change(function() {
        var country = $(this).val();
        console.log(country);
    });
});
</script>
<?= $this->endSection() ?>