<?php

use Modules\Acp\Enums\Store\Product\ProductStatusEnum;

echo $this->extend($config->viewLayout);
echo $this->section('content');
?>
<form id="<?= $controller ?>Form" role="form" method="post" class="form-horizontal" enctype="multipart/form-data">
    <div class="row" id="productApp" data-cattype="product">
        <?= csrf_field() ?>
        <div class="col-md-9">
            <div class="card card-outline card-primary">
                <div class="card-body pad">

                    <div class="form-group">
                        <label for="inputProductName"><?= lang('Product.pd_name') ?> <span class="text-danger">*</span></label>
                        <input type="text" name="pd_name" class="form-control <?= session('errors.pd_name') ? 'is-invalid' : '' ?>" id="inputProductName" placeholder="<?= lang('Product.pd_name') ?>" value="<?= old('pd_name') ?>">
                    </div>

                    <div class="form-group">
                        <label for="inputProductName"><?= lang('Product.pd_sku') ?> <span class="text-danger">*</span></label>
                        <input type="text" name="pd_sku" class="form-control <?= session('errors.pd_sku') ? 'is-invalid' : '' ?>" id="inputProductName" placeholder="<?= lang('Product.pd_sku') ?>" value="<?= old('pd_sku') ?>">
                    </div>


                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="inputOriginPrice"><?= lang('Product.pd_origin_price') ?> <span class="text-danger">*</span></label>
                                <input type="number" name="origin_price" class="form-control <?= session('errors.origin_price') ? 'is-invalid' : '' ?>" id="inputOriginPrice" placeholder="<?= lang('Product.pd_origin_price') ?>" value="<?= old('origin_price') ?? 0 ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="inputPrice"><?= lang('Product.pd_price') ?> <span class="text-danger">*</span></label>
                                <input type="number" name="price" class="form-control <?= session('errors.price') ? 'is-invalid' : '' ?>" id="inputPrice" placeholder="<?= lang('Product.pd_price') ?>" value="<?= old('price') ?? 0 ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="inputPriceDiscount"><?= lang('Product.pd_price_discount') ?>
                                    <span class="text-danger">*</span> &nbsp;
                                    <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="<?=lang('Product.pd_price_discount_tooltip')?>"></i>
                                </label>
                                <input type="number" name="price_discount" class="form-control <?= session('errors.price_discount') ? 'is-invalid' : '' ?>" id="inputPriceDiscount" placeholder="<?= lang('Product.pd_price_discount') ?>" value="<?= old('price_discount') ?? 0 ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="inputMinimum"><?= lang('Product.minimum') ?> <span class="text-danger">*</span></label>
                                <input type="number" name="minimum" class="form-control <?= session('errors.minimum') ? 'is-invalid' : '' ?>" id="inputMinimum" placeholder="<?= lang('Product.minimum') ?>" value="<?= old('minimum') ?? 1 ?>">
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="inputWeight"><?= lang('Product.weight') ?> <span class="text-danger">*</span></label>
                                <div class="input-group" >
                                    <input type="number" step="any" name="weight" class="form-control <?= session('errors.weight') ? 'is-invalid' : '' ?>" id="inputWeight" placeholder="<?= lang('Product.weight') ?>" value="<?= old('weight') ?? 1 ?>">
                                    <div class="input-group-append" data-target="#timepicker" data-toggle="datetimepicker">
                                        <div class="input-group-text">kg &nbsp;<i class="fas fa-weight"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <config-img  img-desc="<?= lang('Product.pd_image_other') ?>" select-img-type="2" input-name="images_product" return-img="id" img-data="" ></config-img>

                    <div class="form-group">
                        <label><?= lang('Product.description') ?></label>
                        <textarea rows="5" class="textarea <?= session('errors.description') ? 'is-invalid' : '' ?>" id="description-editor" name="description"><?= old('description') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label><?= lang('Product.product_info') ?> </label>
                        <textarea rows="5" class="textarea <?= session('errors.product_info') ? 'is-invalid' : '' ?>" id="tmteditor" name="product_info"><?= old('product_info') ?></textarea>
                    </div>
                </div>
            </div>
            <div class="card card-outline card-primary">
                <?php echo view('Modules\Acp\Views\blog\metaData\metaFrm'); ?>
                <div class="card-body">
                    <input type="hidden" name="mod_name" id="inpModName" value="product">
                    <button class="btn btn-primary mr-2" id="postSave" name="save" type="submit"><?= lang('Acp.save') ?> (F2)</button>
                    <button class="btn btn-primary mr-2" id="postSaveExit" name="save_exit" type="submit"><?= lang('Acp.save_exit') ?> (F7)</button>
                    <button class="btn btn-primary" name="save_addnew" type="submit"><?= lang('Acp.save_addnew') ?></button>
                    <a href="<?= route_to('product') ?>" class="btn btn-default" type="reset"><?= lang('Acp.cancel') ?></a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
        <div class="card card-outline card-primary">
            <div class="card-body">
                <div class="form-group ">
                    <label><?= lang('Acp.lang') ?>: <span class="badge badge-info "> <?= $curLang->name  ?></span> </label>
                </div>

                <div class="form-group">
                    <label for="inputProductStatus"><?= lang('Product.pd_status') ?> <span class="text-danger">*</span></label>
                    <select class="custom-select form-control" name="pd_status">
                        <?php foreach (ProductStatusEnum::toArray() as $item): ?>
                            <option value="<?= $item ?>" <?= old('cat_id') == $item ? 'selected' : ''  ?> ><?= lang("Product.status_{$item}") ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>
        </div>
        
            <div class="card card-outline card-primary">
                <div class="card-body">
                    <feature-img img-desc="<?= lang('Product.avatar_size') ?>" img-title="<?= lang('Product.image') ?>"></feature-img>

                    <div class="form-group">
                        <label for="inputProductCategory"><?= lang('Product.pd_category') ?> <span class="text-danger">*</span></label>
                        <select class="custom-select form-control" name="cat_id">
                            <?php foreach ($product_category as $item): ?>
                                <option value="<?= $item->cat_id ?>" <?= old('cat_id') == $item->cat_id ? 'selected' : ''  ?> ><?= $item->title ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group ">
                        <label><?= lang('Product.pd_tags') ?></label>
                        <vposttags :limitlength="2"></vposttags>
                    </div>

                </div>
            </div>

        </div>

    </div>
