<div class="offcanvas offcanvas-start canvas-mb" id="mobileMenu">
    <span class="icon-close icon-close-popup" data-bs-dismiss="offcanvas" aria-label="Close"></span>
    <div class="mb-canvas-content">
        <div class="mb-body">
            <?php echo view_cell('\App\Cells\Menu\MenuTopCell::mobileMenu', null, 60*60, '_main_menu_'.$currentLang->locale)?>
            

            <div class="mb-other-content">
                <div class="d-flex group-icon">
                    <!-- <a href="wishlist.html" class="site-nav-icon"><i class="icon icon-heart"></i>Wishlist</a> -->
                    <a href="home-search.html" class="site-nav-icon"><i class="icon icon-search"></i>Search</a>
                </div>
                <div class="mb-notice">
                    <a href="contact-1.html" class="text-need"><?=lang('Home.footer_contact_info')?></a>
                </div>
                <ul class="mb-info">
                    <li><i class="icon fs-14 icon-place"></i> <?=get_theme_config('general_address')?></li>
                    <li><i class="icon fs-14 icon-mail"></i> <a href="mailto:<?=get_theme_config('general_email')?>"><?=get_theme_config('general_email')?></a></li>
                    <li><i class="icon fs-14 icon-suport"></i> <a href="tel:<?=get_theme_config('hotline')?>" class="footer-menu_item"><?=get_theme_config('general_hotline')?></a></li>
                </ul>
            </div>
        </div>
        <div class="mb-bottom">
            <a href="login.html" class="site-nav-icon"><i class="icon icon-account"></i>Login</a>
            <div class="bottom-bar-language">
                <!-- <div class="tf-currencies">
                    <select class="image-select center style-default type-currencies">
                        <option data-thumbnail="images/country/fr.svg">EUR <span>€ | France</span></option>
                        <option data-thumbnail="images/country/de.svg">EUR <span>€ | Germany</span></option>
                        <option selected data-thumbnail="images/country/us.svg">USD <span>$ | United States</span></option>
                        <option data-thumbnail="images/country/vn.svg">VND <span>₫ | Vietnam</span></option>
                    </select>
                </div> -->
                
                <?php echo view_cell('\App\Cells\Widgets\SelectLangCell')?>
            </div>
        </div>
    </div>       
</div>