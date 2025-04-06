<?php
/**
 * Created by: tmtuan
 * Email: tmtuan801@gmail.com
 * Date: 10-Feb-20
 * Time: 7:18 PM
 */

?>
<!--vuejs post tags -->
<script type="text/x-template" id="vpostTags-template">
    <div class="col-sm-12">

        <vtagcloud-item v-if="tagitem" :tagdata="tagitem" @remove-tag-cloud="deleteTag" ></vtagcloud-item>

        <div class="input-group">
            <input class="form-control" type="text" v-model="searchText" @keyup="onKeyUp()" >
            <input type="hidden" id="cstoken" value="<?= csrf_hash() ?>" >
            <input type="hidden" id="csname" value="<?= csrf_token() ?>" >
            <span class="input-group-append">
                <button class="btn btn-primary" type="button" @click="addTag()"><i class="fa fa-plus "></i> Add Tags</button>
            </span>
        </div>

        <div class="autocomplete list-group" style="width:100%;" id="tag_list">
            <vauto-list  :itemdata="tagslist" @set-tag-cloud="updateTag" @clear-tag-input="clearTagInput()" ></vauto-list>
        </div>
    </div>
</script>

<script type="text/x-template" id="vautocomplete-item">
    <div class='autocomplete-items' id='autocomplete-list'>
        <div class='list-group-item list-group-item-action' v-for="tag in itemdata" :key="tag.id" >
            <a href='#' @click.prevent="setTag(tag)" class='tag-item'>{{ tag.title }}</a>
        </div>
    </div>
</script>

<script type="text/x-template" id="vtag-cloud-item">
    <ul class="list-inline" id="tags-cloud">
        <li class="list-inline-item" v-for="tag in tagdata" :key="tag.id">
            <span class="badge badge-primary">{{ tag.title }}
                <a class="tag-item-remove ml-2 text-light" href="#"  @click.prevent="removeTag(tag)" >x</a>
            </span>
            <input type='hidden' class='tmt_inp_tagcloud' name='tagcloud[]' :value='setTagVal(tag)' >
        </li>
    </ul>
</script>

<script src="<?= base_url($config->scriptsPath)?>/acp/blog/vPostTags.js"></script>