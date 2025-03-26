<?php

/**
 * @author brianhas289
 */

echo $this->extend($config->viewLayout);
echo $this->section('content')
?>

<div class="row" id="details">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <tbody>
                            <tr>
                                <th><?= lang('Permissions.id'); ?></th>
                                <td>
                                    <?= $sysAct->id; ?>
                                </td>
                            </tr>
                            <tr>
                                <th><?= lang('Log.modified_title'); ?></th>
                                <td><?= $sysAct->title; ?></td>
                            </tr>
                            <tr>
                                <th><?= lang('Log.subject_type'); ?></th>
                                <td><?= $sysAct->subject_type; ?></td>
                            </tr>
                            <tr>
                                <th><?= lang('Permissions.module'); ?></th>
                                <td><?= $sysAct->module; ?></td>
                            </tr>
                            <tr>
                                <th><?= lang('Permissions.description'); ?></th>
                                <td><?= $sysAct->description; ?></td>
                            </tr>
                            <tr>
                                <th><?= lang('Log.properties'); ?></th>
                                <td style="height: auto">
                                    <?php
                                    $array = json_decode($sysAct->properties, true);
                                    echo '<pre>';
                                    print_r($array);
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th width="35%"><?= lang('Log.modified_date'); ?></th>
                                <td><?= $sysAct->created_at; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('pageStyles'); ?>
<?= $this->endSection() ?>

<?php echo $this->section('pageScripts') ?>
<!-- Import Logs JS -->
<script src="<?= base_url($config->scriptsPath) ?>/acp/sys/vLog.js"></script>
<script>
</script>
<?= $this->endSection() ?>