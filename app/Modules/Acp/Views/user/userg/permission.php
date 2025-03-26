<?php
/**
 * @author tmtuan
 * created Date: 25-Sep-20
 */

echo $this->extend($config->viewLayout); //echo '<pre>'; print_r($groupData);exit;
echo $this->section('content');

?>
<div class="row">
    <div class="col-sm-12">
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title"><?=$dataTitle??''?></h3>
        </div>


        <?=form_open(route_to('userg_permission', $groupData->id))?>
        <div class="card-body">
            <div class="row">
            <?php foreach ($persData as $key=>$items): ?>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th colspan="3" style="text-align: center">Module <?=$key?></th>
                        </tr>
                        <tr>
                            <th style="width: 10px">
                                <div class="icheck-primary">
                                    <input type="checkbox" class="chkPer-toggle"  id="perCheck<?=$key?>">
                                    <label for="perCheck<?=$key?>"></label>
                                </div>
                            </th>
                            <th>Request</th>
                            <th>Mô tả</th>
                        </tr>
                        <?php foreach ( $items as $per ) : ?>
                        <tr>
                            <td>
                                <!-- checkbox -->
                                <div class="icheck-primary permissions">
                                    <input class="" type="checkbox" <?=(in_array($per->id, $groupData->permissions))?'checked':''?> value="<?=$per->id?>" name="pers[]" id="perCheck<?=$per->id?>">
                                    <label for="perCheck<?=$per->id?>"></label>
                                </div>
                            </td>
                            <td>
                                <code><?="{$per->group}/{$per->name}/{$per->action}"?></code>
                            </td>
                            <td>
                                <?=$per->description?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endforeach; ?>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" id="postSave" class="btn btn-primary">Save (F2)</button>
        </div>
        </form>
    </div>
    </div>
</div>
<?= $this->endSection() ?>

<?php echo $this->section('pageScripts') ?>
<script>
    $(document).on('change', '.chkPer-toggle', function(e){
        e && e.preventDefault();
        var $table = $(e.target).closest('table'), $checked = $(e.target).is(':checked');
        $('tbody [type="checkbox"]',$table).prop('checked', $checked);
    });

</script>
<?= $this->endSection() ?>