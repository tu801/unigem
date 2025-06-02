<?php

use App\Enums\ContactEnum;

echo $this->extend($config->viewLayout);
echo $this->section('content')
?>

<div class="row">
    <div class="col-12">
        <form method="post" action="<?= route_to('list_contact') ?>">
            <?= csrf_field() ?>
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <a class="<?= ($listType == ContactEnum::FORM_CONTACT_TYPE) ? 'badge badge-primary text-light' : 'text-primary' ?>"
                            href="<?= base_url(route_to('list_contact') . '?listType=' . ContactEnum::FORM_CONTACT_TYPE) ?>"><?= lang('Contact.contact_list_type_form') ?></a>
                        |
                        <a class="<?= ($listType == ContactEnum::SUBSCRIBE_CONTACT_TYPE) ? 'badge badge-primary text-light' : 'text-primary' ?>"
                            href="<?= base_url(route_to('list_contact') . '?listType=' . ContactEnum::SUBSCRIBE_CONTACT_TYPE) ?>"><?= lang('Contact.contact_list_type_subscribe') ?></a>
                    </div>

                    <div class="card-tools mt-2">
                        <div class="input-group input-group-sm">
                            <input type="text" value="<?= (isset($search_title)) ? $search_title : '' ?>" name="email"
                                class="form-control" placeholder="<?= lang('Acp.search') ?>">
                            <div class="input-group-append">
                                <button type="submit" name="search" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row table-responsive">
                        <table id="subscribeDataTable" class="table table-bordered table-hover dataTable dtr-inline">
                            <thead>
                                <tr>
                                    <th width="3%">No</th>
                                    <th><?= lang('Contact.contact_email') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (count($data) > 0):
                                    $i = 0;
                                    foreach ($data as $row) {
                                        $i++;

                                ?>
                                        <tr>
                                            <td><?= $i ?></td>
                                            <td><?= $row->email ?></td>
                                        </tr>
                                    <?php }
                                else : ?>
                                    <tr>
                                        <td colspan="7">
                                            <?= lang('Acp.no_item_found') ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    <?php echo $pager->links('default', 'acp_full') ?>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('pageStyles') ?>
<!-- DataTables -->
<link rel="stylesheet"
    href="<?= base_url($config->scriptsPath) ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet"
    href="<?= base_url($config->scriptsPath) ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet"
    href="<?= base_url($config->scriptsPath) ?>/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<!-- DataTables  & Plugins -->
<script src="<?= base_url($config->scriptsPath) ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url($config->scriptsPath) ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url($config->scriptsPath) ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js">
</script>
<script src="<?= base_url($config->scriptsPath) ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js">
</script>
<script src="<?= base_url($config->scriptsPath) ?>/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url($config->scriptsPath) ?>/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?= base_url($config->scriptsPath) ?>/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?= base_url($config->scriptsPath) ?>/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?= base_url($config->scriptsPath) ?>/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<script>
    $(document).ready(function() {
        $('#subscribeDataTable').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>
<?= $this->endSection() ?>