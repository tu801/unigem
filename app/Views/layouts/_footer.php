<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-md-0">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-12">
                        <div class="footer_logo">
                            <img loading="lazy"  src="<?=get_logo_url($configs)?>" alt="<?= get_theme_config('general_site_title') ?>">
                        </div>
                        <div class="footet_text">
                            <?=get_theme_config('general_site_desc')?>
                        </div>
                    </div>
<!--                    <div class="col-12 col-md-6 col-lg-12">-->
<!--                        <div class="footer_newslet">-->
<!--                            <h4>Newsletter</h4>-->
<!--                            <form class="footernews_form">-->
<!--                                <input type="text" placeholder="Your email address">-->
<!--                                <button type="submit" class="default_btn">Subscribe</button>-->
<!--                            </form>-->
<!--                        </div>-->
<!--                    </div>-->
                </div>
            </div>
            <div class="col-lg-4 mb-3 mb-md-0">
                <div class="row">
                    <div class="col-6">
                        <?php echo view_cell('\App\Cells\Menu\MenuFooterCell::first', null, 300)?>
                    </div>
                    <div class="col-6">
                        <?php echo view_cell('\App\Cells\Menu\MenuFooterCell::second', null, 300)?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="footer_download">
                    <div class="row">
                        <div class="col-lg-6 col-lg-12">
                            <h4 class="footer_title"><?=lang('Home.footer_contact_info')?></h4>
                            <div class="footer_contact">
                                <p>
                                    <span class="icn"><i class="las la-map-marker-alt"></i></span>
                                    <?=get_theme_config('general_address')?>
                                </p>
                                <p class="phn">
                                    <span class="icn"><i class="las la-phone"></i></span>
                                    <?=get_theme_config('general_hotline')?>
                                </p>
                                <p class="eml">
                                    <span class="icn"><i class="lar la-envelope"></i></span>
                                    <?=get_theme_config('general_email')?>
                                </p>
                            </div>
                        </div>
                        <div class="footer_social col-lg-6 col-lg-12">
                            <?php
                            $fb_link = get_theme_config('general_facebook_link');
                            $yt_link = get_theme_config('general_youtube_link');
                            $tk_link = get_theme_config('general_tiktok_link');
                            ?>
                            <div class="footer_icon d-flex">
                                <?php if ( isset($fb_link) && !empty($fb_link) ) : ?>
                                <a href="<?=$fb_link?>" class="facebook"><i class="lab la-facebook-f"></i></a>
                                <?php endif; ?>

                                <?php if ( isset($yt_link) && !empty($yt_link) ) : ?>
                                <a href="<?=$yt_link?>" style="background-color: #CD201F"><i class="lab la-youtube"></i></a>
                                <?php endif; ?>

                                <?php if ( isset($tk_link) && !empty($tk_link) ) : ?>
                                <a href="<?=$tk_link?>" class="instagram">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                                        <path d="M448,209.91a210.06,210.06,0,0,1-122.77-39.25V349.38A162.55,162.55,0,1,1,185,188.31V278.2a74.62,74.62,0,1,0,52.23,71.18V0l88,0a121.18,121.18,0,0,0,1.86,22.17h0A122.18,122.18,0,0,0,381,102.39a121.43,121.43,0,0,0,67,20.14Z"/>
                                    </svg>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
