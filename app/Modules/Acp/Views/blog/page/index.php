<?php
echo $this->extend($config->viewLayout);
echo $this->section('content')
?>

<div class="row">
    <div class="col-12">
        <form method="post" action="<?=route_to('page') ?>" >
            <?=csrf_field()?>
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <div class="btn-group">
                            <a href="<?=route_to('add_page') ?>" class="btn btn-normal btn-primary btn-sm" title="<?=lang('Acp.add_new')?>" >
                                <i class="fa fa-plus text"></i> <?=lang('Acp.add_new')?>
                            </a>
                            <button class="btn btn-normal btn-danger btn-sm btnDelete" type="submit">
                                <i class="fa fa-trash-alt text"></i>&nbsp; <?=lang('Acp.delete')?>
                            </button>

                        </div>
                    </div>

                    <div class="card-tools">
                        <div class="input-group input-group-sm">
                            <input type="text" value="<?=(isset($search_title))?$search_title:''?>" 
                                name="title" class="form-control" placeholder="<?=lang('Acp.search')?>">
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
                            <th width="3%">
                                <input class="form-control" style="width: 15px;" type="checkbox" value="" id="selectAll">
                            </th>
                            <th width="3%">ID</th>
                            <th width="55%"><?=lang('Post.title')?></th>
                            <th><?=lang('Post.created_view')?></th>
                            <th><?=lang('Post.post_status')?></th>
                            <th width="10%"><?=lang('Actions')?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ( count($data) > 0 ): foreach ($data as $row) { ?>
                            <tr>
                                <td>
                                    <input type="checkbox" style="width: 15px;" value="<?=$row->id?>" name="sel[]" class="form-control" id="post_<?=$row->id?>">
                                </td>
                                <td><?=$row->id?></td>
                                <td>
                                    <a  href="<?=route_to("edit_page", $row->id)?>"><?=$row->title?></a>
                                </td>
                                <td><?=$row->created_at->format('d/m/Y')?></td>
                                <td class="todo-list">
                                    <?php
                                    switch ($row->post_status) {
                                        case 'draft':
                                            echo '<span class="badge badge-info">'.$config->cmsStatus['status']['draft'].'</span>';
                                            break;
                                        case 'pending':
                                            echo '<span class="badge badge-warning">'.$config->cmsStatus['status']['pending'].'</span>';
                                            break;
                                        case 'publish':
                                            echo '<span class="badge badge-primary">'.$config->cmsStatus['status']['publish'].'</span>';
                                            break;
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a class="btn btn-primary btn-sm mb-2"  href="<?=route_to("edit_page", $row->id)?>"><i class="fas fa-edit"></i></a>
                                    <a class="btn btn-danger btn-sm mb-2 acpRmItem" title="Move to Trash" data-delete="<?=route_to("remove_page")?>"
                                       data-id="<?=$row->id?>" data-delete-message="Bạn có chắc chắn muốn xoá item này?" ><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php }
                        else : ?>
                            <tr>
                                <td colspan="6">
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
