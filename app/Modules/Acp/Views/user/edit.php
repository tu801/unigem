<?php
echo $this->extend($config->viewLayout);
echo $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <!-- form start -->
        <form id="<?=$module?>Form" method="post" class="form-horizontal" enctype="multipart/form-data">
            <?=csrf_field()?>
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><?php echo lang('User.title_account_info')?></h3>
                </div>

                <div class="card-body">

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?=lang('User.username')?> <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" disabled class="form-control <?php if(session('errors.username')) : ?>is-invalid<?php endif ?>"
                                   value="<?=old('username') ? old('username') : $userData->username?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?=lang('User.email')?> <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="email" <?=($login_user->gid == 1)? "name='email'" : "disabled"?> class="form-control <?php if(session('errors.email')) : ?>is-invalid<?php endif ?>"
                                   value="<?=old('email') ? old('email') : $userData->email?>" >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?=lang('User.user_group')?></label>
                        <div class="col-sm-10">
                            <select class="form-control m-b" name="gid">
                                <?php if($list_userg){
                                    foreach($list_userg as $userg){
                                        $sel = ( $userData->gid == $userg->id ) ? 'selected' : '';
                                        echo "<option  value='".$userg->id."' ".$sel.">".$userg->name."</option>";
                                    }
                                }?>
                            </select>
                            <br>
                            <div class="icheck-danger d-inline">
                                <input type="checkbox" name="sync_permission" value="1" id="checkboxRequiredChangePass">
                                <label for="checkboxRequiredChangePass">Cập nhật lại quyền cho user này theo quyền của nhóm được chọn
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><?php echo lang('User.title_user_info')?></h3>
                </div>

                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?=lang('User.avata')?></label>
                        <div class="col-sm-10">
                            <input type="file" name="avatar" class="filestyle" data-icon="false" data-classButton="btn btn-default"
                                   data-classInput="form-control inline input-s"><br>

                            <img src="<?=getUserAvatar($userData)?>" class="img-lg img-thumbnail mt-2">
                        </div>
                    </div>
                </div>

            </div>

            <!--Form Meta-->
            <?php
            if ( !empty($config->user_meta) ) echo view('Modules\Acp\Views\user\meta\frm_meta');
            ?>
            <!--Form Meta-->

            <div class="card">
                <div class="card-footer">
                    <div class="col-sm-12 col-sm-offset-2">
                        <button class="btn btn-primary" name="save" type="submit"><?=lang('Acp.save')?></button>
                        <button class="btn btn-primary" name="save_exit" type="submit"><?=lang('Acp.save_exit')?></button>
                        <button class="btn btn-primary" name="save_addnew" type="submit"><?=lang('Acp.save_addnew')?></button>
                        <a href="<?=route_to('list_user')?>" class="btn btn-default" type="reset"><?=lang('Acp.cancel')?></a>
                    </div>
                </div>
            </div>
        </form>


    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('pageStyles') ?>
<link rel="stylesheet" href="<?=base_url($config->scriptsPath)?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
<link rel="stylesheet" href="<?=base_url($config->scriptsPath)?>/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<?= $this->endSection() ?>
<?=$this->section('pageScripts')?>
<script src="<?= base_url($config->scriptsPath)?>/plugins/select2/js/select2.full.min.js"></script>
<script>
    $(document).ready(function () {
        $("#cus_datepicker").datepicker({
            todayHighlight: true,
            format: "dd-mm-yyyy",
            autoclose: true,
        });
    });
</script>
<?= $this->endSection() ?>
