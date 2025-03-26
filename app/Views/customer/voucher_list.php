<?php
/**
 * Author: tmtuan
 * Created date: 11/13/2023
 * Project: Unigem
 **/

echo $this->extend($configs->viewLayout);
echo $this->section('content');

use Modules\Acp\Enums\Store\Promotion\LuckyDrawHistoryStatusEnum; ?>
<!-- breadcrumbs -->
<?php echo view_cell('App\Libraries\BreadCrumb\BreadCrumbCell') ?>
<!-- account -->
<div class="my_account_wrap section_padding_b" id="myVoucherApp">
    <div class="container">
        <div class="row">
            <!--  account sidebar  -->
            <div class="col-lg-3">
                <?= view('customer/components/account_sidebar', ['user' => $user]) ?>
            </div>
            <!-- account content -->
            <div class="col-lg-9">
                <div class="acprof_info_wrap voucher shadow_sm">
                    <h4 class="text_xl mb-3"><?=lang('CustomerProfile.cus_voucher_menu')?></h4>

                    <?= view('customer/templates/_message_block') ?>

                    <?php if ( isset($voucherData) && count($voucherData) ) :
                    foreach ( $voucherData as $voucher ) : ?>
                        <div class="payment_meth_wrp">
                            <div class="single_paymethod d-flex flex-wrap shadow_sm padding_default">
                                <div class="paymeth_img mb-3 mb-md-0">
                                    <img loading="lazy" src="<?=base_url($configs->templatePath)?>/assets/images/coupon.svg" alt="coupon">
                                </div>
                                <div class="single_orderdet w-130px ms-3 me-3">
                                    <h5></h5>
                                    <p style="font-size: large"><b><?=$voucher->voucher_code?></b></p>
                                </div>
                                <div class="single_orderdet ms-sm-auto ms-0 me-3">
                                    <h5><?=lang('CustomerProfile.voucher_title')?> </h5>
                                    <p><?=$voucher->voucher_title?></p>
                                </div>
                                <div class="single_orderdet ms-auto me-3">
                                    <h5><?=lang('CustomerProfile.voucher_expired_date')?></h5>
                                    <p>
                                        <?php
                                        if (!empty($voucher->voucher_end_date)) {
                                            $expiredDate = new \CodeIgniter\I18n\Time($voucher->voucher_end_date);
                                            echo $expiredDate->format('d-m-Y');
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </p>
                                </div>
<!--                                <div class="single_orderdet ms-auto me-3 w-sm-20 mb-3 mb-sm-0">-->
<!--                                    <h5>Defalut</h5>-->
<!--                                    <p>Yes</p>-->
<!--                                </div>-->
                                <div class="orderdet_btn ms-auto me-3 ">
                                    <?php
                                    // check if this voucher is free gift
                                    if ($voucher->voucher_discount_type == \Modules\Acp\Enums\Store\Promotion\PromotionEnum::DISCOUNT_TYPE_FREE_GIFT) {
                                        // check for the gift history
                                        $checkGameHistory = model(\Modules\Acp\Models\Store\Promotion\LuckyDrawHistoryModel::class)
                                            ->select('history_id, status')
                                            ->where('promo_voucher_id', $voucher->voucher_id)
                                            ->first();
                                        // check for history status of voucher
                                        if ( isset($checkGameHistory->history_id) && $checkGameHistory->status == LuckyDrawHistoryStatusEnum::NEW ) {
                                            echo '<a href="'.base_url(route_to('claim_gift', $voucher->voucher_id)).'" class="default_btn second rounded text-capitalize xs_btn me-2">'.lang('CustomerProfile.claim_gift').'</a>';
                                        } elseif ( isset($checkGameHistory->history_id) && $checkGameHistory->status == LuckyDrawHistoryStatusEnum::REWARD_CLAIMED ) {
                                            echo '<span class="badge badge-info text-color">'.lang('LuckyDraw.gift_processing').'</span>';
                                        } else {
                                            echo '<span class="badge badge-success text-color">'.lang('LuckyDraw.gift_processing_finish').'</span>';
                                        }
                                    } else { // this voucher is discount voucher
                                        if ( $voucher->voucher_status == \Modules\Acp\Enums\Store\Promotion\EVoucherStatus::UNUSED ) {
                                            echo '<a href="'.base_url().'" class="default_btn second rounded text-capitalize xs_btn">'.lang('CustomerProfile.use_voucher').'</a>';
                                        } else {
                                            echo '<span class="badge badge-success text-color">'.lang('CustomerProfile.voucher_is_used').'</span>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>

                        </div>
                    <?php endforeach; else: ?>
                    <p><?=lang('CustomerProfile.no_voucher_item')?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
