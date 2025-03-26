<?php
/**
 * @author tmtuan
 * created Date: 31-Mar-21
 */
?>
<script type="text/x-template" id="vpost-slug">
    <div class='' >
        <div class="form-group ">
            <label>{{ label }}</label>
            <div class="row" v-if="showSlug">
                <div class="col-sm-11" id="post_slug_frm">
                    <code>{{ renderPostUrl() }}</code>
                </div>
                <div class="col-sm-1" >
                    <a class="btn btn-sm btn-danger" @click.prevent="edit()" href="#"
                       postID="<?=$itemData->id?>" postSlug="<?=$itemData->slug?>" >Edit</a>
                </div>
            </div>
            <div class="row" v-else>
                <div class="col-lg-10 col-sm-9">
                    <code style="width:30%"><?=base_url()?>/</code><input style="width:70%" type="text" v-model="newSlug">
                </div>
                <div class="col-lg-2 col-sm-3" >
                    <button class="btn btn-sm btn-success mr-1" type="button" @click="saveSlug" >Save</button>
                    <button class="btn btn-sm btn-danger" type="button" @click="cancelSlug" >Cancel</button>
                </div>
            </div>
        </div>
    </div>
</script>

<script src="<?= base_url($config->scriptsPath)?>/acp/blog/vPostSlug.js"></script>
