<?php
echo $this->extend($config->viewLayout);
echo $this->section('content') ?>

<script src="<?= base_url($config->scriptsPath) ?>/acp/blog/postWordCount.js"></script>
<div class="row" id="categoryApp">
    <div class="col-12">
        <!-- form start -->
        <form id="<?= $module ?>Form" method="post" class="form-horizontal" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="card card-primary" >
                <div class="card-header">
                    <h3 class="card-title"><?php echo lang('Category.edit_title') ?></h3>
                </div>

                <div class="card-body">

                    <div class="form-group">
                        <label for="categoryTitle"><?= lang('Category.title') ?></label>
                        <input type="text" class="form-control <?= session('errors.title') ? 'is-invalid' : '' ?>" id="categoryTitle" name="title" value="<?= $data->title ?>" placeholder="<?= lang('Category.title') ?>">
                        <div id="updateSlugOption" style="display: none; margin-top: 10px;" class="form-check">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="onChangeSlug" name="onChangeSlug">
                                <label for="onChangeSlug" class="custom-control-label"><?=lang('Category.change_slug')?></label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <category-slug full-slug="<?= base_url(route_to('category_page', $data->slug, $data->id)) ?>" slug="<?= $data->slug ?>" label="<?= lang('Category.slug') ?>" category-id="<?= $data->id ?>" token="<?= csrf_hash() ?>" tkname="<?= csrf_token() ?>"></category-slug>
                    </div>

                    <div class="form-group">
                        <label><?= lang('Category.parent_cat') ?></label>
                        <select class="form-control" name="parent_id">
                            <?php if ($list_parent) {
                                $opts = "<option value='0'>None</option>";
                                foreach ($list_parent as $parent) {
                                    $sel = @$data->parent_id == $parent->id ? 'selected' : '';
                                    $opts .= "<option $sel value='{$parent->id}'>{$parent->title}</option>";
                                }
                                echo $opts;
                            } else echo "<option value='0'>Chọn danh mục cha</option>";
                            ?>
                        </select>
                    </div>

                    <div class="form-group ">
                        <label><?= lang('Category.cat_status') ?></label>
                        <select class="form-control" name="cat_status">
                            <?php foreach ($config->cmsStatus['status'] as $key => $title) : ?>
                                <option <?= $data->cat_status == $key ? 'selected' : '' ?> value='<?= $key ?>'><?= $title ?></option>
                            <?php endforeach;    ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><?= lang('Category.description') ?></label>
                        <textarea class="form-control" rows="3" name="description" placeholder="Nhập mô tả"><?= $data->description ?></textarea>
                    </div>

                    <div class="">
                        <p class="h5">
                        <div class="d-flex justify-content-between align-items-center">
                            <b><?= lang('Acp.search_engine_optimize'); ?></b>
                            <a class="btn btn-link" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                <?= lang('Acp.edit_seo_meta'); ?>
                            </a>
                        </div>
                        <div style="margin-top:5px;margin-bottom:5px;height:1px;width:100%;border-top:1px solid #ccc;"></div>
                        <br />
                        <div>
                            <span style="color:#1a0dab;"><?= $data->title ?></span>
                            <div>
                                <p style="color:#006621;"><?= base_url($data->slug) ?></p>
                            </div>
                            <span><?= $data->description ?></span>
                        </div>
                        </p>
                    </div>
                    <div class="collapse" id="collapseExample">
                        <div class="card-body">
                            <div class="form-group ">
                                <label><?= lang('Post.meta_title') ?></label> - <?= lang('Acp.meta_desc_left'); ?> <span id="title_word_left">70</span>
                                <input id="title_word_count" type="text" class="form-control" name="seo_title" value="<?= $data->seo_meta->seo_title ?? '' ?>">
                            </div>

                            <div class="form-group ">
                                <label><?= lang('Post.meta_keyword') ?></label>
                                <textarea class="form-control" name="seo_keyword"><?= $data->seo_meta->seo_keyword ?? '' ?></textarea>
                            </div>

                            <div class="form-group ">
                                <label><?= lang('Post.meta_description') ?></label> - <?= lang('Acp.meta_desc_left'); ?> <span id="word_left">300</span>
                                <textarea id="word_count" class="form-control" name="seo_description"><?= $data->seo_meta->seo_description ?? '' ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="col-sm-12 col-sm-offset-2">
                        <button class="btn btn-primary mr-1" name="save" type="submit"><?= lang('Acp.save') ?></button>
                        <button class="btn btn-primary mr-1" name="save_exit" type="submit"><?= lang('Acp.save_exit') ?></button>
                        <a href="<?= route_to('category', $data->cat_type) ?>" class="btn btn-danger" type="reset"><?= lang('Acp.cancel') ?></a>
                    </div>
                </div>
            </div>

        </form>


    </div>
