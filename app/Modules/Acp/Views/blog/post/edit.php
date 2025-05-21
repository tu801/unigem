<?php

use App\Enums\Post\PostPositionEnum;
use App\Enums\Post\PostStatusEnum;

echo $this->extend($config->viewLayout);
echo $this->section('content');

?>
<!-- form start -->
<form id="<?= $controller ?>Form" role="form" method="post" class="form-horizontal" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="row" id="postApp" data-cattype="post">
        <div class="col-md-9">
            <div class="card card-outline card-primary">
                <div class="card-body pad">
                    <div class="form-group">
                        <label for="postInputTitle"><?= lang('Post.title') ?> <span class="text-danger">*</span></label>
                        <input type="text" name="title"
                            class="form-control <?= session('errors.title') ? 'is-invalid' : '' ?>" id="postInputTitle"
                            placeholder="<?= lang('Post.title') ?>" value="<?= htmlspecialchars($itemData->title) ?>">

                        <div class="custom-control mt-2 custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="createNewSlug" name="createSlug">
                            <label for="createNewSlug"
                                class="custom-control-label"><?= lang('Post.create_new_slug') ?></label>
                        </div>
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
                            <content-gallery v-if="showModal" @close="showModal = false" @close-modal="hideGallery"
                                att-type="tmteditor">
                                </vgallery>

                        </div>
                    </div>

                    <div class="form-group ">
                        <label>Tags</label>
                        <vposttags :limitlength="2"></vposttags>
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
                        <span><?= $itemData->description ?></span>
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
                        <button class="btn btn-primary mr-2" name="save_addnew"
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
                            foreach (PostStatusEnum::toArray() as $key) :
                                $sel = ($key == $itemData->post_status) ? 'selected' : '';
                            ?>
                            <option <?= $sel ?> value='<?= $key ?>'><?= lang('Post.post_status_' . $key) ?></option>
                            <?php endforeach;    ?>
                        </select>
                    </div>

                    <div class="form-group ">
                        <label><?= lang('Post.post_position') ?></label>
                        <select class="form-control" name="post_position">
                            <option value="">--Chọn Vị Trí--</option>
                            <?php
                            foreach (PostPositionEnum::toArray() as $key) :
                                $sel = ($key == $itemData->post_position) ? 'selected' : '';
                            ?>
                            <option <?= $sel ?> value='<?= $key ?>'><?= lang('Post.post_position_' . $key) ?></option>
                            <?php endforeach;    ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card card-outline card-primary">
                <div class="card-body">
                    <div class="form-group ">
                        <label><?= lang('Post.category') ?> <span class="text-danger">*</span></label>
                        <?php
                        $_cats = model(\App\Models\Blog\CategoryModel::class);
                        $catData = $_cats->where('cat_type', 'post')
                            ->join('category_content', 'category_content.cat_id = category.id')
                            ->where('lang_id', $currentLang->id)
                            ->where('cat_status', 'publish')
                            ->findAll();
                        ?>
                        <select class="category" id="slt2Cat" multiple="multiple"
                            data-placeholder="<?= lang('Post.select_cat_holder') ?>" name="categories[]"
                            style="width: 100%;">
                            <?php foreach ($catData as $catItem) :
                                $selected = '';
                                foreach ($itemData->categories as $selCat) {
                                    if ($catItem->id == $selCat['id']) $selected = 'selected="selected"';
                                }
                            ?>
                            <option value="<?= $catItem->id ?>" <?= $selected ?>><?= $catItem->title ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group"
                        style="display: <?= (isset($itemData->categories) && count($itemData->categories) > 0) ? 'block' : 'none' ?>"
                        id="frm_cat_primary">
                        <label><?= lang('Post.primary_category') ?></label>
                        <select class="form-control" name="cat_is_primary" id="cat_primary">
                            <?php
                            foreach ($itemData->categories as $category) :
                                $selected = '';
                                if ($category['is_primary'] == 1) $selected = 'selected="selected"';
                            ?>
                            <option value="<?= $category['id'] ?>" <?= $selected ?>><?= $category['title'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card card-outline card-primary">
                <div class="card-body">
                    <feature-img img-desc="<?= lang('Post.feature_image_desc') ?>"
                        demo="<?= (!empty($itemData->images)) ? $itemData->images['thumbnail'] : '' ?>"
                        img-title="<?= lang('Post.image') ?>"></feature-img>
                </div>
            </div>

        </div>
    </div>
</form>

<?php
echo view($config->view . '\blog\post\_vPostTags');
echo view($config->view . '\components\_vFeatureImg');
echo view($config->view . '\components\_vPostSlug');
//vuejs content gallery
echo view($config->view . '\system\attach\_contentGallery');
?>
<?= $this->endSection() ?>

<?php echo $this->section('pageScripts') ?>
<link type="text/css" rel="stylesheet"
    href="<?= base_url("{$config->scriptsPath}/vuejs-plugins/multiselect/default.css") ?>" />
<link type="text/css" rel="stylesheet" href="https://unpkg.com/vue-next-select/dist/index.min.css" />
<?= $this->endSection() ?>

<?php echo $this->section('pageScripts') ?>
<script src="<?= base_url("{$config->scriptsPath}/tinymce/tinymce.min.js") ?>"></script>
<script src="<?= base_url("{$config->scriptsPath}/tinymce/jquery.tinymce.min.js") ?>"></script>
<script src="<?= base_url("{$config->scriptsPath}/acp/blog/postEditor.js") ?>"></script>

<script src="<?= base_url("{$config->scriptsPath}/vuejs-plugins/multiselect/multiselect.global.js") ?>"></script>
<script src="https://unpkg.com/vue-next-select/dist/vue-next-select.iife.prod.js"></script>
<script src="<?= base_url($config->scriptsPath) ?>/acp/blog/vPost.js?v=1"></script>
<script src="<?= base_url($config->scriptsPath) ?>/acp/blog/postWordCount.js"></script>
<script type="text/javascript">
$(function() {
    //Initialize Select2 Elements
    $('#slt2Cat').select2();
    var $eventSelect = $("#slt2Cat");
    $eventSelect.on("select2:close", function(e) {
        var catSelected = $('#slt2Cat').select2("data");
        console.log(catSelected);
        if (catSelected.length > 0) {
            $('#cat_primary').empty();
            for (var i = 0; i <= catSelected.length - 1; i++) {
                $('#cat_primary').append(new Option(catSelected[i].text, catSelected[i].id));
            }
            $('#frm_cat_primary').show();
        } else {
            $('#cat_primary').empty();
            $('#frm_cat_primary').hide();
        }
    });
});
</script>

<?= $this->endSection() ?>