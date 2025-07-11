<?php

use App\Enums\Store\Order\EDeliveryType;

echo $this->extend($configs->viewLayout);
echo $this->section('content');
// dd($customer);
?>
<!-- page-title -->
<div class="tf-page-title">
    <div class="container-full">
        <div class="row">
            <div class="col-12">
                <div class="heading text-center"><?= $page_title ?></div>
                <?php echo view_cell('App\Libraries\BreadCrumb\BreadCrumbCell') ?>
            </div>
        </div>
    </div>
</div>
<!-- /page-title -->

<!-- checkout -->
<section class="flat-spacing-11">
    <div class="container">
        <?= view($configs->view . '\components\session-alert-block') ?>

        <form action="<?= route_to('order_checkout') ?>" method="post" id="checkoutForm">
            <?= csrf_field() ?>
            <div class="tf-page-cart-wrap layout-2">
                <div class="tf-page-cart-item">
                    <div class="form-checkout">
                        <h5 class="fw-5 mb_20"><?= lang('Order.billing_detail') ?></h5>

                        <div class="box grid-2">
                            <fieldset class="fieldset">
                                <label for="first-name"><?= lang('Order.customer_name') ?></label>
                                <input type="text" id="first-name" name="customer_name" value="<?= old('first_name', $customer->cus_full_name ?? '') ?>">
                            </fieldset>
                            <fieldset class="fieldset">
                                <label for="phone"><?= lang('Order.customer_phone') ?></label>
                                <input type="text" id="phone" name="customer_phone" value="<?= old('phone', $customer->cus_phone ?? '') ?>">
                            </fieldset>
                        </div>
                        <fieldset class="box fieldset no-required">
                            <label for="email"><?= lang('Order.customer_email') ?></label>
                            <input type="email" id="email" name="customer_email" value="<?= old('email', $customer->cus_email ?? '') ?>">
                        </fieldset>


                        <div id="shipping_address" class="mb_20">
                            <hr>
                            <h5 class="mb_15"><?= lang('Order.shipping_address') ?></h5>

                            <div class="box grid-2">
                                <fieldset class="fieldset">
                                    <label for="ship_full_name"><?= lang('Order.ship_full_name') ?></label>
                                    <input type="text" id="ship_full_name" name="ship_full_name" value="<?= old('ship_full_name', $customer->cus_full_name ?? '') ?>">
                                </fieldset>
                                <fieldset class="fieldset">
                                    <label for="ship_telephone"><?= lang('Order.ship_telephone') ?></label>
                                    <input type="text" id="ship_telephone" name="ship_telephone" value="<?= old('ship_telephone', $customer->cus_phone ?? '') ?>">
                                </fieldset>
                            </div>
                            <fieldset class="box fieldset no-required">
                                <label for="ship_email"><?= lang('Order.ship_email') ?></label>
                                <input type="email" id="ship_email" name="ship_email" value="<?= old('ship_email', $customer->cus_email ?? '') ?>">
                            </fieldset>

                            <fieldset class="box fieldset">
                                <label for="country"><?= lang('Common.country') ?></label>
                                <div class="select-custom">
                                    <select class="tf-select w-100" id="country" name="country_id" country-selected="<?= old('country_id', $customer->country_id) ?>">
                                        <?php foreach ($countries as $country): ?>
                                            <option value="<?= $country->id ?>" <?= ($country->id == old('country_id', $customer->country_id)) ? 'selected' : '' ?>
                                                data-code="<?= $country->code ?>" data-flag="<?= $country->flags->svg ?>">
                                                <?= $country->name ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </fieldset>
                            <div id="vietnam_address" class="<?= $customer->country_id != VIETNAM_COUNTRY_ID ? 'd-none' : '' ?>">
                                <div class="mb_15">
                                    <select class=" w-100 select_province" id="province" name="province_id"
                                        area-selected="<?= $customer->province_id ?? old('province_id', $customer->province_id) ?>"></select>
                                </div>
                                <div class=" mb_15">
                                    <select class=" w-100 select_district" id="district" name="district_id"
                                        area-selected="<?= $customer->district_id ?? old('district_id', $customer->district_id) ?>"></select>
                                </div>
                                <div class=" mb_15">
                                    <select class=" w-100 select_ward" id="ward" name="ward_id"
                                        area-selected="<?= $customer->ward_id ?? old('ward_id', $customer->ward_id) ?>"></select>
                                </div>
                            </div>

                            <fieldset class="box fieldset">
                                <label for="address"><?= lang('Customer.address') ?></label>
                                <input type="text" id="address" name="ship_address" value="<?= old('address', $customer->cus_address ?? '') ?>">
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="tf-page-cart-footer">
                    <div class="tf-cart-footer-inner">
                        <h5 class="fw-5 mb_20">Your order</h5>
                        <div class="tf-page-cart-checkout widget-wrap-checkout">
                            <ul class="wrap-checkout-product">
                                <li class="checkout-product-item" v-for="(product, index) in carts" :key="index">
                                    <figure class="img-product">
                                        <img :src="product.feature_image.thumbnail" :alt="product.pd_name">
                                        <span class="quantity">{{ product.quantity }}</span>
                                    </figure>
                                    <div class="content">
                                        <div class="info">
                                            <p class="name">{{ product.pd_name }}</p>
                                            <!-- <span class="variant">Brown / M</span> -->
                                        </div>
                                        <span class="price">{{ formatCurrency(calculateFinalProductPrice(product) * product.quantity) }}</span>
                                    </div>
                                </li>
                            </ul>

                            <div v-for="(item, index) in carts">
                                <input type="hidden" min="1" :name="`product[${index}][quantity]`" :value="item.quantity">
                                <input type="hidden" :name="`product[${index}][product_id]`" :value="item.id">
                            </div>

                            <div class="d-flex justify-content-between line pb_20">
                                <h6 class="fw-5"><?= lang('Order.sub_total') ?></h6>
                                <h6 class="total fw-5">{{ formatCurrency(bill.sub_total) }}</h6>
                            </div>

                            <div class="d-flex justify-content-between line pb_20" v-if="bill.discount > 0">
                                <h6 class="fw-5"><?= lang('Order.discount') ?></h6>
                                <h6 class="total fw-5">{{ formatCurrency(bill.discount) }}</h6>
                                <input type="hidden" name="voucher_code" :value="voucher.voucher_code">
                            </div>

                            <div class="d-flex justify-content-between line pb_20" v-if="order.lang_id > 1">
                                <h6 class="fw-5"><?= lang('Order.exchange_rate') ?></h6>
                                <h6 class="total fw-5">{{ formatCurrency(order.exchange_rate) }}</h6>
                            </div>
                            <div class="d-flex justify-content-between line pb_20" v-if="order.lang_id > 1">
                                <h6 class="fw-5"><?= lang('Order.total_in_vnd') ?></h6>
                                <h6 class="total fw-5">{{ formatVnd(bill.total * order.exchange_rate) }}</h6>
                            </div>

                            <div class="d-flex justify-content-between line pb_20">
                                <h6 class="fw-5"><?= lang('Order.total') ?></h6>
                                <h6 class="total fw-5">{{ formatCurrency(bill.total) }}</h6>
                            </div>

                            <h6 class="fw-5 mb_20"><?= lang('Order.delivery_type') ?></h6>
                            <div class="wd-check-payment">
                                <div class="fieldset-radio mb_20">
                                    <input type="radio" name="delivery_type" id="pickupType" class="tf-check" checked="" value="<?= EDeliveryType::PICK_UP ?>">
                                    <label for="pickupType"><?= lang('Order.deliveryMethod.pick_up') ?></label>
                                </div>
                                <div class="fieldset-radio mb_20">
                                    <input type="radio" name="delivery_type" id="homeDelivery" class="tf-check" value="<?= EDeliveryType::HOME_DELIVERY ?>">
                                    <label for="homeDelivery"><?= lang('Order.deliveryMethod.home_delivery') ?></label>
                                </div>

                                <p class="text_black-2 mb_20"><?= lang('Order.payment_currency_policy') ?></p>
                                <!-- <div class="box-checkbox fieldset-radio mb_20">
                                    <input type="checkbox" id="check-agree" class="tf-check">
                                    <label for="check-agree" class="text_black-2">I have read and agree to the
                                        website <a href="terms-conditions.html" class="text-decoration-underline">terms and conditions</a>.</label>
                                </div> -->
                                <input type="hidden" name="verify_code" :value="order.verify_code" />
                            </div>
                            <button type="submit" class="tf-btn radius-3 btn-fill btn-icon animate-hover-btn justify-content-center"><?= lang('Order.place_order') ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<!-- /checkout -->

