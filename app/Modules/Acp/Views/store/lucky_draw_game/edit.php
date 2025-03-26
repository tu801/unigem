<?php
echo $this->extend($config->viewLayout);
echo $this->section('content');

$postConfigs = $config->cmsStatus;
?>
<!-- form start -->
<form id="<?= $controller ?>Form" role="form" method="post" class="form-horizontal" enctype="multipart/form-data">
    <div class="row" id="rewardConfigApp">
        <?= csrf_field() ?>
        <div class="col-md-9">
            <div class="card card-outline card-primary">
                <div class="card-body pad">
                    <div class="form-group">
                        <label for="postInputTitle"><?= lang('Promo.minigame_promo_name') ?> <span class="text-danger">*</span></label>
                        <input type="text" name="promo_name" class="form-control <?= session('errors.name') ? 'is-invalid' : '' ?>"
                               id="inputName" placeholder="<?= lang('Promo.minigame_promo_name') ?>" value="<?= htmlspecialchars($itemData->promo_name) ?>">
                    </div>

                    <div class="form-group">
                        <label for="postInputTitle"><?= lang('Promo.minigame_promo_desc') ?> <span class="text-danger">*</span></label>
                        <input type="text" name="promo_description" class="form-control <?= session('errors.phone') ? 'is-invalid' : '' ?>" id="postInputTitle"
                               placeholder="<?= lang('Promo.minigame_promo_desc') ?>" value="<?= $itemData->promo_description ?>">
                    </div>

                    <div class="form-group">
                        <label for="postInputTitle"><?= lang('Promo.discount_type') ?> <span class="text-danger">*</span></label>
                        <select class="form-control" id="tmtDiscountType" select-value="<?=$itemData->discount_type?>" name="discount_type" v-model="discountType" @change="changeType">
                            <?php
                            $selectHtml = '';
                            foreach (\Modules\Acp\Enums\Store\Promotion\PromotionEnum::getListDiscountType() as $key => $title) {
                                $selectHtml .= '<option value="'.$key.'" >'.$title.'</option>';
                            }
                            echo $selectHtml;
                            ?>
                        </select>
                        <div class="input-group mt-2" v-if="showValue" >
                            <input class="form-control <?= session('errors.default_shipping_fee') ? 'is-invalid' : '' ?>" id="default_shipping_fee"
                                   name="discount_value" value="<?=$itemData->discount_value ?? 0?>" >
                            <div class="input-group-append">
                                <span class="input-group-text">đ</span>
                            </div>
                        </div>
                        <div class="input-group mt-2" v-if="showPercent">
                            <input class="form-control <?= session('errors.default_shipping_fee') ? 'is-invalid' : '' ?>" id="default_shipping_fee"
                                   name="discount_value" value="<?=$itemData->discount_value ?? 0?>" >
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-footer">
                    <div class="col-sm-12 col-sm-offset-2">
                        <button class="btn btn-primary mr-2" name="save" id="postSave" type="submit"><?= lang('Acp.save') ?> (F2)</button>
                        <button class="btn btn-primary mr-2" name="save_exit" id="postSaveExit" type="submit"><?= lang('Acp.save_exit') ?> (F7)</button>
                        <a href="<?= route_to('lucky_draw') ?>" class="btn btn-default" type="reset"><?= lang('Acp.cancel') ?></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-outline card-primary">
                <div class="card-body">
                    <?php if ($multiLang) : ?>
                    <div class="form-group ">
                        <label><?= lang('Acp.lang') ?>: <span class="badge badge-info "> <?= $curLang->name  ?></span> </label>
                    </div>
                    <?php endif; ?>

                    <div class="form-group ">
                        <label><?= lang('Post.post_status') ?></label>
                        <select class="form-control" name="promo_status">
                            <?php
                            foreach (\Modules\Acp\Enums\Store\Promotion\PromotionEnum::STATUS as $key => $val) :
                                $sel = ($val == $itemData->promo_status) ? 'selected' : '';
                            ?>
                                <option <?= $sel ?> value='<?= $val ?>'><?= lang('Promo.status_'.$val) ?></option>
                            <?php endforeach;    ?>
                        </select>
                    </div>

                </div>
            </div>

            <div class="card card-outline card-primary" v-if="showImage">
                <div class="card-body">
                    <feature-img img-desc="<?= lang('Promo.discount_image_desc') ?>" img-title="<?= lang('Promo.discount_image') ?>"
                                 demo="<?= (!empty($itemData->image)) ? $itemData->image : '' ?>" ></feature-img>
                </div>
            </div>
        </div>
    </div>
</form>
<?php
echo view($config->view . '\components\_vFeatureImg');
?>
<?= $this->endSection() ?>

<?php echo $this->section('pageScripts') ?>
<script>
    const app = Vue.createApp({
        components: {
            'feature-img': featureImg,
        },
        data() {
            return {
                showValue: false,
                showPercent: false,
                showImage: false,
                discountType: null
            }
        },
        mounted() {
            let selected = $('#tmtDiscountType').attr('select-value');
            if ( selected !== undefined || selected !== '' ) {
                this.discountType = selected;
                this.changeType();
            }
        },
        methods: {
            changeType() {
                switch (this.discountType) {
                    case 'percent':
                        this.showPercent = true;
                        this.showValue = false;
                        this.showImage = false;
                        break;
                    case 'value':
                        this.showPercent = false;
                        this.showValue = true;
                        this.showImage = false;
                        break;
                    case 'free_gift':
                        this.showPercent = false;
                        this.showValue = false;
                        this.showImage = true;
                        break;
                }
            }
        }
    });
    app.mount('#rewardConfigApp');
</script>
<?= $this->endSection() ?>