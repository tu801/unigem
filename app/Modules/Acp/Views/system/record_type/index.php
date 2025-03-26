<?php
/**
 * @author tmtuan
 * created Date: 9/14/2021
 * project: woh-tuyendung
 */

echo $this->extend($config->viewLayout);
echo $this->section('content')
?>

<div class="row">
    <div class="col-12" id="lstRecordType">
        <div class="card">
            <div class="card-body">
                <?php if ( $login_user->is_root ) : ?>
                <div class="mailbox-controls">
                    <a v-on:click="onCreate" class="btn btn-normal btn-primary btn-sm"><i class="fa fa-plus text"></i>&nbsp;<?= lang('Acp.add') ?></a>
                </div>
                <?php endif; ?>

                <div v-if="loading == false">
                    <table id="tblRecords" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th><?= lang('Acp.record_type_name') ?></th>
                                <th><?= lang('Acp.developer_name') ?></th>
                                <th><?= lang('Acp.object_type') ?></th>
                                <th><?= lang('Acp.actions') ?></th>
                            </tr>
                        </thead>
                        <tbody v-if="recordTypes.length">
                            <tr v-for="item in recordTypes">
                                <td><a>{{ item.id }}</a></td>
                                <td>{{ item.name }}</td>
                                <td>{{ item.developer_name }}</td>
                                <td>
                                    <code>{{ item.object_type }}</code>
                                </td>
                                <td>
                                    <a class="btn btn-primary btn-sm mb-2" v-on:click="onEdit(item)" :key="item.id"><i class="fas fa-edit"></i></a>&nbsp;
                                    <a class="btn btn-warning btn-sm mb-2" v-on:click="onClone(item)" :key="item.id">
                                        <i class="fa fa-copy"></i>
                                    </a>&nbsp;
                                    <a class="btn btn-danger btn-sm mb-2" v-on:click="onDelete(item, '<?= csrf_hash() ?>', '<?= csrf_token() ?>')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="5">
                                    Chưa có Record Type! Vui lòng thêm Record Type mới.
                                </td>
                            </tr>
                        </tbody>

                    </table>
                </div>
                <div v-else>
                    <div><img class="image img-size-64" src="<?= base_url($config->templatePath) ?>/assets/img/loading.svg"></div>
                </div>
            </div>
        </div>

        <vrecord-modal  :header="records_modal_title" :refs_record_item="current_item" @close-record-modal="onCloseModal" @add-success="onAddRecordSuccess"
        :modal_action="action" ></vrecord-modal>

    </div>
</div>

<?php
echo view($config->view . '/system/record_type/_modal_record');
?>

<?php echo $this->section('pageStyles'); ?>
<!---DataTable -->
<link rel="stylesheet" href="<?= base_url($config->scriptsPath)?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url($config->scriptsPath)?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<?= $this->endSection() ?>

<?= $this->endSection() ?>
<?php echo $this->section('pageScripts') ?>
<!-- DataTable -->
<script src="<?=base_url("{$config->scriptsPath}/plugins/datatables/jquery.dataTables.min.js")?>"></script>
<script src="<?=base_url("{$config->scriptsPath}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js")?>"></script>
<script src="<?=base_url("{$config->scriptsPath}/plugins/datatables-responsive/js/dataTables.responsive.min.js")?>"></script>
<script src="<?=base_url("{$config->scriptsPath}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js")?>"></script>

<!-- Import Users JS -->
<script src="<?= base_url($config->scriptsPath) ?>/acp/recordType.js"></script>

<?= $this->endSection() ?>