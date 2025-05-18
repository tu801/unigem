<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?= get_theme_config('general_site_title') ?? getenv('app.site_name') ?? '' ?></title>
    <meta name="author" content="tmtuan">
    <meta name="robots" content="noindex, follow" />

    <!-- font -->
    <link rel="stylesheet" href="<?=base_url($configs->templatePath)?>fonts/fonts.css">
    <!-- Icons -->
    <link rel="stylesheet" href="<?=base_url($configs->templatePath)?>fonts/font-icons.css">
    <link rel="stylesheet" href="<?=base_url($configs->templatePath)?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=base_url($configs->templatePath)?>css/drift-basic.min.css">
    <link rel="stylesheet" href="<?=base_url($configs->templatePath)?>css/photoswipe.css">
    <link rel="stylesheet" href="<?=base_url($configs->templatePath)?>css/swiper-bundle.min.css">
    <link rel="stylesheet" href="<?=base_url($configs->templatePath)?>css/animate.css">
    <link rel="stylesheet"type="text/css" href="<?=base_url($configs->templatePath)?>css/styles.css"/>
    <link rel="stylesheet"type="text/css" href="<?=base_url($configs->templatePath)?>css/custom-styles.css"/>

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
    <button id="goTop">
        <span class="border-progress"></span>
        <span class="icon icon-arrow-up"></span>
    </button>
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
    <script type="text/javascript" src="<?=base_url($configs->templatePath)?>js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?=base_url($configs->templatePath)?>js/jquery.min.js"></script>
    <script type="text/javascript" src="<?=base_url($configs->templatePath)?>js/swiper-bundle.min.js"></script>
    <script type="text/javascript" src="<?=base_url($configs->templatePath)?>js/carousel.js"></script>
    <script type="text/javascript" src="<?=base_url($configs->templatePath)?>js/count-down.js"></script>
    <script type="text/javascript" src="<?=base_url($configs->templatePath)?>js/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="<?=base_url($configs->templatePath)?>js/lazysize.min.js"></script>
    <script type="text/javascript" src="<?=base_url($configs->templatePath)?>js/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="<?=base_url($configs->templatePath)?>js/drift.min.js"></script>
    <script type="text/javascript" src="<?=base_url($configs->templatePath)?>js/wow.min.js"></script>
    <script type="text/javascript" src="<?=base_url($configs->templatePath)?>js/multiple-modal.js"></script>
    <script type="text/javascript" src="<?=base_url($configs->templatePath)?>js/main.js"></script>  
    <script type="module" src="<?=base_url($configs->templatePath)?>js/model-viewer.min.js"></script>
    
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
    </script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>