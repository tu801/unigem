<?php
/**
 * @author tmtuan
 * created Date: 10/22/2023
 * Project: Unigem
 */

namespace App\Enums;


class BankingPaymentEnum extends BaseEnum
{
    const BANK_LIST = [
        ['name' => 'Eximbank', 'logo' => 'themes/bank_logo/logo_eximbank.jpg'],
        ['name' => 'Vietcombank', 'logo' => 'themes/bank_logo/logo-vietcombank.jpg'],
        ['name' => 'Techcombank', 'logo' => 'themes/bank_logo/logo-teccombank.jpg'],
        ['name' => 'Ngân hàng Quốc Tế VIB', 'logo' => 'themes/bank_logo/logo-vib.jpg'],
        ['name' => 'Ngân hàng Quân đội - MB', 'logo' => 'themes/bank_logo/logo-MB.jpg'],
        ['name' => 'Ngân hàng HDBank', 'logo' => 'themes/bank_logo/logo-hdbank.jpg'],
        ['name' => 'Ngân hàng Thương mại cổ phần Sài Gòn', 'logo' => 'themes/bank_logo/logo-sgcb.jpg'],
        ['name' => 'NCB – Ngân Hàng Quốc Dân', 'logo' => 'themes/bank_logo/logo-ncb.jpg'],
        ['name' => 'Ngân hàng thương mại cổ phần Hàng hải Việt Nam', 'logo' => 'themes/bank_logo/logo-msbbank.jpg'],
        ['name' => 'Ngân hàng Thương mại Cổ phần Việt Á', 'logo' => 'themes/bank_logo/logo-vieta.jpg'],
        ['name' => 'Ngân hàng Thương mại cổ phần Việt Nam Thịnh Vượng', 'logo' => 'themes/bank_logo/logo-vbbank.jpg'],
        ['name' => 'Ngân hàng Thương mại cổ phần Đầu tư và Phát triển Việt Nam', 'logo' => 'themes/bank_logo/logo-bidv.jpg'],
        ['name' => 'Ngân hàng Nông nghiệp và Phát triển Nông thôn Việt Nam', 'logo' => 'themes/bank_logo/logo-aribank.jpg'],
        ['name' => 'Ngân hàng thương mại cổ phần Nam Á', 'logo' => 'themes/bank_logo/logo-namA.jpg'],
        ['name' => 'Ngân hàng SHB', 'logo' => 'themes/bank_logo/logo-shb.jpg'],
        ['name' => 'Tien Phong Commercial Joint Stock Bank', 'logo' => 'themes/bank_logo/logo-tbbank.jpg'],
        ['name' => 'SeABank', 'logo' => 'themes/bank_logo/logo-sea.jpg'],
        ['name' => 'PVcomBank', 'logo' => 'themes/bank_logo/logo-pvcb.jpg'],
        ['name' => 'Ngân Hàng Bản Việt', 'logo' => 'themes/bank_logo/logo-vccb.jpg'],
        ['name' => 'Ngân Hàng Bắc Á', 'logo' => 'themes/bank_logo/logo-bacabank.jpg'],
        ['name' => 'Ngân hàng thương mại cổ phần Á Châu', 'logo' => 'themes/bank_logo/logo-acb.jpg'],
        ['name' => 'GP Bank', 'logo' => 'themes/bank_logo/logo-GP-bank.jpg'],
        ['name' => 'Ngân hàng thương mại cổ phần Phương Đông', 'logo' => 'themes/bank_logo/logo-ocb.jpg'],
        ['name' => 'Ngân hàng Thương mại Cổ phần Bưu điện Liên Việt', 'logo' => 'themes/bank_logo/logo-lienviet.jpg'],
        ['name' => 'BAOVIET Bank', 'logo' => 'themes/bank_logo/logo-BV-bank.jpg'],
        ['name' => 'Ngân hàng Kiên Long', 'logo' => 'themes/bank_logo/logo-klb.jpg'],
        ['name' => 'Ngân hàng Liên doanh Việt - Nga (VRB)', 'logo' => 'themes/bank_logo/logo-vrb.jpg'],
        ['name' => 'PublicBank Viet Nam', 'logo' => 'themes/bank_logo/logo-pbvn.jpg'],
        ['name' => 'Ngân hàng thương mại cổ phần Sài Gòn Công Thương', 'logo' => 'themes/bank_logo/logo-sgbank.jpg'],
        ['name' => 'PG Bank: Ngân Hàng TMCP Xăng Dầu Petrolimex', 'logo' => 'themes/bank_logo/logo-pgbank.jpg'],
        ['name' => 'Indovina Bank', 'logo' => 'themes/bank_logo/logo-ivb.jpg'],
        ['name' => 'Woori Bank', 'logo' => 'themes/bank_logo/logo-woori.jpg'],
        ['name' => 'Ngân hàng UOB', 'logo' => 'themes/bank_logo/logo-uob.jpg'],
        ['name' => 'Shinhan Bank', 'logo' => 'themes/bank_logo/logo-shinhan.jpg'],
        ['name' => 'Ocean Bank', 'logo' => 'themes/bank_logo/logo-ocean.jpg'],
        ['name' => 'Sacombank', 'logo' => 'themes/bank_logo/logo-sacom.jpg'],
        ['name' => 'Ngân hàng thương mại cổ phần Việt Nam Thương Tín', 'logo' => 'themes/bank_logo/logo-vietbank.jpg'],
        ['name' => 'DongA Bank', 'logo' => 'themes/bank_logo/logo-dongA.jpg'],
        ['name' => 'VietinBank', 'logo' => 'themes/bank_logo/logo-viettin.jpg'],
        ['name' => 'Ngân hàng Thương mại Cổ phần An Bình', 'logo' => 'themes/bank_logo/logo-abbank.jpg'],
    ];

    const CONFIG_GROUP = 'payment_config';
    const CONFIG_KEY   = 'payment_info';
}