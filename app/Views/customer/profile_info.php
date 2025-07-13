<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 11/6/2023
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
                    
                    <div class="mb_60">
                        <form class="" id="form-profile-change" action="<?=route_to('edit_cus_profile')?>" method="post">
                            <?= csrf_field() ?>

                            <div class="tf-field style-1 mb_15">
                                <input class="tf-field-input tf-input" placeholder=" " type="text" id="cusFullName" name="cus_full_name" value="<?= esc($customer->cus_full_name) ?>">
                                <label class="tf-field-label fw-4 text_black-2" for="cusFullName"><?=lang('Customer.fullName')?></label>
                            </div>
                            <div class="tf-field style-1 mb_15">
                                <input class="tf-field-input tf-input" placeholder=" " type="text" id="cusEmail" value="<?= esc($customer->cus_email) ?>" disabled>
                                <label class="tf-field-label fw-4 text_black-2" for="cusEmail"><?=lang('Customer.email')?></label>
                            </div>

                            <div class="tf-field style-1 mb_15">
                                <input class="tf-field-input tf-input" placeholder=" " type="text" id="cusPhone" name="cus_phone" value="<?= esc($customer->cus_phone) ?>">
                                <label class="tf-field-label fw-4 text_black-2" for="cusPhone"><?=lang('Customer.phoneNumber')?></label>
                            </div>
                            <div class="tf-field style-1 mb_15">
                                <input class="tf-field-input tf-input" placeholder=" " type="text" id="cusBirthday" name="cus_birthday" value="<?= esc($customer->cus_birthday) ?>">
                                <label class="tf-field-label fw-4 text_black-2" for="cusBirthday"><?=lang('Customer.birthday')?></label>
                            </div>

                            <h6 class="mb_15"><?= lang('Customer.address') ?></h6>
                            <fieldset class="box fieldset mb_15">
                                <label for="country"><?= lang('Customer.select_country') ?></label>
                                <div class="select-custom">
                                    <select class="w-100" id="country" name="country_id"
                                        country-selected="<?= $customer->country_id ?? old('country_id') ?? 200 ?>">
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
                            </fieldset>
                            <div id="vietnam_address">
                                <div class="mb_15">
                                    <select class=" w-100 select_province" id="province" name="province_id"
                                        area-selected="<?= $customer->province_id ?? old('province_id') ?>"></select>
                                </div>
                                <div class=" mb_15">
                                    <select class=" w-100 select_district" id="district" name="district_id"
                                        area-selected="<?= $customer->district_id ?? old('district_id') ?>"></select>
                                </div>
                                <div class=" mb_15">
                                    <select class=" w-100 select_ward" id="ward" name="ward_id"
                                        area-selected="<?= $customer->ward_id ?? old('ward_id') ?>"></select>
                                </div>
                                <div class="tf-field style-1 mb_15">
                                    <input class="tf-field-input tf-input" placeholder=" " type="text"
                                        name="cus_address" value="<?= $customer->cus_address ?? old('cus_address') ?>">
                                    <label class="tf-field-label fw-4 text_black-2"><?= lang('Customer.address') ?>*</label>
                                </div>
                            </div>

                            <div id="other_country_address" class="d-none">
                                <div class="tf-field style-1 mb_15">
                                    <input class="tf-field-input tf-input" placeholder=" " type="text"
                                        name="cus_address" value="<?= $customer->cus_address ?? old('cus_address') ?>">
                                    <label class="tf-field-label fw-4 text_black-2"><?= lang('Customer.address') ?>*</label>
                                </div>
                            </div>

                            
                            <div class="mb_20">
                                <button type="submit" class="tf-btn w-100 radius-3 btn-fill animate-hover-btn justify-content-center"><?=lang('Common.save_changes')?></button>
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
<script src="<?= base_url($configs->scriptsPath) ?>/areaLocation.js"></script>
<script src="<?= base_url($configs->scriptsPath) ?>/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script>

$(document).ready(function () {
    $("#cusBirthday").datepicker({
    todayHighlight: true,
    format: "dd-mm-yyyy",
    autoclose: true,
    endDate: new Date(),
  });

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
            $('#vietnam_address input[name="cus_address"]').prop("disabled", false);
            $('#other_country_address input[name="cus_address"]').prop("disabled", true);
            
            // Initialize Vietnam address selects if not already done
            if (!$("#province").hasClass('select2-hidden-accessible')) {
                initVietnamAddressSelects();
            }
        } else {
            $("#vietnam_address").addClass("d-none");
            $("#other_country_address").removeClass("d-none");
            $('#vietnam_address input[name="cus_address"]').prop("disabled", true);
            $('#other_country_address input[name="cus_address"]').prop("disabled", false);
        }
    }

    function initVietnamAddressSelects() {
        // Initialize province, district, ward selects
        // This function should be defined in areaLocation.js
        if (typeof initAreaSelects === 'function') {
            initAreaSelects();
        }
    }

    countryElement.on("change", toggleAddressFields);

    // Set selected value after binding event
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
<link rel="stylesheet" href="<?= base_url($configs->scriptsPath) ?>/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">

<?= $this->endSection() ?>