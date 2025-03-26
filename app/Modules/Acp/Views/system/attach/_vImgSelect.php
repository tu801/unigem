<?php
/**
 * @author tmtuan
 * created Date: 20-Sep-20
 */
?>

<script type="text/x-template" id="tpl-vImgSelect">
    <div class="card card-primary">
        <div class="card-body">
            <div class="form-group row">
                <div class="custom-file">
                    <input type="file" :name="file_name" id="tmt-attach" class="custom-file-input" ref="image" v-on:change="handleFileUpload()"
                           multiple >
                    <label class="custom-file-label" for="tmt-attach"><?=lang('Acp.select_image')?></label>
                </div>
                <span class="help-block" v-if="htmlnote">{{htmlnote}}</span>

                <div class="progress" style="display: none">
                    <div class="progress-bar"></div>
                </div>
            </div>

            <vfile-reivew v-if="demofile" :error="uploaderror" :images="files" @remove-file-upload="unsetFile" ></vfile-reivew>
        </div>

        <div class="card-footer" v-if="files.length">
            <input type="hidden" id="cstoken" value="<?= csrf_hash() ?>">
            <input type="hidden" id="csname" value="<?= csrf_token() ?>">
            <button type="button" @click.prevent.stop="submitFile" class="btn btn-primary float-right" >Upload</button>
        </div>
    </div>
</script>

<script type="text/x-template" id="vFilePreReview-template">
    <div class="tmt-att-files" v-for="(img, index) in images">
        <img  class="img-thumbnail" :src=imageUrl(img)>
        <a class="btn btn-danger btn-xs tmt-close" title="Remove" @click.prevent="removeAttach(index)" href="#" ><i class="fa fa-times"></i> </a>
    </div>
</script>

<script src="<?= base_url($config->scriptsPath)?>/acp/components/vFilesSelect.js"></script>