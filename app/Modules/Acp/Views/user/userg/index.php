<?php
$usergModel = new \Modules\Acp\Models\User\UsergModel();
echo $this->extend($config->viewLayout);
echo $this->section('content') ?>

<div class="row">
	<div class="col-12">
		<div class="card card-info" id="usergApp">
            <?=form_open(route_to('list_userg'))?>
				<div class="card-header">
                    <a href="<?=route_to('list_user')?>" class="btn btn-success btn-sm mr-2" ><i class="fa fa-users"></i> Quản lý User</a>
                    <a href="#" @click.prevent="addGroup" class="btn btn-primary btn-sm" ><i class="fa fa-plus text"></i> Thêm nhóm</a>

				</div>

				<div class="card-body">

                    <table id="usergTbl" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>#<?=lang('ID')?></th>
                            <th><?=lang('User.userGroup_name')?></th>
                            <th><?=lang('User.created')?></th>
                            <th><?=lang('Actions')?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="group in userg_list">
                            <td>{{ group.id }}</td>
                            <td>{{ group.name }}</td>
                            <td>{{ created_view(group) }}</td>
                            <td>
                                <a class="btn btn-primary btn-sm mr-2" @click.prevent="editGroup(group)" href="#"><i class="fa fa-edit"></i></a>

                                <?php if ( $login_user->is_root || $login_user->can($config->adminSlug.'/usergroup/editPermission') ) : ?>
                                    <a class="btn btn-warning btn-sm mr-2"  :href="per_url(group)"><i class="fa fa-user-shield"></i></a>
                                <?php endif; ?>

                                <a class="btn btn-danger btn-sm mr-2" @click.prevent="delGroup(group)" href="#"><i class="fa fa-trash-alt"></i></a>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <!--vuejs add modal-->
                    <vaddgroup @close="showmodal = false" @add-success="addGroupSuccess" :header="mdheader" :groupeditdata="edit_data"
                               token="<?=csrf_hash()?>" tkname="<?=csrf_token()?>"></vaddgroup>
				</div>
			</form>
		</div>
	</div>
</div>


<?php
echo view('Modules\Acp\Views\user\userg\_vGroupModal');
?>
<?= $this->endSection() ?>

<?php echo $this->section('pageStyles') ?>

<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url($config->scriptsPath)?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url($config->scriptsPath)?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<?php echo $this->endSection() ?>

<?php echo $this->section('pageScripts') ?>

<!-- DataTables -->
<script src="<?= base_url($config->scriptsPath)?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url($config->scriptsPath)?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url($config->scriptsPath)?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url($config->scriptsPath)?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= base_url($config->scriptsPath)?>/plugins/moment/moment.min.js"></script>

<script src="<?= base_url($config->scriptsPath)?>/acp/user/userg.js"></script>

<?= $this->endSection() ?>
