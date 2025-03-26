<?php
/**
 * @author tmtuan
 * created Date: 9/14/2021
 * project: woh-tuyendung
 */
?>
<script type="text/x-template" id="vrecord-modal-tmpl">
    <transition name="modal">
        <div class="modal fade modal-attach" id="vRecordsModal" ref="recordsModal"  @click.self="closeModal">
            <div class="modal-dialog" style="width: 40%">
                <div class="modal-content">
                    <input type="hidden" id="cstoken" value="<?= csrf_hash() ?>">
                    <input type="hidden" id="csname" value="<?= csrf_token() ?>">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ header }} </h4>
                        <button type="button" class="close" data-dismiss="modal" @click="closeModal">
                            <span aria-hidden="true">&times;</span></button>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-danger alert-dismissible" v-if="errors.length">
                            <h5><i class="icon fas fa-ban"></i></h5>
                            <ul class = "list-unstyled">
                                <li v-for="error in errors">{{ error }}</li>
                            </ul>
                        </div>


                        <div class="form-group row">
                            <label for="postInputTitle" class="col-sm-3 col-form-label"><?=lang('Acp.record_type_name')?><span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="name" class="form-control"  v-model="record_type.name">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="postInputTitle" class="col-sm-3 col-form-label"><?=lang('Acp.developer_name')?><span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="description" class="form-control" v-model="record_type.developer_name">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="postInputTitle" class="col-sm-3 col-form-label"><?=lang('Acp.object_type')?><span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="description" class="form-control"  v-model="record_type.object_type">
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="button" @click.prevent="onSubmit" class="btn btn-primary float-right">Lưu</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</script>
