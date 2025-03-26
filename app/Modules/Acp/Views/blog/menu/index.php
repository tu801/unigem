<?php
/**
 * @author tmtuan
 * created Date: 10/11/2021
 * project: basic_cms
 */

echo $this->extend($config->viewLayout);
echo $this->section('content');
?>

<div class="row">
    <div class="col-12">

        <div class="card" id="menuCard">
            <div class="card-header">
                <div class="card-title">
                    <button type="button" class="btn btn-primary btn-sm" @click.prevent="showAddMenu" >
                        <?=lang('Acp.add_new')?>
                    </button>
                    <vadd-menu  @close="showModal = false" token="<?= csrf_hash() ?>" tkname="<?= csrf_token() ?>" @add-done="addSuccess" @close-modal="hideAddMenu"></vadd-menu>
                </div>
                <div class="card-tools">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" placeholder="<?=lang('User.search')?>" v-model="searchKey" v-on:keyup="searchItem">
                        <div class="input-group-append">
                            <button class="btn btn-primary" @click.prevent="searchItem" >
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div v-if="loading" class="spinner-border text-danger" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <table v-else id="<?php echo $module."_".$method ?>_DataTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                    <thead>
                    <tr>
                        <th width="5%"><?=lang('ID')?></th>
                        <th width="45%"><?=lang('Menu.menu_name')?></th>
                        <th width="20%"><?=lang('Menu.menu_created')?></th>
                        <th width="20%"><?=lang('Menu.menu_status')?></th>
                        <th width="10%"><?=lang('Actions')?></th>
                    </tr>
                    </thead>
                    <tbody v-if="menuList.length">
                        <tr v-for="menu in menuList" :key="menu.id">
                            <td>#{{ menu.id }}</td>
                            <td v-html="rdTitle(menu)"></td>
                            <td>{{ menu.created }}</td>
                            <td v-html="rdStatus(menu)"></td>
                            <td>
                                <input type="hidden" id="token" value="<?=csrf_hash()?>" >
                                <input type="hidden" id="tk_key" value="<?=csrf_token()?>" >
                                <a class="btn btn-primary btn-sm mb-2 mr-2" :href="rdEditUrl(menu)"><i class="fas fa-edit"></i></a>
                                <a class="btn btn-danger btn-sm mb-2 " @click.prevent="deleteMenu(menu)" title="Move to Trash" href="#"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td colspan="5"><?=lang('Menu.no_menu_data')?></td>
                        </tr>
                    </tbody>

                </table>

            </div>

        </div>

        <?= view($config->view . '\blog\menu\components\_addMenuForm') ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
    <script src="<?= base_url($config->scriptsPath) ?>/acp/blog/vMenuIndex.js"></script>
    <script>
        $(document).ready(function() {
            menuApp.mount('#menuCard');
        });
    </script>
<?= $this->endSection() ?>