<!-- recently view -->
<?php
if (isset($recentlyViewedProducts) && count($recentlyViewedProducts) > 0) {
    // render view component
    if (isset($recentlyViewedProducts) && count($recentlyViewedProducts) > 0) {
        $recentlyViewSectionData = [
            'sectionTitle' => lang('Product.recently_view_products'),
            'productData' => $recentlyViewedProducts,
            'currentLang' => $currentLang,
        ];
        echo view($configs->view . '\components\product\related_products', $recentlyViewSectionData);
    }
} ?>
<!-- /recently view -->

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Select2 -->
<script src="<?= base_url($configs->scriptsPath) ?>/plugins/select2/js/select2.full.min.js"></script>
<script src="<?= base_url($configs->scriptsPath) ?>areaLocation.js"></script>
<script>
    $(document).ready(function() {
        var countryElement = $("#country");
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

        function toggleAddressFields() {
            var selectedValue = countryElement.val();
            var selectedOption = countryElement.find(
                'option[value="' + selectedValue + '"]'
            );
            var countryCode = selectedOption.attr("data-code");

            if (countryCode == "VN") {
                $("#vietnam_address").removeClass("d-none");
                $("#other_country_address").addClass("d-none");
                $('#vietnam_address input[name="cus_address"]').prop("disabled", false);
                $('#other_country_address input[name="cus_address"]').prop("disabled", true);
            } else {
                $("#vietnam_address").addClass("d-none");
                $("#other_country_address").removeClass("d-none");
                $('#vietnam_address input[name="cus_address"]').prop("disabled", true);
                $('#other_country_address input[name="cus_address"]').prop("disabled", false);
            }
        }

        countryElement.on("change", toggleAddressFields);

        // Set selected value sau khi bind event
        var country_selected_value = $("#country").attr("country-selected");
        if (country_selected_value !== undefined && country_selected_value > 0) {
            $("#country").val(country_selected_value);
            $("#country").trigger("change");
        }

        toggleAddressFields();

        // Initialize delivery type radio buttons
        function toggleShippingAddress() {
            if ($('#pickupType').is(':checked')) {
                $('#shipping_address').addClass('d-none');
            } else if ($('#homeDelivery').is(':checked')) {
                $('#shipping_address').removeClass('d-none');
            }
        }

        // Check initial state when page loads
        toggleShippingAddress();

        // Handle radio button changes
        $('#pickupType').on('change', function() {
            if ($(this).is(':checked')) {
                $('#shipping_address').addClass('d-none');
            }
        });

        $('#homeDelivery').on('change', function() {
            if ($(this).is(':checked')) {
                $('#shipping_address').removeClass('d-none');
            }
        });
    });
</script>


<?= $this->endSection() ?>


<?= $this->section('style') ?>
<!-- Select2 -->
<link rel="stylesheet" href="<?= base_url($configs->scriptsPath) ?>/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?= base_url($configs->scriptsPath) ?>/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

<style>
    .form-checkout .fieldset.no-required label::after {
        display: none;
    }
</style>
<?= $this->endSection() ?>