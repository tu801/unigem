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
            <?=csrf_field()?>
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <div class="card-title"><?= lang('Order.info_basic') ?></div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <label><?= lang('Order.customer_name') ?><span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <input type="text" v-model="order.full_name"  name="full_name"  class="form-control <?= session('errors.full_name') ? 'is-invalid' : '' ?>" placeholder="<?= lang('Khách Hàng') ?>" readonly>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label><?= lang('Order.phone') ?><span class="text-danger">*</span></label>
                                <input type="text" v-model="order.phone" name="phone" class="form-control <?= session('errors.phone') ? 'is-invalid' : '' ?>" placeholder="<?= lang('Số điện thoại') ?>" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label><?= lang('Order.email') ?></label>
                                <input type="text" v-model="order.email"  name="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" placeholder="<?= lang('Email') ?>" readonly>
                            </div>
                        </div>
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
                    </div>
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
                    </div>

                    <div class="row" v-show="order.delivery_type == <?= EDeliveryType::HOME_DELIVERY ?>">
                        <div class="col-6">
                            <div class="form-group ">
                                <label><?= lang('Acp.province') ?> <span class="text-danger">*</span> </label>
                                <select name="province_id" area-selected="<?= $order->delivery_info->province_id ?? '' ?>" class="form-control select_province" style="width: 100%;"></select>
                            </div>
                        </div>
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
                        <div class="col-12">
                            <div class="form-group">
                                <label for="postInputTitle"><?= lang('Shop.address') ?> <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="address"><?= $order->delivery_info->address ?? '' ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label><?= lang('Order.title') ?></label>
                                <input type="text" name="title" class="form-control <?= session('errors.title') ? 'is-invalid' : '' ?>" value="<?= $order->title ?>" placeholder="<?= lang('Tiêu đề') ?>" >
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label><?= lang('Order.note') ?></label>
                                <textarea class="form-control" name="note" placeholder="<?= lang('Ghi chú') ?>" ><?= $order->note ?></textarea>
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
                                <select class="form-control" name="payment_status" v-model="order.payment_status">
                                    <?php foreach (EPaymentStatus::toArray() as $item): ?>
                                        <option value="<?= $item ?>" <?= ($item == $order->payment_status) ? 'selected' : '' ?>><?= lang("Order.payment_status_{$item}") ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label><?= lang('Order.payment_method') ?> <span class="text-danger">*</span> </label>
                                <select class="form-control"  name="payment_method">
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
                                <input type="number" name="customer_paid" v-model="order.customer_paid" class="form-control <?= session('errors.customer_paid') ? 'is-invalid' : '' ?>" placeholder="<?= lang('Order.customer_paid') ?>">
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
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <th style="width:50%"><?=lang('Order.sub_total')?> :</th>
                                        <td> {{ formatVnd(bill.sub_total) }}</td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <?=lang('Order.shipping_fee')?> :
                                            <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="<?=lang('Order.shipping_fee_tooltips')?>"></i>
                                        </th>
                                        <td>{{ formatVnd(bill.shipping_fee) }}</td>
                                    </tr>
                                    <tr v-if="bill.discount > 0">
                                        <th><?=lang('Order.discount_amount')?> :</th>
                                        <td>{{ formatVnd(bill.discount) }}</td>
                                    </tr>
                                    <tr>
                                        <th><?=lang('Order.order_total')?> :</th>
                                        <td>{{ formatVnd(bill.total) }}</td>
                                    </tr>
                                    <tr v-if="order.payment_status == <?= EPaymentStatus::DEPOSIT ?> && order.customer_paid != bill.total">
                                        <th><?=lang('Order.debt')?> :</th>
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
    <div class="modal fade" id="add-product-modal" tabindex="-1" aria-labelledby="add-product-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add-product-modal"><?= lang('Order.search_product') ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" @keyup="eventSearchProduct()" v-model="product_keyword_search" placeholder="<?= lang('Order.search_product') ?>">
                            <div class="input-group-append">
                                <button name="search" @click="searchProduct()" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center" v-for="(item, index) in products_search">
                                {{ item.pd_name }}
                                <button type="button" class="btn btn-primary btn-sm" @click="addProduct(index)"> <i class="fa fa-plus text"></i> </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="customer-modal" tabindex="-1" aria-labelledby="customer-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add-product-modal"><?= lang('Order.search_customer') ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" @keyup="eventSearchCustomer()" class="form-control" v-model="customer_keyword_search" placeholder="<?= lang('Order.search_customer') ?>">
                            <div class="input-group-append">
                                <button name="search" @click="searchCustomer()" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center" v-for="(item, index) in customers_search">
                                {{ item.cus_full_name }} - {{ item.cus_phone }}
                                <button type="button" class="btn btn-primary btn-sm" @click="selectCustomer(index)"> <i class="fa fa-plus text"></i> </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="cstoken" value="<?= csrf_hash() ?>">
    <input type="hidden" id="csname" value="<?= csrf_token() ?>">
