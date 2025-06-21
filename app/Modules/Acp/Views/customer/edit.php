<?php

use CodeIgniter\I18n\Time;

echo $this->extend($config->viewLayout);
echo $this->section('content');
// print_r($customer->categories);exit;
$postConfigs = $config->cmsStatus;
?>
<!-- form start -->
<form id="<?= $controller ?>Form" role="form" method="post" class="form-horizontal" enctype="multipart/form-data">
    <div class="row" id="postApp" data-cattype="page">
        <?= csrf_field() ?>
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><?php echo lang('User.title_user_info')?></h3>
                </div>
                <div class="card-body pad">
                    <div class="form-group">
                        <label for="inputFullName" class="col-sm-2 col-form-label"><?= lang('Customer.full_name') ?> <span class="text-danger">*</span></label>
                        <input type="text" name="cus_full_name" class="form-control <?= session('errors.cus_full_name') ? 'is-invalid' : '' ?>"
                               id="inputFullName" placeholder="<?= lang('Customer.full_name') ?>" value="<?= $customer->cus_full_name ?>">
                    </div>

                    <div class="form-group">
                        <label for="inputPhone" class="col-sm-2 col-form-label"><?= lang('Customer.phone') ?> <span class="text-danger">*</span></label>
                        <input type="text" name="cus_phone" class="form-control <?= session('errors.cus_phone') ? 'is-invalid' : '' ?>"
                               id="inputPhone" placeholder="<?= lang('Customer.phone') ?>" value="<?= $customer->cus_phone ?>">
                    </div>
                    <div class="form-group">
                        <label for="inputEmail" class="col-sm-2 col-form-label"><?= lang('Customer.email') ?> <span class="text-danger">*</span></label>
                        <input type="text" disabled class="form-control"
                               id="inputEmail" placeholder="<?= lang('Customer.email') ?>" value="<?= $customer->cus_email ?>">
                    </div>


                    <div class="form-group">
                        <label><?=lang('Customer.birthday')?> </label>
                        <div class="input-group date">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                            </div>
                            <?php
                            if ( empty($customer->cus_birthday) ) {
                                $birthday = !empty(old('cus_birthday')) ? Time::parse(old('cus_birthday'))->format('d-m-Y') : '';
                            }
                            else {
                                $birthday = $customer->cus_birthday;
                            }
                            ?>
                            <input id="cus_datepicker" name="cus_birthday" class="form-control"  type="text" value="<?= $birthday?>"
                                   data-date-format="dd-mm-yyyy">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label><?= lang('Acp.country') ?></label>
                                <?php if (isset($countries)): ?>
                                    <select name="country_id" class="form-control select_country" style="width: 100%;"
                                        id="country" country-selected="<?= $customer->country_id ?? old('country_id') ?>">
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
                                <label><?= lang('Acp.province') ?> </label>
                                <select name="province_id" area-selected="<?= $customer->province_id ?>" class="form-control select_province" style="width: 100%;"></select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group ">
                                <label><?= lang('Acp.district') ?> </label>
                                <select name="district_id" area-selected="<?= $customer->district_id ?>" class="form-control select_district" style="width: 100%;"></select>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="postInputTitle"><?= lang('Acp.ward') ?></label>
                                <select name="ward_id" area-selected="<?= $customer->ward_id ?? '' ?>" class="form-control select_ward" style="width: 100%;"></select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><?= lang('Customer.address') ?> <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="cus_address"><?= $customer->cus_address ?></textarea>
                    </div>

                </div>

                <div class="card-footer">
                    <div class="col-sm-12 col-sm-offset-2">
                        <button class="btn btn-primary mr-2" name="save" id="postSave" type="submit"><?= lang('Acp.save') ?> (F2)</button>
                        <button class="btn btn-primary mr-2" name="save_exit" id="postSaveExit" type="submit"><?= lang('Acp.save_exit') ?> (F7)</button>
                        <button class="btn btn-primary" name="save_addnew" type="submit"><?= lang('Acp.save_addnew') ?></button>
                        <a href="<?= route_to('customer') ?>" class="btn btn-danger ml-2" type="reset"><?= lang('Acp.cancel') ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<?= $this->endSection() ?>
<?= $this->section('pageStyles') ?>
<link rel="stylesheet" href="<?=base_url($config->scriptsPath)?>/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<?= $this->endSection() ?>
<?php echo $this->section('pageScripts') ?>
<script src="<?= base_url($config->scriptsPath)?>/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url($config->scriptsPath)?>/plugins/moment/moment.min.js"></script>
<script src="<?= base_url($config->scriptsPath)?>/acp/areaLocation.js"></script>
<script src="<?= base_url($config->scriptsPath) ?>/acp/customer.js"></script>

<?= $this->endSection() ?>