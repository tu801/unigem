<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= get_theme_config('general_site_title') ?? env('app.site_name') ?? '' ?></title>
    <meta name="author" content="tmtuan">
    <meta name="robots" content="noindex, follow" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?=get_favicon_url($configs)?>" type="image/x-icon">

    <?php echo view_cell('\App\Libraries\SeoMeta\SeoMetaCell::render')?>

    <!-- all css -->
    <link rel="stylesheet" href="<?=base_url($configs->templatePath)?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=base_url($configs->templatePath)?>/assets/css/jquery-ui.css">
    <link rel="stylesheet" href="<?=base_url($configs->templatePath)?>/assets/css/slick.css">
    <link rel="stylesheet" href="<?=base_url($configs->templatePath)?>/assets/css/line-awesome.css">
    <link rel="stylesheet" href="<?=base_url($configs->templatePath)?>/assets/css/nice-select.css">
    <link rel="stylesheet" href="<?=base_url($configs->templatePath)?>/assets/css/style.css">
    <link rel="stylesheet" href="<?=base_url($configs->templatePath)?>/assets/css/responsive.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="<?= base_url($configs->scriptsPath)?>/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">

    <!--load custom css-->
    <?= $this->renderSection('style') ?>

    <script type="text/javascript">
        var site_url = '<?=base_url()?>';
    </script>

</head>

<body>
    <div id="app">
        <!-- Preloader -->
        <div class="preloader">
            <img src="<?=base_url($configs->templatePath)?>/assets/images/preloader.gif" alt="preloader">
        </div>

        <!-- top header -->
        <?= $this->include($configs->view . '\layouts\_header') ?>

        <!-- navbar -->
        <?= $this->include($configs->view . '\layouts\_navbar') ?>

        <!-- mobile bottom bar -->
        <?= $this->include($configs->view . '\layouts\_mobile_bottom_bar') ?>

        <!-- mobile menu -->
        <?= $this->include($configs->view . '\layouts\_mobile_menu') ?>

        <!--  mobile cart -->
        <?= $this->include($configs->view . '\layouts\_mobile_cart') ?>

        <!-- mobile searchbar -->
        <?= $this->include($configs->view . '\layouts\_mobile_search_bar') ?>

        <!-- mobile category -->
        <?= $this->include($configs->view . '\layouts\_mobile_category') ?>

        <!-- CONTENT BEGINS -->
        <?= $this->renderSection('content') ?>
        <!-- CONTENT ENDS -->

        <!-- footer area -->
        <?= $this->include($configs->view . '\layouts\_footer') ?>

        <!-- copyright -->
        <?= $this->include($configs->view . '\layouts\_copyright') ?>

        <!-- popup -->
        <?= $this->include($configs->view . '\components\popup') ?>
    </div>

    <script src="<?=insert_vue()?>"></script>
    <!-- all js -->
    <script src="<?=base_url($configs->templatePath)?>/assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?=base_url($configs->templatePath)?>/assets/js/jquery-3.5.1.min.js"></script>
    <script src="<?=base_url($configs->templatePath)?>/assets/js/jquery-ui.min.js"></script>
    <script src="<?=base_url($configs->templatePath)?>/assets/js/slick.min.js"></script>
    <script src="<?=base_url($configs->templatePath)?>/assets/js/jquery.nice-select.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="<?=base_url($configs->scriptsPath)?>/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="<?=base_url('/themes/store/shop.js')?>"></script>
    <script src="<?=base_url($configs->templatePath)?>/assets/js/app.js"></script>

    <?= $this->renderSection('scripts') ?>
</body>
</html>