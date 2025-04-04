<?php
echo $this->extend($config->viewLayout);
echo $this->section('content');?>

<div class="row">

    <!--Add New Category-->
    <div class="col-5">
        <?= form_open(route_to('add_category', $cat_type)) ?>
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><?= lang('Category.add_category') ?></h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="form-group">
                    <label for="categoryTitle"><?= lang('Category.title') ?></label>
                    <input type="text" class="form-control <?= session('errors.title') ? 'is-invalid' : '' ?>" id="categoryTitle" name="title" value="<?= old('title') ?>" placeholder="<?= lang('Category.title') ?>">
                </div>

                <div class="form-group">
                    <label><?= lang('Category.parent_cat') ?></label>
                    <select class="form-control" name="parent_id">
                        <?php if ($list_parent) {
                            $opts = "<option value='0'>None</option>";
                            foreach ($list_parent as $parent) {
                                $sel = @old('parent_id') == $parent->id ? 'selected' : '';
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
                            <option <?= old('cat_status') == $key ? 'selected' : ''  ?> value='<?= $key ?>'><?= $title ?></option>
                        <?php endforeach;    ?>
                    </select>
                </div>

                <div class="form-group">
                    <label><?= lang('Category.description') ?></label>
                    <textarea class="form-control" rows="3" name="description" placeholder="Nhập mô tả"><?= old('description') ?></textarea>
                </div>

                <hr>

                <div class="ml-0">
                    <p class="h5">
                    <div class="d-flex justify-content-between align-items-center">
                        <b><?= lang('Acp.search_engine_optimize'); ?></b>
                        <a class="btn btn-link" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                            <?= lang('Acp.edit_seo_meta'); ?>
                        </a>
                    </div>
                    <div style="margin-top:5px;margin-bottom:5px;height:1px;width:100%;border-top:1px solid #ccc;"></div>
                    <br />
                    <?= lang('Acp.setup_seo_desc'); ?>
                    </p>
                </div>
                <div class="collapse" id="collapseExample">
                    <div class="card-body">
                        <div class="form-group ">
                            <label><?= lang('Post.meta_title') ?></label> - <?= lang('Acp.meta_desc_left'); ?> <span id="title_word_left">70</span>
                            <input id="title_word_count" name="seo_title" type="text" class="form-control" value="<?= old('meta_title') ?>">
                        </div>

                        <div class="form-group ">
                            <label><?= lang('Post.meta_keyword') ?></label>
                            <textarea class="form-control" name="seo_keyword"><?= old('seo_keyword') ?></textarea>
                        </div>

                        <div class="form-group ">
                            <label><?= lang('Post.meta_description') ?></label> - <?= lang('Acp.meta_desc_left'); ?> <span id="word_left">300</span>
                            <textarea id="word_count" class="form-control" name="seo_description"><?= old('seo_description') ?></textarea>
                        </div>
                    </div>
                </div>



            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><?= lang('Acp.add_new') ?></button>
            </div>

        </div>
        </form>
    </div>

    <!--List Category-->
    <div class="col-7" id="listCat" data-cat-type="<?= $cat_type ?>" data-action="<?= $action ?>">

        <div class="card card-primary card-outline">
            <div class="card-header">
                <div class="card-title">
                    <a class="<?= ($action == 'all') ? 'badge badge-primary text-light' : 'text-primary' ?>" href="<?= base_url("{$config->adminSlug}/category/{$cat_type}") ?>">All</a> |
                    <a class="<?= ($action == 'deleted') ? 'badge badge-primary text-light' : 'text-primary' ?>" href="<?= base_url("{$config->adminSlug}/category/{$cat_type}?deleted=1") ?>">Deleted</a>
                </div>

                <div class="card-tools">
                    <div class="input-group input-group-sm">
                        <input type="text" name="title" class="form-control" placeholder="<?= lang('Category.search') ?>" v-model="searchkey" @keyup="onSearch">
                        <div class="input-group-append">
                            <button type="submit" name="search" class="btn btn-primary" @click.prevent="onSearch">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="mailbox-controls">
                    <div class="input-group col-3">
                        <select name="action" id="bulk-action-selector-top" class="form-control">
                            <option value="-1">Bulk Actions</option>
                            <option value="mdelete">Delete</option>
                        </select>
                        <span class="input-group-append">
                            <button class="btn btn-info btn-flat" name="act_submit">Apply</button>
                        </span>
                    </div>
                </div>

                <div class="table-responsive p-0" v-if="loading == false">
                    <table id="catListTbl" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5">
                                    <div class="icheck-primary">
                                        <input type="checkbox" value="" id="cat_check">
                                        <label for="cat_check"></label>
                                    </div>
                                </th>
                                <th><?= lang('Category.title') ?></th>
                                <th><?= lang('Category.parent_cat') ?></th>
                                <th><?= lang('Category.slug') ?></th>
                                <th><?= lang('Category.cat_status') ?></th>
                                <th><?= lang('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody v-if="categories.length">
                            <tr v-for="cat in categories">
                                <td>
                                    <div class="icheck-primary">
                                        <input type="checkbox" :value="cat.id" name="sel[]" id="{{ 'cat_'+cat.id }}">
                                        <label for="cat_"></label>
                                    </div>
                                </td>
                                <td><a :href="renderEditUrl(cat)">{{ cat.title }}</a></td>
                                <td>{{ catParent(cat) }}</td>
                                <td>{{ cat.slug }}</td>
                                <td>{{ cat.status }}</td>
                                <td>
                                    <a class="btn btn-primary btn-sm mb-2" :href="renderEditUrl(cat)"><i class="fas fa-edit"></i></a> &nbsp;
                                        <a class="btn btn-danger btn-sm mb-2" @click.prevent="delCat(cat.id)"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="6"><?= lang('Acp.no_item_found') ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="text-center"><img class="image img-size-64" src="<?= base_url($config->templatePath) ?>/assets/img/loading.svg"></div>
            </div>
        </div>

    </div>

</div>
<?= $this->endSection() ?>

<?php echo $this->section('pageScripts') ?>
<!-- Import Category App -->
<script src="<?= base_url($config->scriptsPath) ?>/acp/blog/vCategory.js"></script>
<script src="<?= base_url($config->scriptsPath) ?>/acp/blog/postWordCount.js"></script>
<script>
    const listCatMounted = catList.mount('#listCat');
</script>
<?= $this->endSection() ?>