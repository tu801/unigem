<?php
/**
 * @author tmtuan
 * created Date: 02-Sep-20
 */
?>

<!--vuejs add new group -->
<script type="text/x-template" id="vaddgroup-tmpl">
    <transition name="modal">
        <div class="modal fade modal-attach" id="vAddGroupModal" ref="modal" role="dialog" >
            <div class="modal-dialog" style="width: 50%">
                <div class="modal-content" >

                    <div class="modal-header">
                        <h4 class="modal-title">{{ header }} </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="$emit('close')">
                            <span aria-hidden="true">&times;</span></button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label ><?=lang('User.userGroup_name')?></label>
                            <input type="text" name="name" class="form-control" v-model="userg.name" placeholder="<?=lang('User.userGroup_name')?>" >
                        </div>
                        <div class="form-group">
                            <label><?=lang('User.userGroup_description')?></label>
                            <textarea class="form-control" rows="3" placeholder="Nhập mô tả cho nhóm" v-model="userg.description" ></textarea>
                        </div>
                        <div class="form-group">
                            <button type="button" @click.prevent="onSubmit" class="btn btn-primary float-right">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</script>
