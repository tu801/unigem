<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title"><?=lang('User.user_meta_title')?></h3>
    </div>

    <div class="card-body">
        <?php //print_r($userData->meta);exit;
        if ( !empty($config->user_meta)) {
            foreach ($config->user_meta as $metaKey=>$val) {
                $metaVal = ( isset($userData->meta[$metaKey]) && $userData->meta[$metaKey]!== '' ) ? $userData->meta[$metaKey] : old($metaKey);
                if ( ! $metaVal ) $metaVal = '';
            ?>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label"><?=$val['title']?></label>
            <div class="col-sm-10">
                <?php
                if ( $val['input'] === 'text' ) {
                    $htmlData = array(
                        'name'	=> $metaKey,
                        'id'    => $metaKey,
                        'class' => 'form-control'
                    );
                    echo form_input($htmlData, $metaVal);
                }

                if ( $val['input'] == 'textarea') {
                    $htmlData = array(
                        'name'  => $metaKey,
                        'id'    => $metaKey,
                        'class' => 'form-control'
                    );
                    echo form_textarea($htmlData, $metaVal);
                }

                if ( $val['input'] == 'option') {
                    $htmlData = ['class' => 'form-control'];
                    echo form_dropdown($metaKey, $val['value'], $metaVal, $htmlData);
                }

                if ( $val['input'] == 'date') {
                ?>
                    <div class="input-group date">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                        <input id="cus_datepicker" name="<?= $metaKey ?>" class="form-control"  type="text" value="<?= $metaVal ?>" data-date-format="dd-mm-yyyy" >
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
            <?php
            }
        }

        ?>
    </div>
</div>