<?php
echo $this->extend($config->viewLayout);
echo $this->section('content');
$cfGroups = $config->cfGroup; //print_r($configs);exit;
?>
<div class="row">
    <div class="col-12">
        <?=form_open(route_to('config'))?>
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <?php
                        $total = count($cfGroups);
                        $i = 0;
                        foreach ($cfGroups as $key=>$val) :
                            $i++;
                            $title = $val['title'];
                            $url = ( $val['type'] === 'default' ) ? base_url("acp/config?group={$key}") : base_url("acp/config/custom/{$val['type']}");
                        ?>
                        <a class="badge badge-primary" href="<?=$url?>"><?=$title?></a>
                            <?php if ( $i < $total )  echo " | " ?>
                        <?php endforeach; ?>
                    </div>

                    <div class="card-tools">
                        <div class="input-group input-group-sm">
                            <input type="text" value="<?=(isset($search_title))?$search_title:''?>" name="title" class="form-control" placeholder="Search Config">
                            <div class="input-group-append">
                                <button name="search" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="mailbox-controls">
                        <div class="btn-group">
                            <a href="<?=route_to('add_config', $dfGroup) ?>" class="btn btn-normal btn-primary btn-sm" title="Add New Config" >
                                <i class="fa fa-plus text"></i> <?=lang('Acp.cf_add')?>
                            </a>
                            <?php if ($login_user->inGroup('superadmin')) : ?>
                            <a href="<?=route_to('clear_cache') ?>" class="btn btn-normal btn-danger btn-sm ml-2" title="Clear Cache" >
                                <i class="fas fa-database"></i> <?=lang('Acp.clear_cache')?>
                            </a>

                            <!-- <a href="<?=route_to('setup-language') ?>" class="btn btn-normal btn-warning btn-sm ml-2" title="Clear Cache" >
                                <i class="fas fa-language"></i> <?=lang('Acp.setup_lang')?>
                            </a> -->
                            <?php endif; ?>
                        </div>
                        <!-- /.btn-group -->
                        <div class="float-right row mb-2">
                            <div class="col-sm-12">

                            </div>
                        </div>
                        <!-- /.float-right -->
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                        <tr>
                            <th><?=lang('Acp.cf_title')?></th>
                            <th><?=lang('Acp.cf_key')?></th>
                            <th><?=lang('Acp.cf_value')?></th>
                            <th><?=lang('Actions')?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ( count($data) ) :
                        foreach ($data as $row) :
                        ?>
                            <tr>
                                <td><?=$row->title?></td>
                                <td><?=$row->key?></td>
                                <td><?=$row->value?></td>
                                <td>
                                    <a class="btn btn-primary btn-xs mb-2" 
                                        href="<?=route_to("edit_config", $row->id)?>">
                                            <i class="fas fa-edit"></i></a>
                                    <a class="btn btn-warning btn-xs mb-2"
                                       href="<?=route_to("clone_config", $row->id)?>">
                                        <i class="fas fa-copy"></i></a>
                                    <?php if ( $login_user->inGroup('superadmin') ) : ?>
                                    <a class="btn btn-danger btn-xs mb-2 acp_item_del"
                                        href="<?=route_to("delete_config", $row->id)?>" >
                                            <i class="fas fa-trash"></i></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; else :?>
                            <tr>
                                <td colspan="4"><?=lang('Acp.no_item_found')?></td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
<?php echo $this->section('pageScripts') ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#setLang').on('change', function () {
                window.location = bkApiUrl + '/config?lang=' + $('#setLang').val();
            });
        });
    </script>
<?= $this->endSection() ?>