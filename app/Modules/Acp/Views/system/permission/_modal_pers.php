<?php

/**
 * Created by: brianha289
 */

?>
<script type="text/x-template" id="vpers-modal-tmpl">
    <transition name="modal">
    <div class="modal fade modal-attach" id="vPersModal" ref="persModal"  @click.self="closeModal">
        <div class="modal-dialog" style="width: 50%">
            <div class="modal-content">
                <input type="hidden" id="cstoken" value="<?= csrf_hash() ?>">
                <input type="hidden" id="csname" value="<?= csrf_token() ?>">
                <div class="modal-header">
                    <h4 class="modal-title">{{ header }} </h4>
                    <button type="button" class="close" data-dismiss="modal" @click="closeModal">
                        <span aria-hidden="true">&times;</span></button>
                </div>

                <div class="modal-body">
                    <div class="form-group row text-danger" v-if="errors.length">
                        <ul>
                            <li v-for="error in errors">{{ error }}</li>
                        </ul>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tên nhóm</label>
                        <div class="col-sm-9 pt-2">
                            <code v-if="group_main_info != null"> {{ group_main_info.title }} </code>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="postInputTitle" class="col-sm-3 col-form-label">Mô-đun <span class="text-danger">*</span></label>
                        <div v-if="group_data_options != undefined" class="col-sm-9">
                            <select class="form-control" v-model="permission.name">
                                <option v-for="(item, key) in group_data_options" :value="key">{{item}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="postInputTitle" class="col-sm-3 col-form-label">Chỉ định cho URL có<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="name" class="form-control" placeholder="VD: index, edit hoặc add ..." v-model="permission.action">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="postInputTitle" class="col-sm-3 col-form-label">Mô tả<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="description" class="form-control" placeholder="Mô tả chi tiết" v-model="permission.description">
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="button" @click.prevent="onSubmit" class="btn btn-primary float-right">Nộp</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </transition>    
</script>