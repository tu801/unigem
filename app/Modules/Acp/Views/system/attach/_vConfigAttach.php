<?php
/**
 * @author tmtuan
 * created Date: 09-Apr-21
 * cho phép user chọn ảnh và add ảnh vào 1 field trong phần config
 * Usage:
 * <config-img img-desc="test" select-img-type="1"  ></config-img>
 *
 */
?>
<script type="text/x-template" id="vConfig-img-component">
    <div>
        <div v-if="selectedImg.length > 0">

            <div v-if="renderType != 'config'" class="card card-primary" style="width:100%">
                <div class="card-body">
                    <vimg-reivew v-if="showImg" :images="selectedImg"
                        :att-type="selectImgType" @remove-file="removeFile" ></vimg-reivew>
                        <input type="hidden" :name="inputName" :value="attImgs">
                </div>
            </div>
            <div v-else>
                <div v-for="(image, indexImage) in selectedImg" :key="indexImage"
                    class="card card-primary" style="width:100%"  >

                    <div class="card-header">
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" @click.prevent="removeFile(image)">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                
                                <img :src="renderImgUrl(image)" width="100%" />
                                
                                <input type="hidden"
                                    :name="inputName + '[' + indexImage + '][image]'"
                                    :value="imgUrl(image)">

                                <input type="hidden"
                                    :name="inputName + '[' + indexImage + '][id]'"
                                    :value="image.id">
                            </div>
                            <div class="col-sm-8">
                                <div v-for="(item, index) in JSON.parse(configData)"
                                    :key="index" :class="[item.class]">
                                        <div class="form-group">
                                            {{ item.label }} {{  }}
                                            <input class="form-control" type=""
                                                :name="inputName + '[' + indexImage + ']['+item.name + ']'"
                                                v-model="image[item.name]"
                                            >
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="card card-primary " style="width:100%"  >
            <div class="card-body pt-3 pb-2">
                <div class="row col-12">
                    <button @click.prevent="showGallery" class="btn btn-primary mb-2" >Select Images</button>
                    <input v-if="selectedImg.length === 0" type="hidden" name="removeAttachMeta" :value="imgAttachMetaId">
                </div>

                <div class="row col-12" v-if="imgDesc">
                    <code>{{ imgDesc }}</code>
                </div>
                
                <vgallery v-if="showModal" @close="showModal = false" 
                    :selecteditem="selectedImg" @show-file="setAttachFiles" @remove-file="deleteUploadFile" 
                    :att-type="selectImgType" @close-modal="hideGallery" ></vgallery>
                    
            </div>
        </div>
    </div>
</script>

<script src="<?= base_url($config->scriptsPath)?>/acp/components/attachFiles.js"></script>