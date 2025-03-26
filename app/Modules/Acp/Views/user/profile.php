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
                    <img class="profile-user-img img-fluid img-responsive" src="<?=$User->img_avatar?>" alt="<?=$User->fullname?>">
                </div>

                <h3 class="profile-username text-center"><?=$User->fullname?></h3>

                <p class="text-muted text-center"><?=$User->GroupInfo->name?></p>

                <a href="<?=base_url("acp/user/edit/{$User->id}")?>" class="btn btn-primary btn-block"><b><?=lang('Acp.edit')?></b></a>
                <a href="<?=base_url("acp/user/edit-password/{$User->id}")?>" class="btn btn-warning btn-block"><b><?=lang('User.edit_password')?></b></a>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <!-- About Me Box -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><?=lang('User.title_info')?></h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <strong><i class="fas fa-user mr-1"></i> <?=lang('User.username')?></strong>
                <p class="text-muted"><?=$User->username?></p>

                <hr>

                <strong><i class="fas fa-envelope mr-1"></i> <?=lang('User.email')?></strong>
                <p class="text-muted"><?=$User->email?></p>

                <hr>

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div> <!-- /.lefcol -->

    <div class="col-md-9">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#info" data-toggle="tab">Thông Tin</a></li>
                </ul>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">

                    <div class="active tab-pane" id="info">
                        <!--info content-->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Thông tin thêm</h3>
                            </div>

                            <div class="card-body">
                                <?php
                                if (!empty($config->user_meta)) :  ?>
                                    <div class="table-responsive">
                                        <table class="table no-margin">
                                            <tbody>
                                            <?php foreach ($config->user_meta as $metaKey => $val) :
                                                ?>
                                                <tr>
                                                    <th width="35%"><?= $val['title']??'' ?></th>
                                                    <td><?= $User->meta["{$metaKey}"]??'' ?></td>
                                                </tr>
                                            <?php endforeach; ?>

                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.table-responsive -->
                                <?php endif; ?>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!--End info content-->
                    </div>
                    <!-- /.tab-pane -->


                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->

</div>

<?= $this->endSection() ?>