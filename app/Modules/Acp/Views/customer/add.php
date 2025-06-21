<?php
echo $this->extend($config->viewLayout);
echo $this->section('content');

$postConfigs = $config->cmsStatus;
?>
<!-- form start -->
<form id="<?= $controller ?>Form" role="form" method="post" class="form-horizontal" enctype="multipart/form-data">
    <div class="row" id="postApp" data-cattype="page">
        <?= csrf_field() ?>
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><?php echo lang('User.title_user_info') ?></h3>
                </div>
                <div class="card-body pad">
                    <div class="form-group">
                        <label for="inputFullName" class="col-form-label"><?= lang('Customer.full_name') ?> <span
                                class="text-danger">*</span></label>
                        <input type="text" name="cus_full_name"
                            class="form-control <?= session('errors.cus_full_name') ? 'is-invalid' : '' ?>"
                            id="inputFullName" placeholder="<?= lang('Customer.full_name') ?>"
                            value="<?= old('cus_full_name') ?>">
                    </div>

                    <div class="form-group">
                        <label for="inputPhone" class="col-form-label"><?= lang('Customer.phone') ?> <span
                                class="text-danger">*</span></label>
                        <input type="text" name="cus_phone"
                            class="form-control <?= session('errors.cus_phone') ? 'is-invalid' : '' ?>" id="inputPhone"
                            placeholder="<?= lang('Customer.phone') ?>" value="<?= old('cus_phone') ?>">
                    </div>

                    <div class="form-group">
                        <label for="inputEmail" class="col-form-label"><?= lang('Customer.email') ?> <span
                                class="text-danger">*</span></label>
                        <input type="text" name="cus_email"
                            class="form-control <?= session('errors.cus_email') ? 'is-invalid' : '' ?>" id="inputEmail"
                            placeholder="<?= lang('Customer.email') ?>" value="<?= old('cus_email') ?>">
                    </div>

                    <div class="form-group">
                        <label class="col-form-label"><?= lang('Customer.password') ?> <span
                                class="text-danger">*</span></label>
                        <input type="password"
                            class="form-control <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>"
                            name="password" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label class="col-form-label"><?= lang('Customer.password_confirm') ?> <span
                                class="text-danger">*</span></label>
                        <input type="password"
                            class="form-control <?php if (session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>"
                            name="password_confirm" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label><?= lang('Customer.birthday') ?> </label>
                        <div class="input-group date">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                            </div>
                            <input id="cus_datepicker" name="cus_birthday" class="form-control" type="text"
                                value="<?= old('cus_birthday') ?>" data-date-format="dd-mm-yyyy">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label><?= lang('Acp.country') ?> <span class="text-danger">*</span></label>
                                <?php if (isset($countries)): ?>
                                    <select name="country_id" class="form-control select_country" style="width: 100%;"
                                        id="country" country-selected="<?= old('country_id') ?? 200 ?>">
                                        <?php foreach ($countries as $country): ?>
                                            <option value="<?= $country->id ?>" data-flag="<?= $country->flags->svg ?>"
                                                data-code="<?= $country->code ?>"><?= $country->name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group ">
                                <label><?= lang('Acp.province') ?> <span class="text-danger">*</span> </label>
                                <select name="province_id" area-selected="<?= old('province_id') ?>"
                                    class="form-control select_province" style="width: 100%;"></select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group ">
                                <label><?= lang('Acp.district') ?> <span class="text-danger">*</span></label>
                                <select name="district_id" area-selected="<?= old('district_id') ?>"
                                    class="form-control select_district" style="width: 100%;"></select>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="postInputTitle"><?= lang('Acp.ward') ?> <span
                                        class="text-danger">*</span></label>
                                <select name="ward_id" area-selected="<?= old('ward_id') ?>"
                                    class="form-control select_ward" style="width: 100%;"></select>
                            </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <label><?= lang('Customer.address') ?></label>
                        <textarea class="form-control" name="cus_address"><?= old('cus_address') ?></textarea>
                    </div>

                </div>

                <div class="card-footer">
                    <input type="hidden" name="user_init" id="inpUserID" value="<?= $login_user->id ?>">
                    <button class="btn btn-primary mr-2" id="postSave" name="save" type="submit"><?= lang('Acp.save') ?>
                        (F2)</button>
                    <button class="btn btn-primary mr-2" id="postSaveExit" name="save_exit"
                        type="submit"><?= lang('Acp.save_exit') ?> (F7)</button>
                    <button class="btn btn-primary" name="save_addnew"
                        type="submit"><?= lang('Acp.save_addnew') ?></button>
                    <a href="<?= route_to('customer') ?>" class="btn btn-danger ml-2"
                        type="reset"><?= lang('Acp.cancel') ?></a>
                </div>
            </div>

        </div>
    </div>
</form>
<?= $this->endSection() ?>
<?= $this->section('pageStyles') ?>
<link rel="stylesheet"
    href="<?= base_url($config->scriptsPath) ?>/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
<script src="<?= base_url($config->scriptsPath) ?>/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js">
</script>
<script src="<?= base_url($config->scriptsPath) ?>/plugins/moment/moment.min.js"></script>
<script src="<?= base_url($config->scriptsPath) ?>/acp/areaLocation.js"></script>
<script src="<?= base_url($config->scriptsPath) ?>/acp/customer.js"></script>

<?= $this->endSection() ?>