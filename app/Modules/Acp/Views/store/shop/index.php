<?php
echo $this->extend($config->viewLayout);
echo $this->section('content')
?>

<div class="row">
    <div class="col-12">
        <form method="post" action="<?=route_to('list_shop') ?>" >
            <?=csrf_field()?>
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <div class="btn-group">
                            <a href="<?=route_to('add_shop') ?>" class="btn btn-normal btn-primary btn-sm" title="<?=lang('Acp.add_new')?>" >
                                <i class="fa fa-plus text"></i> <?=lang('Acp.add_new')?>
                            </a>
                            <button class="btn btn-normal btn-danger btn-sm btnDelete" type="submit">
                                <i class="fa fa-trash-alt text"></i>&nbsp; <?=lang('Acp.delete')?>
                            </button>
                        </div>
                    </div>

                    <div class="card-tools mt-2">
                        <div class="input-group input-group-sm">
                            <input type="text" value="<?=(isset($search_title))?$search_title:''?>" 
                                name="name" class="form-control" placeholder="<?=lang('Acp.search')?>">
                            <div class="input-group-append">
                                <button type="submit" name="search" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive">

                    <table id="<?php echo $controller."_".$method ?>_DataTable" 
                    class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                        <tr>
                            <th width="3%">
                                <input class="form-control" style="width: 15px;" type="checkbox" value="" id="selectAll">
                            </th>
                            <th width="3%">ID</th>
                            <th><?=lang('Shop.image')?></th>
                            <th><?=lang('Shop.name')?></th>
                            <th><?=lang('Shop.phone')?></th>
                            <th><?=lang('Shop.address')?></th>
                            <th><?=lang('Acp.created_view')?></th>
                            <th><?=lang('Acp.status')?></th>
                            <th width="10%"><?=lang('Actions')?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ( count($data) > 0 ): foreach ($data as $row) { ?>
                            <tr>
                                <td>
                                    <input type="checkbox" style="width: 15px;" value="<?=$row->shop_id?>" name="sel[]" class="form-control" id="shop_<?=$row->shop_id?>">
                                </td>
                                <td><?=$row->shop_id?></td>
                                <td>
                                    <img src="<?=$row->images['thumbnail']?>" class="img img-thumbnail img-lg">
                                </td>
                                <td>
                                    <a  href="<?=route_to("edit_shop", $row->shop_id)?>"><?=$row->name?></a>
                                </td>
                                <td><?=$row->phone?></td>
                                <td><?=$row->full_address?></td>
                                <td><?=$row->created_at->format('d/m/Y')?></td>
                                <td class="todo-list">
                                    <?php
                                    switch ($row->status) {
                                        case 0:
                                            echo '<span class="badge badge-info">'.lang('Shop.status_'.\App\Enums\Store\ShopEnum::_getStatusKey(0)).'</span>';
                                            break;
                                        case 1:
                                            echo '<span class="badge badge-success">'.lang('Shop.status_'.\App\Enums\Store\ShopEnum::_getStatusKey(1)).'</span>';
                                            break;
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a class="btn btn-primary btn-sm mb-2"  href="<?=route_to("edit_shop", $row->shop_id)?>"><i class="fas fa-edit"></i></a>
                                    <a class="btn btn-danger btn-sm mb-2 acpRmItem" title="Move to Trash" data-delete="<?=route_to("remove_shop")?>"
                                       data-id="<?=$row->shop_id?>" data-delete-message="Bạn có chắc chắn muốn xoá item này?" ><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php }
                        else : ?>
                            <tr>
                                <td colspan="9">
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
