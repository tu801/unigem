<header class="home-2 d-none d-lg-block">
    <div class="container">
        <div class="d-flex align-items-center">
            <div class="logo">
                <a href="<?=base_url()?>">
                    <img loading="lazy"  src="<?=get_logo_url($configs)?>" alt="<?= get_theme_config('general_site_title') ?>">
                </a>
            </div>

            <?php echo view_cell('\App\Cells\Menu\MenuTopCell', null, 300)?>

            <div class="tophead_items ms-auto">
<!--                <div class="me-3">-->
<!--                    <a class="text-semibold me-0" href="--><?//=route_to('cus_login')?><!--">--><?//=lang('Home.cus_login')?><!--</a><span class="text_xs">/</span><a-->
<!--                            class="text-semibold" href="--><?//=route_to('cus_register')?><!--">--><?//=lang('Home.cus_register')?><!--</a>-->
<!--                </div>-->
<!--                <div>-->
<!--                    <select class="nice_select">-->
<!--                        <option>Language</option>-->
<!--                        <option>English</option>-->
<!--                        <option>Franch</option>-->
<!--                    </select>-->
<!--                </div>-->
<!--                <div class="ms-3">-->
<!--                    <select class="nice_select">-->
<!--                        <option>Currency</option>-->
<!--                        <option>Dollar</option>-->
<!--                        <option>Euro</option>-->
<!--                    </select>-->
<!--                </div>-->
            </div>
        </div>
    </div>
</header>