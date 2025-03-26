<?php

/**
 * @author tmtuan
 */

echo $this->extend($config->viewLayout);
echo $this->section('content')
?>

<div class="row">
    <div class="col-12" id="lstPermissions">
        <div class="card">
            <div class="card-header">
                <div class="card-title row">
                    <div class="mailbox-controls">
                        <a v-on:click="onCreate" class="btn btn-normal btn-primary btn-sm"><i class="fa fa-plus text"></i>&nbsp;<?= lang('Permissions.add') ?></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div v-if="loading == false && permissions.length > 0">
                    <table id="tblPermission" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th><?= lang('Permissions.id') ?></th>
                                <th><?= lang('Permissions.description') ?></th>
                                <th><?= lang('Permissions.module') ?></th>
                                <th><?= lang('Permissions.specify_url') ?></th>
                                <th><?= lang('Permissions.action') ?></th>
                            </tr>
                        </thead>
                        <tbody v-if="permissions.length">
                            <tr v-for="per in permissions">
                                <td><a>{{ per.id }}</a></td>
                                <td>{{ per.description }}</td>
                                <td>{{ per.group }}</td>
                                <td>
                                    <code>{{ per.group + '/' + per.name + '/' + per.action }}</code>
                                </td>
                                <td>
                                    <a class="btn btn-primary btn-sm mb-2" v-on:click="onEdit(per)" :key="per.id"><i class="fas fa-edit"></i></a>&nbsp;
                                    <a class="btn btn-warning btn-sm mb-2" v-on:click="onCopy(per)" :key="per.id">
                                        <i class="fa fa-copy"></i>
                                    </a>&nbsp;
                                    <a class="btn btn-danger btn-sm mb-2" data-token="<?= csrf_hash() ?>" data-token-key="<?= csrf_token() ?>" v-on:click="onDelete(per, '<?= csrf_hash() ?>', '<?= csrf_token() ?>')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td><?= lang('Permissions.no_item') ?></td>
                            </tr>
                        </tbody>

                    </table>
                </div>
                <div v-else-if="loading == true">
                    <div><img class="image img-size-64" src="<?= base_url($config->templatePath) ?>/assets/img/loading.svg"></div>
                </div>
                <div v-else-if="loading == false && permissions.length <= 0">
                    <?= lang('Permissions.no_item'); ?>
                </div>
            </div>
        </div>
        <div v-if="loading == false">
            <vpers-modal v-show="isActivePers" @close-pers-modal="onHidePers" :header="pers_title" :refs_pers_item="current_pers_item" :group_main_info="selected_per_groups.admin" :group_data_options="selected_per_groups.admin.objects" :groupkey="selected_group_key" @add-success-more-per="onUpdatePageMore" :pers_item_action="action">
            </vpers-modal>
        </div>

    </div>
</div>

<?php
echo view($config->view . '\system\permission\_modal_pers');
?>
<?= $this->endSection() ?>
<?=$this->section('pageStyles'); ?>
<!---DataTable -->
<link rel="stylesheet" href="<?= base_url($config->scriptsPath)?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url($config->scriptsPath)?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<?= $this->endSection() ?>

<?php echo $this->section('pageScripts') ?>
<!-- DataTable -->
<script src="<?= base_url($config->scriptsPath) ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url($config->scriptsPath) ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url($config->scriptsPath) ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= base_url($config->scriptsPath) ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>


<!-- Import Users JS -->
<script src="<?= base_url($config->scriptsPath) ?>/acp/permission.js"></script>

<?= $this->endSection() ?>