</div>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
    <script src="<?= base_url($config->scriptsPath)?>/acp/areaLocation.js"></script>
    <script>
        const app = Vue.createApp({
            data() {
                return {
                    order: {
                        delivery_type: <?= $order->delivery_type ?>,
                        full_name: '<?= $order->customer_info->name ?? '' ?>',
                        phone: '<?= $order->customer_info->phone ?? '' ?>',
                        email: '<?= $order->customer_info->email ?? '' ?>',
                        payment_status: <?= $order->payment_status ?>,
                        customer_paid: <?= $order->customer_paid ?>,
                        voucher_code: '<?= $order->voucher_code ?? '' ?>',
                    },
                    order_items: [],
                    products_search: [],
                    product_keyword_search: '',
                    customers_search: [],
                    customer_keyword_search: '',
                    delay: null,
                    bill: {
                        sub_total: 0,
                        total: 0,
                        shipping_fee: 0,
                        discount: 0,
                        weight_product_total: 0,
                        ship_fee_province: 0,
                        ship_fee_on_weight: 0,
                    },
                    discount: {
                        value: <?= $voucher->voucher_discount_value ?? 0 ?>,
                        type: '<?= $voucher->voucher_discount_type ?? '' ?>',
                    }
                }
            },
            methods: {
                eventSearchCustomer () {
                    if (this.delay) {
                        clearTimeout(this.delay);
                        this.delay = null;
                    }

                    this.delay = setTimeout( () => {
                        this.searchCustomer()
                    }, 800);
                },
                eventSearchProduct () {
                    if (this.delay) {
                        clearTimeout(this.delay);
                        this.delay = null;
                    }
                    this.delay = setTimeout( () => {
                        this.searchProduct()
                    }, 800);
                },
                searchProduct() {
                    let formData = new FormData();

                    let csName = $("#csname").val();
                    let csToken = $("#cstoken").val();

                    // add a sign token
                    formData.append(csName, csToken);
                    formData.append('keyword_search', this.product_keyword_search);

                    // send data to server
                    $.ajax({
                        url: '<?= route_to('search_product') ?>',
                        data: formData,
                        dataType: "json",
                        contentType: false,
                        processData: false,
                        type: "POST",
                        success: (response) => {
                            if (response.error === 1) {
                                SwalAlert.fire({
                                    icon: "error",
                                    title: response.message,
                                });
                            }else {
                                this.products_search = response.data
                            }
                        }
                    });
                },
                searchCustomer() {
                    let formData = new FormData();

                    let csName = $("#csname").val();
                    let csToken = $("#cstoken").val();

                    // add a sign token
                    formData.append(csName, csToken);
                    formData.append('keyword_search', this.customer_keyword_search);

                    // send data to server
                    $.ajax({
                        url: '<?= route_to('search_customer') ?>',
                        data: formData,
                        dataType: "json",
                        contentType: false,
                        processData: false,
                        type: "POST",
                        success: (response) => {
                            if (response.error === 1) {
                                SwalAlert.fire({
                                    icon: "error",
                                    title: response.message,
                                });
                            }else {
                                this.customers_search = response.data
                            }
                        }
                    });
                },
                selectCustomer(index) {
                    const customer = this.customers_search[index];
                    this.order.full_name = customer.cus_full_name
                    this.order.phone = customer.cus_phone
                    this.order.email = customer.cus_email
                    $('#customer-modal').modal('hide')
                    if(customer.province_id && customer.district_id && customer.ward_id) {
                        $('[name="province_id"]').val(customer.province_id).change();
                        $('[name="district_id"]').val(customer.district_id).change();
                        $('[name="ward_id"]').val(customer.ward_id).change();
                        $('[name="address"]').val(customer.cus_address).change();

                    }
                    SwalAlert.fire({
                        icon: "success",
                        title: `Đã chon khách hàng ${customer.cus_full_name}`,
                    });
                },
                addProduct(index) {
                    const productID = this.products_search[index].id;
                    const hasProduct = (element) =>  element.id == productID;
                    const indexOrderItem = this.order_items.findIndex(hasProduct);
                    if (indexOrderItem == -1) {
                        this.order_items.push({...this.products_search[index],...{ quantity: 1}})
                        SwalAlert.fire({
                            icon: "success",
                            title: 'Thêm Sản phẩm thành công',
                        });
                    }else {
                        this.order_items[indexOrderItem].quantity += 1
                        SwalAlert.fire({
                            icon: "success",
                            title: 'Cộng số lượng Sản phẩm lên 1',
                        });
                    }
                    this.charge()
                },
                minusQuantityProduct(index) {
                    if(this.order_items[index].quantity > 1) {
                        this.order_items[index].quantity -= 1
                    }
                    this.charge()
                },
                plusQuantityProduct(index) {
                    this.order_items[index].quantity += 1
                    this.charge()
                },
                deleteProduct(index) {
                    this.order_items.splice(index, 1);
                    SwalAlert.fire({
                        icon: "success",
                        title: 'Xóa thành công',
                    });
                    this.charge()
                },
                getOrderItems() {
                    $.ajax({
                        url: '<?= route_to('items_order', $order->order_id) ?>',
                        dataType: "json",
                        contentType: false,
                        processData: false,
                        type: "GET",
                        success: (response) => {
                            if (response.error === 1) {
                                SwalAlert.fire({
                                    icon: "error",
                                    title: response.message,
                                });
                            }else {
                                this.order_items = response.data
                                this.charge()
                            }
                        }
                    });

                },
                charge() {
                    let sub_total = 0;
                    let total = 0;
                    let shipping_fee = 0;
                    let discount = 0;
                    let weightProductTotal = 0;
                    let shipFeeOnWeight = this.ship_fee_on_weight;
                    let shipFeeProvince = this.ship_fee_province;
                    // product bill
                    this.order_items.forEach((item) => {
                        const priceDiscount = Number(item.price_discount);
                        const price = Number(item.price);
                        const finalPrice = priceDiscount > 0 && priceDiscount < price ? priceDiscount : price;
                        const quantity = Number(item.quantity);
                        weightProductTotal += Number(item.product_meta.weight) * quantity;
                        total += finalPrice * quantity
                    })
                    sub_total = total
                    if (this.order.delivery_type == <?= EDeliveryType::HOME_DELIVERY ?>) {
                        shipping_fee    = (weightProductTotal * shipFeeOnWeight) + shipFeeProvince;
                    }else {
                        shipping_fee = 0
                    }

                    total = total + shipping_fee

                    if (this.discount.type && this.discount.value) {
                        if (this.discount.type == 'percent') {
                            discount = total * (this.discount.value / 100)
                        }
                        if (this.discount.type == 'value') {
                            discount = this.discount.value
                        }
                    }

                    this.bill.sub_total = sub_total
                    this.bill.total = total - discount
                    this.bill.shipping_fee = shipping_fee
                    this.bill.discount = discount
                    this.bill.weight_product_total = weightProductTotal
                },
                getShipFee() {
                    let province_id = $('[name="province_id"]').val() ?? 1
                    $.ajax({
                        url: '<?= route_to('get_shipping_fee') ?>?province_id=' + province_id,
                        dataType: "json",
                        contentType: false,
                        processData: false,
                        type: "GET",
                        success: (response) => {
                            if (response.error === 1) {
                                SwalAlert.fire({
                                    icon: "error",
                                    title: response.message,
                                });
                            } else {
                                this.ship_fee_province = Number(response.data.ship_fee_province)
                                this.ship_fee_on_weight = Number(response.data.ship_fee_on_weight)
                                this.charge()
                            }
                        }
                    });
                },
                applyVoucher() {
                    $.ajax({
                        url: base_url + 'order/apply-voucher?voucher_code=' + this.order.voucher_code,
                        dataType: "json",
                        contentType: false,
                        processData: false,
                        type: "GET",
                        success: (response) => {
                            if (response.error === 1) {
                                SwalAlert.fire({
                                    icon: "error",
                                    title: response.message,
                                });
                            } else {
                                this.discount = response.data
                                this.charge()
                            }
                        }
                    });
                },
                formatVnd(value) {
                    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
                }
            },
            mounted() {
                $('[name="province_id"]').change((data) => {
                    this.getShipFee()
                })
                this.getShipFee()
                this.getOrderItems()
            }
        });
        app.mount('#orderApp');
    </script>

<?= $this->endSection() ?>