<?php

use App\Enums\Store\Order\EOrderStatus;
use App\Enums\Store\Order\EPaymentStatus;

echo $this->extend($config->viewLayout);
echo $this->section('content')
?>

<div class="row">
    <div class="col-12">
        <form action="<?=route_to('order') ?>" >
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <a class="<?= ($listtype == 'user') ? 'badge badge-primary text-light' : 'text-primary' ?>" href="<?= base_url("acp/order?listtype=user") ?>"><?= lang('Order.list_user') ?></a> |
                        <?php if (in_array($login_user->gid, [1, 2])) : ?>
                            <a class="<?= ($listtype == 'all') ? 'badge badge-primary text-light' : 'text-primary' ?>" href="<?= base_url("acp/order?listtype=all") ?>"><?= lang('Order.list_all') ?></a> |
                        <?php endif; ?>
                        <a class="<?= ($listtype == 'deleted') ? 'badge badge-primary text-light' : 'text-primary' ?>" href="<?= base_url("acp/order?listtype=deleted") ?>"><?= lang('Order.list_delete') ?></a>
                    </div>

                    <div class="card-tools">
                        <div class="input-group input-group-sm">
                            <input type="text" value="<?=(isset($search_title))?$search_title:''?>" 
                                name="keyword" class="form-control" placeholder="<?=lang('Acp.search')?>">
                            <div class="input-group-append">
                                <button type="submit" name="search" class="btn btn-primary">
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
                            <a href="<?= route_to('add_order') ?>" class="btn btn-normal btn-primary btn-sm" title="<?= lang('Order.add_title') ?>">
                                <i class="fa fa-plus text"></i> <?= lang('Order.add_title') ?>
                            </a>
                            <button type="submit" name="mdelete" class="btn btn-danger btn-sm ml-2"><i class="far fa-trash-alt"></i></button>
                        </div>
                        <!-- /.btn-group -->

                        <div class="float-right col-4 mr-0 pr-0">
                            <div class="row">
                                <div class="col-sm-5">
                                    <div id="chkCategory" class="form-group">
                                        <select class="custom-select form-control" name="status">
                                            <option value=""><?= lang('Order.status') ?></option>
                                            <?php foreach (EOrderStatus::toArray() as $item): ?>
                                                <option value="<?= $item ?>"><?= lang('Order.order_status_'.$item) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-7">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <select class="custom-select form-control" name="payment_status">
                                                <option value=""><?= lang('Order.payment_status') ?></option>
                                                <?php foreach (EPaymentStatus::toArray() as $item): ?>
                                                    <option value="<?= $item ?>"><?= lang('Order.payment_status_'.$item) ?></option>
                                                <?php endforeach; ?>
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

                    <table id="<?php echo $controller."_".$method ?>_DataTable" 
                    class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                        <tr>
                            <th width="3%">
                                <input class="form-control" style="width: 15px;" type="checkbox" value="" id="selectAll">
                            </th>
                            <th><?=lang('Order.code')?></th>
                            <th><?=lang('Order.customer_name')?></th>
                            <th><?=lang('Order.phone')?></th>
                            <th><?=lang('Order.count_product')?></th>
                            <th><?=lang('Order.total')?></th>
                            <th><?=lang('Order.status')?></th>
                            <th><?=lang('Order.payment_status')?></th>
                            <th><?=lang('Order.created_view')?></th>
                            <th width="10%"><?=lang('Acp.actions')?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ( count($data) > 0 ): foreach ($data as $row) { ?>
                            <tr>
                                <td>
                                    <input type="checkbox" style="width: 15px;" value="<?=$row->order_id?>" name="sel[]" class="form-control" id="shop_<?=$row->order_id?>">
                                </td>
                                <td><a href="<?= route_to('invoice_order', $row->order_id) ?>"><?= $row->code ?></a></td>
                                <td><?= $row->cus_full_name ?? '' ?></td>
                                <td><?= $row->cus_phone ?? '' ?></td>
                                <td><?= $row->count_product ?? ''?></td>
                                <td><span class="badge badge-info"> <?= number_format($row->total) ?>đ</span></td>
                                <td>
                                    <?php
                                    switch ($row->status) {
                                        case EOrderStatus::OPEN:
                                            echo '<span class="badge badge-light">'.lang("Order.order_status_{$row->status}").'</span>';
                                            break;
                                        case EOrderStatus::CONFIRMED:
                                        case EOrderStatus::SHIPPED:
                                        case EOrderStatus::PROCESSED:
                                            echo '<span class="badge badge-info">'.lang("Order.order_status_{$row->status}").'</span>';
                                            break;
                                        case EOrderStatus::CANCELLED:
                                            echo '<span class="badge badge-danger">'.lang("Order.order_status_{$row->status}").'</span>';
                                            break;
                                        case EOrderStatus::COMPLETE:
                                            echo '<span class="badge badge-success">'.lang("Order.order_status_{$row->status}").'</span>';
                                            break;
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    switch ($row->payment_status) {
                                        case EPaymentStatus::UNPAID:
                                            echo '<span class="badge badge-warning">'. lang("Order.payment_status_{$row->payment_status}") .' </span>';
                                            break;
                                        case EPaymentStatus::PAID:
                                            echo '<span class="badge badge-success">'. lang("Order.payment_status_{$row->payment_status}") .'</span>';
                                            break;
                                        case EPaymentStatus::DEPOSIT:
                                            echo '<span class="badge badge-secondary">'. lang("Order.payment_status_{$row->payment_status}") .'</span>';
                                            break;
                                    }
                                    ?>
                                    <?php if($row->payment_status == EPaymentStatus::DEPOSIT): ?>
                                        <a href="<?= route_to("view_deposit_order", $row->order_id) ?>"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                    <?php endif; ?>
                                </td>
                                <td><?=$row->created_at->format('d-m-Y')?></td>
                                <td>
                                    <?php if ($listtype !== 'deleted') : ?>
                                        <a class="btn btn-primary btn-sm mb-2"  href="<?=route_to("edit_order", $row->order_id)?>"><i class="fas fa-edit"></i></a>
                                        <a class="btn btn-danger btn-sm mb-2 acpRmItem" title="Move to Trash" data-delete="<?=route_to("remove_order")?>" data-id="<?=$row->order_id?>" data-delete-message="Bạn có chắc chắn muốn xoá item này?" ><i class="fas fa-trash"></i></a>
                                    <?php else : ?>
                                        <a class="btn btn-primary btn-sm mb-2" title="Recover Item" href="<?= route_to("recover_order", $row->order_id) ?>"><i class="fas fa-reply"></i></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php }
                        else : ?>
                            <tr>
                                <td colspan="10">
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
