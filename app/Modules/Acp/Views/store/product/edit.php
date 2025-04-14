<?php

use App\Enums\Store\Product\ProductStatusEnum;

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
                        <input type="text" name="pd_name" class="form-control <?= session('errors.pd_name') ? 'is-invalid' : '' ?>" id="inputProductName" placeholder="<?= lang('Product.pd_name') ?>" value="<?= $itemData->pd_name  ?>">
                    </div>

                    <div class="form-group">
                        <label for="inputProductName"><?= lang('Product.pd_sku') ?> <span class="text-danger">*</span></label>
                        <input type="text" name="pd_sku" class="form-control <?= session('errors.pd_sku') ? 'is-invalid' : '' ?>" id="inputProductName" placeholder="<?= lang('Product.pd_sku') ?>" value="<?= $itemData->pd_sku  ?>">
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="inputPrice"><?= lang('Product.pd_price') ?> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="price" 
                                           step="<?= ($currentLang->locale == 'en') ? '0.01' : '1' ?>" 
                                           class="form-control <?= session('errors.price') ? 'is-invalid' : '' ?>" 
                                           id="inputPrice" 
                                           placeholder="<?= lang('Product.pd_price') ?>" 
                                           value="<?= ($currentLang->locale == 'en') ? number_format($itemData->price, 2) : floor((float)$itemData->price) ?>">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><?=lang('Acp.currency_sign')?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="inputPriceDiscount"><?= lang('Product.pd_price_discount') ?>
                                    <span class="text-danger">*</span> &nbsp;
                                    <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="<?=lang('Product.pd_price_discount_tooltip')?>"></i>
                                </label>
                                <div class="input-group">
                                    <input  type="number" name="price_discount" 
                                            step="<?= ($currentLang->locale == 'en') ? '0.01' : '1' ?>" 
                                            class="form-control <?= session('errors.price_discount') ? 'is-invalid' : '' ?>" 
                                            id="inputPriceDiscount" placeholder="<?= lang('Product.pd_price_discount') ?>" 
                                            value="<?= ($currentLang->locale == 'en') ? number_format($itemData->price_discount, 2) : floor((float)$itemData->price_discount) ?>">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><?=lang('Acp.currency_sign')?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <config-img  img-desc="<?= lang('Product.pd_image_other') ?>" select-img-type="2" input-name="images_product" return-img="id" img-data="<?php
                        if (isset($itemData->images->images)) {
                            $listImage = [];
                            foreach ($itemData->images->images as $item) {
                                $listImage[] = $item->id;
                            }
                            echo implode(';', $listImage);
                        }
                    ?>"></config-img>

                    <div class="form-group">
                        <label><?= lang('Product.description') ?> </label>
                        <textarea rows="5" class="textarea <?= session('errors.description') ? 'is-invalid' : '' ?>" 
                                id="description-editor" name="pd_description"><?= $itemData->pd_description ?? '' ?></textarea>
                    </div>

                    <div class="form-group">
                        <label><?= lang('Product.product_info') ?> </label>
                        <textarea rows="5" class="textarea <?= session('errors.product_info') ? 'is-invalid' : '' ?>" 
                                id="tmteditor" name="product_info"><?= $itemData->product_info ?? '' ?></textarea>
                    </div>

                </div>
            </div>
            <div class="card card-outline card-primary">
                <div class="pl-3 pr-2">
                    <p class="h5">
                    <div class="d-flex justify-content-between align-items-center">
                        <b><?= lang('Acp.search_engine_optimize'); ?></b>
                        <a class="btn btn-link" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                            <?= lang('Acp.edit_seo_meta'); ?>
                        </a>
                    </div>
                    <div style="margin-top:5px;margin-bottom:5px;height:1px;width:100%;border-top:1px solid #ccc;"></div>
                    <br />
                    <div>
                        <span style="color:#1a0dab;"><?= $itemData->pd_name ?></span>
                        <div>
                            <p style="color:#006621;"><?= $itemData->url ?></p>
                        </div>
                        <span><?= $itemData->product_meta['seo_description'] ?? '' ?></span>
                    </div>
                    </p>
                </div>
                <div class="collapse" id="collapseExample">
                    <div class="card-body">
                        <div class="form-group ">
                            <label><?= lang('Post.meta_title') ?></label> - <?= lang('Acp.meta_desc_left'); ?> <span id="title_word_left">70</span>
                            <input id="title_word_count" class="form-control" name="seo_title" value="<?=$itemData->product_meta['seo_title'] ?? '' ?>" />
                        </div>
                        <div class="form-group ">
                            <label><?= lang('Post.meta_keyword') ?></label>
                            <textarea class="form-control" name="seo_keyword"><?= $itemData->product_meta['seo_keyword'] ?? '' ?></textarea>
                        </div>

                        <div class="form-group ">
                            <label><?= lang('Post.meta_description') ?></label> - <?= lang('Acp.meta_desc_left'); ?> <span id="word_left">300</span>
                            <textarea id="word_count" class="form-control" name="seo_description"><?= $itemData->product_meta['seo_description'] ?? '' ?></textarea>
                        </div>

                    </div>
                </div>

                <div class="card-body">
                    <input type="hidden" name="mod_id" id="inpModId" value="<?= $itemData->id ?>">
                    <input type="hidden" name="mod_name" id="inpModName" value="product">
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
                    <div class="form-group ">
                        <label><?= lang('Acp.lang') ?>: <span class="badge badge-info "> <?= $currentLang->name  ?></span> </label>
                    </div>

                    <div class="form-group">
                        <label for="inputProductStatus"><?= lang('Product.pd_status') ?> <span class="text-danger">*</span></label>
                        <select class="custom-select form-control" name="pd_status">
                            <?php foreach (ProductStatusEnum::toArray() as $item): ?>
                                <option value="<?= $item ?>" <?= $itemData->pd_status == $item ? 'selected' : ''  ?> ><?= lang("Product.status_{$item}") ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="inputProductCategory"><?= lang('Product.pd_category') ?> <span class="text-danger">*</span></label>
                        <select class="custom-select form-control" name="cat_id">
                            <?php foreach ($product_category as $item): ?>
                                <option value="<?= $item->cat_id ?>" <?= $item->cat_id == $itemData->cat_id ? 'selected' : ''  ?> ><?= $item->title ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="inputWeight"><?= lang('Product.weight') ?> <span class="text-danger">*</span></label>
                        <div class="input-group" >
                            <input type="number" name="pd_weight" step="any" 
                                    class="form-control <?= session('errors.weight') ? 'is-invalid' : '' ?>" 
                                    id="inputWeight" placeholder="<?= lang('Product.weight') ?>" 
                                    value="<?= $itemData->pd_weight ?? 1  ?>">
                            <div class="input-group-append">
                                <div class="input-group-text">g &nbsp;<i class="fas fa-weight"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputWeight"><?= lang('Product.size') ?></label>
                        <div class="input-group" >
                            <input type="text" name="pd_size" 
                                class="form-control" id="inputSize" 
                                placeholder="<?= lang('Product.size') ?>" 
                                value="<?= $itemData->pd_size ?? 1 ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputWeight"><?= lang('Product.cut_angle') ?></label>
                        <div class="input-group" >
                            <input type="text" name="pd_cut_angle" 
                                    class="form-control" id="inputCutAngle" 
                                    placeholder="<?= lang('Product.cut_angle') ?>" 
                                    value="<?= $itemData->pd_cut_angle ?? 1 ?>">
                        </div>
                    </div>

                    <div class="form-group ">
                        <label><?= lang('Product.pd_tags') ?></label>
                        <vposttags :limitlength="2"></vposttags>
                    </div>
                </div>
            </div>

            <div class="card card-outline card-primary">
                <div class="card-body">
                    <feature-img img-desc="<?= lang('Product.avatar_size') ?>" 
                                demo="<?= (!empty($itemData->feature_image)) ? $itemData->feature_image['thumbnail'] : '' ?>" 
                                img-title="<?= lang('Product.image') ?>"></feature-img>
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
<?= $this->endSection() ?>
