<?php
echo $this->extend($config->viewLayout);
echo $this->section('content');
$frmUrl = (isset($action) && $action == 'deleted') ? route_to('list_user') . '?deleted=1' : route_to('list_user');
?>

<div class="row">
    <div class="col-12">
        <?= form_open($frmUrl) ?>
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <a class="<?= ($action == 'all') ? 'badge badge-primary' : '' ?>"
                        href="<?= route_to('list_user') ?>">All</a> |
                    <a class="<?= ($action == 'deleted') ? 'badge badge-primary' : '' ?>"
                        href="<?= base_url("{$config->adminSlug}/user?deleted=1") ?>">Deleted</a>
                </div>
                <div class="card-tools mt-2">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" placeholder="<?= lang('User.search') ?>" name="username"
                            value="<?= isset($search_title) ? $search_title : '' ?>">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit" name="search">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="mailbox-controls">
                    <a href="<?= route_to('add_user') ?>" class="btn btn-normal btn-primary btn-sm"
                        title="Add New User"><i class="fa fa-plus text"></i> <?= lang('Acp.add_new') ?></a>

                    <!--                        <div class="btn-group">-->
                    <!--                            <button type="button" class="btn btn-default btn-sm"><i class="far fa-trash-alt"></i></button>-->
                    <!--                            <button type="button" class="btn btn-default btn-sm"><i class="fas fa-reply"></i></button>-->
                    <!--                            <button type="button" class="btn btn-default btn-sm"><i class="fas fa-share"></i></button>-->
                    <!--                        </div>-->
                    <!-- /.btn-group -->

                    <div class="float-right">
                        1-<?= count($data) ?>/<?= $total ?>
                        <!-- /.btn-group -->
                    </div>
                    <!-- /.float-right -->
                </div>

                <div class="row table-responsive">
                    <table id="<?php echo $module . "_" . $method ?>_DataTable"
                        class="table table-striped table-bordered dt-responsive nowrap">
                        <thead>
                            <tr>
                                <th width="5"><?= lang('ID') ?></th>
                                <th><?= lang('User.username') ?></th>
                                <th><?= lang('User.avata') ?></th>
                                <th><?= lang('User.full_name') ?></th>
                                <th><?= lang('User.user_group') ?></th>
                                <th><?= lang('User.created') ?></th>
                                <th><?= lang('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($data as $row) {  ?>
                            <tr>
                                <td><?= $row->id ?></td>
                                <td><a href="<?= base_url("acp/user/profile?user={$row->id}") ?>"><?= $row->username ?></a>
                                </td>
                                <td>
                                    <img src="<?= getUserAvatar($row) ?>" class="img-lg img-thumbnail">
                                </td>
                                <td><?= $row->meta['fullname'] ?? '' ?></td>
                                <td><?= (isset($row->groupData) && is_array($row->groupData)) ? $row->groupData['name'] : '' ?>
                                </td>
                                <td><?= $row->created_at->toLocalizedString('d/m/Y') ?></td>
                                <td>
                                    <a class="btn btn-primary btn-sm mb-2" href="<?= route_to("edit_user", $row->id) ?>"><i
                                            class="fas fa-edit"></i></a>
                                    <?php if ($login_user->inGroup('superadmin', 'admin') && $row->isNotActivated()) : ?>
                                    <a class="btn btn-success btn-sm mb-2" title="Activate User"
                                        href="<?= route_to("active_user", $row->id) ?>"><i class="fa fa-lock-open"></i></a>
                                    <?php endif; ?>
                                    <?php if ($action == 'all') : ?>
                                    <a class="btn btn-danger btn-sm mb-2 acp_item_del"
                                        href="<?= route_to("remove_user", $row->id) ?>"><i class="fas fa-trash"></i></a>
                                    <?php elseif ($action == 'deleted' && $login_user->root_user) : ?>
                                    <a class="btn btn-success btn-sm mb-2"
                                        href="<?= route_to("recover_user", $row->id) ?>"><i class="fa fa-redo"></i></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php } ?>

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