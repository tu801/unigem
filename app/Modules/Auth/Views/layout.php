<?php
$config = config('Acp');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />

    <title><?= $this->renderSection('title') ?></title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url("{$config->scriptsPath}plugins/fontawesome-free/css/all.min.css"); ?>">
    <!-- icheck bootstrap -->
    <link rel="stylesheet"
        href="<?= base_url($config->scriptsPath) ?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url("{$config->templatePath}assets/css/adminlte.min.css"); ?>">

    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <?= $this->renderSection('pageStyles') ?>
</head>

<body class="hold-transition login-page">

    <div class="login-logo">
        <a href="<?= base_url() ?>">
            <img src="<?= base_url() ?>/themes/unigem/images/unigem-logo.png" alt="TMT CMS" width="150px" height="150px"
                class="img-fluid rounded mx-auto d-block">
        </a>
    </div>
    <!-- /.login-logo -->

    <?= $this->renderSection('main') ?>


    <!-- Bootstrap core JavaScript
================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- jQuery -->
    <script src="<?= base_url($config->scriptsPath) ?>/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url($config->scriptsPath) ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url($config->templatePath) ?>/assets/js/adminlte.min.js"></script>

</body>

</html>