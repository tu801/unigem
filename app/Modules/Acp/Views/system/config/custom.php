<?php
echo $this->extend($config->viewLayout);
echo $this->section('content');
$cfGroups = $config->cfGroup;
//echo "<pre>";print_r($cfData);exit;
?>
<div id="myVue">
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <?php
                $total = count($cfGroups);
                $i = 0;
                foreach ($cfGroups as $key=>$val) :
                    $i++;
                    $title = $val['title'];
                    $url = ( $val['type'] === 'default' ) ? base_url("acp/config?group={$key}") : base_url("acp/config/custom/{$val['type']}");
                    ?>
                    <a class="badge badge-primary" href="<?=$url?>"><?=$title?></a>
                    <?php if ( $i < $total )  echo " | " ?>
                <?php endforeach; ?>
            </h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-5 col-sm-3">
                    <div class="nav flex-column nav-tabs h-100" 
                        id="customcf-tab" role="tablist"  aria-orientation="vertical">
                        <?php
                        $i = 0;
                        foreach($cfData as $key=>$val) :
                        ?>
                        <a class="nav-link <?=($i==0)?'active':''?>" id="customcf-<?=$key?>-tab" data-toggle="pill" href="#customcf-<?=$key?>" role="tab"
                           aria-controls="customcf-<?=$key?>" aria-selected="<?=($i==0)?'true':'false'?>">
                            <?=$val['title']['data']??''?></a>
                        <?php $i++; endforeach; ?>
                    </div>
                </div>
                <div class="col-7 col-sm-9" id="configApp">
                    <div class="tab-content" id="vert-tabs-tabContent">
                        <?php $i = 0;
                        foreach($cfData as $key=>$val) : ?>
                            <div class="tab-pane text-left fade show <?=($i==0)?'active':''?>" 
                                id="customcf-<?=$key?>" role="tabpanel" 
                                aria-labelledby="customcf-<?=$key?>-tab">
                                <form  role="form" method="post" class="form-horizontal" >
                                    <?php foreach($val as $kdata=>$data): ?>
                                        <div class="form-group row clearfix">
                                            <label  class="col-sm-2 col-form-label">
                                                <?=$data['label']??$data['desc']?>
                                            </label>
                                            <div class="col-sm-10">
                                                <?php if($data['type'] == 'input'):?>
                                                    <input type="text" name="<?=$kdata?>"  placeholder="<?=$kdata?>"
                                                        value="<?=$data['data']?>" class="form-control">
                                                <?php elseif ($data['type'] == 'checkbox'): ?>
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" id="chk-<?="{$key}_{$kdata}"?>" name="status"
                                                            <?=($data['data'])?'checked':''?> class="form-control">
                                                            <label for="chk-<?="{$key}_{$kdata}"?>"> Hiển Thị </label>
                                                    </div>

                                                <?php elseif ($data['type'] == 'select'): ?>    
                                                    <?php if(isset($page)) {?>
                                                        <div class="form-group">
                                                            <select class="form-control" name=<?="{$kdata}"?>>
                                                            <?php foreach($page as $item) { ?>
                                                                <option value="<?=$item->id?>" 
                                                                    <?=(($item->id == $data['data'])?'selected':'')?>>
                                                                        <?=$item->title?>
                                                                </option>     
                                                            <?php } ?>
                                                            </select>
                                                        </div>

                                                    <?php } ?>
                                                <?php elseif ($data['type'] == 'text'): ?> 
                                                    <div class="info-container">
                                                        <?php if( empty($data['data']) ) { ?>
                                                            <img width="100%" 
                                                                src="<?=base_url( 'public/themes/'.$config->sys['themes_name'])?>/guide/<?=$key?>.png" />
                                                        <?php } else {  ?>
                                                        <code class="info">
                                                            <?=$data['data']??''?>
                                                        </code>
                                                        <?php } ?>
                                                    </div>
                                                <?php elseif ($data['type'] == 'array'): ?>
                                                    <div class="arData">
                                                        <div id="<?=$kdata?>" class="form-group">
                                                            <?php if($data['data']) { ?>
                                                                <?php foreach($data['data'] as $karData=>$arData){  ?>
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                Title <input class="form-control" name="<?=$kdata?>[<?=$karData?>][title]" value="<?=$arData['title']?>">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                Icon
                                                                                <select class="form-control" name="<?=$kdata?>[<?=$karData?>][icon]">
                                                                                    <option value="ti-settings" <?=(($arData['icon'] == 'ti-settings')?"selected":"")?>>ti-settings</option> 
                                                                                    <option value="fa fa-sliders" <?=(($arData['icon'] == 'fa fa-sliders')?"selected":"")?>>fa fa-sliders</option>
                                                                                    <option value="fa fa-lightbulb-o" <?=(($arData['icon'] == 'fa fa-lightbulb-o')?"selected":"")?>>fa fa-lightbulb-o</option>  
                                                                                    <option value="lnr lnr-layers" <?=(($arData['icon'] == 'lnr lnr-layers')?"selected":"")?>>lnr lnr-layers</option> 
                                                                                    <option value="fa fa-hand-pointer-o" <?=(($arData['icon'] == 'fa fa-hand-pointer-o')?"selected":"")?>>fa fa-hand-pointer-o</option>
                                                                                    <option value="ti-layout-media-center-alt" <?=(($arData['icon'] == 'ti-layout-media-center-alt')?"selected":"")?>>ti-layout-media-center-alt</option> 
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                Content <textarea class="form-control" name="<?=$kdata?>[<?=$karData?>][content]"><?=$arData['content']?></textarea>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                Link <input class="form-control" name="<?=$kdata?>[<?=$karData?>][link]" value="<?=($arData['link']??'')?>">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                Title <input class="form-control" name="<?=$kdata?>[0][title]">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                Icon
                                                                                <select class="form-control" name="<?=$kdata?>[0][icon]">
                                                                                <option value="ti-settings">ti-settings</option> 
                                                                                <option value="fa fa-sliders">fa fa-sliders</option>
                                                                                <option value="fa fa-lightbulb-o">fa fa-lightbulb-o</option>  
                                                                                <option value="lnr lnr-layers">lnr lnr-layers</option> 
                                                                                <option value="fa fa-hand-pointer-o">fa fa-hand-pointer-o</option>
                                                                                <option value="ti-layout-media-center-alt">ti-layout-media-center-alt</option> 
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                Content 
                                                                                <textarea class="form-control" name="<?=$kdata?>[0][content]"></textarea>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                Link <input class="form-control" name="<?=$kdata?>[0][link]">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </div>

                                                        <div class="row form-group">
                                                            <button id="<?=$kdata?>"  class="btn btn-info btn_plus_col">Thêm Cột</button>
                                                        </div>
                                                    </div>

                                                
                                                <?php elseif ( $data['type'] == 'upload' ):
                                                    $attType = 0;
                                                    if ( isset($data['storage_type']) ) {
                                                        if ( $data['storage_type'] == 'single' ) $attType = 'single';
                                                        else if ($data['storage_type'] == 'gallery' ) $attType = 'multiple';
                                                    }
                                                    $inputName = "{$key}_{$kdata}";
                                                    if( isset($data['config'])) {
                                                        $rendertype = 'config';
                                                        $configData = json_encode($data['config']);
//                                                        $imgData = json_encode($data['data']);
                                                        $imgData = '';
                                                        if (!empty($data['data']) ) {
                                                            $arrImgData = [];
                                                            foreach($data['data'] as $kimg=>$img){
                                                                array_push( $arrImgData,  $img['id']);
                                                            }
                                                            $imgData = is_array($arrImgData)  
                                                                ? implode(';', $arrImgData) 
                                                                : $arrImgData;
                                                            $oldData = json_encode($data['data']);
                                                        } else {
                                                            $oldData = '';
                                                        }
                                                        
                                                    } else {
                                                        $rendertype = '';
                                                        $configData = '';
                                                        $imgData = ''; 
                                                        if (!empty($data['data']) ) {
                                                            $imgData = is_array($data['data'])  
                                                                ? implode(';', $data['data']) 
                                                                : $data['data'];
                                                        }
                                                    } //echo '<pre>'; print_r($dataOld); //print_r(json_decode($configData));
                                                    ?>

                                                    <config-img img-desc="<?=$data['desc']??''?>" 
                                                        select-img-type="<?=$attType?>" old-data='<?=$oldData?>'
                                                        render-type="<?=$rendertype?>" config-data='<?=$configData?>'
                                                        input-name="<?=$inputName?>" return-img="id" img-data='<?=$imgData?>'>
                                                    </config-img>

                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>

                                    <div class="form-group">
                                        <button class="btn btn-primary" name="<?=$key?>" type="submit"><?=lang('Acp.save')?></button>
                                        <button class="btn btn-danger" name="resetCustom" value="<?=$key?>" type="submit"><?=lang('Acp.reset')?></button>
                                    </div>
                                </form>
                            </div>
                        <?php $i++; endforeach; ?>
                    </div>
                </div>

            </div>
        </div>
        <!-- /.card -->
    </div>
</div>

<?php
echo view($config->view.'\system\attach\_modalGallery');
echo view($config->view.'\system\attach\_vConfigAttach');
echo $this->endSection();
?>

<?php echo $this->section('pageScripts') ?>

<script src="<?= base_url($config->scriptsPath)?>/plugins/vuejs-dialog/vuejs-dialog.min.js"></script>

<script>
    $(document).ready(function () {
        $('.arData').on('click','.btn_plus_col',function(){
           
            var el = $(this).attr('id');
            var num = $('#'+ el + ' .card').length;
            var html_expertise = 
                    '<div class="card">'
                    +   '<div class="card-body">'
                    +   '        <div class="row">'
                    +   '            <div class="col-md-6">'
                    +   '                Title <input class="form-control" name="'+el+'['+num+'][title]">'
                    +   '            </div>'
                    +   '            <div class="col-md-6">'
                    +   '                Icon'
                    +   '                <select class="form-control" name="'+el+'['+num+'][icon]">'
                    +   '                <option value="ti-settings">ti-settings</option> '
                    +   '                <option value="fa fa-sliders">fa fa-sliders</option>'
                    +   '                <option value="fa fa-lightbulb-o">fa fa-lightbulb-o</option> ' 
                    +   '                <option value="lnr lnr-layers">lnr lnr-layers</option> '
                    +   '                <option value="fa fa-hand-pointer-o">fa fa-hand-pointer-o</option>'
                    +   '                <option value="ti-layout-media-center-alt">ti-layout-media-center-alt</option> '
                    +   '                </select>'
                    +   '            </div>'
                    +   '            <div class="col-md-12">'
                    +   '               Link <input class="form-control" name="'+el+'['+num+'][link]">'
                    +   '            </div>'
                    +   '            <div class="col-md-12">'
                    +   '                Content <textarea class="form-control" name="'+el+'['+num+'][content]"></textarea>'
                    +   '           </div>'
                    +   '        </div>'
                    +   '    </div>'
                    +   '</div>';
            $('#'+el).append(html_expertise);
            return false;
        });
    });
</script>

<?= $this->endSection() ?>