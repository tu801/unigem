<?php
echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>
<!-- page-title -->
<div class="tf-page-title style-2">
    <div class="container-full">
        <div class="heading text-center"><?=$page_title?></div>
    </div>
</div>
<!-- /page-title -->
<!-- map -->
<div class="w-100">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.2091061600277!2d106.74192967408776!3d10.79529055884743!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x317527f9ded5369d%3A0xc4348550d673f07a!2zQ-G7rWEgaMOgbmcgxJDDoSBxdcO9IFVuaWdlbQ!5e0!3m2!1svi!2s!4v1722913674441!5m2!1svi!2s" width="100%" height="646" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>
<!-- /map -->
<!-- form -->
<section class="flat-spacing-21">
    <div class="container">
        <div class="tf-grid-layout gap30 lg-col-2">
            <div class="tf-content-left">
                <h5 class="mb_20"><?=get_theme_config('general_company_name')?></h5>
                <div class="mb_20">
                    <p class="mb_15"><strong><?=lang('Home.company_address')?></strong></p>
                    <p><?=get_theme_config('general_address')?></p>
                </div>
                <div class="mb_20">
                    <p class="mb_15"><strong><?=lang('Home.company_phone')?></strong></p>
                    <p><a href="tel:<?=get_theme_config('hotline')?>" class="footer-menu_item"><?=get_theme_config('general_hotline')?></a></p>
                </div>
                <div class="mb_20">
                    <p class="mb_15"><strong><?=lang('Home.company_email')?></strong></p>
                    <p><a href="mailto:<?=get_theme_config('general_email')?>"><?=get_theme_config('general_email')?></a></p>
                </div>
                <div class="mb_36">
                    <p class="mb_15"><strong><?=lang('Home.company_open_time')?></strong></p>
                    <p class="mb_15"><?=lang('Home.company_open_time_desc')?></p>
                </div>
                <div>
                <?=view_cell('\App\Cells\Widgets\SocialLinksCell', null, $configs->viewCellCacheTtl, '_social_links_'.$currentLang->locale) ?>
                </div>
            </div>
            <div class="tf-content-right">
                <h5 class="mb_20"><?=lang('Home.contact_form_title')?></h5>
                <p class="mb_24"><?=lang('Home.contact_form_desc')?></p>
                <div>
                    <form class="form-contact" id="contactform" action="./contact/contact-process.php" method="post">
                        <div class="d-flex gap-15 mb_15">
                            <fieldset class="w-100">
                                <input type="text" name="name" id="name" required placeholder="Name *"/>
                            </fieldset>
                            <fieldset class="w-100">
                                <input type="email" name="email" id="email" required placeholder="Email *"/>
                            </fieldset>
                        </div>
                        <div class="mb_15">
                            <textarea placeholder="Message" name="message" id="message" required cols="30" rows="10"></textarea>
                        </div>
                        <div class="send-wrap">
                            <button type="submit" class="tf-btn w-100 radius-3 btn-fill animate-hover-btn justify-content-center">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /form -->
<?= $this->endSection() ?>