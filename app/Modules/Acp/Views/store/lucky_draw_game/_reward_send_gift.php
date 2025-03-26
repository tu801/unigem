<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 11/16/2023
 */
?>
<div class="card card-primary " style="display: none" id="rewardSendGiftForm">
    <div class="card-header">
        <h3 class="card-title">Gửi quà tặng cho khách trúng thưởng</h3>
    </div>
    <form action="<?=base_url(route_to('save_reward_send_gift'))?>" method="post" id="saveSendGiftForm">
        <?= csrf_field() ?>
        <div class="card-body">

            <div class="form-group row">
                <label for="inputNote" class="col-sm-2 col-form-label">Ghi Chú <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                    <textarea name="note" class="form-control" id="inputNote"></textarea>
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
