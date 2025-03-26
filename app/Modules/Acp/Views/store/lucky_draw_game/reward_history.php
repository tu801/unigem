<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 11/2/2023
 */

echo $this->extend($config->viewLayout);
echo $this->section('content')
?>

<div class="row">
    <div class="col-12">
        <form method="get" action="<?=route_to('reward_history') ?>" >
            <?=csrf_field()?>
            <div class="card">
                <div class="card-body">
                    <div class="mailbox-controls mb-2 ml-0 pl-0 mr-0 pr-0">
                        <div class="float-right col-6 mr-0 pr-0">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <input type="text" value="<?= (isset($search_voucher)) ? $search_voucher : '' ?>" name="voucher_keyword" class="form-control"
                                               placeholder="<?=lang('Promo.minigame_voucher_search')?>">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <input type="text" value="<?= (isset($search_title)) ? $search_title : '' ?>" name="search" class="form-control"
                                               placeholder="<?=lang('Promo.reward_search')?>">
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <select class="custom-select form-control" name="status">
                                                <option value="">-- Trạng thái --</option>
                                                <?php foreach (\Modules\Acp\Enums\Store\Promotion\LuckyDrawHistoryStatusEnum::toArray() as $key => $val) :
                                                    $sel = (isset($history_status) && $history_status == $val) ? 'selected' : '';
                                                    ?>
                                                    <option value='<?= $val ?>' <?= $sel ?>><?= lang('Promo.history_status_'.strtolower($key)) ?></option>
                                                <?php endforeach;    ?>
                                            </select>
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary float-right">
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
                            <th width="3%">ID</th>
                            <th><?=lang('Promo.minigame_player_name')?></th>
                            <th><?=lang('Promo.minigame_user_email')?></th>
                            <th><?=lang('Promo.minigame_user_phone')?></th>
                            <th><?=lang('Promo.minigame_voucher')?></th>
                            <th><?=lang('Promo.minigame_created_view')?></th>
                            <th><?=lang('Promo.minigame_player_ip')?></th>
                            <th><?=lang('Acp.status')?></th>
                            <th width="10%"><?=lang('Acp.actions')?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ( count($data) > 0 ): foreach ($data as $row) { ?>
                            <tr>
                                <td>#<?=$row->history_id ?></td>
                                <td><?=$row->user_full_name ? $row->user_full_name : 'N/A'?></td>
                                <td><?=$row->user_email ? $row->user_email : 'N/A'?></td>
                                <td><?=$row->user_phone ? $row->user_phone : 'N/A' ?></td>
                                <td>
                                    <p><b><?=$row->voucher_code ?></b></p>
                                    <p><?=$row->voucher_title ?></p>
                                </td>
                                <td><?=$row->created_at->format('d/m/Y')?></td>
                                <td><?=$row->ip_address ?></td>
                                <td class="todo-list">
                                    <?php
                                    $statusCollection = new \Libraries\Collection\Collection(\Modules\Acp\Enums\Store\Promotion\LuckyDrawHistoryStatusEnum::toArray());
                                    $statusKey = $statusCollection->findIndex(function($item, $key) use($row) {
                                        return $item == $row->status;
                                    });
                                    $statusText = lang('Promo.history_status_'.strtolower($statusKey));
                                    switch ($row->status) {
                                        case 0:
                                            echo '<span class="badge badge-warning">'.$statusText.'</span>';
                                            break;
                                        case 1:
                                            echo '<span class="badge badge-success">'.$statusText.'</span>';
                                            break;
                                        case 2:
                                            echo '<span class="badge badge-success">'.$statusText.'</span>';
                                            break;
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a class="btn btn-primary btn-sm mb-2"  href="<?=route_to("reward_history_detail", $row->history_id)?>"><i class="fas fa-eye"></i></a>
                                </td>
                            </tr>
                        <?php }
                        else : ?>
                            <tr>
                                <td colspan="9">
                                    <p><?=lang('Acp.no_item_found')?></p>
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
