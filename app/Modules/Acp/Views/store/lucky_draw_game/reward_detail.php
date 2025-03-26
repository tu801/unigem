<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 11/2/2023
 */

use Modules\Acp\Enums\Store\Promotion\PromotionEnum;
use Modules\Acp\Enums\UploadFolderEnum;

echo $this->extend($config->viewLayout);
echo $this->section('content')
?>
<div class="row" id="rewardApp">
    <div class="col-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><?=lang('Promo.minigame_player_info')?></h3>
            </div>

            <div class="card-body">
                <strong><i class="fas fa-book mr-1"></i> Tên người chơi</strong>
                <p class="text-muted"><?=$itemData->user_full_name ? $itemData->user_full_name : 'N/A'?></p>
                <hr>
                <strong><i class="fas fa-envelope mr-1"></i> Email</strong>
                <p class="text-muted"><?=$itemData->user_email ? $itemData->user_email : 'N/A'?></p>
                <hr>
                <strong><i class="fas fa-phone-alt mr-1"></i> Số điện thoại</strong>
                <p class="text-muted"><?=$itemData->user_phone ? $itemData->user_phone : 'N/A' ?></p>
            </div>

        </div>

        <?php if ( $itemData->status == \Modules\Acp\Enums\Store\Promotion\LuckyDrawHistoryStatusEnum::REWARD_CLAIMED ) : ?>
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Thông tin nhận quà</h3>
            </div>

            <div class="card-body">
                <strong><i class="fas fa-book mr-1"></i> Tên người nhận</strong>
                <p class="text-muted"><?=$itemData->ship_full_name ? $itemData->ship_full_name : 'N/A'?></p>
                <hr>
                <strong><i class="fas fa-envelope mr-1"></i> Email người nhận</strong>
                <p class="text-muted"><?=$itemData->ship_email ? $itemData->ship_email : 'N/A'?></p>
                <hr>
                <strong><i class="fas fa-phone-alt mr-1"></i> Số điện thoại người nhận</strong>
                <p class="text-muted"><?=$itemData->ship_telephone ? $itemData->ship_telephone : 'N/A' ?></p>
                <strong><i class="fas fa-phone-alt mr-1"></i> Địa chỉ</strong>
                <p class="text-muted"><?=$itemData->full_ship_address ? $itemData->full_ship_address : 'N/A' ?></p>
            </div>
            <div class="card-footer">
                <button type="button" id="btnSendGift" class="btn btn-primary btn-sm float-right">Gửi quà tặng</button>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <div class="col-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><?=lang('Promo.minigame_reward_info')?></h3>
            </div>
            <div class="card-body p-0">
                <?php if ( isset($rewardData->voucher_id) ) : ?>
                <table class="table">
                    <tr>
                        <td>
                            <?php
                            $imgUrl = ($rewardData->voucher_discount_type == PromotionEnum::DISCOUNT_TYPE_FREE_GIFT)
                                ? base_url('uploads/'. UploadFolderEnum::PROMOTION .'/'.$rewardData->discount_image)
                                : base_url($config->templatePath.'/assets/img/coupon.svg');
                            ?>
                            <img class="image  img-lg" loading="lazy" src="<?=$imgUrl?>" alt="coupon">
                        </td>
                        <td>
                            <p><?=$rewardData->voucher_code?></p>
                            <p><?=$rewardData->voucher_title?></p>
                        </td>
                        <td>
                            <p><?=lang('Promo.minigame_expired_date')?></p>
                            <p>
                                <?php
                                if (!empty($rewardData->voucher_end_date)) {
                                    $expiredDate = new \CodeIgniter\I18n\Time($rewardData->voucher_end_date);
                                    echo $expiredDate->format('d-m-Y');
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                            </p>
                        </td>
                        <td>
                            <p><?=lang('Acp.status')?></p>
                            <p>
                            <?php
                                switch ($rewardData->voucher_status) {
                                    case 0:
                                        echo '<span class="badge badge-warning">'.lang('Promo.promo_status_unused').'</span>';
                                        break;
                                    case 1:
                                        echo '<span class="badge badge-success">'.lang('Promo.promo_status_used').'</span>';
                                        break;
                                }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <?php if ( $rewardData->voucher_status == \Modules\Acp\Enums\Store\Promotion\EVoucherStatus::UNUSED
                                && $rewardData->voucher_discount_type == \Modules\Acp\Enums\Store\Promotion\PromotionEnum::DISCOUNT_TYPE_VALUE ) : ?>
                            <button type="button" id="showRewardPayForm" @click="showRewardPayForm" class="btn btn-primary float-right">Tặng tiền</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
                <?php endif; ?>

                <?php if ( $rewardData->voucher_status == \Modules\Acp\Enums\Store\Promotion\EVoucherStatus::USED
                && $rewardData->voucher_discount_type == \Modules\Acp\Enums\Store\Promotion\PromotionEnum::DISCOUNT_TYPE_VALUE ) :
                    $rewardLog = model(\Modules\Acp\Models\Store\Promotion\LuckyDrawRewardLogModel::class)
                        ->join('users', 'users.id = lucky_draw_reward_log.presenter_user_id')
                        ->select('lucky_draw_reward_log.*, users.username')
                        ->where('history_id', $itemData->history_id)
                        ->first();
                    $paymentInfo = isset($rewardLog->log_content) ? json_decode($rewardLog->log_content) : [];
                    ?>
                <div class="accordion" id="accordionExample">
                    <div class="card">
                        <div class="card-header" id="rewardLogHistory">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Admin #<?=$rewardLog->username ?? ''?> đã trao quà cho người chơi #<?=$itemData->user_full_name?>
                                </button>
                            </h2>
                        </div>

                        <div id="collapseOne" class="collapse show" aria-labelledby="accordionExample" >
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Tên Ngân Hàng</label>
                                    <div class="col-sm-10">
                                        <?=$paymentInfo->bank_name ?? ''?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Tên Chủ Tài Khoản</label>
                                    <div class="col-sm-10">
                                        <?=$paymentInfo->account_name ?? ''?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Số Tài Khoản</label>
                                    <div class="col-sm-10">
                                        <?=$paymentInfo->account_number ?? ''?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Ghi Chú</label>
                                    <div class="col-sm-10">
                                        <?=$paymentInfo->note ?? ''?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

        </div>

        <?=view($config->view.'\store\lucky_draw_game\_reward_payment_form')?>
        <?=view($config->view.'\store\lucky_draw_game\_reward_send_gift')?>

    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script src="<?= base_url($config->scriptsPath)?>/plugins/jquery-validation/jquery.validate.min.js"></script>
<script type="text/javascript">
    $('#showRewardPayForm').on('click', function () {
        $('#rewardPayForm').toggle();
    });

    $('#btnSendGift').on('click', function () {
        $('#rewardSendGiftForm').toggle();
    });

    $('#saveSendGiftForm').validate({
        rules: {
            note: {
                required: true
            },
        },
        messages: {
            note: "<?=lang('Promo.rw_payment_note_required')?>"
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });

    $('#saveRewardPayForm').validate({
        rules: {
            bank_name: {
                required: true,
            },
            account_name: {
                required: true,
            },
            account_number: {
                required: true,
                minlength: 5
            },
            note: {
                required: true
            },
        },
        messages: {
            bank_name: {
                required: "<?=lang('Promo.rw_payment_bank_name_required')?>",
            },
            account_name: {
                required: "<?=lang('Promo.rw_payment_bank_acc_required')?>",
            },
            account_number: {
                required: "<?=lang('Promo.rw_payment_bank_acc_number_required')?>",
                minlength: "<?=lang('Promo.rw_payment_bank_acc_number_minlength')?>"
            },
            note: "<?=lang('Promo.rw_payment_note_required')?>"
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
    $('.collapse').collapse();
</script>
<?= $this->endSection() ?>