<?php
/**
 * @author tmtuan
 * created Date: 10/20/2023
 * Project: Unigem
 */
?>

<div class="popup_wrap">
    <div class="popup_container">
        <h2 class="text-center"><?= lang('LuckyDraw.lucky_spin') ?></h2>
        <a href="<?= base_url(route_to('lucky_spin')) ?>">
            <div class="d-flex justify-content-center">
                <img class="img-fluid text-center" style="max-width: 300px"
                     src="<?= base_url($configs->templatePath) ?>/assets/images/lucky-spin/bg.png" alt="">
            </div>
        </a>
    </div>
</div>
