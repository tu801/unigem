<?php
/**
 * Created by: tmtuan
 * Email: tmtuan801@gmail.com
 * Date: 10-Feb-20
 * Time: 7:18 PM
 */

?>
<script type="text/x-template" id="vgallery-template">
    <div>
        <transition name="modal">
            <div class="modal fade modal-attach" id="vGalleryModal" >
                <div class="modal-dialog" style="width: 95%">
                    <div class="modal-content" >

                        <div class="modal-header">
                            <h4 class="modal-title">Quản lý Ảnh</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="$emit('close')">
                                <span aria-hidden="true">&times;</span></button>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-8 img-list" id="tmt-list-attach">
                                    <div class="" style="cursor: pointer;">
                                        <div class='row tmt-attach' id='tmtallAttItemGroup'>
                                            <div v-if="loading" class="loader-wrapper" >
                                                <div class="loader"></div>
                                            </div>

                                            <!--loop the image in vue object-->
                                            <vgallery-img v-for="(attItem, index) in allUploadData" :key="attItem.id" :imgindex="index" @show-image-info="showInfo"
                                                        multi="0"  @dell-img="removeImg" :attItem="attItem"></vgallery-img>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="card card-primary">

                                        <div class="card-header with-border">
                                            <h3 class="card-title">Tải ảnh lên</h3>
                                        </div>

                                        <div class="card-body">
                                            <div class="form-group">
                                                <label class="control-label">Tiêu đề</label>
                                                <input type="text" name="file_title" id="file_title" v-model="file.title" class="form-control" placeholder="Tiêu đề" >
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-file">
                                                    <input type="file" name="images[]" id="tmt-attach" class="custom-file-input" ref="images" v-on:change="handleFileUpload()" multiple >
                                                    <label class="custom-file-label" for="tmt-attach">Chọn Ảnh</label>
                                                </div>
                                                <p class="help-block">Chọn ảnh để upload. File ảnh phải có kích thước nhỏ hơn 2Mb</p>
                                            </div>
                                            <div class="form-group row">
                                                <div class="progress" style="display: none">
                                                    <div class="progress-bar"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <vfile-reivew v-if="demofile" :error="uploaderror" :images="files" @remove-file-upload="unsetFile" ></vfile-reivew>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button type="button" @click.prevent.stop="submitFile" class="btn btn-primary float-right">Upload</button>
                                        </div>

                                    </div>

                                    <!--show img info-->
                                    <vimg-infor v-if="showImgInfo" :showtype="attType" :imgData="vimgInfo" :cardstt="cardimgstt"
                                                @hide-card-info="removeCard" @set-selected-img="setSelectedFile" ></vimg-infor>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </transition>
    </div>
</script>

<script type="text/x-template" id="vgalleryImg-template">
    <div class="col-md-2 text-center" :id="imgItemClass" :key="attItem.id" >
        <input v-if="multi" type="checkbox" class="form-check-input" @change="checkImageInfo($event, attItem)" style="margin-left: 0px;" >
        <img :src="vImgSource" @click="showImageInfo(attItem)" class="img-fluid img-thumbnail" style="padding-bottom: 5px;">
        <div class="tmt-attact-body" >
            <a :href="vImgDeleteUrl" class="btn btn-danger btn-xs" v-on:click.prevent="deleteImg(vImgDeleteUrl)" title="Delete Attach">
                <i class="fa fa-trash"></i> Xóa file</a>
        </div>
    </div>
</script>

<script type="text/x-template" id="vImgInfor-template">
    <div class="card card-primary" id="tmt-att-info" v-if="cardstt == 1" >

        <div class="card-header with-border">
            <h3 class="card-title">Chọn File(s)</h3>
            <div class="card-tools">

                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>

                <button type="button" class="btn btn-tool" @click="hideCard" ><i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <!-- /.box-header -->
        <div v-if="showtype === 'multiple'" class="card-body">
            <div  class="row">
                <div class="col-sm-2" v-for="item in imgData" :key="item.id">
                    <img :src="renderImgUrl(item)" class="img-fluid" >
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-primary float-right" @click.prevent="selectImg" >Select Image</button>
            </div>
        </div>

        <div v-else class="card-body">
            <form  class="form-horizontal">
                <div class="form-group">
                    <label class="control-label">URL Ảnh</label>
                    <textarea type="text" v-model="imgurl" rows="3" class="form-control" id="tmt-att-url" placeholder="URL Ảnh" ></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">ALT Ảnh</label>
                    <input type="text" v-model="imgalt" class="form-control" id="tmt-att-alt" placeholder="ALT Ảnh" >
                </div>
                <div class="form-group">
                    <button class="btn btn-primary float-right" @click.prevent="selectImg" >Select Image</button>
                </div>
            </form>
        </div>
    </div>
</script>


<script type="text/x-template" id="vImageReview-template">
    <div class="row text-center" >
        <div class="tmt-att-single-files"  v-if="attType == 'single'">
            <img class="img-thumbnail" :src="imageUrl(images)">
            <a class="btn btn-danger btn-xs tmt-close" title="Remove" @click.prevent="removeAttach(images[0])" href="#" ><i class="fa fa-times"></i> </a>
        </div>
        <div class="col-md-12" v-if="attType == 'multiple'" >
            <div v-if="images" class="row">
                <div class="tmt-att-files" v-for="(img, index) in images">
                    <img  class="img-thumbnail" :src=imageUrl(img)>
                    <a class="btn btn-danger btn-xs tmt-close" title="Remove" @click.prevent="removeAttach(img)" href="#" ><i class="fa fa-times"></i> </a>
                </div>
            </div>

        </div>

    </div>
</script>

<script type="text/x-template" id="vFileReview-template">
    <div class="row text-center" >
        <div v-if="images" class="row">
            <div class="tmt-att-files" v-for="(img, index) in images">
                <img  class="img-thumbnail" :src=imageUrl(img)>
                <a class="btn btn-danger btn-xs tmt-close" title="Remove" @click.prevent="removeAttach(index)" href="#" ><i class="fa fa-times"></i> </a>
            </div>
        </div>
    </div>
</script>

<script src="<?= base_url($config->scriptsPath)?>/acp/components/vModalGallery.js"></script>