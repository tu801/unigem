<?php
/**
 * Author: tmtuan
 * Created date: 11/11/2023
 * Project: Unigem
 **/

echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>
<!-- breadcrumbs -->
<?php echo view_cell('App\Libraries\BreadCrumb\BreadCrumbCell') ?>
<!-- account -->

<!-- account -->
<div class="my_account_wrap section_padding_b">
    <div class="container">
        <div class="row">
            <!--  account sidebar  -->
            <div class="col-lg-3">
                <?= view('customer/components/account_sidebar', ['user' => $user]) ?>
            </div>

            <div class="col-lg-9">
                <div class="acprof_wrap shadow_sm">
                    <h4><?=lang('CustomerProfile.cus_information')?></h4>
                    <form action="<?=current_url()?>" method="post">
                        <?= csrf_field() ?>
                        <div class="row">
                            <?= view('customer/templates/_message_block') ?>

                            <div class="col-md-6">
                                <div class="single_billing_inp">
                                    <label><?= lang('AuthCustomer.fullname') ?> <span>*</span></label>
                                    <input name="full_name" class="<?= session('errors.full_name') ? 'form-control is-invalid' : '' ?>" type="text" placeholder="<?= lang('AuthCustomer.fullname') ?>" value="<?=$user->cus_full_name?>">
                                    <?php if (session('errors.full_name')): ?>
                                        <div class="invalid-feedback"><?= session('errors.full_name') ?></div>
                                    <?php endif;?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="single_billing_inp">
                                    <label><?= lang('AuthCustomer.email') ?> <span>*</span></label>
                                    <input disabled type="email" class="<?= session('errors.email') ? 'form-control is-invalid' : '' ?>" placeholder="example@mail.com" value="<?=$user->cus_email?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="single_billing_inp">
                                    <label><?= lang('AuthCustomer.phone_number') ?> <span>*</span></label>
                                    <input disabled class="<?= session('errors.phone') ? 'form-control is-invalid' : '' ?>" type="text" placeholder="<?= lang('AuthCustomer.phone_number') ?>" value="<?=$user->cus_phone?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="single_billing_inp">
                                    <label><?= lang('CustomerProfile.cus_birthday') ?> </label>
                                    <input name="cus_birthday" id="cus_datepicker" type="text" data-date-format="dd-mm-yyyy" value="<?= !empty($user->cus_birthday) ? $user->cus_birthday : date('d-m-Y')?>">
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="single_billing_inp">
                                    <label><?= lang('Acp.province') ?> <span class="text-danger">*</span> </label>
                                    <select style="width: 100%" name="province_id" area-selected="<?= $user->province_id ?? '' ?>"
                                            class="form-control select_province <?= session('errors.province_id') ? 'is-invalid' : '' ?>"></select>
                                    <?php if (session('errors.province_id')): ?>
                                        <div class="invalid-feedback"><?= session('errors.province_id') ?></div>
                                    <?php endif;?>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="single_billing_inp">
                                    <label><?= lang('Acp.district') ?> <span class="text-danger">*</span></label>
                                    <select name="district_id" area-selected="<?= $user->district_id ?? '' ?>"
                                            class="form-control select_district <?= session('errors.district_id') ? 'is-invalid' : '' ?>"></select>
                                    <?php if (session('errors.district_id')): ?>
                                        <div class="invalid-feedback"><?= session('errors.district_id') ?></div>
                                    <?php endif;?>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="single_billing_inp">
                                    <label for="postInputTitle"><?= lang('Acp.ward') ?> <span class="text-danger">*</span></label>
                                    <select name="ward_id" area-selected="<?= $user->ward_id ?? '' ?>"
                                            class="form-control select_ward <?= session('errors.ward_id') ? 'is-invalid' : '' ?>"></select>
                                    <?php if (session('errors.ward_id')): ?>
                                        <div class="invalid-feedback"><?= session('errors.ward_id') ?></div>
                                    <?php endif;?>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="single_billing_inp">
                                    <label for="postInputTitle"><?= lang('Shop.address') ?> <span class="text-danger">*</span></label>
                                    <textarea class="form-control <?= session('errors.address') ? 'is-invalid' : '' ?>" name="address"><?= $user->cus_address ?? '' ?></textarea>
                                    <?php if (session('errors.address')): ?>
                                        <div class="invalid-feedback"><?= session('errors.address') ?></div>
                                    <?php endif;?>
                                </div>
                            </div>

                            <div class="col-12 mt-3 acprof_subbtn">
                                <button type="submit" class="default_btn xs_btn rounded px-4 d-block w-100"><?=lang('Home.save_action')?></button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?=$this->section('style');?>
<link rel="stylesheet" href="<?=base_url($configs->scriptsPath)?>/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?=base_url($configs->scriptsPath)?>/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?=base_url($configs->scriptsPath)?>/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<?= $this->endSection() ?>

<?=$this->section('scripts');?>
<script src="<?=base_url($configs->scriptsPath)?>/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?=base_url($configs->scriptsPath.'/plugins/select2/js/select2.full.min.js')?>"></script>
<script src="<?=base_url($configs->scriptsPath.'/areaLocation.js')?>"></script>
<script>
    $(document).ready(function () {
        $("#cus_datepicker").datepicker({
            locale: 'vi',
            format: "dd-mm-yyyy",
            autoclose: true,
            startDate: '-70y',
            endDate: new Date()
        });
    });
</script>
<?= $this->endSection() ?>