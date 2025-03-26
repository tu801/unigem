<?php
/**
 * @author tmtuan
 * created Date: 08-May-21
 */
?>
<div class="card-body form-horizontal">
    <div class="form-group">
        <label for="inputEmail3" class="control-label">Tiêu đề</label>
        <input type="text" class="form-control" id="contentTitle_<?=$row->id?>" value="<?=$row->title?>" placeholder="Tiêu đề">
    </div>
</div>
<!-- /.card-body -->
<div class="card-footer">
    <button type="button" onclick="editmenuitem(<?=$row->id?>, <?=$row->related_id ?>);" class="btn btn-primary float-right">Lưu</button>
</div>
<!-- /.card-footer -->
