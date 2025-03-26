<?php
echo $this->extend($config->viewLayout);
echo $this->section('content')
?>

<div class="row">
    <div class="col-12">
        <form method="post" action="<?=route_to('customer') ?>" >
            <?=csrf_field()?>
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <div class="btn-group">
                            <a href="<?=route_to('add_customer') ?>" class="btn btn-normal btn-primary btn-sm" title="<?=lang('Acp.add_new')?>" >
                                <i class="fa fa-plus text"></i> <?=lang('Acp.add_new')?>
                            </a>
                            <button class="btn btn-normal btn-danger btn-sm btnDelete" type="submit">
                                <i class="fa fa-trash-alt text"></i>&nbsp; <?=lang('Acp.delete')?>
                            </button>
                            <?php if ( $login_user->is_root ) : ?>
                                <a class="btn btn-normal btn-warning btn-sm ml-2" href="<?= route_to('generate_customer') ?>">
                                    <i class="fas fa-step-forward"></i> &nbsp; <?=lang('Acp.faker')?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card-tools">
                        <div class="input-group input-group-sm">
                            <input type="text" value="<?=(isset($search_title))?$search_title:''?>" 
                                name="keyword" class="form-control" placeholder="<?=lang('Acp.search')?>">
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

                    <table id="<?php echo $controller."_".$method ?>_DataTable" 
                    class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                        <tr>
                            <th width="3%">ID</th>
                            <th><?=lang('Customer.full_name')?></th>
                            <th><?=lang('Customer.customer_code')?></th>
                            <th><?=lang('Customer.phone')?></th>
                            <th><?=lang('Customer.email')?></th>
                            <th width="10%"><?=lang('Actions')?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ( count($data) > 0 ): foreach ($data as $row) { ?>
                            <tr>
                                <td><?=$row->id?></td>
                                <td>
                                    <a href="<?= route_to('customer_detail', $row->id) ?>"> <?=$row->cus_full_name ?? ''?></a>
                                </td>
                                <td><?= $row->cus_code ?? '' ?></td>
                                <td><?=$row->cus_phone ?? ''?></td>
                                <td><?=$row->cus_email ?? ''?></td>
                                <td>
                                    <a class="btn btn-primary btn-sm mb-2"  href="<?=route_to("edit_customer", $row->id)?>"><i class="fas fa-edit"></i></a>
                                    <?php if ( $login_user->root_user && $row->active == \Modules\Acp\Enums\Store\CustomerActiveEnum::INACTIVE ) : ?>
                                        <a class="btn btn-success btn-sm mb-2" title="Activate Customer" href="<?=route_to("active_customer", $row->id)?>"><i class="fa fa-lock-open"></i></a>
                                    <?php endif; ?>
                                    <a class="btn btn-danger btn-sm mb-2 acpRmItem" title="Move to Trash" data-delete="<?=route_to("remove_customer", $row->id)?>"
                                       data-id="<?=$row->id?>" data-delete-message="Bạn có chắc chắn muốn xoá item này?" ><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php }
                        else : ?>
                            <tr>
                                <td colspan="7">
                                    <?=lang('Acp.no_item_found')?>
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>

                </div>

                <div class="card-footer">
                    <?php echo $pager->links('default', 'acp_full') ?>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
