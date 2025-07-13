<?php

use App\Enums\Store\Order\EDeliveryType;
?>

<script type="text/javascript">
    $(document).ready(function() {
        $('.type-languages').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var redirectUrl = selectedOption.data('href');
            if (redirectUrl) {
                window.location.href = redirectUrl;
            }
        });
    });

    const lang_id = '<?= $currentLang->id ?>';
    const currency = '<?= $currentLang->currency_code ?>';
    const exchange_rate = <?= getExchangeRate($currentLang->id) ?>;
    const VietNamCountryId = <?= VIETNAM_COUNTRY_ID ?>;
    const HomeDeliveryType = <?= EDeliveryType::HOME_DELIVERY ?>;

    const shopMessages = {
        addItemToCartSuccess: '<?= lang('Order.addItemToCartSuccess') ?>',
        cartSavingError: '<?= lang('Order.cartSavingError') ?>',
        loginToCheckout: '<?= lang('Order.loginToCheckout') ?>',
        voucherError: '<?= lang('Order.voucherError') ?>',
        voucherAppliedSuccess: '<?= lang('Order.voucherAppliedSuccess') ?>',
        emptyCart: '<?= lang('Order.emptyCart') ?>',
    };

    ecomApp.mount("#ecomApp");
</script>