</div>

<script type="text/x-template" id="vcategory-slug">
    <div>
        <div class="form-group ">
            <label>{{ label }}</label>
            <div class="row" v-if="showSlug">
                <div class="col-sm-11" id="post_slug_frm">
                    <code>{{ renderPostUrl() }}</code>
                </div>
                <div class="col-sm-1" >
                    <a class="btn btn-sm btn-danger" @click.prevent="edit()" href="#"
                       postID="<?=$data->id?>" categorySlug="<?=$data->slug?>" >Edit</a>
                </div>
            </div>
            <div class="row" v-else>
                <div class="col-lg-10 col-sm-9">
                    <code style="width:30%"><?=base_url()?></code><input class="form-control d-inline-block" style="width:70%" type="text" v-model="newSlug">
                </div>
                <div class="col-lg-2 col-sm-3" >
                    <button class="btn btn-sm btn-success mr-1" type="button" @click="saveSlug" >Save</button>
                    <button class="btn btn-sm btn-danger" type="button" @click="cancelSlug" >Cancel</button>
                </div>
            </div>
        </div>
    </div>
</script>

<?= $this->endSection() ?>
<?php echo $this->section('pageScripts') ?>
<script>
    $( document ).ready(function() {
        // Track original title to detect changes
        const originalTitle = document.getElementById('categoryTitle').value;

        // Show update slug option when title changes
        $('#categoryTitle').on('keyup', function() {
            if (this.value.length > 1) {
                const titleChanged = this.value !== originalTitle;
        document.getElementById('updateSlugOption').style.display = titleChanged ? 'block' : 'none';
            }
        });
    });

    
    const categorySlug = {
        props:['slug', 'fullSlug', 'label', 'categoryId', 'token', 'tkname'],
        template: "#vcategory-slug",
        data: function () {
            return {
                showSlug: true,
                newSlug: '',
                newUrl: '',
            }
        },
        mounted() {
            // Store reference to this component for access from outside Vue
            slugComponent = this;
        },
        methods: {
            edit() {
                this.showSlug = false;
                this.newSlug = this.slug;
            },
            saveSlug() {
                if ( this.newSlug == '' ) {
                    SwalAlert.fire({
                        icon: 'error',
                        title: 'Vui lòng nhập vào url',
                    });
                    return false;
                } else {
                    var url = base_url+"/acp/category/ajxEditSlug/"+this.categoryId;
                    data = new FormData();
                    data.append("category_slug", this.newSlug);
                    data.append(this.tkname, this.token);
                    $.ajax({
                        url: url,
                        data: data,
                        cache: false,
                        dataType: 'json',
                        contentType: false,
                        processData: false,
                        type: "POST",
                        success: function (response) {
                            if ( response.error == 0 ) {
                                this.showSlug = true;
                                this.newUrl = response.postData.fullSlug;
                                this.newSlug = response.postData.slug;
                                SwalAlert.fire(
                                    'Updated!',
                                    response.text,
                                    'success'
                                );
                            } else {
                                SwalAlert.fire(
                                    '',
                                    response.text,
                                    'error'
                                );
                            }

                        }.bind(this),
                        error: function (jqXHR, textStatus, errorThrow) {
                            console.log(textStatus+' '+errorThrow);
                        }
                    })
                }
            },
            cancelSlug() {
                this.newSlug = '';
                this.showSlug = true;
            },
            renderPostUrl() {
                if ( this.newSlug == '' ) return this.fullSlug;
                else return this.newUrl;
            }
        }
    };
    const app = Vue.createApp({});
    app.component('category-slug', categorySlug);
    app.mount('#categoryApp');

</script>
<?= $this->endSection() ?>
