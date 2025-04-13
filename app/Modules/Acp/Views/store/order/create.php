<?php
/**
 * Author: tmtuan
 * Created date: 11/19/2023
 * Project: Unigem
 **/

use App\Enums\Store\Order\EDeliveryType;
use App\Enums\Store\Order\EOrderStatus;
use App\Enums\Store\Order\EPaymentMethod;
use App\Enums\Store\Order\EPaymentStatus;

echo $this->extend($config->viewLayout);
echo $this->section('content');
?>

<div class="row" id="orderApp">
    <div class="col-md-8 col-sm-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <div class="card-title"><?= lang('Order.list_product') ?></div>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <label for="inputSearchProduct" class="col-sm-2 col-form-label"><?= lang('Order.search_product') ?></label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" id="inputSearchProduct" placeholder="<?= lang('Order.search_product') ?>">
                            <div class="input-group-append">
                                <button name="search" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- product list -->
                <div class="row text-center mt-2">
                    <table class="table table-striped" >
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col"><?= lang('Order.product_name') ?></th>
                            <th scope="col"><?= lang('Order.quantity') ?></th>
                            <th scope="col"><?= lang('Acp.actions') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">#1</th>
                                <td>Tủ lạnh Electrolux Inverter 335 lít EBB3742K-H</td>
                                <td class="w-25">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button type="button" @click="minusQuantityProduct(index)" class="btn btn-primary btn-sm">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                        <input type="number" class="form-control text-center" value="1">
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
                            <tr>
                                <th scope="row">#2</th>
                                <td>Tivi Casper EBB3742K-H</td>
                                <td class="w-25">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button type="button" @click="minusQuantityProduct(index)" class="btn btn-primary btn-sm">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                        <input type="number" class="form-control text-center" value="1">
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
                    <div class="text-center" v-if="order_items.length == 0"><?= lang('Order.no_product') ?></div>
                </div>

                <!-- order info -->
                <div class="row mt-2">
                    <div class="col-6">
                        <div class="form-group">
                            <label><?= lang('Order.title') ?></label>
                            <input type="text" name="title" class="form-control <?= session('errors.title') ? 'is-invalid' : '' ?>" value="<?= old('title') ?>" placeholder="<?= lang('Tiêu đề') ?>" >
                        </div>
                        <div class="form-group">
                            <label><?= lang('Order.note') ?></label>
                            <textarea class="form-control" name="note" placeholder="<?= lang('Ghi chú cho đơn hàng') ?>" ><?= old('note') ?></textarea>
                        </div>
                        <div class="form-group">
                            <label><?= lang('Order.payment_status') ?> <span class="text-danger">*</span> </label>
                            <select class="form-control" name="payment_status" >
                                <?php foreach (EPaymentStatus::toArray() as $item): ?>
                                    <option value="<?= $item ?>" <?= ($item == old('payment_status')) ? 'selected' : '' ?>><?= lang("Order.payment_status_{$item}") ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><?= lang('Order.payment_method') ?> <span class="text-danger">*</span> </label>
                            <select class="form-control" name="payment_method">
                                <?php foreach (EPaymentMethod::toArray() as $item): ?>
                                    <option value="<?= $item ?>" <?= ($item == old('payment_method')) ? 'selected' : '' ?>><?= lang("Order.payment_method_{$item}") ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><?= lang('Order.customer_paid') ?> </label>
                            <input type="number" name="customer_paid" v-model="order.customer_paid" class="form-control <?= session('errors.customer_paid') ? 'is-invalid' : '' ?>" placeholder="<?= lang('Order.customer_paid') ?>">
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <label><?= lang('Order.voucher_code') ?> </label>
                            <div class="input-group mb-3">
                                <input type="text" name="voucher_code" v-model="order.voucher_code" class="form-control <?= session('errors.voucher_code') ? 'is-invalid' : '' ?>" value="<?= old('voucher_code') ?>" placeholder="<?= lang('Order.voucher_code') ?>">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-success btn-sm" @click="applyVoucher()"><?= lang('Order.apply') ?></button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <th style="width:50%"><?=lang('Order.sub_total')?> :</th>
                                    <td>10.000.000đ</td>
                                </tr>
                                <tr>
                                    <th>
                                        <?=lang('Order.shipping_fee')?> :
                                        <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="<?=lang('Order.shipping_fee_tooltips')?>"></i>
                                    </th>
                                    <td> 30.000đ</td>
                                </tr>
                                <tr v-if="bill.discount > 0">
                                    <th><?=lang('Order.discount_amount')?> :</th>
                                    <td>-50.000đ</td>
                                </tr>
                                <tr>
                                    <th><?=lang('Order.order_total')?> :</th>
                                    <td>9.950.000đ</td>
                                </tr>
                                <tr >
                                    <th><?=lang('Order.debt')?> :</th>
                                    <td class="text-danger">0đ</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <div class="card-title">Thông tin khách hàng</div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label><?= lang('Order.shop_id') ?> <span class="text-danger">*</span> </label>
                    <select class="form-control" name="shop_id">
                        <?php foreach ($shops as $index => $item): ?>
                            <option value="<?= $item->shop_id ?>" <?= ($item->shop_id == old('shop_id')) ? 'selected' : '' ?>><?= $item->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label><?= lang('Order.customer_name') ?><span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="text" v-model="order.full_name"  name="full_name"  class="form-control <?= session('errors.full_name') ? 'is-invalid' : '' ?>" placeholder="<?= lang('Khách Hàng') ?>">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#customer-modal"><?= lang('Order.select_customer') ?></button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label><?= lang('Order.phone') ?><span class="text-danger">*</span></label>
                    <input type="text" v-model="order.phone" name="phone" class="form-control <?= session('errors.phone') ? 'is-invalid' : '' ?>" placeholder="<?= lang('Số điện thoại') ?>" >
                </div>

                <div class="form-group">
                    <label><?= lang('Order.email') ?></label>
                    <input type="text" v-model="order.email" name="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" placeholder="<?= lang('Email') ?>">
                </div>

                <div class="form-group">
                    <label><?= lang('Order.delivery_type') ?> <span class="text-danger">*</span></label>
                    <select class="form-control" name="delivery_type" v-model="order.delivery_type" @change="charge()">
                        <?php foreach (EDeliveryType::toArray() as $item): ?>
                            <option value="<?= $item ?>" <?= ($item == old('delivery_type')) ? 'selected' : '' ?>><?= lang("Order.delivery_type_{$item}") ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>
        </div>

        <?=view($config->view.'\store\order\_ship_information', ['disable'=> true]) ?>

    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script src="<?= base_url($config->scriptsPath)?>/acp/areaLocation.js"></script>
<?= $this->endSection() ?>