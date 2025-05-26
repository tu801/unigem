<?php
echo $this->extend($config->viewLayout);
echo $this->section('content');
// print_r($itemData->categories);exit;
$postConfigs = $config->cmsStatus;
?>
<!-- form start -->
<form id="<?= $controller ?>Form" role="form" method="post" class="form-horizontal" enctype="multipart/form-data">
    <div class="row" id="shopApp" data-cattype="page">
        <?= csrf_field() ?>
        <div class="col-md-9">
            <div class="card card-outline card-primary">
                <div class="card-body pad">
                    <div class="form-group">
                        <label for="postInputTitle"><?= lang('Shop.name') ?> <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control <?= session('errors.name') ? 'is-invalid' : '' ?>"
                               id="inputName" placeholder="<?= lang('Shop.name') ?>" value="<?= htmlspecialchars($itemData->name) ?>">
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="postInputTitle"><?= lang('Shop.phone') ?> <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control <?= session('errors.phone') ? 'is-invalid' : '' ?>" id="postInputTitle"
                                       placeholder="<?= lang('Shop.phone') ?>" value="<?= $itemData->phone ?>">
                            </div>

                            <div class="form-group ">
                                <label><?= lang('Acp.district') ?> <span class="text-danger">*</span></label>
                                <select name="district_id" area-selected="<?= $itemData->district_id ?>" class="form-control select_district" style="width: 100%;"></select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group ">
                                <label><?= lang('Acp.province') ?> <span class="text-danger">*</span> </label>
                                <select name="province_id" area-selected="<?= $itemData->province_id ?>" class="form-control select_province" style="width: 100%;"></select>
                            </div>

                            <div class="form-group">
                                <label for="postInputTitle"><?= lang('Acp.ward') ?> <span class="text-danger">*</span></label>
                                <select name="ward_id" area-selected="<?= $itemData->ward_id ?>" class="form-control select_ward" style="width: 100%;"></select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="postInputTitle"><?= lang('Shop.address') ?> <span class="text-danger">*</span></label>
                        <textarea class="form-control <?= session('errors.address') ? 'is-invalid' : '' ?>" name="address"><?= htmlspecialchars($itemData->address) ?></textarea>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="col-sm-12 col-sm-offset-2">
                        <button class="btn btn-primary mr-2" name="save" id="postSave" type="submit"><?= lang('Acp.save') ?> (F2)</button>
                        <button class="btn btn-primary mr-2" name="save_exit" id="postSaveExit" type="submit"><?= lang('Acp.save_exit') ?> (F7)</button>
                        <button class="btn btn-primary" name="save_addnew" type="submit"><?= lang('Acp.save_addnew') ?></button>
                        <a href="<?= route_to('list_shop') ?>" class="btn btn-default" type="reset"><?= lang('Acp.cancel') ?></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">

            <div class="card card-outline card-primary">
                <div class="card-body">
                    <?php if ($multiLang) : ?>
                    <div class="form-group ">
                        <label><?= lang('Acp.lang') ?>: <span class="badge badge-info "> <?= $currentLang->name  ?></span> </label>
                    </div>
                    <?php endif; ?>

                    <div class="form-group ">
                        <label><?= lang('Post.post_status') ?></label>
                        <select class="form-control" name="status">
                            <?php
                            foreach (\App\Enums\Store\ShopEnum::STATUS as $key => $val) :
                                $sel = ($val == $itemData->status) ? 'selected' : '';
                            ?>
                                <option <?= $sel ?> value='<?= $val ?>'><?= lang('Shop.status_'.$key) ?></option>
                            <?php endforeach;    ?>
                        </select>
                    </div>

                </div>
            </div>

            <div class="card card-outline card-primary">
                <div class="card-body">
                    <feature-img demo="<?= (!empty($itemData->images)) ? $itemData->images['thumbnail'] : '' ?>" img-desc="Hình đại diện phải có kích thước lớn hơn hoặc bằng 925 x 520pixel" img-title="<?= lang('Post.image') ?>"></feature-img>
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
    <script src="<?= base_url($config->scriptsPath)?>/acp/areaLocation.js"></script>
    <script>
        const app = Vue.createApp({
            components: {
                'feature-img': featureImg,
            }
        });
        app.mount('#shopApp');
    </script>
<?= $this->endSection() ?>