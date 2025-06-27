<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 22/06/2025
 */

echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>
<!-- page-title -->
<div class="tf-page-title">
    <div class="container-full">
        <div class="heading text-center"><?=$page_title?></div>
    </div>
</div>
<!-- /page-title -->

<!-- page-cart -->
<section class="flat-spacing-11">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <?= view('customer/components/account_sidebar', ['customer' => $customer]) ?>
            </div>
            <div class="col-lg-9">
                <div class="my-account-content account-dashboard">
                    <?=view('customer/components/customer_alert_block')?>
                    
                    <div class="text-center widget-inner-address">
                        <form class="wd-form-address mt-0" id="formnewAddress" action="<?=route_to('add_new_address')?>" method="post">
                            <?= csrf_field() ?>
                                                        
                            <div class="box-field">
                                <div class="tf-field style-1">
                                    <input class="tf-field-input tf-input" placeholder=" " type="text" id="shipToName" name="ship_full_name">
                                    <label class="tf-field-label fw-4 text_black-2" for="shipToName"><?=lang('Customer.ship_full_name')?> *</label>
                                </div>
                            </div>
                            <div class="box-field">
                                <div class="tf-field style-1">
                                    <input class="tf-field-input tf-input" placeholder=" " type="text" id="shipTelephone" name="ship_telephone">
                                    <label class="tf-field-label fw-4 text_black-2" for="shipTelephone"><?=lang('Customer.ship_telephone')?> *</label>
                                </div>
                            </div>
                            <div class="box-field">
                                <div class="tf-field style-1">
                                    <input class="tf-field-input tf-input" placeholder=" " type="email" id="shipEmail" name="ship_email">
                                    <label class="tf-field-label fw-4 text_black-2" for="shipEmail"><?=lang('Customer.ship_email')?></label>
                                </div>
                            </div>

                            <div class="box-field">
                                <label for="country" class="mb_10 fw-4 text-start d-block text_black-2 "><?=lang('Customer.select_country')?></label>
                                <div class="select-custom">
                                    <select class="tf-select w-100" id="country" name="country_id" country-selected="<?= old('country_id') ?? $customer->country_id ?>">
                                        <?php if (!empty($countries)) :
                                            foreach ($countries as $item) : ?>
                                                <option value="<?= $item->id ?>" data-code="<?= $item->code ?>"
                                                    data-flag="<?= $item->flags->svg ?>">
                                                    <?= $item->name ?> - <?= $item->code ?>
                                                </option>
                                        <?php endforeach;
                                        endif; ?>
                                    </select>
                                </div>
                            </div>

                            <div id="vietnam_address">
                                <div class="box-field">
                                    <div class="tf-field style-1">
                                        <select class=" w-100 select_province" id="province" name="province_id" area-selected="<?= old('province_id') ?>"></select>
                                    </div>
                                </div>
                                <div class="box-field">
                                    <div class="tf-field style-1">
                                        <select class=" w-100 select_district" id="district" name="district_id" area-selected="<?= old('district_id') ?>"></select>
                                    </div>
                                </div>
                                <div class="box-field">
                                    <div class="tf-field style-1">
                                        <select class=" w-100 select_ward" id="ward" name="ward_id" area-selected="<?= old('ward_id') ?>"></select>
                                    </div>
                                </div>
                                <div class="box-field">
                                    <div class="tf-field style-1">
                                        <input class="tf-field-input tf-input" placeholder=" " type="text" name="ship_address" value="<?= old('ship_address') ?>">
                                        <label class="tf-field-label fw-4 text_black-2"><?= lang('Customer.address') ?> *</label>
                                    </div>
                                </div>
                            </div>

                            <div id="other_country_address" class="d-none">
                                <div class="tf-field style-1 mb_15">
                                    <input class="tf-field-input tf-input" placeholder=" " type="text" name="ship_address" value="<?= old('ship_address') ?>">
                                    <label class="tf-field-label fw-4 text_black-2"><?= lang('Customer.address') ?> *</label>
                                </div>
                            </div>

                            <div class="box-field text-start">
                                <div class="box-checkbox fieldset-radio d-flex align-items-center gap-8">
                                    <?php
                                    if (isset($customer->shippingAddress) && count($customer->shippingAddress) == 0) {
                                        $isDefaultChecked = 'checked';
                                    }
                                    else {
                                        $isDefaultChecked = old('is_default') ? 'checked' : '';
                                    }
                                    ?>
                                    <input type="checkbox" id="check-new-address" class="tf-check" name="is_default" value="1" <?= $isDefaultChecked ?>>
                                    <label for="check-new-address" class="text_black-2 fw-4"><?=lang('Customer.set_default_address')?></label>
                                </div>
                            </div>


                            <div class="d-flex align-items-center justify-content-center gap-20">
                                <button type="submit" class="tf-btn btn-fill animate-hover-btn"><?=lang('Customer.add_new_address')?></button>
                            </div>
                        </form>
                        
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<!-- page-cart -->

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Select2 -->
<script src="<?= base_url($configs->scriptsPath) ?>/plugins/select2/js/select2.full.min.js"></script>
<script src="<?= base_url($configs->scriptsPath) ?>areaLocation.js"></script>
<script>


$(document).ready(function () {
    var countryElement = $("#country");
    countryElement.select2({
    templateResult: function (state) {
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
    templateSelection: function (state) {
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
    escapeMarkup: function (m) {
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
            $('#vietnam_address input[name="ship_address"]').prop("disabled", false);
            $('#other_country_address input[name="ship_address"]').prop("disabled", true );
        } else {
            $("#vietnam_address").addClass("d-none");
            $("#other_country_address").removeClass("d-none");
            $('#vietnam_address input[name="ship_address"]').prop("disabled", true);
            $('#other_country_address input[name="ship_address"]').prop("disabled", false );
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
});
</script>


<?= $this->endSection() ?>


<?= $this->section('style') ?>
<!-- Select2 -->
<link rel="stylesheet" href="<?= base_url($configs->scriptsPath) ?>/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?= base_url($configs->scriptsPath) ?>/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

<?= $this->endSection() ?>