<?php
$selectCatArray = json_decode($itemData['value']) ?? [];
?>
<div class="row">
    <div class="col-12">
        <div class="form-group">
            <select multiple="multiple" class="form-control select2" style="width: 100%;" name="<?=$itemName?>[]" tabindex="-1">
                <?php if (isset($itemData['data']) && !empty($itemData['data']) ) : ?>
                    <?php foreach ($itemData['data'] as $key => $val) : ?>
                        <option value="<?=$key?>" data-select2-id="<?=$key?>" <?=in_array($key, $selectCatArray) ? 'selected' : ''?> ><?=$val?></option>
                    <?php endforeach; ?>
                <?php else:?>
                    <option value=""><?=lang('Acp.no_data')?></option>
                <?php endif; ?>
            </select>
        </div>
    </div>
</div>

<?php echo $this->section('pageScripts') ?>
<script type="text/javascript">
    $(function() {
        //Initialize Select2 Elements
        $('.select2').select2()
    });
</script>
<?= $this->endSection() ?>