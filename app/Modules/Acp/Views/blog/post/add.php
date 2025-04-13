<?php

use App\Enums\Post\PostPositionEnum;
use App\Enums\Post\PostStatusEnum;

echo $this->extend($config->viewLayout);
echo $this->section('content');

$frmAttr = ['enctype' => 'multipart/form-data', 'class' => 'form-horizontal', 'id' => $controller . 'Form'];
?>
<!-- form start -->
<?= form_open(route_to('add_post'), $frmAttr) ?>
<div class="row" id="postApp" data-cattype="post">

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
                    <div class="col-sm-12">
                        <div class="row mb-2">
                            <button @click.prevent="showGallery" class="btn btn-primary" id="showGallery">Gallery</button>
                        </div>
                        <vgallery v-if="showModal" @close="showModal = false" @close-modal="hideGallery" att-type="tmteditor"></vgallery>

                    </div>
                </div>

                <div class="form-group ">
                    <label>Tags</label>
                    <vposttags :limitlength="2"></vposttags>
                </div>

            </div>
        </div>

        <div class="card card-outline card-primary">
            <?php echo view('Modules\Acp\Views\blog\metaData\metaFrm'); ?>
            <div class="card-footer">
                <input type="hidden" name="mod_name" id="inpModName" value="post">
                <input type="hidden" name="user_init" id="inpUserID" value="<?= $login_user->id ?>">
                <button class="btn btn-sm btn-success mr-2" id="postSave" name="save" type="submit"><?= lang('Acp.save') ?> (F2)</button>
                <button class="btn btn-sm btn-primary mr-2" id="postSaveExit" name="save_exit" type="submit"><?= lang('Acp.save_exit') ?> (F7)</button>
                <button class="btn btn-sm btn-primary mr-2" name="save_addnew" type="submit"><?= lang('Acp.save_addnew') ?></button>
                <a href="<?= route_to('post') ?>" class="btn btn-sm btn-danger" type="reset"><?= lang('Acp.cancel') ?></a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-outline card-primary">
            <div class="card-body">
                <div class="form-group ">
                    <label><?= lang('Acp.lang') ?>: <span class="badge badge-info "> <?= $curLang->name  ?></span> </label>
                </div>

                <div class="form-group ">
                    <label><?= lang('Post.post_status') ?></label>
                    <select class="form-control" name="post_status">
                        <?php foreach (PostStatusEnum::toArray() as $key) : ?>
                            <option value='<?= $key ?>'><?= lang('Post.post_status_'.$key) ?></option>
                        <?php endforeach;    ?>
                    </select>
                </div>

                <div class="form-group ">
                    <label><?= lang('Post.post_position') ?></label>
                    <select class="form-control" name="post_position">
                        <option value="">--Chọn Vị Trí--</option>
                        <?php foreach ( PostPositionEnum::toArray() as $key ) : ?>
                            <option value='<?= $key ?>'><?= lang('Post.post_position_'.$key) ?></option>
                        <?php endforeach;    ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="card card-outline card-primary">
            <div class="card-body">
                <div class="form-group">
                    <label><?= lang('Post.category') ?> <span class="text-danger">*</span></label>
                    <?php
                    $_cats = model(\App\Models\Blog\CategoryModel::class);
                    $catData = $_cats->where('cat_type', 'post')
                        ->join('category_content', 'category_content.cat_id = category.id')
                        ->where('lang_id', $curLang->id)
                        ->where('cat_status', 'publish')
                        ->findAll();
                    $catOld = old('categories');
                    ?>
                    <select class="category" id="slt2Cat" multiple="multiple" data-placeholder="<?= lang('Post.select_cat_holder') ?>" name="categories[]" style="width: 100%;">
                        <?php foreach ($catData as $catItem) :
                            $selected = '';
                            if (isset($catOld) && count($catOld) > 0) :
                                foreach ($catOld as $selCat) {
                                    if ($catItem->id == $selCat) $selected = 'selected="selected"';
                                }
                            endif;
                        ?>
                            <option value="<?= $catItem->id ?>" <?= $selected ?>><?= $catItem->title ?></option>
                        <?php endforeach; ?>
                    </select>

                </div>

                <div class="form-group" style="display: <?= (isset($catOld) && count($catOld) > 0) ? 'block' : 'none' ?>" id="frm_cat_primary">
                    <label><?= lang('Post.primary_category') ?></label>
                    <select class="form-control" name="cat_is_primary" id="cat_primary">
                        <?php
                        $catPriOld = old('cat_is_primary');
                        foreach ($catData as $catItem) :
                            $selected = ($catItem->id == $catPriOld) ? 'selected="selected"' : '';
                            ?>
                            <option value="<?= $catItem->id ?>" <?= $selected ?>><?= $catItem->title ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="card card-outline card-primary">
            <div class="card-body">
                <feature-img img-desc="<?=lang('Post.feature_image_desc')?>" img-title="<?= lang('Post.image') ?>"></feature-img>
            </div>
        </div>

    </div>

</div>
</form>
<?php
echo view($config->view . '\blog\post\_vPostTags');
echo view($config->view . '\components\_vFeatureImg');
//vuejs gallery
echo view($config->view . '\system\attach\_modalGallery');
?>
<?= $this->endSection() ?>

<?php echo $this->section('pageScripts') ?>
<script src="<?= base_url("{$config->scriptsPath}/tinymce/tinymce.min.js") ?>"></script>
<script src="<?= base_url("{$config->scriptsPath}/tinymce/jquery.tinymce.min.js") ?>"></script>
<script src="<?= base_url("{$config->scriptsPath}/acp/blog/postEditor.js") ?>"></script>

<script src="<?= base_url($config->scriptsPath) ?>/acp/blog/vPost.js?v=1"></script>
<script src="<?= base_url($config->scriptsPath) ?>/acp/blog/postWordCount.js"></script>

<script type="text/javascript">
    $(function() {
        //Initialize Select2 Elements
        $('.category').select2();
        var $eventSelect = $(".category");
        $eventSelect.on("select2:close", function(e) {
            var catSelected = $('#slt2Cat').select2("data");

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