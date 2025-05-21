<?php
echo $this->extend($config->viewLayout);
echo $this->section('content');
// print_r($itemData->categories);exit;
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
                        <input type="text" name="title"
                            class="form-control <?= session('errors.title') ? 'is-invalid' : '' ?>" id="postInputTitle"
                            placeholder="<?= lang('Post.title') ?>" value="<?= htmlspecialchars($itemData->title) ?>">

                    </div>

                    <post-slug full-slug="<?= $itemData->url ?>" slug="<?= $itemData->slug ?>"
                        label="<?= lang('Post.post_url') ?>" post="<?= $itemData->id ?>" token="<?= csrf_hash() ?>"
                        tkname="<?= csrf_token() ?>"></post-slug>

                    <div class="form-group ">
                        <label><?= lang('Post.description') ?></label>
                        <textarea class="form-control" name="description"><?= $itemData->description ?></textarea>
                    </div>

                    <div class="form-group ">
                        <label><?= lang('Post.content') ?> <span class="text-danger">*</span></label>
                        <textarea rows="10" class="textarea <?= session('errors.content') ? 'is-invalid' : '' ?>"
                            id="tmteditor" name="content"><?= $itemData->content ?></textarea>
                    </div>

                    <div class="form-group " id="vGalleryApp">
                        <div class="col-sm-12">
                            <div class="row mb-2">
                                <button @click.prevent="showGallery" class="btn btn-primary"
                                    id="showGallery">Gallery</button>
                            </div>
                            <content-gallery v-if="showModal" @close="showModal = false" :selecteditem="selectedImg"
                                @show-file="setAttachFiles" @remove-file="deleteUploadFile" @close-modal="hideGallery"
                                att-type="tmteditor"></content-gallery>

                        </div>
                    </div>

                </div>
            </div>

            <div class="card card-outline card-primary">

                <div class="pl-3 pr-2">
                    <p class="h5">
                    <div class="d-flex justify-content-between align-items-center">
                        <b><?= lang('Acp.search_engine_optimize'); ?></b>
                        <a class="btn btn-link" data-toggle="collapse" data-target="#collapseExample"
                            aria-expanded="false" aria-controls="collapseExample">
                            <?= lang('Acp.edit_seo_meta'); ?>
                        </a>
                    </div>
                    <div style="margin-top:5px;margin-bottom:5px;height:1px;width:100%;border-top:1px solid #ccc;">
                    </div>
                    <br />
                    <div>
                        <span style="color:#1a0dab;"><?= $itemData->title ?></span>
                        <div>
                            <p style="color:#006621;"><?= $itemData->url ?></p>
                        </div>
                        <span><?= $itemData->seo_description ?></span>
                    </div>
                    </p>
                </div>
                <div class="collapse" id="collapseExample">
                    <div class="card-body">
                        <div class="form-group ">
                            <label><?= lang('Post.meta_title') ?></label> - <?= lang('Acp.meta_desc_left'); ?> <span
                                id="title_word_left">70</span>
                            <input id="title_word_count" class="form-control" name="seo_title"
                                value="<?= $itemData->seo_meta->seo_title ?? '' ?>" />
                        </div>
                        <div class="form-group ">
                            <label><?= lang('Post.meta_keyword') ?></label>
                            <textarea class="form-control"
                                name="seo_keyword"><?= $itemData->seo_meta->seo_keyword ?? '' ?></textarea>
                        </div>

                        <div class="form-group ">
                            <label><?= lang('Post.meta_description') ?></label> - <?= lang('Acp.meta_desc_left'); ?>
                            <span id="word_left">300</span>
                            <textarea id="word_count" class="form-control"
                                name="seo_description"><?= $itemData->seo_meta->seo_description ?? '' ?></textarea>
                        </div>
                    </div>
                </div>


                <div class="card-footer">
                    <div class="col-sm-12 col-sm-offset-2">
                        <input type="hidden" name="mod_id" id="inpModId" value="<?= $itemData->id ?>">
                        <input type="hidden" name="mod_name" id="inpModName" value="post">
                        <input type="hidden" name="curruser_id" id="inpUserID" value="<?= $login_user->id ?>">
                        <button class="btn btn-primary mr-2" name="save" id="postSave"
                            type="submit"><?= lang('Acp.save') ?> (F2)</button>
                        <button class="btn btn-primary mr-2" name="save_exit" id="postSaveExit"
                            type="submit"><?= lang('Acp.save_exit') ?> (F7)</button>
                        <button class="btn btn-primary" name="save_addnew"
                            type="submit"><?= lang('Acp.save_addnew') ?></button>
                        <a href="<?= route_to('post') ?>" class="btn btn-default"
                            type="reset"><?= lang('Acp.cancel') ?></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">

            <div class="card card-outline card-primary">
                <div class="card-body">
                    <div class="form-group ">
                        <label><?= lang('Acp.lang') ?>: <span class="badge badge-info ">
                                <?= $currentLang->name  ?></span> </label>
                    </div>
                    <div class="form-group ">
                        <label><?= lang('Post.post_status') ?></label>
                        <select class="form-control" name="post_status">
                            <?php
                            foreach ($postConfigs['status'] as $key => $title) :
                                $sel = ($key == $itemData->post_status) ? 'selected' : '';
                            ?>
                            <option <?= $sel ?> value='<?= $key ?>'><?= $title ?></option>
                            <?php endforeach;    ?>
                        </select>
                    </div>

                </div>
            </div>

            <div class="card card-outline card-primary">
                <div class="card-body">
                    <feature-img demo="<?= (!empty($itemData->images)) ? $itemData->images['thumbnail'] : '' ?>"
                        img-desc="Hình đại diện phải có kích thước lớn hơn hoặc bằng 925 x 520pixel"
                        img-title="<?= lang('Post.image') ?>"></feature-img>
                </div>
            </div>

        </div>
    </div>
</form>

<?php
echo view($config->view . '\components\_vFeatureImg');
echo view($config->view . '\components\_vPostSlug');
//vuejs content gallery
echo view($config->view . '\system\attach\_contentGallery');
?>
<?= $this->endSection() ?>

<?php echo $this->section('pageScripts') ?>
<script src="<?= base_url("{$config->scriptsPath}/tinymce/tinymce.min.js") ?>"></script>
<script src="<?= base_url("{$config->scriptsPath}/tinymce/jquery.tinymce.min.js") ?>"></script>
<script src="<?= base_url("{$config->scriptsPath}/acp/blog/postEditor.js") ?>"></script>

<script src="<?= base_url($config->scriptsPath) ?>/acp/blog/vPost.js"></script>
<script src="<?= base_url($config->scriptsPath) ?>/acp/blog/postWordCount.js"></script>
<?= $this->endSection() ?>