/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 10/26/2023
 */

function format_vnd(x) {
    var num = decode_vnd(x);
    return num.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")
}

function decode_vnd(obs) {
    if (obs == '')
        return 0;
    else
        return parseInt(obs.replace(/,/g, ''));
}