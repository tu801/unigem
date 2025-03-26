<?php
/**
 * @author tmtuan
 * created Date: 30-Mar-21
 */

?>
<script type="text/x-template" id="vfeature-image">
    <div class='vFeatureImg' >
        <div class="form-group ">
            <label>{{ imgTitle }}</label>
            <div class="input-group">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="image" ref="image" id="imageInpt" v-on:change="handleFileSelected()" >
                    <label class="custom-file-label" for="imageInpt">Chọn Ảnh</label>
                </div>
            </div>
            <small class="form-text text-muted">{{ imgDesc }}</small>
        </div>

        <div class="tmt-att-files mt-2" v-if="demofile" style="display: flex">
            <img class="img-thumbnail" :src="demofile">
            <a class="btn btn-danger btn-xs tmt-close" title="Remove" style="position: absolute" @click.prevent="removeAttach()" href="#" ><i class="fa fa-times"></i> </a>
        </div>
    </div>
</script>

<script src="<?= base_url($config->scriptsPath)?>/acp/components/vFeatureImage.js"></script>

