<?php

/**
 * @author brianha289
 */

echo $this->extend($config->viewLayout);
echo $this->section('content')
?>

<div class="row" id="lstSysLog">
    <div class="col-12">
        <div class="card card-primary card-outline card-tabs">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs"  role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#tabs-sysact" role="tab" aria-controls="tabs-sysact" aria-selected="true"
                           @click.prevent="setActive('sysact')" :class="{ active: isActive('sysact') }" ><?= lang('Hệ thống'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"  data-toggle="pill" href="#tabs-syslogin" role="tab" aria-controls="tabs-syslogin" aria-selected="false"
                           @click.prevent="setActive('syslogin')" :class="{ active: isActive('syslogin') }" ><?= lang('Đăng nhập'); ?></a>
                    </li>

                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" >
                    <div class="tab-pane fade" id="tabs-sysact" role="tabpanel" aria-labelledby="tabs-sysact" :class="{ 'active show': isActive('sysact') }" >
                        <vsys-act-tab @em-target-page="targetNextPage" @em-sys-module="selectSysModule" @em-sys-keyword="changeSysKeyword" :sys_act_data="lstSysAct"
                                      :cur_page="curPageOfSysAct" :pages="pagesOfSysAct" :sltModule="selSysActModule" />
                    </div>
                    <div class="tab-pane fade" id="tabs-syslogin" role="tabpanel" aria-labelledby="tabs-syslogin" :class="{ 'active show': isActive('syslogin') }" >
                        <vsys-login-tab @em-target-page="targetNextPage" @em-sys-module="selectSysModule" @em-sys-keyword="changeSysKeyword" :sys_act_data="lstSysAct" :cur_page="curPageOfSysAct" :pages="pagesOfSysAct" />
                    </div>

                </div>
            </div>
            <!-- /.card -->
        </div>

    </div>
</div>

<?php
echo view($config->view . '\system\log\components\_sys_act_tab');
echo view($config->view . '\system\log\components\_sys_login_tab');
?>
<?= $this->endSection() ?>

<?php echo $this->section('pageScripts') ?>

<script src="<?= base_url($config->scriptsPath)?>/vuejs-plugins/multiselect/multiselect.global.js"></script>
<!-- Import Logs JS -->
<script src="<?= base_url($config->scriptsPath) ?>/acp/sys/vLog.js"></script>
<script>
</script>
<?= $this->endSection() ?>