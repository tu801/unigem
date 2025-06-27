<?php

/**
 * Created by: brianha289
 */

?>
<script type="text/x-template" id="vsys-act-tab-tmpl">
    <div class="card">
        <!-- header -->
        <div class="card-header">
            <div class="card-title">
                <select name="sltModule" class="select-module form-control" v-model="optSelected"
                        selected-item="<?= old('sltModule') ? old('sltModule') : '' ?>" @change="onSysModuleChanged($event)">
                    <option value="">-- Chọn Mô-đun --</option>
                </select>
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
                <table id="tblSysAct" class="table table-striped table-bordered" >
                    <thead>
                    <tr>
                        <th width="5%"><?= lang('Log.id') ?></th>
                        <th width="20%"><?= lang('Log.modified_title') ?></th>
                        <th width="30%"><?= lang('Log.subject_type') ?></th>
                        <th width="8%"><?= lang('Log.module') ?></th>
                        <th width="25%"><?= lang('Log.description') ?></th>
                        <th width="15%"><?= lang('Log.modified_date') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="sys in sys_act_data">
                        <td>{{ sys.id }}</td>
                        <td><a :href="'/acp/log/detail/' + sys.id">{{ sys.title }}</a></td>
                        <td>{{ sys.subject_type }}</td>
                        <td>{{ sys.module }}</td>
                        <td>{{ sys.description }}</td>
                        <td>{{ sys.created_at }}</td>
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