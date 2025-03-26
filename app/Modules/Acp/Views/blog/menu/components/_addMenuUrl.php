<?php
/**
 * @author tmtuan
 * created Date: 08-May-21
 */
?>

<div class="card card-primary card-outline collapsed-card" data-action="<?=route_to("add_url_item")?>" id="addUrlApp"
    data-redirect="<?=route_to("edit_menu", $menuItem->id)?>">

    <div class="card-header">
        <h3 class="card-title"><?=lang('Menu.static_url_header')?></h3>
        <div class="card-tools">
            <button type="button" class="btn bg-primary btn-xs mr-1" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
            </button>
            <button type="button" class="btn bg-primary btn-xs" data-card-widget="remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <!-- /.card-header -->


    <div class="card-body">
        <div class="form-group">
            <label for="inputEmail3" class="control-label" >Tiêu đề</label>
            <input type="text" class="form-control <?=session('errors.title') ? 'is-invalid' : ''?>"
                    v-model="title" placeholder="Tiêu đề">
        </div>
        <div class="form-group">
            <label  class="control-label" >Menu Cha</label>
            <select v-model="parent_id" class="form-control">
                <option value=''>--Chọn Menu--</option>
                <?php foreach ($lstparentMenuItems as $mnItem) : ?>
                <option value="<?=$mnItem->id?>"><?=$mnItem->title?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="inputPassword3" class="control-label" >URL</label>
            <input type="text" class="form-control <?=session('errors.url') ? 'is-invalid' : ''?>"
                    v-model="url"  placeholder="URL">
        </div>

        <div class="form-group">
            <label  class="control-label" >Target</label>
            <select v-model="target" class="form-control" >
                <option value="_self">Open link directly</option>
                <option value="_blank">Open link in new tab</option>
            </select>
        </div>

    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <input type="hidden" id="menukey" value="<?=$menuItem->id??0?>">
        <button type="button" @click.prevent="resetForm" class="btn btn-default">Cancel</button>
        <button type="button" @click.prevent="submitForm" class="btn btn-info float-right">Thêm đường dẫn</button>
    </div>
    <!-- /.card-footer -->

</div>
