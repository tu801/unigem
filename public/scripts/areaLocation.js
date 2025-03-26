/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 10/25/2023
 */
function getProvince() {
    let requestUrl = site_url +'/ajax/get-province'
    //get province item
    $.ajax({
        url: requestUrl,
        type: 'GET',
        dataType: "json",
        success: function (response) {
            if ( response.error == 0 ) {
                this.province_data = response.province;
                var data = $.map(response.province, function (obj) {
                    return {
                        id: obj.id,
                        text: obj.name
                    };
                });
                $(".select_province").select2({
                    data: data
                });
            } else {
                $(document).Toasts('create', {
                    class: 'bg-danger',
                    body: response.message,
                    position: 'topRight',
                });
            }
        }
    }).then(function () {
        var selected_value = $('.select_province').attr('area-selected');

        if ( selected_value !== undefined && selected_value > 0 ) {
            $(".select_province").val(selected_value);
            $('.select_province').trigger('change');
            $('.select_province').removeAttr('area-selected');
            return;
        }
        getDistrict($(".select_province").val());
    });
}
function getDistrict(province) {
    let url = site_url  + '/ajax/get-district/' + province;
    $.ajax({
        type: "GET",
        url: url ,
        dataType: "json",
        success: function (response) {
            if ( response.error == 0 ) {
                var data = $.map(response.district, function (obj) {
                    return {
                        id: obj.id,
                        text: obj.name
                    };
                });
                $('.select_district').empty();
                $(".select_district").select2({
                    data: data
                });
            } else {
                $(document).Toasts('create', {
                    class: 'bg-danger',
                    body: response.message,
                    position: 'topRight',
                });
            }
        }
    }).then(function () {
        var selected_value = $('.select_district').attr('area-selected');
        if ( selected_value !== undefined && selected_value > 0 ) {
            $(".select_district").val(selected_value);
            $('.select_district').trigger('change');
            $('.select_district').removeAttr('area-selected');
            return;
        }
        getWard($('.select_district').val());
    });
}

function getWard(district) {
    let url = site_url  + '/ajax/get-ward/' + district;
    $.ajax({
        type: "GET",
        url: url ,
        dataType: "json",
        success: function (response) {
            if ( response.error == 0 ) {
                var data = $.map(response.ward, function (obj) {
                    return {
                        id: obj.id,
                        text: obj.name
                    };
                });
                $('.select_ward').empty();
                $(".select_ward").select2({
                    data: data
                });
            } else {
                $(document).Toasts('create', {
                    class: 'bg-danger',
                    body: response.message,
                    position: 'topRight',
                });
            }
        }
    }).then(function () {
        var selected_value = $('.select_ward').attr('area-selected');
        if ( selected_value !== undefined && selected_value > 0 ) {
            $(".select_ward").val(selected_value);
            $('.select_ward').trigger('change');
            $('.select_ward').removeAttr('area-selected');
            return;
        }
    });
}

$(function () {
    $(".select_district").select2();
    $(".select_ward").select2();
    getProvince();

    $( ".select_province" ).on( "change", function() {
        var province_id = $('.select_province').val();
        getDistrict(province_id);
    });
    $(".select_district").on( "change", function() {
        var district_id = $('.select_district').val();
        getWard(district_id);
    });
});