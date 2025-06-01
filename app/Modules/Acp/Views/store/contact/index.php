<?php

use App\Enums\ContactEnum;

echo $this->extend($config->viewLayout);
echo $this->section('content')
?>

<div class="row">
    <div class="col-12">
        <form method="post" action="<?=route_to('list_contact') ?>" >
            <?=csrf_field()?>
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <div class="btn-group">
                            <button class="btn btn-normal btn-danger btn-sm btnDelete" type="submit">
                                <i class="fa fa-trash-alt text"></i>&nbsp; <?=lang('Acp.delete')?>
                            </button>
                        </div>
                    </div>

                    <div class="card-tools mt-2">
                        <div class="input-group input-group-sm">
                            <input type="text" value="<?=(isset($search_title))?$search_title:''?>" 
                                name="email" class="form-control" placeholder="<?=lang('Acp.search')?>">
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
                    <div class="row">
                        <div class="col-12 d-flex justify-content-end">
                            <div class="col-12 col-sm-8 col-md-4 px-0 d-flex justify-content-md-end justify-content-center mx-md-0 mx-auto">
                                <div class="form-group mb-2 w-100">
                                    <div class="input-group">
                                        <select class="custom-select form-control" name="status">
                                            <option value="">-- <?= lang('Acp.status') ?> --</option>
                                            <?php foreach (ContactEnum::getContactStatusList() as $item) :
                                                $sttSel = (isset($filter_status) && $filter_status == $item) ? 'selected' : '';
                                            ?>
                                                <option value='<?= $item ?>' <?= $sttSel ?>><?= lang("Contact.contact_status_{$item}") ?></option>
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

                    <div class="row table-responsive">
                        <table id="<?php echo $controller."_".$method ?>_DataTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                            <thead>
                            <tr>
                                <th width="3%">
                                    <input class="form-control" style="width: 15px;" type="checkbox" value="" id="selectAll">
                                </th>
                                <th width="3%">ID</th>
                                <th><?=lang('Contact.contact_name')?></th>
                                <th><?=lang('Contact.contact_email')?></th>
                                <th><?=lang('Contact.contact_subject')?></th>
                                <th><?=lang('Acp.status')?></th>
                                <th width="10%"><?=lang('Actions')?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if ( count($data) > 0 ): foreach ($data as $row) { ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" style="width: 15px;" value="<?=$row->id?>" name="sel[]" class="form-control" id="shop_<?=$row->id?>">
                                    </td>
                                    <td><?=$row->id?></td>
                                    <td>
                                        <a  href="<?=route_to("edit_contact", $row->id)?>"><?=$row->fullname?></a>
                                    </td>
                                    <td><?=$row->email?></td>
                                    <td><?=lang('Home.subject_'.$row->subject)?></td>
                                    <td class="todo-list">
                                        <?php
                                        $statusText = lang('Contact.contact_status_'.$row->status);
                                        switch ($row->status) {
                                            case ContactEnum::STATUS_NEW:
                                                echo '<span class="badge badge-info">'.$statusText.'</span>';
                                                break;
                                            case ContactEnum::STATUS_READ:
                                                echo '<span class="badge badge-success">'.$statusText.'</span>';
                                                break;
                                            case ContactEnum::STATUS_REPLIED:
                                                echo '<span class="badge badge-secondary">'.$statusText.'</span>';
                                                break;
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a class="btn btn-primary btn-sm mb-2"  href="<?=base_url(route_to("view_contact", $row->id))?>"><i class="fas fa-eye"></i></a>
                                        <a class="btn btn-danger btn-sm mb-2 acpRmItem" title="Move to Trash" data-delete="<?=route_to("remove_contact")?>"
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
                </div>

                <div class="card-footer">
                    <?php echo $pager->links('default', 'acp_full') ?>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
