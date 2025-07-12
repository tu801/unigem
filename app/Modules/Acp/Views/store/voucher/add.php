<?php

use \App\Enums\Store\Voucher\VoucherDiscountTypeEnum;
use \App\Enums\Store\Voucher\VoucherStatusEnum;

echo $this->extend($config->viewLayout);
echo $this->section('content');
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
                            placeholder="<?= lang('Shop.voucher_title') ?>" value="<?= old('voucher_title') ?>">
                    </div>
                    <div class="form-group">
                        <label for="inputCode"><?= lang('Shop.voucher_code') ?></label>
                        <input type="text" name="voucher_code" class="form-control" id="inputCode"
                            placeholder="<?= lang('Shop.voucher_code') ?>" value="<?= old('voucher_code') ?>">
                        <div class="mt-3">
                            <p class="text-warning text-small"><?= lang('Shop.voucher_code_info') ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputDescription"><?= lang('Shop.voucher_description') ?></label>
                        <textarea name="voucher_description" rows="3"
                            class="form-control" id="inputDescription"
                            placeholder="<?= lang('Shop.voucher_description') ?>"><?= old('voucher_description') ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="postInputTitle"><?= lang('Shop.voucher_discount_type') ?> <span class="text-danger">*</span></label>
                                <select name="voucher_discount_type" class="form-control" style="width: 100%;">
                                    <option value="<?= VoucherDiscountTypeEnum::PERCENTAGE ?>" <?= old('voucher_discount_type') == VoucherDiscountTypeEnum::PERCENTAGE ? 'selected' : '' ?>><?= lang('Shop.voucher_discount_type_percentage') ?></option>
                                    <option value="<?= VoucherDiscountTypeEnum::FIXED_AMOUNT ?>" <?= old('voucher_discount_type') == VoucherDiscountTypeEnum::FIXED_AMOUNT ? 'selected' : '' ?>><?= lang('Shop.voucher_discount_type_fixed_amount') ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="inputCode"><?= lang('Shop.voucher_discount_value') ?> <span class="text-danger">*</span></label>
                                <input type="text" name="voucher_discount_value" class="form-control <?= session('errors.voucher_discount_value') ? 'is-invalid' : '' ?>" id="inputCode"
                                    placeholder="<?= lang('Shop.voucher_discount_value') ?>" value="<?= old('voucher_discount_value') ?>">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    <input type="hidden" name="user_init" id="inpUserID" value="<?= $login_user->id ?>">
                    <button class="btn btn-primary mr-2" id="postSave" name="save" type="submit"><?= lang('Acp.save') ?> (F2)</button>
                    <button class="btn btn-primary mr-2" id="postSaveExit" name="save_exit" type="submit"><?= lang('Acp.save_exit') ?> (F7)</button>
                    <button class="btn btn-primary" name="save_addnew" type="submit"><?= lang('Acp.save_addnew') ?></button>
                    <a href="<?= route_to('list_shop') ?>" class="btn btn-default" type="reset"><?= lang('Acp.cancel') ?></a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-outline card-primary">
                <div class="card-body">
                    <div class="form-group ">
                        <label><?= lang('Shop.voucher_currency_list') ?></label>
                        <select class="form-control" name="currency">
                            <?php
                            $selectedItem = old('currency') ?? '';
                            $currencyList = config('Shop')->currencyList;
                            foreach ($currencyList as $key => $val) :
                                $selected = $selectedItem == $val ? 'selected' : '';
                            ?>
                                <option value='<?= $val ?>' <?= $selected ?>><?= $val ?></option>
                            <?php endforeach;    ?>
                        </select>
                    </div>
                    <div class="form-group ">
                        <label><?= lang('Post.post_status') ?></label>
                        <select class="form-control" name="voucher_status">
                            <?php
                            $selectedItem = old('voucher_status') ?? '';
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
                            placeholder="<?= lang('Shop.voucher_start_date') ?>" value="<?= old('voucher_start_date') ?>">
                    </div>

                    <div class="form-group">
                        <label for="inputCode"><?= lang('Shop.voucher_end_date') ?></label>
                        <input type="text" name="voucher_end_date" id="voucher_end_date" class="form-control"
                            placeholder="<?= lang('Shop.voucher_end_date') ?>" value="<?= old('voucher_end_date') ?>">
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

<?= $this->section('pageScripts') ?>
<script src="<?= base_url($config->scriptsPath) ?>/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url($config->scriptsPath) ?>/acp/voucher.js"></script>
<?= $this->endSection() ?>