<?php

use App\Enums\Store\Order\EDeliveryType;
use App\Enums\Store\Order\EOrderStatus;
use App\Enums\Store\Order\EPaymentMethod;
use App\Enums\Store\Order\EPaymentStatus;

echo $this->extend($config->viewLayout);
echo $this->section('content');
?>
<div class="row" id="orderApp">
    <div class="col-md-12">
        <form action="" method="post">
            <?= csrf_field() ?>
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <div class="card-title"><?= lang('Order.info_basic') ?></div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label><?= lang('Order.shop_id') ?> <span class="text-danger">*</span> </label>
                                <select class="form-control" name="shop_id">
                                    <?php foreach ($shops as $index => $item): ?>
                                        <option value="<?= $item->shop_id ?>" <?= ($item->shop_id == $order->shop_id) ? 'selected' : '' ?>><?= $item->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-6">
                            <label><?= lang('Order.customer_name') ?></label>
                            <div class="input-group mb-3">
                                <input type="text" value="<?= $order->cus_full_name ?>" class="form-control" placeholder="<?= lang('Khách Hàng') ?>" readonly>
                                <input type="hidden" name="customer_id" value="<?= $order->customer_id ?>">
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label><?= lang('Order.phone') ?></label>
                                <input type="text" value="<?= $order->cus_phone ?>" class="form-control" placeholder="<?= lang('Số điện thoại') ?>" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label><?= lang('Order.email') ?></label>
                                <input type="text" value="<?= $order->cus_email ?>" class="form-control" placeholder="<?= lang('Email') ?>" readonly>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label><?= lang('Order.title') ?></label>
                                <input type="text" name="title" class="form-control <?= session('errors.title') ? 'is-invalid' : '' ?>" value="<?= $order->title ?>">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label><?= lang('Order.note') ?></label>
                                <textarea class="form-control" name="note" placeholder="<?= lang('Ghi chú') ?>"><?= $order->note ?></textarea>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label><?= lang('Order.status') ?> <span class="text-danger">*</span> </label>
                                <select class="form-control" name="status">
                                    <?php foreach (EOrderStatus::toArray() as $item): ?>
                                        <option value="<?= $item ?>" <?= ($item == $order->status) ? 'selected' : '' ?>><?= lang("Order.order_status_{$item}") ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label><?= lang('Order.payment_status') ?> <span class="text-danger">*</span> </label>
                                <select class="form-control" name="payment_status" v-model="order.payment_status" @change="onPaymentStatusChange">
                                    <?php foreach (EPaymentStatus::toArray() as $item): ?>
                                        <option value="<?= $item ?>" <?= ($item == $order->payment_status) ? 'selected' : '' ?>><?= lang("Order.payment_status_{$item}") ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label><?= lang('Order.payment_method') ?> <span class="text-danger">*</span> </label>
                                <select class="form-control" name="payment_method">
                                    <?php foreach (EPaymentMethod::toArray() as $item): ?>
                                        <option value="<?= $item ?>" <?= ($item ==  $order->payment_method) ? 'selected' : '' ?>><?= lang("Order.payment_method_{$item}") ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-6">
                            <label><?= lang('Order.voucher_code') ?> </label>
                            <div class="input-group mb-3">
                                <input name="voucher_code" v-model="order.voucher_code" class="form-control <?= session('errors.voucher_code') ? 'is-invalid' : '' ?>">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-success btn-sm" @click="applyVoucher()"><?= lang('Order.apply') ?></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-6" v-show="order.payment_status != <?= EPaymentStatus::UNPAID ?>">
                            <div class="form-group">
                                <label><?= lang('Order.customer_paid') ?> </label>
                                <input type="number" class="form-control" id="inputCustomerPaid" @input="onCustomerPaidInput"
                                    placeholder="<?= lang('Order.customer_paid') ?>">
                                <input type="hidden" name="customer_paid" id="customer_paid">
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <!-- Shipping info -->
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <div class="card-title"><?= lang('Order.shipping_info') ?></div>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label><?= lang('Order.delivery_type') ?> <span class="text-danger">*</span></label>
                                <select class="form-control" name="delivery_type" v-model="order.delivery_type" @change="charge()">
                                    <?php foreach (EDeliveryType::toArray() as $item): ?>
                                        <option value="<?= $item ?>" <?= ($item == $order->delivery_type) ? 'selected' : '' ?>><?= lang("Order.delivery_type_{$item}") ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label><?= lang('Acp.country') ?> </label>
                                <?php if (isset($countries)): ?>
                                    <select name="country_id" class="form-control select_country" style="width: 100%;"
                                        id="country" country-selected="<?= $order->delivery_info->country_id ?? old('country_id') ?>" v-model="order.country">
                                        <?php foreach ($countries as $country): ?>
                                            <option value="<?= $country->id ?>" data-flag="<?= $country->flags->svg ?>"
                                                data-code="<?= $country->code ?>"><?= $country->name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6" v-show="order.delivery_type == <?= EDeliveryType::HOME_DELIVERY ?> ">
                            <label><?= lang('Order.ship_full_name') ?> <span class="text-danger">*</span></label>
                            <input type="text" name="ship_full_name" value="<?= $order->delivery_info->ship_full_name ?? old('ship_full_name') ?>"
                                class="form-control <?= session('errors.ship_full_name') ? 'is-invalid' : '' ?>">
                        </div>

                        <div class="col-6" v-show="order.delivery_type == <?= EDeliveryType::HOME_DELIVERY ?> ">
                            <label><?= lang('Order.ship_telephone') ?> <span class="text-danger">*</span></label>
                            <input type="text" name="ship_telephone" value="<?= $order->delivery_info->ship_telephone ?? old('ship_telephone') ?>"
                                class="form-control <?= session('errors.ship_telephone') ? 'is-invalid' : '' ?>">
                        </div>

                        <div class="col-6" v-show="order.delivery_type == <?= EDeliveryType::HOME_DELIVERY ?> ">
                            <label><?= lang('Order.ship_email') ?> </label>
                            <input type="text" name="ship_email" value="<?= $order->delivery_info->ship_email ?? old('ship_email') ?>"
                                class="form-control">
                        </div>

                        <div class="col-6" v-show="order.delivery_type == <?= EDeliveryType::HOME_DELIVERY ?> && order.country == <?= VIETNAM_COUNTRY_ID ?> ">
                            <div class="form-group ">
                                <label><?= lang('Acp.province') ?> <span class="text-danger">*</span> </label>
                                <select name="province_id" area-selected="<?= $order->delivery_info->province_id ?? '' ?>" class="form-control select_province" style="width: 100%;"></select>
                            </div>
                        </div>
                    </div>

                    <div class="row" v-show="order.delivery_type == <?= EDeliveryType::HOME_DELIVERY ?>">
                        <div class="col-6">
                            <div class="form-group ">
                                <label><?= lang('Acp.district') ?> <span class="text-danger">*</span></label>
                                <select name="district_id" area-selected="<?= $order->delivery_info->district_id ?? '' ?>" class="form-control select_district" style="width: 100%;"></select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="postInputTitle"><?= lang('Acp.ward') ?> <span class="text-danger">*</span></label>
                                <select name="ward_id" area-selected="<?= $order->delivery_info->ward_id ?? '' ?>" class="form-control select_ward" style="width: 100%;"></select>
                            </div>
                        </div>

                    </div>

                    <div class="row" v-show="order.delivery_type == <?= EDeliveryType::HOME_DELIVERY ?>">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="postInputTitle"><?= lang('Shop.address') ?> <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="address"><?= $order->delivery_info->cus_address ?? '' ?></textarea>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-8">
                    <!-- product list-->
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <div class="card-title"><?= lang('Order.list_product') ?></div>
                            <div class="card-tools">
                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#add-product-modal"> Thêm</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <table class="table table-striped" v-if="order_items.length > 0">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col"><?= lang('Order.product_name') ?></th>
                                            <th scope="col"><?= lang('Order.quantity') ?></th>
                                            <th scope="col"><?= lang('Order.unit_price') ?></th>
                                            <th scope="col"><?= lang('Acp.actions') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in order_items">
                                            <th scope="row">{{ index + 1 }}</th>
                                            <td>{{ item.pd_name }}</td>
                                            <td class="w-50">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <button type="button" @click="minusQuantityProduct(index)" class="btn btn-primary btn-sm">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                    </div>
                                                    <input type="number" class="form-control text-center" v-model="item.quantity">
                                                    <div class="input-group-append">
                                                        <button type="button" @click="plusQuantityProduct(index)" class="btn btn-primary btn-sm">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                            </td>
                                            <td>{{ getDisplayPrice(item) }}</td>
                                            <td>
                                                <button type="button" @click="deleteProduct(index)" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center" v-if="order_items.length == 0"><?= lang('Order.no_product') ?></div>
                        </div>
                        <div v-for="(item, index) in order_items">
                            <input type="hidden" min="1" :name="`product[${index}][quantity]`" :value="item.quantity">
                            <input type="hidden" :name="`product[${index}][product_id]`" :value="item.id">
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card card-outline card-primary">
                        <div class="card-body">
                            <div class="alert alert-warning ">
                                <i class="icon fas fa-info"></i>
                                <?= lang('Order.currency_exchange_note') ?>
                            </div>

                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <?php if ($currentLang->id != 1) : ?>
                                            <tr>
                                                <th style="width:50%"><?= lang('Order.exchange_rate') ?> :</th>
                                                <td> {{ formatVnd(order.exchange_rate) }}</td>
                                            </tr>
                                        <?php endif; ?>
                                        <tr>
                                            <th style="width:50%"><?= lang('Order.sub_total') ?> :</th>
                                            <td> {{ formatVnd(bill.sub_total) }}</td>
                                        </tr>
                                        <!-- <tr>
                                        <th>
                                            <?= lang('Order.shipping_fee') ?> :
                                            <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="<?= lang('Order.shipping_fee_tooltips') ?>"></i>
                                        </th>
                                        <td>{{ formatVnd(bill.shipping_fee) }}</td>
                                    </tr> -->
                                        <tr v-if="bill.discount > 0">
                                            <th><?= lang('Order.discount_amount') ?> :</th>
                                            <td>-{{ formatVnd(bill.discount) }}</td>
                                        </tr>
                                        <tr>
                                            <th><?= lang('Order.order_total') ?> :</th>
                                            <td>{{ formatVnd(bill.total) }}</td>
                                        </tr>
                                        <tr v-if="order.payment_status == <?= EPaymentStatus::DEPOSIT ?> && order.customer_paid != bill.total">
                                            <th><?= lang('Order.debt') ?> :</th>
                                            <td class="text-danger">{{ formatVnd((bill.total - order.customer_paid)) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-outline card-primary">
                <div class="card-body">
                    <button class="btn btn-primary mr-2" id="postSave" name="save" type="submit"><?= lang('Acp.save') ?> (F2)</button>
                    <button class="btn btn-primary mr-2" id="postSaveExit" name="save_exit" type="submit"><?= lang('Acp.save_exit') ?> (F7)</button>
                    <button class="btn btn-primary mr-2" name="save_addnew" type="submit"><?= lang('Acp.save_addnew') ?></button>
                    <a href="<?= route_to('order') ?>" class="btn btn-default mr-2" type="reset"><?= lang('Acp.cancel') ?></a>
                </div>
            </div>

        </form>
    </div>

    <?= view($config->view . '\components\add_product_modal') ?>
    <?= view($config->view . '\components\search_customer_modal') ?>

    <input type="hidden" id="cstoken" value="<?= csrf_hash() ?>">
    <input type="hidden" id="csname" value="<?= csrf_token() ?>">
</div>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script src="<?= base_url($config->scriptsPath) ?>/acp/areaLocation.js"></script>
<script src="<?= base_url($config->scriptsPath) ?>/acp/order.js"></script>
<script>
    const countryElement = $("#country");
    $(document).ready(function() {
        countryElement.select2({
            templateResult: function(state) {
                if (!state.id) return state.text;
                var flag = $(state.element).data("flag");
                if (flag) {
                    return $(
                        '<span><img src="' +
                        flag +
                        '" style="width:20px;height:14px;vertical-align:middle;object-fit:contain;margin-right:6px;">' +
                        state.text +
                        "</span>"
                    );
                }
                return state.text;
            },
            templateSelection: function(state) {
                if (!state.id) return state.text;
                var flag = $(state.element).data("flag");
                if (flag) {
                    return $(
                        '<span><img src="' +
                        flag +
                        '" style="width:20px;height:14px;vertical-align:middle;object-fit:contain;margin-right:6px;">' +
                        state.text +
                        "</span>"
                    );
                }
                return state.text;
            },
            escapeMarkup: function(m) {
                return m;
            },
        });

        // Set selected value sau khi bind event
        const country_selected_value = $("#country").attr("country-selected");
        if (country_selected_value !== undefined && country_selected_value > 0) {
            $("#country").val(country_selected_value);
            $("#country").trigger("change");
        }
    });

    const order_id = '<?= $order->order_id ?>';
    const voucherCode = '<?= $order->voucher_code ?? old('voucher_code') ?>';
    const lang_id = '<?= $order->lang_id ?>';
    const currency = '<?= $order->currency ?>';
    const delivery_type = '<?= $order->delivery_type ?? old('delivery_type') ?>';
    const full_name = '<?= $order->cus_full_name ?>';
    const phone = '<?= $order->cus_phone ?>';
    const email = '<?= $order->cus_email ?>';
    const payment_status = <?= $order->payment_status ?? old('payment_status') ?? EPaymentStatus::UNPAID ?>;
    const customer_paid = <?= $order->customer_paid ?? old('customer_paid') ?? 0 ?>;
    const exchange_rate = <?= $order->exchange_rate ?>;

    const search_product_url = '<?= route_to('search_product') ?>';
    const search_customer_url = '<?= route_to('search_customer') ?>';
    const get_shipping_fee_base_url = '<?= route_to('get_shipping_fee') ?>?province_id=';

    const messages = {
        addItemToCartSuccess: '<?= lang('Order.addItemToCartSuccess') ?>',
        increaseItemQuantity: '<?= lang('Order.increaseItemQuantity') ?>',
        deleteItemFromCartSuccess: '<?= lang('Order.deleteItemFromCartSuccess') ?>',
        invalidVoucherCurrency: '<?= lang('Order.invalidVoucherCurrency') ?>',
        voucherAppliedSuccess: '<?= lang('Order.voucherAppliedSuccess') ?>',
    };

    const HomeDeliveryType = <?= $order->delivery_type ?? EDeliveryType::HOME_DELIVERY ?>;
    const VietNamCountryId = <?= VIETNAM_COUNTRY_ID ?>;


    orderApp.mount('#orderApp');
</script>

<?= $this->endSection() ?>