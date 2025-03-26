<?php

/**
 * Created by: brianha289
 */

?>
<script type="text/x-template" id="vsys-login-tab-tmpl">
    <div class="card">
        <!-- header -->
        <div class="card-header">
            <div class="card-title">
                <!--<select name="sltModule" class="select-module form-control" selected-item="<?= old('sltModule') ? old('sltModule') : '' ?>" @change="onSysModuleChanged($event)">
                    <option value="">-- Chọn Mô-đun --</option>
                </select>-->
            </div>

            <div class="card-tools">
                <div class="input-group input-group-sm">
                    <input type="text" name="title" class="form-control" placeholder="Tìm" @change="onKeywordChanged($event)">
                    <div class="input-group-append">
                        <button name="search" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- body -->
        <div class="card-body p-0">
            <div v-if="sys_act_data.length > 0" class="table-responsive">
                <table id="tblSysAct" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th width="10%"><?= lang('User ID') ?></th>
                        <th width="20%"><?= lang('Email') ?></th>
                        <th width="20%"><?= lang('Log.device_login') ?></th>
                        <th width="20%"><?= lang('Log.subject_type') ?></th>
                        <th width="8%"><?= lang('Log.ip_address') ?></th>
                        <th width="10%"><?= lang('Log.status') ?></th>
                        <th width="15%"><?= lang('Log.login_date') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="sys in sys_act_data">
                        <td>{{ sys.user_id }}</td>
                        <td>{{ sys.email }}</td>
                        <td>{{ sys.user_agent }}</td>
                        <td>{{ sys.user_type }}</td>
                        <td>{{ sys.ip_address }}</td>
                        <td>{{ sys.success == 1 ? 'Thành công': 'Thất bại' }}</td>
                        <td>{{ sys.date }}</td>
                    </tr>
                    </tbody>
                </table>

            </div>
            <div v-else-if="sys_act_data.length <= 0" class="m-3">
                <?= lang('Log.no_item'); ?>
            </div>
        </div>
        <div class="card-footer">
            <ul class="pagination pagination-sm float-right" >
                <li v-for="index in pages" class="page-item " :class="index === cur_page ? 'active':''">
                    <a class="page-link" href="#" @click.prevent="targetPage(index)" >{{ index }}</a>
                </li>
            </ul>
        </div>

    </div>
</script>