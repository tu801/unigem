<?php
/**
 * @author tmtuan
 * created Date: 23-May-21
 */

$status = $config->cmsStatus['status'];
if ( $config->cmsStatus['publish_check'] ) {
    unset($status['publish']);
}
?>
<div class="form-group ">
    <label ><?=lang('Post.post_status')?></label>
    <?php
    $htmlData = ['class' => 'form-control'];
    $controlName = $frm_name ?? 'status';
    $selectedData = $frm_selected??old($controlName);
    if ( $selectedData != 'publish') echo form_dropdown($controlName, $status ,$selectedData , $htmlData);
    elseif ($login_user->root_user || $login_user->can($config->adminSlug.'/post/publish')) echo form_dropdown($controlName, $config->cmsStatus['status'] ,$selectedData , $htmlData);
    else echo '<div class="form-control"> <span class="badge badge-primary">'.$config->cmsStatus['status']['publish'].'</span></div>'
    ?>
</div>