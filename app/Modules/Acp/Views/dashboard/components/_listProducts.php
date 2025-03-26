<?php
/**
 * Author: tmtuan
 * Created date: 11/4/2023
 * Project: Unigem
 **/
?>
<div class="card card-outline card-primary">
    <div class="card-header border-transparent">
        <h3 class="card-title">Sản Phẩm Mới</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table m-0">
                <thead>
                <tr>
                    <th width="3%">#</th>
                    <th width="75%"><?=lang('Product.pd_name')?></th>
                    <th ><?=lang('Post.created_view')?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i= 0;
                foreach ($products as $row) :
                    $i++;
                ?>
                <tr>
                    <td><?=$i?></td>
                    <td>
                        <a href="<?= route_to("edit_product", $row->id) ?>"><?= $row->pd_name ?></a>
                    </td>
                    <td><?= $row->created_at->format('d/m/Y') ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- /.table-responsive -->
    </div>
    <div class="card-footer clearfix">
        <a href="<?=route_to('add_product')?>" class="btn btn-xs btn-primary float-left">Thêm Sản Phẩm Mới</a>
        <a href="<?=route_to('product')?>" class="btn btn-xs btn-dark float-right">Danh sách sản phẩm</a>
    </div>

</div>
