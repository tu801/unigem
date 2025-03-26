<?php

use Modules\Acp\Enums\Store\Order\EDeliveryType;
use Modules\Acp\Enums\Store\Order\EPaymentMethod;

echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>
<!-- breadcrumbs -->
<?php echo view_cell('App\Libraries\BreadCrumb\BreadCrumbCell') ?>

    <!-- cart area -->
    <form method="post">
        <?= csrf_field() ?>
        <div class="cart_area section_padding_b">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-7 col-md-6">
                    <h4 class="shop_cart_title mb-4 ps-3"><?= lang('OrderShop.bill_detail') ?></h4>
                    <div class="billing_form">
                        <div class="row">
                            <div class="col-12">
                                <div class="single_billing_inp">
                                    <label for="full_name"><?= lang('Order.customer_name') ?><span>*</span></label>
                                    <input type="text" name="full_name" value="<?= $customer->cus_full_name ?? '' ?>" class="form-control <?= session('errors.full_name') ? 'is-invalid' : '' ?>">
                                    <?php if (session('errors.full_name')): ?>
                                        <div class="invalid-feedback"><?= session('errors.full_name') ?></div>
                                    <?php endif;?>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="single_billing_inp">
                                    <label for="phone"><?= lang('Order.phone') ?><span>*</span></label>
                                    <input type="text" name="phone" value="<?= $customer->cus_phone ?? '' ?>" class="form-control <?= session('errors.phone') ? 'is-invalid' : '' ?>">
                                    <?php if (session('errors.phone')): ?>
                                        <div class="invalid-feedback"><?= session('errors.phone') ?></div>
                                    <?php endif;?>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="single_billing_inp">
                                    <label for="email"><?= lang('Order.email') ?></label>
                                    <input type="text" name="email" value="<?= $customer->cus_email ?? '' ?>" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>">
                                    <?php if (session('errors.email')): ?>
                                        <div class="invalid-feedback"><?= session('errors.email') ?></div>
                                    <?php endif;?>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="single_billing_inp">
                                    <label><?= lang('Order.delivery_type') ?> <span class="text-danger">*</span> </label>
                                    <select class="form-control" name="delivery_type" v-model="order.delivery_type" @change="charge()">
                                        <?php foreach (EDeliveryType::toArray() as $item): ?>
                                            <option value="<?= $item ?>"> <?= lang("Order.delivery_type_{$item}") ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12" v-show="order.delivery_type == <?= EDeliveryType::PICK_UP  ?>">
                                <div class="single_billing_inp">
                                    <label><?= lang('Order.shop_id') ?> <span class="text-danger">*</span> </label>
                                    <select class="form-control" name="shop_id">
                                        <?php foreach ($shops as $index => $item): ?>
                                            <option value="<?= $item->shop_id ?>"><?= $item->name ?> - <?= $item->full_address ?? '' ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div v-show="order.delivery_type == <?= EDeliveryType::HOME_DELIVERY  ?>">
                                <div class="col-12">
                                    <div class="single_billing_inp">
                                        <label><?= lang('Acp.province') ?> <span class="text-danger">*</span> </label>
                                        <select name="province_id" area-selected="<?= $customer->province_id ?? '' ?>" class="form-control select_province"></select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="single_billing_inp">
                                        <label><?= lang('Acp.district') ?> <span class="text-danger">*</span></label>
                                        <select name="district_id" area-selected="<?= $customer->district_id ?? '' ?>" class="form-control select_district"></select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="single_billing_inp">
                                        <label for="postInputTitle"><?= lang('Acp.ward') ?> <span class="text-danger">*</span></label>
                                        <select name="ward_id" area-selected="<?= $customer->ward_id ?? '' ?>" class="form-control select_ward"></select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="single_billing_inp">
                                        <label for="postInputTitle"><?= lang('Shop.address') ?> <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="address"><?= $customer->cus_address ?? '' ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12" v-show="order.delivery_type == <?= EDeliveryType::PICK_UP  ?>">
                                <div class="single_billing_inp">
                                    <label> <?= lang('Order.payment_method') ?> <span class="text-danger">*</span> </label>
                                    <select class="form-control" name="payment_method">
                                        <?php foreach (EPaymentMethod::toArray() as $item): ?>
                                            <option value="<?= $item ?>"> <?= lang("Order.payment_method_{$item}") ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div v-for="(item, index) in carts">
                                <input type="hidden" min="1" :name="`product[${index}][quantity]`" :value="item.quantity">
                                <input type="hidden" :name="`product[${index}][product_id]`" :value="item.id">
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5 col-md-6">
                    <h4 class="shop_cart_title ps-3"><?= lang('OrderShop.your_order') ?></h4>
                    <div class="checkout_order mt-4">
                        <h4>Sản phẩm</h4>
                        <div class="single_check_order" v-for="(cart,index) in carts">
                            <div class="checkorder_cont">
                                <h5> {{ cart.pd_name }}</h5>
                            </div>
                            <p class="checkorder_qnty">x{{ cart.quantity }}</p>
                            <p class="checkorder_price">{{ formatVnd(((cart.price_discount > 0 && cart.price_discount < cart.price) ? cart.price_discount : cart.price) * cart.quantity) }}</p>
                        </div>

                        <div class="single_check_order subs">
                            <div class="checkorder_cont subtotal-h">
                                <h5><?= lang('Order.sub_total') ?></h5>
                            </div>
                            <p class="checkorder_price">{{ formatVnd(bill.sub_total)  }}</p>
                        </div>
                        <div class="single_check_order subs">
                            <div class="checkorder_cont subtotal-h">
                                <h5><?= lang('Order.shipping_fee') ?></h5>
                            </div>
                            <p class="checkorder_price">{{ formatVnd(bill.shipping_fee) }}</p>
                        </div>
                        <div class="single_check_order subs" v-if="bill.discount > 0">
                            <div class="checkorder_cont subtotal-h">
                                <h5><?= lang('Order.discount_amount') ?></h5>
                            </div>
                            <p class="checkorder_price text-danger"> -{{ formatVnd(bill.discount) }}</p>
                        </div>
                        <h5><?= lang('Order.discount_code') ?></h5>
                        <div class="cart_sum_coupon">
                            <input type="text" v-model="order.voucher_code" name="voucher_code" placeholder="<?= lang('Order.discount_code') ?>">
                            <button type="button" @click="applyVoucher()"><?= lang('Order.apply') ?></button>
                        </div>
                        <div class="single_check_order total">
                            <div class="checkorder_cont">
                                <h5><?= lang('Order.order_total') ?> </h5>
                            </div>
                            <p class="checkorder_price">{{ formatVnd(bill.total)  }}</p>
                        </div>
                        <div class="checkorder_btn">
                            <button type="submit" class="default_btn rounded w-100"><?=  lang('OrderShop.complete_order')  ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
<?= $this->endSection() ?>

<?= $this->section('style'); ?>
<link rel="stylesheet" href="<?=base_url($configs->scriptsPath)?>/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?=base_url($configs->scriptsPath)?>/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<?= $this->endSection() ?>

<?= $this->section('scripts'); ?>
<script src="<?=base_url($configs->scriptsPath.'/plugins/select2/js/select2.full.min.js')?>"></script>
<script src="<?=base_url($configs->scriptsPath.'/areaLocation.js')?>"></script>
<?= $this->endSection() ?>

