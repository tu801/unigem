<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 11/16/2023
 */
?>
<div class="card card-primary " style="display: none" id="rewardPayForm">
    <div class="card-header">
        <h3 class="card-title">Tặng tiền cho khách trúng thưởng</h3>
    </div>
    <form action="<?=base_url(route_to('save_reward_payment'))?>" method="post" id="saveRewardPayForm">
        <?= csrf_field() ?>
        <div class="card-body">
            <div class="form-group row">
                <label for="inputBankName" class="col-sm-2 col-form-label">Tên Ngân Hàng <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                    <input type="text" name="bank_name" class="form-control" id="inputBankName" placeholder="Tên Ngân Hàng">
                </div>
            </div>

            <div class="form-group row">
                <label for="inputAccountName" class="col-sm-2 col-form-label">Tên Chủ Tài Khoản <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                    <input type="text" name="account_name" class="form-control" id="inputAccountName" placeholder="Tên Chủ Tài Khoản">
                </div>
            </div>

            <div class="form-group row">
                <label for="inputAccountNumber" class="col-sm-2 col-form-label">Số Tài Khoản <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                    <input type="text" name="account_number" class="form-control" id="inputAccountNumber" placeholder="Số Tài Khoản">
                </div>
            </div>

            <div class="form-group row">
                <label for="inputPaymentNote" class="col-sm-2 col-form-label">Ghi Chú <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                    <textarea name="note" class="form-control" id="inputPaymentNote"></textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <input type="hidden" name="voucher_id" value="<?=$rewardData->voucher_id?>">
            <input type="hidden" name="history_id" value="<?=$itemData->history_id?>">
            <button class="btn btn-success float-right" type="submit" >Lưu</button>
        </div>
    </form>
</div>