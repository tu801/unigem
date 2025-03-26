<?php
echo $this->extend($config->viewLayout);
echo $this->section('content');
?>

<div class="row" data-js-import="post-js" id="postApp" data-js-attach="attach-js">
    <div class="col-12">
        <div class="card card-outline card-primary">

        <!-- form start -->
        <form id="<?=$controller?>Form" role="form" method="post" class="form-horizontal" enctype="multipart/form-data" >
            <?=csrf_field()?>
            <div class="card-body pad">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><?=lang('Acp.selected_group')?></label>
                    <div class="col-sm-10">
                        <?=$selected_group?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="postInputTitle" class="col-sm-2 col-form-label"><?=lang('Acp.cf_title')?> <span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" name="title" class="form-control <?=session('errors.title') ? 'is-invalid' : ''?>" id="postInputTitle"
                               placeholder="<?=lang('Acp.cf_title')?>" value="<?=old('title')?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="postInputTitle" class="col-sm-2 col-form-label"><?=lang('Acp.cf_key')?> <span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" name="key" class="form-control <?=session('errors.key') ? 'is-invalid' : ''?>" id="postInputTitle"
                               placeholder="<?=lang('Acp.cf_key')?>" value="<?=old('key')?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><?=lang('Acp.cf_value')?> <span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <textarea class="form-control <?=session('errors.value') ? 'is-invalid' : ''?>" name="value" ><?=old('value')?></textarea>
                    </div>
                </div>

            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <div class="col-sm-12 col-sm-offset-2">
                    <input type="hidden" name="group_id" value="<?=$selected_group?>">
                    <button class="btn btn-primary" name="save" type="submit"><?=lang('Acp.save')?></button>
                    <button class="btn btn-primary" name="save_exit" type="submit"><?=lang('Acp.save_exit')?></button>
                    <button class="btn btn-primary" name="save_addnew" type="submit"><?=lang('Acp.save_addnew')?></button>
                    <a href="<?=route_to('config')?>" class="btn btn-default" type="reset"><?=lang('Acp.cancel')?></a>
                </div>
            </div>

        </form>

        </div>
    </div>
</div>

<?= $this->endSection() ?>