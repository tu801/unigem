<?php
/**
 * Author: tmtuan
 * Created date: 11/19/2023
 * Project: Unigem
 **/
?>
<div class="card card-outline card-primary" disableStatus="1">
    <div class="card-header">
        <div class="card-title">Thông tin giao hàng</div>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label>Tên người nhận<span class="text-danger">*</span></label>
            <input type="text"  name="receiver_full_name" class="form-control" >
        </div>
        <div class="form-group">
            <label>Số điện thoại người nhận<span class="text-danger">*</span></label>
            <input <?=$disable ? 'disabled' : ''?> type="text" name="receiver_phone" class="form-control <?= session('errors.phone') ? 'is-invalid' : '' ?>" placeholder="<?= lang('Số điện thoại') ?>" >
        </div>
        <div class="form-group">
            <label ><?= lang('Acp.province') ?> <span class="text-danger">*</span> </label>
            <select name="province_id" area-selected="<?= old('province_id') ?>" class="form-control select_province" style="width: 100%;"></select>
        </div>
        <div class="form-group ">
            <label><?= lang('Acp.district') ?> <span class="text-danger">*</span></label>
            <select name="district_id" area-selected="<?= old('district_id') ?>" class="form-control select_district" style="width: 100%;"></select>
        </div>
        <div class="form-group">
            <label for="postInputTitle"><?= lang('Acp.ward') ?> <span class="text-danger">*</span></label>
            <select name="ward_id" area-selected="<?= old('ward_id') ?>" class="form-control select_ward" style="width: 100%;"></select>
        </div>
        <div class="form-group">
            <label for="postInputTitle"><?= lang('Shop.address') ?> <span class="text-danger">*</span></label>
            <textarea class="form-control" name="address"><?= old('address') ?></textarea>
        </div>
    </div>
</div>
