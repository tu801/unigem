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
                            foreach($cfData as $val) :
                                ?>
                                <a class="nav-link <?=($i==0)?'active':''?>" id="customcf-<?=$val->id?>-tab" data-toggle="pill" href="#customcf-<?=$val->id?>" role="tab"
                                   aria-controls="customcf-<?=$val->id?>" aria-selected="<?=($i==0)?'true':'false'?>"><?=$val->title?></a>
                                <?php $i++; endforeach; ?>
                        </div>
                    </div>
                    <div class="col-sm-9" id="themeApp">
                        <div class="tab-content" id="vert-tabs-tabContent">
                            <?php
                            $i = 0;
                            foreach($cfData as $val) :
                                $itemData = json_decode($val->value);
                                ?>
                                <div class="tab-pane text-left fade show <?=($i==0)?'active':''?>" id="customcf-<?=$val->id?>" role="tabpanel" aria-labelledby="customcf-<?=$val->id?>-tab">
                                        <?php
                                        echo form_open();
                                        foreach ($itemData as $key => $item) : ?>
                                        <div class="form-group">
                                            <label for="item<?=$key?>" class="col-form-label"><?=$item->title?></label>
                                            <?php
                                            $htmlData = array(
                                                'name'	=> $key,
                                                'id'    => 'item_'.$key,
                                                'class' => 'form-control'
                                            );
                                             //print_r($item);
                                            if ( $item->input === 'image' ) :
                                                $valData = json_decode($item->value);                                             ?>
                                            <config-img img-desc="<?=$item->desc??''?>" select-img-type="1" input-name="<?=$key?>" return-img="id"
                                                        img-data="<?=$valData->id??0?>" ></config-img>
                                            <?php else:
                                            if ( $item->input === 'text' ) {
                                                echo form_input($htmlData, $item->value);
                                            }
                                            if ( $item->input === 'textarea' ) {
                                                $htmlData['rows'] = 4;
                                                echo form_textarea($htmlData, $item->value);
                                            }
                                            if ( isset($item->desc) && !empty($item->desc) ) echo "<span class=\"help-block\">{$item->desc}</span>";

                                            endif; ?>
                                        </div>
                                        <?php endforeach; ?>

                                        <div class="form-group">
                                            <input type="hidden" name="theme_option" value="<?=$val->id?>" >
                                            <button type="submit" class="btn btn-success"><?=lang('Acp.save')?></button>
                                        </div>
                                    </form>
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
?>
<?= $this->endSection() ?>
<?php echo $this->section('pageScripts') ?>
    <script src="<?= base_url($config->scriptsPath)?>/acp/sys/vConfigAttach.js"></script>
<script type="text/javascript">

    const themeApp = Vue.createApp({});
    themeApp.component('config-img', vConfigAttach);
    themeApp.component('vgallery', gallery);
    themeApp.component('vimg-reivew', imgGalleryReview);
    themeApp.component('vgallery-img', galleryImg);
    themeApp.component('vimg-infor', imgInfor);
    themeApp.component('vfileReivew', fileReview);
    themeApp.mount('#themeApp');
</script>
<?= $this->endSection() ?>