</form>
<?php
echo view($config->view.'\components\_vFeatureImg');
echo view($config->view.'\system\attach\_vGallery');
echo view($config->view.'\system\attach\_vConfigAttach');
echo view($config->view.'\blog\post\_vPostTags');
?>
<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
<script src="<?= base_url($config->scriptsPath)?>/acp/sys/vConfigAttach.js"></script>
<script type="text/javascript">
    const themeApp = Vue.createApp({});
    themeApp.component('config-img', vConfigAttach);
    themeApp.component('feature-img', featureImg);
    themeApp.component('vgallery', gallery);
    themeApp.component('vimg-reivew', imgGalleryReview);
    themeApp.component('vgallery-img', galleryImg);
    themeApp.component('vimg-infor', imgInfor);
    themeApp.component('vfileReivew', fileReview);
    themeApp.component('vposttags', posttags);
    themeApp.mount('#productApp');
</script>
<script src="<?= base_url("{$config->scriptsPath}/tinymce/tinymce.min.js") ?>"></script>
<script src="<?= base_url("{$config->scriptsPath}/tinymce/jquery.tinymce.min.js") ?>"></script>
<script src="<?= base_url("{$config->scriptsPath}/acp/blog/postEditor.js") ?>"></script>
<script src="<?= base_url("{$config->scriptsPath}/acp/product/productEditor.js") ?>"></script>
<script src="<?= base_url($config->scriptsPath) ?>/acp/blog/postWordCount.js"></script>


<?= $this->endSection() ?>
