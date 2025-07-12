<?php

use \App\Enums\Store\Voucher\VoucherDiscountTypeEnum;
use \App\Enums\Store\Voucher\VoucherStatusEnum;
use CodeIgniter\I18n\Time;

echo $this->extend($config->viewLayout);
echo $this->section('content');

$postConfigs = $config->cmsStatus;
?>
<!-- form start -->
<form id="<?= $controller ?>Form" role="form" method="post" class="form-horizontal" enctype="multipart/form-data">
    <div class="row" id="shopApp" data-cattype="page">
        <?= csrf_field() ?>
        <div class="col-md-8">
            <div class="card card-outline card-primary">
                <div class="card-body pad">
                    <div class="form-group">
                        <label for="inputTitle"><?= lang('Shop.voucher_title') ?> <span class="text-danger">*</span></label>
                        <input type="text" name="voucher_title" class="form-control <?= session('errors.voucher_title') ? 'is-invalid' : '' ?>" id="inputTitle"
                            placeholder="<?= lang('Shop.voucher_title') ?>" value="<?= $voucher->voucher_title ?>">
                    </div>
                    <div class="form-group">
                        <label for="inputCode"><?= lang('Shop.voucher_code') ?></label>
                        <input type="text" name="voucher_code" class="form-control" id="inputCode" disabled
                            placeholder="<?= lang('Shop.voucher_code') ?>" value="<?= $voucher->voucher_code ?>">
                    </div>
                    <div class="form-group">
                        <label for="inputDescription"><?= lang('Shop.voucher_description') ?></label>
                        <textarea name="voucher_description" rows="3"
                            class="form-control" id="inputDescription"
                            placeholder="<?= lang('Shop.voucher_description') ?>"><?= $voucher->voucher_description ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="postInputTitle"><?= lang('Shop.voucher_discount_type') ?> <span class="text-danger">*</span></label>
                                <select name="voucher_discount_type" class="form-control" style="width: 100%;">
                                    <option value="<?= VoucherDiscountTypeEnum::PERCENTAGE ?>" <?= $voucher->voucher_discount_type == VoucherDiscountTypeEnum::PERCENTAGE ? 'selected' : '' ?>><?= lang('Shop.voucher_discount_type_percentage') ?></option>
                                    <option value="<?= VoucherDiscountTypeEnum::FIXED_AMOUNT ?>" <?= $voucher->voucher_discount_type == VoucherDiscountTypeEnum::FIXED_AMOUNT ? 'selected' : '' ?>><?= lang('Shop.voucher_discount_type_fixed_amount') ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="inputCode"><?= lang('Shop.voucher_discount_value') ?> <span class="text-danger">*</span></label>
                                <input type="text" name="voucher_discount_value" class="form-control <?= session('errors.voucher_discount_value') ? 'is-invalid' : '' ?>" id="inputCode"
                                    placeholder="<?= lang('Shop.voucher_discount_value') ?>" value="<?= $voucher->voucher_discount_value ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="col-sm-12 col-sm-offset-2">
                        <button class="btn btn-primary mr-2" name="save" id="postSave" type="submit"><?= lang('Acp.save') ?> (F2)</button>
                        <button class="btn btn-primary mr-2" name="save_exit" id="postSaveExit" type="submit"><?= lang('Acp.save_exit') ?> (F7)</button>
                        <button class="btn btn-primary" name="save_addnew" type="submit"><?= lang('Acp.save_addnew') ?></button>
                        <a href="<?= route_to('list_shop') ?>" class="btn btn-default" type="reset"><?= lang('Acp.cancel') ?></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">

            <div class="card card-outline card-primary">
                <div class="card-body">
                    <div class="form-group ">
                        <label><?= lang('Post.post_status') ?></label>
                        <select class="form-control" name="voucher_status">
                            <?php
                            $selectedItem = $voucher->voucher_status ?? old('voucher_status');
                            foreach (VoucherStatusEnum::toArray() as $key => $val) :
                                $selected = $selectedItem == $val ? 'selected' : '';
                            ?>
                                <option value='<?= $val ?>' <?= $selected ?>><?= lang('Shop.voucher_status_' . $val) ?></option>
                            <?php endforeach;    ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="inputCode"><?= lang('Shop.voucher_start_date') ?> </label>
                        <input type="text" name="voucher_start_date" id="voucher_start_date" class="form-control"
                            placeholder="<?= lang('Shop.voucher_start_date') ?>" value="<?= !empty($voucher->voucher_start_date) ? Time::parse($voucher->voucher_start_date)->format('d-m-Y') : '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="inputCode"><?= lang('Shop.voucher_end_date') ?></label>
                        <input type="text" name="voucher_end_date" id="voucher_end_date" class="form-control"
                            placeholder="<?= lang('Shop.voucher_end_date') ?>" value="<?= !empty($voucher->voucher_end_date) ? Time::parse($voucher->voucher_end_date)->format('d-m-Y') : '' ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>

<?= $this->section('pageStyles') ?>
<link rel="stylesheet" href="<?= base_url($config->scriptsPath) ?>/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<?= $this->endSection() ?>

<?php echo $this->section('pageScripts') ?>
<script src="<?= base_url($config->scriptsPath) ?>/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url($config->scriptsPath) ?>/acp/voucher.js"></script>
<?= $this->endSection() ?>