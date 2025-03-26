<?php
echo $this->extend($config->viewLayout);
echo $this->section('content')
?>

<div class="row">
    <div class="col-12">
        <form method="post" action="<?=route_to('list_shop') ?>" >
            <?=csrf_field()?>
            <div class="card">

                <div class="card-body">

                    <table id="<?php echo $controller."_".$method ?>_DataTable" 
                    class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                        <tr>
                            <th width="3%">ID</th>
                            <th><?=lang('Promo.minigame_promo_name')?></th>
                            <th><?=lang('Promo.minigame_promo_desc')?></th>
                            <th><?=lang('Promo.minigame_promo_value')?></th>
                            <th><?=lang('Acp.created_view')?></th>
                            <th><?=lang('Acp.status')?></th>
                            <th width="10%"><?=lang('Acp.actions')?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ( count($data) > 0 ): foreach ($data as $row) { ?>
                            <tr>
                                <td>#<?=$row->promo_id?></td>
                                <td>
                                    <a  href="<?=route_to("edit_lucky_draw_price", $row->promo_id)?>"><?=$row->promo_name?></a>
                                </td>
                                <td><?=$row->promo_description?></td>
                                <td><?=$row->getRewardValue()?></td>
                                <td><?=$row->created_at->format('d/m/Y')?></td>
                                <td class="todo-list">
                                    <?php
                                    $statusText = lang('Promo.status_'.$row->promo_status);
                                    switch ($row->promo_status) {
                                        case 0:
                                            echo '<span class="badge badge-warning">'.$statusText.'</span>';
                                            break;
                                        case 1:
                                            echo '<span class="badge badge-success">'.$statusText.'</span>';
                                            break;
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a class="btn btn-primary btn-sm mb-2"  href="<?=route_to("edit_lucky_draw_price", $row->promo_id)?>"><i class="fas fa-edit"></i></a>
                                </td>
                            </tr>
                        <?php }
                        else : ?>
                            <tr>
                                <td colspan="7">
                                    <p><?=lang('Acp.no_item_found')?></p>
                                    <?php if ( $login_user->is_root ) : ?>
                                    <p>Vui lòng nhấn vào <a href="<?=route_to('seed_reward')?>">đây</a> để tạo dữ liệu</p>
                                    <?php endif; ?>
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