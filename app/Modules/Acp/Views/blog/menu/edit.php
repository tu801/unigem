<?php
/**
 * @author tmtuan
 * created Date: 10/11/2021
 * project: basic_cms
 */

echo $this->extend($config->viewLayout);
echo $this->section('content') ?>
<?= form_open(route_to('save_menu')) ?>
<div class="row">
    <div class="col-md-9">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline" >
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label"><?= lang('Menu.menu_name'); ?></label>
                                    <div class="col-sm-10">
                                        <input class="form-control" name="name" value="<?=$menuItem->name?>" >
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>

                </div>
                <?= view($config->view . '\blog\menu\components\_addMenuForm') ?>
            </div>
        </div>

        <div class="row">
            <div class="col-4 col-md-4">
                <!--Page List-->
                <?= view($config->view . '\blog\menu\components\_pageList') ?>

                <!--Category List-->
                <?= view($config->view . '\blog\menu\components\_catList') ?>

                <!--card static URL-->
                <?= view($config->view . '\blog\menu\components\_addMenuUrl') ?>
                <!--end card static URL-->

                <?php if ($login_user->is_root) : ?>
                    <!--static link for root admin only-->
                    <div class="card card-body">

                    </div>
                    <!--end static link for root admin only-->
                <?php endif; ?>

            </div>

            <!--List Menu-->
            <div class="col-8 col-md-8">
                
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title row"><i class="ion ion-clipboard"></i>&nbsp;&nbsp;
                            <div class="font-weight-bold"> <?= lang('Menu.menu_structure'); ?>: &nbsp;</div> <?= $menuItem->name ?? '' ?>
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="dd" id="nestable">
                            <ol class="dd-list">
                                <?php
                                if (isset($lstparentMenuItems) && count($lstparentMenuItems) > 0) :
                                    foreach ($lstparentMenuItems as $row) :
                                        ?>
                                        <li class="dd-item" data-id="<?= $row->id ?>">
                                            <!-- drag handle -->
                                            <div class="dd-handle ">
                                                <h3 class="title" id="menuTitle_<?= $row->id ?>">
                                                    <?= $row->title ?? $row->id ?>
                                                </h3>
                                                <div class="float-right">
                                                    <a class="btn btn-primary btn-sm" data-toggle="collapse" href="#editForm_<?= $row->id ?>" role="button" aria-expanded="false" aria-controls="editForm_<?= $row->id ?>">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <a class="btn btn-danger btn-sm acpRemoveMenu" href="<?= base_url($adminSlug . "/menu/remove/{$row->id}?key={$menuItem->slug}") ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="card collapse card-danger card-outline" id="editForm_<?= $row->id ?>">
                                                <?php
                                                if ($row->type == 'category') :
                                                    echo view($config->view . '\blog\menu\components\_editCategoryCard', ['row' => $row]);
                                                else :
                                                    echo view($config->view . '\blog\menu\components\_editMenuCard', ['row' => $row]);
                                                endif;
                                                ?>
                                            </div>

                                            <?php
                                            //get child menu
                                            $childs = model(\App\Models\Blog\MenuModel::class)->getChild($row, $menuItem);
                                            echo $childs;
                                            ?>
                                        </li>
                                    <?php endforeach; ?>
                                    
                                <?php else : ?>
                                    <li>
                                        <span class="text">Hiện chưa có danh mục nào trong này! Vui lòng thêm danh mục.</span>
                                    </li>
                                <?php endif; ?>
                            </ol>
                        </div>
                        
                        <input type="hidden" id="tmt-tk" value="<?= csrf_hash() ?>">
                        <input type="hidden" id="tmt-tkname" value="<?= csrf_token() ?>">
                        <input type="hidden" name="menu_id" value="<?= $menuItem->id ?? 0 ?>">
                        <input type="hidden" name="menuHierarchical" id="nestable-output">
                    </div>
                    <!-- /.card-body -->
                    
                </div>
                
            </div>

        </div>

    </div>
    <div class="col-md-3">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <div class="form-group ">
                            <label ><?=lang('Menu.menu_status')?></label>
                            <select class="form-control" name="status">
                                <?php
                                foreach ($config->cmsStatus['status'] as $key => $title) :
                                    $sel = ($key == $menuItem->status) ? 'selected' : '';
                                ?>
                                    <option <?=$sel?> value='<?=$key?>'><?=$title?></option>
                                <?php endforeach;    ?>
                            </select>
                        </div>
                        <hr>
                        <div class=" ">
                            <?php if ( isset($menu_location) && count($menu_location) > 0 ) : ?>
                            <h5 ><?=lang('Menu.menu_location')?></h5>
                            <?php
                                foreach ($menu_location as $locItem) :
                                    $currentLocation = json_decode($menuItem->location) ?? [];
                                    $sel = ( in_array($locItem->value, $currentLocation) ) ? 'checked' : '';
                                ?>
                                    <div class=" icheck-success d-inline">
                                        <input name="location[]" type="checkbox" <?=$sel?> id="chk<?=$locItem->value?>" value="<?=$locItem->value?>">
                                        <label for="chk<?=$locItem->value?>"><?=$locItem->title?></label>
                                    </div>
                            <?php endforeach; else: ?>
                            <span class="text-danger"><?=lang('Menu.no_location_config')?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card-footer clearfix no-border">
                        <button type="submit" class="btn btn-primary float-right">Lưu trình đơn</button>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

</div>
</form>
<?= $this->endSection() ?>

<?php echo $this->section('pageStyles') ?>
<link rel="stylesheet" href="<?= base_url($config->scriptsPath) ?>/plugins/nestable/nestable.css">
    <link rel="stylesheet" href="<?= base_url($config->scriptsPath) ?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
<?= $this->endSection() ?>

<?php echo $this->section('pageScripts') ?>
<script src="<?= base_url($config->scriptsPath) ?>/plugins/nestable/nestable.js"></script>
<script src="<?= base_url($config->scriptsPath) ?>/acp/blog/vMenuEdit.js"></script>
<script>
    $(document).ready(function() {
        customUrlApp.mount('#addUrlApp');
        listPagesApp.mount('#listPageApp');

        var updateOutput = function(e) {
            var list = e.length ? e : $(e.target),
                output = list.data('output');
            if (window.JSON && typeof output != 'undefined') {
                output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
            } else {
                if ( typeof output != 'undefined' ) output.val('JSON browser support required for this demo.');
            }
        };

        $(".dd a").on("mousedown", function(event) { // mousedown prevent nestable click
            event.preventDefault();
            return false;
        });

        // activate Nestable for list 1
        $('#nestable').nestable({
                maxDepth: 2,
                group: 1
            })
            .on('change', updateOutput);

        // output initial serialised data
        updateOutput($('#nestable').data('output', $('#nestable-output')));

    });
</script>
<?= $this->endSection() ?>