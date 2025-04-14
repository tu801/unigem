<?php
echo $this->extend($config->viewLayout);
echo $this->section('content');

$postConfigs = $config->cmsStatus;
?>
<!-- form start -->
<form id="<?= $controller ?>Form" role="form" method="post" class="form-horizontal" enctype="multipart/form-data">
    <div class="row" id="postApp" data-cattype="page">
        <?= csrf_field() ?>
        <div class="col-md-9">
            <div class="card card-outline card-primary">
                <div class="card-body pad">
                    <div class="form-group">
                        <label for="postInputTitle"><?= lang('Post.title') ?> <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control <?= session('errors.title') ? 'is-invalid' : '' ?>" id="postInputTitle" placeholder="<?= lang('Post.title') ?>" value="<?= old('title') ?>">
                    </div>

                    <div class="form-group">
                        <label><?= lang('Post.description') ?></label>
                        <textarea class="form-control" name="description"><?= old('description') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label><?= lang('Post.content') ?> <span class="text-danger">*</span></label>
                        <textarea rows="10" class="textarea <?= session('errors.content') ? 'is-invalid' : '' ?>" id="tmteditor" name="content"><?= old('content') ?></textarea>
                    </div>
                    <div class="form-group " id="vGalleryApp">
                        <div class="col-sm-12" id="attGallery">
                            <div class="row mb-2">
                                <button @click.prevent="showGallery" class="btn btn-primary" id="showGallery">Gallery</button>
                            </div>
                            <vgallery v-if="showModal" @close="showModal = false" :selecteditem="selectedImg" @show-file="setAttachFiles" @remove-file="deleteUploadFile" @close-modal="hideGallery" att-type="tmteditor"></vgallery>

                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-outline card-primary">
                <?php echo view('Modules\Acp\Views\blog\metaData\metaFrm'); ?>
                <div class="card-footer">
                    <input type="hidden" name="user_init" id="inpUserID" value="<?= $login_user->id ?>">
                    <button class="btn btn-primary mr-2" id="postSave" name="save" type="submit"><?= lang('Acp.save') ?> (F2)</button>
                    <button class="btn btn-primary mr-2" id="postSaveExit" name="save_exit" type="submit"><?= lang('Acp.save_exit') ?> (F7)</button>
                    <button class="btn btn-primary" name="save_addnew" type="submit"><?= lang('Acp.save_addnew') ?></button>
                    <a href="<?= route_to('post') ?>" class="btn btn-default" type="reset"><?= lang('Acp.cancel') ?></a>
                </div>
            </div>
        </div>

        <div class="col-md-3">

            <div class="card card-outline card-primary">
                <div class="card-body">
                    <?php if ( count($language) > 1 ) : ?>
                    <!--<div class="form-group ">
                        <label><?= lang('Acp.choose_lang') ?></label>
                        <select class="form-control" name="lang_id">
                            <?php foreach ($language as $item) :
                                if ( old('lang_id') ) $sel = old('lang_id') == $item->id ? 'selected' : '';
                                else $sel = ( $currentLang->id == $item->id ) ? 'selected' : '';
                                ?>
                                <option value='<?= $item->id ?>' <?=$sel?> >
                                    <?="<img src='{$item->icon}' width='16' title='{$item->name}' alt='{$item->name}'>"?>
                                    <?= $item->name ?></option>
                            <?php endforeach;    ?>
                        </select>
                    </div>-->
                    <?php endif; ?>

                    <div class="form-group ">
                        <label><?= lang('Acp.lang') ?>: <span class="badge badge-info "> <?= $currentLang->name  ?></span> </label>
                    </div>

                    <div class="form-group ">
                        <label><?= lang('Post.post_status') ?></label>
                        <select class="form-control" name="post_status">
                            <?php foreach ($postConfigs['status'] as $key => $title) : ?>
                                <option value='<?= $key ?>'><?= $title ?></option>
                            <?php endforeach;    ?>
                        </select>
                    </div>

                </div>
            </div>

            <div class="card card-outline card-primary">
                <div class="card-body">
                    <feature-img img-desc="Hình đại diện phải có kích thước lớn hơn hoặc bằng 925 x 520pixel" img-title="<?= lang('Post.image') ?>"></feature-img>
                </div>
            </div>

        </div>

    </div>
</form>
<?php
echo view($config->view . '\components\_vFeatureImg');
//vuejs gallery
echo view($config->view . '\system\attach\_modalGallery');
?>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script src="<?= base_url("{$config->scriptsPath}/tinymce/tinymce.min.js") ?>"></script>
<script src="<?= base_url("{$config->scriptsPath}/tinymce/jquery.tinymce.min.js") ?>"></script>
<script src="<?= base_url("{$config->scriptsPath}/acp/blog/postEditor.js") ?>"></script>

<script src="<?= base_url($config->scriptsPath) ?>/acp/blog/vPost.js"></script>

<?= $this->endSection() ?>