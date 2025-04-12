<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?= get_theme_config('general_site_title') ?? getenv('app.site_name') ?? '' ?></title>
    <meta name="author" content="tmtuan">
    <meta name="robots" content="noindex, follow" />

    <!-- font -->
    <link rel="stylesheet" href="<?=base_url($configs->templatePath)?>/assets/fonts/fonts.css">
    <!-- Icons -->
    <link rel="stylesheet" href="<?=base_url($configs->templatePath)?>/assets/fonts/font-icons.css">
    <link rel="stylesheet" href="<?=base_url($configs->templatePath)?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=base_url($configs->templatePath)?>/assets/css/drift-basic.min.css">
    <link rel="stylesheet" href="<?=base_url($configs->templatePath)?>/assets/css/photoswipe.css">
    <link rel="stylesheet" href="<?=base_url($configs->templatePath)?>/assets/css/swiper-bundle.min.css">
    <link rel="stylesheet" href="<?=base_url($configs->templatePath)?>/assets/css/animate.css">
    <link rel="stylesheet"type="text/css" href="<?=base_url($configs->templatePath)?>/assets/css/styles.css"/>

    <!-- Favicon and Touch Icons  -->
    <link rel="shortcut icon" href="<?=get_favicon_url($configs)?>">
    <link rel="apple-touch-icon-precomposed" href="<?=get_favicon_url($configs)?>">

    <?php echo view_cell('\App\Libraries\SeoMeta\SeoMetaCell::render')?>

    <!--load custom css-->
    <?= $this->renderSection('style') ?>

    <script type="text/javascript">
        var site_url = '<?=base_url()?>';
    </script>

</head>

<body class="preload-wrapper color-primary-4">
    <!-- RTL -->
    <!-- <a href="javascript:void(0);" id="toggle-rtl" class="tf-btn animate-hover-btn btn-fill">RTL</a> -->
    <!-- /RTL  -->
    <!-- preload -->
    <div class="preload preload-container">
        <div class="preload-logo">
            <div class="spinner"></div>
        </div>
    </div>
    <!-- /preload -->
    <div id="wrapper">
        <!-- header -->
        <?= $this->include($configs->view . '\templates\header') ?>
        <!-- /header -->
        
        <?= $this->renderSection('content')?>

        <!-- Footer -->
        <?= $this->include($configs->view. '\templates\footer')?>
        <!-- /Footer -->
    </div>

    <!-- gotop -->
    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 286.138;"></path>
        </svg>
    </div>
    <!-- /gotop -->
    
    <!-- toolbar-bottom -->
    <?= $this->include($configs->view. '\templates\toolbar-bottom')?>
    <!-- /toolbar-bottom -->

    <!-- mobile menu -->
    <?= $this->include($configs->view. '\templates\mobile-menu')?>
    <!-- /mobile menu -->

    <!-- canvasSearch -->
    <?= $this->include($configs->view. '\templates\canvas-search')?>
    <!-- /canvasSearch -->

    <!-- toolbarShopmb -->
    <?= $this->include($configs->view. '\templates\toolbar-shop-mobile')?>
    <!-- /toolbarShopmb -->

    <!-- modal login -->
    <?= $this->include($configs->view. '\components\modal-login')?>
    <!-- /modal login -->

    <!-- shoppingCart -->
    <?= $this->include($configs->view. '\components\modal-shopping-cart')?>
    <!-- /shoppingCart -->

    <!-- modal quick_view -->
    <?= $this->include($configs->view. '\components\modal-quick-view')?>
    <!-- /modal quick_view -->

    <!-- modal share social -->
    <?= $this->include($configs->view. '\components\modal-share-social')?>
    <!-- /modal share social -->

    <script src="<?=insert_vue()?>"></script>
    <!-- Javascript -->
    <script type="text/javascript" src="<?=base_url($configs->templatePath)?>/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?=base_url($configs->templatePath)?>/assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="<?=base_url($configs->templatePath)?>/assets/js/swiper-bundle.min.js"></script>
    <script type="text/javascript" src="<?=base_url($configs->templatePath)?>/assets/js/carousel.js"></script>
    <script type="text/javascript" src="<?=base_url($configs->templatePath)?>/assets/js/count-down.js"></script>
    <script type="text/javascript" src="<?=base_url($configs->templatePath)?>/assets/js/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="<?=base_url($configs->templatePath)?>/assets/js/lazysize.min.js"></script>
    <script type="text/javascript" src="<?=base_url($configs->templatePath)?>/assets/js/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="<?=base_url($configs->templatePath)?>/assets/js/drift.min.js"></script>
    <script type="text/javascript" src="<?=base_url($configs->templatePath)?>/assets/js/wow.min.js"></script>
    <script type="text/javascript" src="<?=base_url($configs->templatePath)?>/assets/js/multiple-modal.js"></script>
    <script type="text/javascript" src="<?=base_url($configs->templatePath)?>/assets/js/main.js"></script>  
    <script type="module" src="<?=base_url($configs->templatePath)?>/assets/js/model-viewer.min.js"></script>
    <script type="module" src="<?=base_url($configs->templatePath)?>/assets/js/zoom.js"></script>

    <?= $this->renderSection('scripts') ?>
</body>
</html>