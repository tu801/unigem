$(function () {
    const $hat = $(".m-game__wrapper");
    let start = true;
    let oldDegTemp = 0;
    let luckyHistoryID = null;

    $(".m-game__start").on("click", function () {
        if (!start) return;
        start = false;

        const csrf_name = $('#csrf').attr('name');
        const csrf_token = $('#csrf').val();
        const data = {
            [csrf_name]: csrf_token
        }
        $.ajax({
            url: site_url + '/lucky-draw',
            type: 'post',
            data: data,
            success: function (data) {
                if (data.error == 1) {
                    start = true;
                    return SwalAlert.fire({
                        icon: "error",
                        title: data.message,
                    });
                }else {
                    const spinIndex = data.data.promotion.promo_id;
                    luckyHistoryID = data.data.lucky_draw_history_id;
                    const oldDeg = $hat.data("rotate") ? $hat.data("rotate") : 0;
                    const countSpin = 5 * 360;
                    const nextDeg = (spinIndex * 36)
                    const deg = countSpin + oldDeg + nextDeg + oldDegTemp;
                    oldDegTemp = 360 - nextDeg

                    $hat.data("rotate", deg);
                    $hat.css("transform", `rotate(${deg}deg)`);
                    if (data.data.promotion.discount_type == 'free_gift') {
                        $('#dataSpin').html(`Chúc mừng bạn đã nhận được Phần quà ${data.data.promotion.promo_name} <br><img class="img-fluid" src="${data.data.promotion.discount_image}" width="150" height="150">`)
                    } else {
                        $('#dataSpin').html(`Chúc mừng bạn đã nhận được Mã giảm giá ${data.data.promotion.promo_name} <br> <b class="text-danger">${data.data.voucher_code}</b>`)
                    }

                    setTimeout(function () {
                        start = true;
                        // spin done
                        const spinLuckySuccessModal = new bootstrap.Modal(document.getElementById('spinLuckySuccessModal'), {
                            keyboard: false,
                            backdrop: 'static'
                        });
                        spinLuckySuccessModal.show()

                    }, 7000);
                }

            },
            error: function (error) {
                console.log(error)
            }
        });
    });

    $('#btn-register-info').click(() => {
        console.log(luckyHistoryID)
        if (luckyHistoryID) {
            window.location.href = site_url + '/lucky-draw/register-info/'+luckyHistoryID
        }
    })
});