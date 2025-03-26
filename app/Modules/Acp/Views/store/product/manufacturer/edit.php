<?php

use Modules\Acp\Enums\Store\Product\ProductStatusEnum;

echo $this->extend($config->viewLayout);
echo $this->section('content');
?>
    <form id="<?= $controller ?>Form" role="form" method="post" class="form-horizontal" enctype="multipart/form-data">
        <div class="row">
            <?= csrf_field() ?>
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-body pad">

                        <div class="form-group">
                            <label><?= lang('ProductManufacturer.name') ?> <span class="text-danger">*</span></label>
                            <input type="text" name="manufacture_name" class="form-control <?= session('errors.manufacture_name') ? 'is-invalid' : '' ?>" placeholder="<?= lang('ProductManufacturer.name') ?>" value="<?= $itemData->manufacture_name ?? '' ?>">
                        </div>

                        <div class="form-group">
                            <label><?= lang('ProductManufacturer.description') ?> <span class="text-danger">*</span></label>
                            <textarea rows="3" name="description" class="form-control <?= session('errors.description') ? 'is-invalid' : '' ?>" placeholder="<?= lang('ProductManufacturer.description') ?>"><?= $itemData->description ?? '' ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="inputProductStatus"><?= lang('ProductManufacturer.status') ?> <span class="text-danger">*</span></label>
                            <select class="custom-select form-control" name="status">
                                <?php foreach (ProductStatusEnum::toArray() as $item): ?>
                                    <option value="<?= $item ?>" <?= $itemData->status == $item ? 'selected' : ''  ?> ><?= lang("ProductManufacturer.status_{$item}") ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card card-outline card-primary">
                    <div class="card-body">
                        <button class="btn btn-primary mr-2" id="postSave" name="save" type="submit"><?= lang('Acp.save') ?> (F2)</button>
                        <button class="btn btn-primary mr-2" id="postSaveExit" name="save_exit" type="submit"><?= lang('Acp.save_exit') ?> (F7)</button>
                        <button class="btn btn-primary" name="save_addnew" type="submit"><?= lang('Acp.save_addnew') ?></button>
                        <a href="<?= route_to('manufacture') ?>" class="btn btn-default" type="reset"><?= lang('Acp.cancel') ?></a>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?= $this->endSection() ?>