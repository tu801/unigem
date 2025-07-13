<?php

use App\Enums\Store\Voucher\VoucherDiscountTypeEnum;
use CodeIgniter\I18n\Time;

echo $this->extend($config->viewLayout);
echo $this->section('content')
?>

<div class="row">
    <div class="col-12">
        <form method="post" action="<?= route_to('list_voucher') ?>">
            <?= csrf_field() ?>
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <div class="btn-group">
                            <a href="<?= route_to('add_voucher') ?>" class="btn btn-normal btn-primary btn-sm" title="<?= lang('Acp.add_new') ?>">
                                <i class="fa fa-plus text"></i> <?= lang('Acp.add_new') ?>
                            </a>
                            <button class="btn btn-normal btn-danger btn-sm btnDelete" type="submit">
                                <i class="fa fa-trash-alt text"></i>&nbsp; <?= lang('Acp.delete') ?>
                            </button>
                        </div>
                    </div>

                    <div class="card-tools mt-2">
                        <div class="input-group input-group-sm">
                            <input type="text" value="<?= (isset($search_title)) ? $search_title : '' ?>"
                                name="search_text" class="form-control" placeholder="<?= lang('Acp.search') ?>">
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

                    <table id="<?php echo $controller . "_" . $method ?>_DataTable"
                        class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th width="3%">
                                    <input class="form-control" style="width: 15px;" type="checkbox" value="" id="selectAll">
                                </th>
                                <th width="3%">ID</th>
                                <th><?= lang('Shop.voucher_code') ?></th>
                                <th><?= lang('Shop.voucher_title') ?></th>
                                <th><?= lang('Shop.voucher_discount') ?></th>
                                <th><?= lang('Shop.voucher_time') ?></th>
                                <th><?= lang('Acp.status') ?></th>
                                <th width="10%"><?= lang('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($data) > 0): foreach ($data as $row) { ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" style="width: 15px;" value="<?= $row->voucher_id ?>" name="sel[]" class="form-control" id="shop_<?= $row->voucher_id ?>">
                                        </td>
                                        <td><?= $row->voucher_id ?></td>
                                        <td>
                                            <?= $row->voucher_code ?>
                                        </td>
                                        <td><?= $row->voucher_title ?></td>
                                        <td><?php
                                            if ($row->voucher_discount_type == VoucherDiscountTypeEnum::FIXED_AMOUNT) {
                                                echo format_currency($row->voucher_discount_value);
                                            } else {
                                                echo $row->voucher_discount_value . '%';
                                            }
                                            ?></td>
                                        <td>
                                            <?php
                                            if (isset($row->voucher_start_date) && !empty($row->voucher_start_date)) {
                                                echo Time::parse($row->voucher_start_date)->format('d/m/Y');
                                            } else {
                                                echo 'N/A';
                                            }
                                            if (isset($row->voucher_end_date) && !empty($row->voucher_end_date)) {
                                                echo ' - ' . Time::parse($row->voucher_end_date)->format('d/m/Y');
                                            } else {
                                                echo ' - N/A';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            $statusText = lang('Shop.voucher_status_' . $row->voucher_status);
                                            switch ($row->voucher_status) {
                                                case 0:
                                                    echo '<span class="badge badge-default">' . $statusText . '</span>';
                                                    break;
                                                case 1:
                                                    echo '<span class="badge badge-success">' . $statusText . '</span>';
                                                    break;
                                                case 2:
                                                    echo '<span class="badge badge-secondary">' . $statusText . '</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a class="btn btn-primary btn-sm mb-2" href="<?= route_to("edit_voucher", $row->voucher_id) ?>"><i class="fas fa-edit"></i></a>
                                            <a class="btn btn-danger btn-sm mb-2 acpRmItem" title="Move to Trash" data-delete="<?= route_to("remove_voucher") ?>"
                                                data-id="<?= $row->voucher_id ?>" data-delete-message="Bạn có chắc chắn muốn xoá item này?"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php }
                            else : ?>
                                <tr>
                                    <td colspan="8">
                                        <?= lang('Acp.no_item_found') ?>
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