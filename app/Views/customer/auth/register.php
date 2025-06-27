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
                                <input class="tf-field-input tf-input" placeholder=" " type="text" name="cus_full_name"
                                    value="<?= old('cus_full_name') ?>">
                                <label class="tf-field-label" for=""><?= lang('Customer.fullName') ?> *</label>
                            </div>
                            <div class="tf-field style-1 mb_15">
                                <input class="tf-field-input tf-input" placeholder=" " type="text" name="cus_phone"
                                    value="<?= old('cus_phone') ?>">

                                <label class="tf-field-label" for=""><?= lang('Customer.phoneNumber') ?> *</label>
                            </div>
                            <div class="tf-field style-1 mb_15">
                                <input class="tf-field-input tf-input" placeholder=" " type="email" name="cus_email"
                                    value="<?= old('cus_email') ?>">
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
                            <h6 class="mb_15"><?= lang('Customer.address') ?></h6>
                            <fieldset class="box fieldset mb_15">
                                <label for="country"><?= lang('Customer.select_country') ?></label>
                                <div class="select-custom">
                                    <select class="w-100" id="country" name="country_id"
                                        country-selected="<?= old('country_id') ?? 200 ?>">
                                        <?php if (!empty($countries)) :
                                            foreach ($countries as $item) : ?>
                                                <option value="<?= $item->id ?>" data-code="<?= $item->code ?>"
                                                    data-flag="<?= $item->flags->svg ?>">
                                                    <?= $item->name ?> - <?= $item->code ?>
                                                </option>
                                        <?php endforeach;
                                        endif; ?>
                                    </select>
                                </div>
                            </fieldset>
                            <div id="vietnam_address">
                                <div class="mb_15">
                                    <select class=" w-100 select_province" id="province" name="province_id"
                                        area-selected="<?= old('province_id') ?>"></select>
                                </div>
                                <div class=" mb_15">
                                    <select class=" w-100 select_district" id="district" name="district_id"
                                        area-selected="<?= old('district_id') ?>"></select>
                                </div>
                                <div class=" mb_15">
                                    <select class=" w-100 select_ward" id="ward" name="ward_id"
                                        area-selected="<?= old('ward_id') ?>"></select>
                                </div>
                                <div class="tf-field style-1 mb_15">
                                    <input class="tf-field-input tf-input" placeholder=" " type="text"
                                        name="cus_address" value="<?= old('cus_address') ?>">
                                    <label class="tf-field-label fw-4 text_black-2"><?= lang('Customer.address') ?>
                                        *</label>
                                </div>
                            </div>

                            <div id="other_country_address" class="d-none">
                                <div class="tf-field style-1 mb_15">
                                    <input class="tf-field-input tf-input" placeholder=" " type="text"
                                        name="cus_address" value="<?= old('cus_address') ?>">
                                    <label
                                        class="tf-field-label fw-4 text_black-2"><?= lang('Customer.address') ?></label>
                                </div>
                            </div>

                            <div class="mb_20">
                                <button type="submit"
                                    class="tf-btn w-100 radius-3 btn-fill animate-hover-btn justify-content-center"><?= lang('Home.cus_register') ?></button>
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

<?= $this->section('style') ?>
<!-- Select2 -->
<link rel="stylesheet" href="<?= base_url($configs->scriptsPath) ?>/plugins/select2/css/select2.min.css">
<link rel="stylesheet"
    href="<?= base_url($configs->scriptsPath) ?>/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

<!-- Add CSS for error styling -->
<style>
    .tf-field.error .tf-field-input,
    .mb_15.error select,
    fieldset.error select {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
    }

    .error-message {
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Select2 -->
<script src="<?= base_url($configs->scriptsPath) ?>/plugins/select2/js/select2.full.min.js"></script>
<script src="<?= base_url($configs->scriptsPath) ?>areaLocation.js"></script>
<script>
    // Error messages
    const errorMessages = {
        fullNameRequired: '<?= lang('Customer.cus_full_name_required') ?>',
        phoneRequired: '<?= lang('Customer.cus_phone_required') ?>',
        phoneInvalid: '<?= lang('Customer.cus_phone_invalid') ?>',
        emailRequired: '<?= lang('Customer.cus_email_required') ?>',
        emailInvalid: '<?= lang('Customer.cus_email_valid_email') ?>',
        passwordRequired: '<?= lang('Customer.password_required') ?>',
        passwordMinLength: '<?= lang('Customer.password_min_length') ?>',
        passwordConfirmRequired: '<?= lang('Customer.password_confirm_required') ?>',
        passwordNotMatch: '<?= lang('Customer.password_confirm_matches_password') ?>',
        countryRequired: '<?= lang('Customer.country_required') ?>',
        provinceRequired: '<?= lang('Customer.province_required') ?>',
        districtRequired: '<?= lang('Customer.district_required') ?>',
        wardRequired: '<?= lang('Customer.ward_required') ?>',
        vnAddressRequired: '<?= lang('Customer.cus_address_required') ?>',
        addressRequired: '<?= lang('Customer.cus_address_required') ?>',
        processing: '<?= lang('Customer.processing') ?>'
    };
</script>

<script src="<?= base_url($configs->scriptsPath) ?>registerForm.js"></script>

<?= $this->endSection() ?>