<?php
echo $this->extend($config->viewLayout);
echo $this->section('content');

?>

<div class="row">
    <div class="col-md-3">

        <!-- Profile Image -->
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-responsive" src="<?= $customer->img_avatar ?? base_url("{$config->templatePath}assets/img/avatar.png")?>" alt="<?=$customer->cus_full_name ?>">
                </div>

                <h3 class="profile-username text-center"><?= lang('Common.customer') ?></h3>

                <a href="<?=base_url(route_to('edit_customer', $customer->id))?>" class="btn btn-primary btn-block"><b><?=lang('Customer.edit_profile')?></b></a>
                <?php if(isset($customer->user_id)): ?>
                <a href="<?=base_url("acp/user/edit-password/{$customer->user_id}")?>" class="btn btn-warning btn-block"><b><?=lang('User.edit_password')?></b></a>
                <?php else: ?>
                    <a href="<?=base_url(route_to('create_customer_account', $customer->id))?>" class="btn btn-warning btn-block"><b><?=lang('Tạo tài khoản')?></b></a>
                <?php endif; ?>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <!-- About Me Box -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><?= lang('User.title_info') ?></h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <strong><i class="fas fa-user mr-1"></i> <?= lang('User.username') ?></strong>
                <p class="text-muted"><?= $customer->username ?></p>
                <hr>
                <strong><i class="fas fa-envelope mr-1"></i> <?= lang('User.email') ?></strong>
                <p class="text-muted"><?= $customer->cus_email ?></p>
                <hr>
            </div>
            <!-- /.card-body -->
        </div>

    </div> <!-- /.lefcol -->
    <div class="col-md-9">
        <!-- About Me Box -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><?=lang('User.title_general_info')?></h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <strong><?=lang('Customer.full_name')?></strong>
                <p class="text-muted"><?=$customer->cus_full_name?></p>

                <hr>

                <strong><?=lang('User.email')?></strong>
                <p class="text-muted"><?=$customer->cus_email?></p>

                <hr>

                <strong> <?=lang('Customer.phone')?></strong>
                <p class="text-muted"><?=$customer->cus_phone?></p>

                <hr>

                <strong><?=lang('Customer.customer_code')?></strong>
                <p class="text-muted"><?=$customer->cus_code?></p>

                <hr>
                <strong><?=lang('Customer.address')?></strong>
                <p class="text-muted"><?=$customer->full_address?></p>
                <hr>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>

<?= $this->endSection() ?>
<?php echo $this->section('pageScripts') ?>
<script src="<?= base_url($config->scriptsPath)?>/acp/areaLocation.js"></script>
<?= $this->endSection() ?>
