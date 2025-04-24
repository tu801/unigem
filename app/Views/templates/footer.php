<footer id="footer" class="footer  border-container has-all-border has-border-full md-pb-70">
    <div class="footer-wrap">
        <div class="footer-body">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-md-6 col-12">
                        <?php 
                        echo view_cell('\App\Cells\Menu\MenuFooterCell::first', null, 60*60, '_menu_footer_1_'.$currentLang->locale)
                        // echo view_cell('\App\Cells\Menu\MenuFooterCell::first', null)
                        ?>
                    </div>
                    <div class="col-xl-3 col-md-6 col-12">
                        <?php 
                        echo view_cell('\App\Cells\Menu\MenuFooterCell::second', null, 60*60, '_menu_footer_2_'.$currentLang->locale)
                        // echo view_cell('\App\Cells\Menu\MenuFooterCell::second', null)
                        ?>
                    </div>
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="footer-col footer-col-3 footer-col-block">
                            <div class="footer-heading footer-heading-desktop">
                                <h6 class="fs-14 text-uppercase fw-8"><?=lang('Home.footer_contact_info')?></h6>
                            </div>
                            <div class="footer-heading footer-heading-moblie">
                                <h6 class="fs-14 text-uppercase fw-8"><?=lang('Home.footer_contact_info')?></h6>
                            </div>
                            <ul class="footer-menu-list tf-collapse-content">
                                <li>
                                    <div class="footer-menu_item"><?=get_theme_config('general_company_name')?></div>
                                </li>
                                <li> 
                                    <p><i class="icon fs-14 icon-mail"></i> <a href="mailto:<?=get_theme_config('general_email')?>"><?=get_theme_config('general_email')?></a></p>
                                </li>
                                <li> 
                                    <p><i class="icon fs-14 icon-place"></i> <?=get_theme_config('general_address')?></p>
                                </li>
                                <li> 
                                    <p><?=lang('Home.tax_num')?>: <?=get_theme_config('general_mst')?></p>
                                </li>
                                <li> 
                                    <a href="tel:<?=get_theme_config('hotline')?>" class="footer-menu_item"><i class="icon fs-14 icon-suport"></i> <?=get_theme_config('general_hotline')?></a>
                                </li>
                                <li>
                                    <?php
                                    echo view_cell('\App\Cells\Widgets\SocialLinksCell', null, 60*60, '_social_links_'.$currentLang->locale)
                                    ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="footer-newsletter footer-col-block">
                            <div class="footer-heading footer-heading-desktop">
                                <h6 class="fs-14 text-uppercase fw-8"><?=lang('Home.sign_up_for_mail')?></h6>
                            </div>
                            <div class="footer-heading footer-heading-moblie">
                                <h6 class="fs-14 text-uppercase fw-8"><?=lang('Home.sign_up_for_mail')?></h6>
                            </div>
                            <div class="tf-collapse-content">
                                <div class="footer-menu_item"><?=lang('Home.sign_up_for_mail_desc')?></div>
                                <form class="form-newsletter" id="subscribe-form" action="#" method="post" accept-charset="utf-8" data-mailchimp="true">
                                    <div id="subscribe-content">
                                        <fieldset class="email">
                                            <input type="email" name="email-form" id="subscribe-email" placeholder="<?=lang('Home.sign_up_for_mail_placeholder')?>"  tabindex="0" value="" aria-required="true" required="">
                                        </fieldset>
                                        <div class="button-submit">
                                            <button id="subscribe-button" class="tf-btn btn-sm radius-3 btn-fill btn-icon animate--hover-light_skew" type="submit"><?=lang('Home.subscribe_btn')?><i class="icon icon-arrow1-top-left"></i></button>
                                        </div>
                                    </div>
                                    <div id="subscribe-msg"></div>
                                </form>
                                <div class="tf-cur">
                                <?php echo view_cell('\App\Cells\Widgets\SelectLangCell')?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="footer-bottom-wrap d-flex gap-20 flex-wrap justify-content-between align-items-center">
                            <div class="footer-menu_item"><?=get_theme_config("general_copyright")?></div>
                            <div class="tf-payment">
                                <img width="42" src="<?=base_url($configs->templatePath)?>assets/images/payments/visa.png" alt="<?= get_theme_config('general_site_title') ?>">
                                <img width="42" src="<?=base_url($configs->templatePath)?>assets/images/payments/img-1.png" alt="<?= get_theme_config('general_site_title') ?>">
                                <img width="42" src="<?=base_url($configs->templatePath)?>assets/images/payments/img-2.png" alt="<?= get_theme_config('general_site_title') ?>">
                                <img width="42" src="<?=base_url($configs->templatePath)?>assets/images/payments/img-3.png" alt="<?= get_theme_config('general_site_title') ?>">
                                <img width="42" src="<?=base_url($configs->templatePath)?>assets/images/payments/img-4.png" alt="<?= get_theme_config('general_site_title') ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>