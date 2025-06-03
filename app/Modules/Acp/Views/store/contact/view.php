<?php

use App\Enums\ContactEnum;

echo $this->extend($config->viewLayout);
echo $this->section('content');
?>
<form id="<?= $controller ?>Form" role="form" method="post" class="form-horizontal" enctype="multipart/form-data">
    <div class="row" >
        <?= csrf_field() ?>
        <div class="col-md-9">
            <div class="card card-outline card-primary">
                <div class="card-body pad">
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label"><?=lang('Contact.contact_subject')?></label>
                        <div class="col-sm-10">
                          <p><?= lang('Contact.subject_'.$itemData->subject ) ?></p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label"><?=lang('Contact.contact_name')?></label>
                        <div class="col-sm-10">
                          <p><?= $itemData->fullname  ?></p>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label"><?=lang('Contact.contact_email')?></label>
                        <div class="col-sm-10">
                          <p><?= $itemData->email  ?></p>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label"><?=lang('Contact.contact_message')?></label>
                        <div class="col-sm-10">
                        <?= $itemData->message  ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-outline card-primary">
                <div class="card-body">
                    <div class="form-group">
                        <label for="inputProductStatus"><?= lang('Acp.status') ?> <span
                                class="text-danger">*</span></label>
                        <select class="custom-select form-control" name="status">
                            <?php foreach (ContactEnum::getContactStatusList() as $item): ?>
                            <option value="<?= $item ?>" <?= $itemData->status == $item ? 'selected' : ''  ?>>
                                <?= lang("Contact.contact_status_{$item}") ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary float-right"><?= lang('Acp.save')?></button>
                </div>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>