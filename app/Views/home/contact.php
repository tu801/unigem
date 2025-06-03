<?php

use App\Enums\ContactEnum;

echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>
<!-- page-title -->
<div class="tf-page-title style-2">
    <div class="container-full">
        <div class="heading text-center"><?= $page_title ?></div>
    </div>
</div>
<!-- /page-title -->
<!-- map -->
<div class="w-100">
    <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.2091061600277!2d106.74192967408776!3d10.79529055884743!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x317527f9ded5369d%3A0xc4348550d673f07a!2zQ-G7rWEgaMOgbmcgxJDDoSBxdcO9IFVuaWdlbQ!5e0!3m2!1svi!2s!4v1722913674441!5m2!1svi!2s"
        width="100%" height="646" style="border:0;" allowfullscreen="" loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>
<!-- /map -->
<!-- form -->
<section class="flat-spacing-21">
    <div class="container">
        <div class="tf-grid-layout gap30 lg-col-2">
            <div class="tf-content-left">
                <h5 class="mb_20"><?= get_theme_config('general_company_name') ?></h5>
                <div class="mb_20">
                    <p class="mb_15"><strong><?= lang('Contact.company_address') ?></strong></p>
                    <p><?= get_theme_config('general_address') ?></p>
                </div>
                <div class="mb_20">
                    <p class="mb_15"><strong><?= lang('Contact.company_phone') ?></strong></p>
                    <p><a href="tel:<?= get_theme_config('general_hotline') ?>"
                            class="footer-menu_item"><?= get_theme_config('general_hotline') ?></a></p>
                </div>
                <div class="mb_20">
                    <p class="mb_15"><strong><?= lang('Contact.company_email') ?></strong></p>
                    <p><a
                            href="mailto:<?= get_theme_config('general_email') ?>"><?= get_theme_config('general_email') ?></a>
                    </p>
                </div>
                <div class="mb_36">
                    <p class="mb_15"><strong><?= lang('Contact.company_open_time') ?></strong></p>
                    <p class="mb_15"><?= lang('Contact.company_open_time_desc') ?></p>
                </div>
                <div>
                    <?= view_cell('\App\Cells\Widgets\SocialLinksCell', null, $configs->viewCellCacheTtl, '_social_links_' . $currentLang->locale) ?>
                </div>
            </div>
            <div class="tf-content-right">
                <h5 class="mb_20"><?= lang('Contact.contact_form_title') ?></h5>
                <p class="mb_24"><?= lang('Contact.contact_form_desc') ?></p>

                <div>
                <?php if (session()->has('errors')) : ?>
                    <ul class="alert alert-danger alert-dismissible text-danger">
                    <?php foreach (session('errors') as $error) : ?>
                        <li><?= $error ?></li>
                    <?php endforeach ?>
                    </ul>
                <?php endif ?>
                <?php if (session()->has('message')) : ?>
                    <div class="alert alert-success">
                        <?= session('message') ?>
                    </div>
                <?php endif ?>
                </div>
                <div>
                    <form class="form-contact" id="contactForm" action="<?=base_url(route_to('contactUs'))?>" method="post">
                        <?= csrf_field() ?>
                        <div class="d-flex gap-15 mb_15">
                            <fieldset class="w-100">
                                <input type="text" name="fullname" id="name" value="<?= old('fullname')?>"
                                    placeholder="<?= lang('Contact.contact_name') ?>" />
                            </fieldset>
                            <fieldset class="w-100">
                                <input type="email" name="email" id="email" value="<?= old('email')?>"
                                    placeholder="<?= lang('Contact.contact_email') ?>" />
                            </fieldset>
                        </div>
                        <div class="d-flex mb_15">
                            <div class="select-custom w-100">
                                <select class="tf-select w-100" name="subject">
                                    <option value="">-- <?=lang('Contact.select_subject')?> --</option>
                                    <?php foreach ( ContactEnum::CONTACT_SUBJECT_LIST as $key ) : ?>
                                    <option value="<?=$key?>" <?= old('subject') == $key ? "selected" : "" ?> ><?=lang('Contact.subject_'.$key)?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="mb_15">
                            <textarea placeholder="<?=lang('Contact.contact_message')?>" name="message" id="message" cols="30"
                                rows="10"><?=old('message')?></textarea>
                        </div>
                        <div class="send-wrap">
                            <button type="submit"
                                class="tf-btn w-100 radius-3 btn-fill animate-hover-btn justify-content-center">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /form -->
<?= $this->endSection() ?>

<?= $this->section('scripts')?>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script>
$(document).ready(function() {
    $('#contactForm').validate({
        rules: {
            fullname: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            subject: {
                required: true
            },
            message: {
                required: true
            }
        },
        messages: {
            fullname: "<?=lang('Contact.contact_fullname_required')?>",
            email: {
                required: "<?=lang('Contact.contact_email_required')?>",
                email: "<?=lang('Contact.contact_email_invalid')?>" 
            },
            subject: "<?=lang('Contact.contact_subject_required')?>",
            message: "<?=lang('Contact.contact_message_required')?>"
        },
        errorElement: 'label',
        errorClass: 'error text-danger',
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
})
</script>
<?= $this->endSection()?>