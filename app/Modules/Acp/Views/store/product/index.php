<?php

use App\Enums\Store\Product\ProductStatusEnum;

echo $this->extend($config->viewLayout);
echo $this->section('content');
?>
    <div class="row">
        <div class="col-12">
            <form method="get" action="<?= route_to('product') ?>">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <a class="<?= ($listtype == 'all') ? 'badge badge-primary text-light' : 'text-primary' ?>" href="<?= base_url("acp/product?listtype=all") ?>"><?= lang('Product.list_all_product') ?></a> |
                            <a class="<?= ($listtype == 'user') ? 'badge badge-primary text-light' : 'text-primary' ?>" href="<?= base_url("acp/product?listtype=user") ?>"><?= lang('Product.list_user_product') ?></a> |
                            <a class="<?= ($listtype == 'deleted') ? 'badge badge-primary text-light' : 'text-primary' ?>" href="<?= base_url("acp/product?listtype=deleted") ?>"><?= lang('Product.list_delete_product') ?></a>
                        </div>

                        <div class="card-tools">
                            <div class="input-group input-group-sm">
                                <input name="listtype" id="listtype" value="<?= $listtype ?? 'user'; ?>" class="form-control" type="hidden" />
                                <input type="text" value="<?= (isset($search_title)) ? $search_title : '' ?>" name="pd_name" class="form-control" placeholder="Tìm tên sản phẩm">
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
                        <div class="mailbox-controls mb-2 ml-0 pl-0 mr-0 pr-0">

                            <div class="btn-group">
                                <a href="<?= route_to('add_product') ?>" class="btn btn-normal btn-primary btn-sm" title="<?= lang('Product.title_add') ?>">
                                    <i class="fa fa-plus text"></i> <?= lang('Product.title_add') ?>
                                </a>
                                <button type="submit" name="mdelete" class="btn btn-danger btn-sm ml-2"><i class="far fa-trash-alt"></i></button>
                            </div>
                            <!-- /.btn-group -->

                            <div class="float-right col-4 mr-0 pr-0">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <div id="chkCategory" class="form-group">
                                            <select class="custom-select form-control" name="category">
                                                <option value=""><?= lang('Product.pd_category') ?></option>
                                                <?php foreach ($product_category as $item) :
                                                    $selCat = (isset($select_cat) && $select_cat == $item->id) ? 'selected' : '';
                                                    ?>
                                                    <option value='<?= $item->id ?>' <?= $selCat ?>><?= $item->title ?></option>
                                                <?php endforeach;    ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-7">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <select class="custom-select form-control" name="pd_status">
                                                    <option value=""><?= lang('Product.pd_status') ?></option>
                                                    <?php foreach (ProductStatusEnum::toArray() as $key) :
                                                        $sttSel = (isset($pd_status) && $pd_status == $key) ? 'selected' : '';
                                                        ?>
                                                        <option value='<?= $key ?>' <?= $sttSel ?>><?=  lang('Product.status_'.$key) ?></option>
                                                    <?php endforeach;    ?>
                                                </select>
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary float-right" name="filter">
                                                        <i class="fas fa-filter"></i>&nbsp;Lọc</button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- /.float-right -->
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
                                <th width="35%"><?= lang('Product.pd_name') ?></th>
                                <th><?= lang('Product.image') ?></th>
                                <th><?= lang('Product.pd_sku') ?></th>
                                <th><?= lang('Product.pd_category') ?></th>
                                <th><?= lang('Product.pd_status') ?></th>
                                <th><?= lang('Actions') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (isset($data) && count($data) > 0) :
                                foreach ($data as $row) {
                                    $img = (isset($row->feature_image['thumbnail']) && $row->feature_image['thumbnail'] !== null) ? $row->feature_image['thumbnail'] : base_url($config->noimg);
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="icheck-primary">
                                                <input type="checkbox" value="<?= $row->id ?>" name="sel[]" id="post_<?= $row->id ?>">
                                                <label for="post_<?= $row->id ?>"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="<?= route_to("edit_product", $row->id) ?>"><?= $row->pd_name ?></a>
                                            <p><i class="fas fa-cart-plus fa-lg mr-2"></i>&nbsp;<?=lang('Product.pd_price')?>: <?=format_currency($row->price, $curLang->locale )?></p>
                                        </td>
                                        <td>
                                            <img src="<?= $img ?>" class="img-responsive img-thumbnail" style="max-width:150px">
                                        </td>
                                        <td>
                                            <?= $row->pd_sku ?? "" ?>
                                        </td>
                                        <td> <?= $row->category->title ?? '' ?></td>
                                        <td class="todo-list">
                                            <?php
                                            switch ($row->pd_status) {
                                                case ProductStatusEnum::PUBLISH:
                                                    echo '<span class="badge badge-success">' . lang('Product.status_'.$row->pd_status) . '</span>';
                                                    break;
                                                case ProductStatusEnum::PENDING :
                                                    echo '<span class="badge badge-warning">' . lang('Product.status_'.$row->pd_status) . '</span>';
                                                    break;
                                                case ProductStatusEnum::DRAFT :
                                                    echo '<span class="badge badge-secondary">' . lang('Product.status_'.$row->pd_status). '</span>';
                                                    break;
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a class="btn btn-primary btn-sm mb-2" href="<?= route_to("edit_product", $row->id) ?>"><i class="fas fa-edit"></i></a>
                                            <?php if ($listtype !== 'deleted') : ?>
                                                <a class="btn btn-danger btn-sm mb-2 acpRmItem" title="Move to Trash" data-id="<?=$row->id?>"
                                                   data-delete="<?= route_to("remove_product") ?>" data-delete-message="Bạn có chắc chắn muốn xoá item này?" ><i class="fas fa-trash"></i></a>
                                            <?php else : ?>
                                                <a class="btn btn-primary btn-sm mb-2" title="Recover Item" href="<?= route_to("recover_product", $row->id) ?>"><i class="fas fa-reply"></i></a>
                                            <?php endif; ?>
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
<?php echo $this->section('pageScripts') ?>
<script>
    $(document).ready(function () {
        const catType = 'product';
        const url = bkUrl + '/category/list-cat/' + catType;
        $.ajax({
            type: "GET",
            url: url ,
            dataType: "json",
            success: function (response) {
                if( response.error === 1 ) {
                    // Store value in localStorage when category is empty
                    localStorage.setItem('product_category_is_empty', '1');
                    
                    Swal.fire({
                        icon: 'error',
                        text: response.message,
                    }).then((result) => {
                        window.location.href = '<?= route_to('category', 'product') ?>'
                    });
                }
            }.bind(this)
        });
    })
</script>
<?= $this->endSection() ?>

