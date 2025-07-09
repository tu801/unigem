<?php

use App\Enums\Store\Order\EDeliveryType;
use App\Enums\Store\Order\EPaymentStatus;

$user = auth()->getUser();
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

    const customer_id = '<?= $user->id ?? 0 ?>';
    const full_name = '<?= $user->full_name ?? '' ?>';
    const phone = '<?= $user->phone ?? '' ?>';
    const email = '<?= $user->email ?? '' ?>';

    const messages = {
        addItemToCartSuccess: '<?= lang('Order.addItemToCartSuccess') ?>',
    };

    ecomApp.mount("#ecomApp");
</script>