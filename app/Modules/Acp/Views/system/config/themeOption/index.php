<?php
/**
 * @author tmtuan
 * created Date: 10/20/2021
 * project: basic_cms
 */
echo $this->extend($config->viewLayout);
echo $this->section('content');
?>
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <?php if ( !empty($cfData) ) : ?>
                    <div class="col-sm-3">
                        <div class="nav flex-column nav-tabs h-100" id="customcf-tab" role="tablist" aria-orientation="vertical">
                            <?php
                            $i = 0;
                            foreach($cfData as $groupKey => $group) :
                            ?>
                                <a class="nav-link <?=($i==0)?'active':''?>" id="customcf-<?=$groupKey?>-tab" data-toggle="pill" href="#customcf-<?=$groupKey?>" role="tab"
                                   aria-controls="customcf-<?=$groupKey?>" aria-selected="<?=($i==0)?'true':'false'?>"><?=$group['title']?></a>
                            <?php $i++; endforeach; ?>
                        </div>
                    </div>
                    <div class="col-sm-9" id="themeApp">
                        <div class="tab-content" id="vert-tabs-tabContent">
                            <?php
                            $i = 0;
                            foreach($cfData as $groupKey => $group) :
                                $itemData = $group['items'] ?? [];
                                ?>
                                <div class="tab-pane text-left fade show <?=($i==0)?'active':''?>" id="customcf-<?=$groupKey?>" role="tabpanel" aria-labelledby="customcf-<?=$groupKey?>-tab">
                                    <?php
                                    echo form_open();
                                        foreach ($itemData as $key => $item) :
                                            $disableSaveButton = false;
                                        ?>
                                        <div class="form-group">
                                            <label for="item<?=$key?>" class="col-form-label"><?=$item['title']?></label>
                                            <?php
                                            $htmlData = array(
                                                'name'	=> $key,
                                                'id'    => 'item_'.$key,
                                                'class' => 'form-control'
                                            );

                                            switch ($item['input']) {
                                                case 'image':
                                                    $valData = json_decode($item['value']);
                                                    ?>
                                                    <config-img id="<?=$i?>" img-desc="<?=$item['desc']??''?>" select-img-type="1" input-name="<?=$key?>" return-img="id"
                                                                img-data="<?=$valData->id ?? 0?>" ></config-img>
                                                    <?php
                                                    break;
                                                case 'images':
                                                    ?>
                                                    <config-img id="<?=$i?>" img-desc="<?=$item['desc']??''?>" select-img-type="2" input-name="<?=$key?>" return-img="id"
                                                                img-data="<?=$item['value']?>" ></config-img>
                                                    <?php
                                                    break;
                                                case 'text':
                                                    echo form_input($htmlData, $item['value']);
                                                    break;
                                                case 'textarea':
                                                    $htmlData['rows'] = 4;
                                                    echo form_textarea($htmlData, $item['value']);
                                                    break;
                                                case 'slider':
                                                    $disableSaveButton = true;
                                                    echo "<vfile-selector></vfile-selector>";
                                                    break;
                                                case 'switch':
                                                    $checked = $item['value'] == 1 ? 'checked' : '';
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <input type="checkbox" name="<?=$key?>" value="1" <?=$checked?> data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                                        </div>
                                                    </div>
                                                    <?php
                                                    
                                                    break;
                                                case 'dropdown' :
                                                    echo form_dropdown($htmlData, $item['data'], $item['value']);
                                                    break;
                                                case 'item_list':
                                                    echo view($config->view.'\system\config\themeOption\_item_list', ['itemData' => $item, 'itemName' => $key]);
                                                    
                                                    break;
                                            }
                                            if ( isset($item['desc']) && !empty($item['desc']) ) {
                                                echo '<span class="help-block">'. $item['desc'] .'</span>';
                                            }
                                            ?>
                                        </div>
                                        <?php endforeach; ?>

                                    <?php if(!$disableSaveButton): ?>
                                        <div class="form-group">
                                            <input type="hidden" name="option_group" value="<?=$groupKey?>" >
                                            <button type="submit" class="btn btn-success"><?=lang('Acp.save')?></button>
                                        </div>
                                    <?php endif; ?>
                                    <?=form_close()?>
                                </div>
                                <?php $i++; endforeach; ?>

                        </div>
                    </div>
                    <?php else: ?>
                        <p><?=lang('Config.invalid_theme_config_contact_admin')?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
echo view($config->view.'\system\attach\_vGallery');
echo view($config->view.'\system\attach\_vConfigAttach');
echo view($config->view.'\system\attach\_vImgSelect');
echo view($config->view.'\system\config\themeOption\_slider_selector');
?>
<?= $this->endSection() ?>
<?php echo $this->section('pageScripts') ?>
<script src="<?= base_url($config->scriptsPath)?>/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script src="<?= base_url($config->scriptsPath)?>/acp/sys/vConfigAttach.js"></script>
<script type="text/javascript">
    const themeApp = Vue.createApp({});
    themeApp.component('config-img', vConfigAttach);
    themeApp.component('vgallery', gallery);
    themeApp.component('vimg-reivew', imgGalleryReview);
    themeApp.component('vgallery-img', galleryImg);
    themeApp.component('vimg-infor', imgInfor);
    themeApp.component('vfileReivew', fileReview);
    themeApp.component('vimg-select', vFileSelector);
    themeApp.component('vfile-selector', vSliderSelector);
    themeApp.component('vfile-preivew', filePreview);
    themeApp.mount('#themeApp');

    $(function () {
        $("input[data-bootstrap-switch]").each(function(){
          $(this).bootstrapSwitch('state', $(this).prop('checked'));
        })
    });
</script>

<?= $this->endSection() ?>