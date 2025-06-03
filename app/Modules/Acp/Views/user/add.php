<?php
echo $this->extend($config->viewLayout);
echo $this->section('content') ?>

<div class="row" >
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
                            <input type="text" name="username" class="form-control <?=session('errors.username') ? 'is-invalid' : ''?>"
                                   value="<?=old('username')?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?=lang('User.password')?> <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control <?php if(session('errors.password')) : ?>is-invalid<?php endif ?>"
                                   name="password" autocomplete="off">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?=lang('User.repassword')?> <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control <?php if(session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>"
                                   name="pass_confirm" autocomplete="off">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-10">
                            <div class="icheck-danger d-inline">
                                <input type="checkbox" checked name="force_pass_reset" value="1" id="checkboxRequiredChangePass">
                                <label for="checkboxRequiredChangePass">Yêu cầu đổi mật khẩu sau khi đăng nhập
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?=lang('User.user_group')?></label>
                        <div class="col-sm-10">
                            <select class="select2 form-control m-b" name="groups[]" multiple>
                                <?php if($list_userg){
                                    foreach ($list_userg as $key => $group) {
                                        echo "<option ".set_select('gid', $key)." value='".$key."'>".$group['title']."</option>";
                                    }
                                }?>
                            </select>
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
                        <label class="col-sm-2 col-form-label"><?=lang('User.email')?> <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="email" name="email" class="form-control <?php if(session('errors.email')) : ?>is-invalid<?php endif ?>"
                                   value="<?=old('email')?>" >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><?=lang('User.avata')?></label>
                        <div class="col-sm-10">
                            <input type="file" name="avatar" class="filestyle" data-icon="false" data-classButton="btn btn-default"
                                   data-classInput="form-control inline input-s">
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
<script src="<?= base_url($config->scriptsPath)?>/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

<script>
    $(document).ready(function () {
        $("#cus_datepicker").datepicker({
            todayHighlight: true,
            format: "dd-mm-yyyy",
            autoclose: true,
        });
        //Initialize Select2 Elements
        $('.select2').select2();
    });
</script>
<?= $this->endSection() ?>