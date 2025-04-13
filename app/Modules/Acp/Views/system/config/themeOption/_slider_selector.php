<?php
/**
 * @author tmtuan
 * created Date: 8/25/2023
 * project: nha-khoa-viet-my
 */
?>

<script type="text/x-template" id="tpl-vSliderSelector">
    <div class="form-group">
        <label class="col-form-label"><?= lang('Theme.slider_image_selector') ?></label>
        <vimg-select file_name="image[]" htmlnote="<?= lang('Theme.slider_image_info') ?>" @image-uploaded="sliderUploadSuccess"></vimg-select>
    </div>
    <div v-if="form.length">
        <input type="hidden" id="cstoken" value="<?= csrf_hash() ?>">
        <input type="hidden" id="csname" value="<?= csrf_token() ?>">
        <div v-for="(attItem, index) in form" :key="attItem.id" :imgindex="index">
            <form @submit.prevent="onSubmit(index)">
                <div class="card collapsed-card" :id="'card'+index">
                    <div class="card-header border-0">
                        <h4 class="card-title text-primary">
                            #{{ attItem.image.id }} {{ form[index].title_big }}
                        </h4>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary btn-sm" data-card-widget="collapse"
                                    title="Collapse">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group d-none">
                                        <label><?= lang('Theme.slider_title_small') ?></label>
                                        <input class="form-control" v-model="form[index].title_small" name="title_small" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label><?= lang('Theme.slider_title_big') ?></label>
                                        <input class="form-control" v-model="form[index].title_big" name="title_big" type="text">
                                    </div>
                                    <div class="form-group d-none">
                                        <label><?= lang('Theme.slider_detail') ?></label>
                                        <input class="form-control" v-model="form[index].slider_detail" name="slider_detail" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label><?= lang('Theme.slider_url') ?></label>
                                        <input class="form-control" v-model="form[index].slider_url" name="slider_url" type="text">
                                    </div>
                                    <div class="form-group d-none">
                                        <label><?= lang('Theme.slider_button') ?></label>
                                        <input class="form-control" v-model="form[index].slider_button" name="slider_button" type="text">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <img v-bind:src="vImgSource(attItem.image.thumb_image)" class="img-fluid">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer gap-">
                            <button type="submit" class="btn btn-success btn-sm m-1">Lưu</button>
                            <button type="button" @click="sliderDelete(index)" class="btn btn-danger btn-sm m-1">Xóa</button>
                        </div>
            </div>
            </form>
        </div>
    </div>
</script>

<script src="<?= base_url($config->scriptsPath)?>/acp/vSliderSelector.js"></script>