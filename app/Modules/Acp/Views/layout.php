<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title><?='CMS'?> - <?php echo $title??'';?></title>
    <meta name="description" content="overview &amp; stats" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?=csrf_meta()?>
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="<?=base_url($config->templatePath)?>/assets/img/favicon.png">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url("{$config->scriptsPath}plugins/fontawesome-free/css/all.min.css");?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?=base_url($config->scriptsPath)?>/plugins/ionicons/css/ionicons.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= base_url($config->scriptsPath)?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="<?= base_url($config->scriptsPath)?>/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">

    <!-- Toastr -->
    <link rel="stylesheet" href="<?=base_url($config->scriptsPath)?>/plugins/toastr/toastr.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?=base_url($config->scriptsPath)?>/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?=base_url($config->scriptsPath)?>/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?=base_url($config->scriptsPath)?>/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <?= $this->renderSection('pageStyles') ?>
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url("{$config->templatePath}assets/css/adminlte.min.css");?>">

    <!--load custom css-->
    <?php put_headers() ?>

    <link rel="stylesheet" href="<?= base_url("{$config->templatePath}assets/css/acp.css");?>">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <script type="text/javascript">
        var base_url = '<?=base_url()?>';
        var bkUrl = '<?=base_url($config->adminSlug)?>';
    </script>
    <script src="<?=insert_vue()?>"></script>
    <!-- jQuery -->
    <script src="<?= base_url($config->scriptsPath)?>/plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="<?= base_url($config->scriptsPath)?>/plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <!-- Top Navbar -->
    <?= view('Modules\Acp\Views\templates\_topnav') ?>
    <!-- /.top navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar elevation-4 sidebar-light-primary">
        <!-- Sidebar Menu -->
        <?= view('Modules\Acp\Views\templates\_sidebar') ?>
        <!-- /.sidebar-menu -->
    </aside>
    <!--End Main Sidebar Container -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark"><?=$title??''?></h1>
                    </div><!-- /.col -->

                    <div class="col-sm-6"><!--breadcrums-->

                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <?= view('Modules\Acp\Views\templates\_message_block') ?>

                <!-- PAGE CONTENT BEGINS -->
                <?= $this->renderSection('content') ?>
                <!-- PAGE CONTENT ENDS -->
            </div>
        </section>
        <!-- /.content -->

    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <strong>
            Copyright &copy; <?php echo date('Y')?> <a href="<?=base_url()?>"><?=$config->sys['system_site_name']??''?></a>. All rights reserved.

        </strong>
        <!-- Default to the left -->
        <!--<div class="float-right d-none d-sm-inline-block">
            Developed by <a href="https://www.facebook.com/tmtuan">tmtuan</a>
        </div>-->
    </footer>

</div><!-- /.main-container -->


<!-- /.modal -->
<?= view('Modules\Acp\Views\templates\_modal') ?>
<!-- /.modal -->

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<!-- Bootstrap 4 -->
<script src="<?=base_url($config->scriptsPath)?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="<?=base_url($config->scriptsPath)?>/plugins/select2/js/select2.full.min.js"></script>

<!-- SweetAlert2 -->
<script src="<?=base_url($config->scriptsPath)?>/plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="<?=base_url($config->scriptsPath)?>/plugins/toastr/toastr.min.js"></script>

<!-- overlayScrollbars -->
<script src="<?= base_url($config->scriptsPath)?>/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>

<!--load custom js-->
<?php put_footers() ?>
<!-- AdminLTE App -->
<script src="<?= base_url($config->templatePath)?>/assets/js/adminlte.js"></script>
<script src="<?= base_url($config->templatePath)?>/assets/js/acp.js"  ></script>
<script id="_mainJS" data-plugins="load"></script>
<?= $this->renderSection('pageScripts') ?>
<script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
</script>
</body>
</html>