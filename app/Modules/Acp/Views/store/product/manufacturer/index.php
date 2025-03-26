<?php

use Modules\Acp\Enums\Store\Product\ProductStatusEnum;

echo $this->extend($config->viewLayout);
echo $this->section('content');
?>
<div class="row">
    <div class="col-12">
        <form method="get" action="<?= route_to('manufacture') ?>">
            <div class="card">
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="mailbox-controls mb-2 ml-0 pl-0 mr-0 pr-0">

                        <div class="btn-group">
                            <a href="<?= route_to('add_manufacture') ?>" class="btn btn-normal btn-primary btn-sm" title="<?= lang('ProductManufacturer.title_add') ?>">
                                <i class="fa fa-plus text"></i> <?= lang('ProductManufacturer.title_add') ?>
                            </a>
                            <button type="submit" name="mdelete" class="btn btn-danger btn-sm ml-2"><i class="far fa-trash-alt"></i></button>
                        </div>
                        <!-- /.btn-group -->
                    </div>

                    <table id="<?php echo $controller . "_" . $method ?>_DataTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                        <tr>
                            <th width="2%">
                                <div class="icheck-primary">
                                    <input type="checkbox" value="" id="post_check">
                                    <label for="post_check"></label>
                                </div>
                            </th>
                            <th width="35%"><?= lang('ProductManufacturer.name') ?></th>
                            <th><?= lang('ProductManufacturer.description') ?></th>
                            <th><?= lang('ProductManufacturer.status') ?></th>
                            <th><?= lang('Actions') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (isset($data) && count($data) > 0) :
                            foreach ($data as $row) {
                                ?>
                                <tr>
                                    <td>
                                        <div class="icheck-primary">
                                            <input type="checkbox" value="<?= $row->manufacturer_id ?>" name="sel[]" id="post_<?= $row->manufacturer_id ?>">
                                            <label for="post_<?= $row->manufacturer_id ?>"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="<?= route_to("edit_manufacture", $row->manufacturer_id) ?>"><?= $row->manufacture_name ?? '' ?></a>
                                    </td>
                                    <td>
                                        <?= $row->description ?? "" ?>
                                    </td>
                                    <td class="todo-list">
                                        <?php
                                        switch ($row->status) {
                                            case ProductStatusEnum::PUBLISH:
                                                echo '<span class="badge badge-info">' . lang('ProductManufacturer.status_'.$row->status) . '</span>';
                                                break;
                                            case ProductStatusEnum::PENDING :
                                                echo '<span class="badge badge-warning">' . lang('ProductManufacturer.status_'.$row->status) . '</span>';
                                                break;
                                            case ProductStatusEnum::DRAFT :
                                                echo '<span class="badge badge-primary">' . lang('ProductManufacturer.status_'.$row->status). '</span>';
                                                break;
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a class="btn btn-primary btn-sm mb-2" href="<?= route_to("edit_manufacture", $row->manufacturer_id) ?>"><i class="fas fa-edit"></i></a>
                                            <a class="btn btn-danger btn-sm mb-2 acpRmItem" title="Move to Trash" data-id="<?=$row->manufacturer_id?>"
                                               data-delete="<?= route_to("remove_manufacture") ?>" data-delete-message="Bạn có chắc chắn muốn xoá item này?" ><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php }
                        else : ?>
                            <tr>
                                <td colspan="8"><?= lang('Acp.no_item_found') ?></td>
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