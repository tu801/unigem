<?php
echo $this->extend($config->viewLayout);
echo $this->section('content')
?>

<div class="row">
    <div class="col-12">
        <form method="get" action="<?= route_to('post') ?>">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <a class="<?= ($listtype == 'all') ? 'badge badge-primary text-light' : 'text-primary' ?>" href="<?= base_url("acp/post?listtype=all") ?>"><?= lang('Post.list_all_post') ?></a> |
                        <a class="<?= ($listtype == 'user') ? 'badge badge-primary text-light' : 'text-primary' ?>" href="<?= base_url("acp/post?listtype=user") ?>"><?= lang('Post.list_user_post') ?></a> | 
                        <a class="<?= ($listtype == 'deleted') ? 'badge badge-primary text-light' : 'text-primary' ?>" href="<?= base_url("acp/post?listtype=deleted") ?>"><?= lang('Post.list_delete_post') ?></a>
                    </div>

                    <div class="card-tools mt-2">
                        <div class="input-group input-group-sm">
                            <input name="listtype" id="listtype" value="<?= $listtype ?? 'user'; ?>" class="form-control" type="hidden" />
                            <input type="text" value="<?= (isset($search_title)) ? $search_title : '' ?>" name="title" class="form-control" placeholder="Search Post">
                            <div class="input-group-append">
                                <button name="search" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 mb-2">
                            <div class="btn-group">
                                <a href="<?= route_to('add_post') ?>" class="btn btn-normal btn-primary btn-sm" title="Add New Post">
                                    <i class="fa fa-plus text"></i> <?= lang('Post.title_add') ?>
                                </a>
                                <button type="submit" name="mdelete" class="btn btn-default btn-sm ml-2"><i class="far fa-trash-alt"></i></button>
                            </div>
                            <!-- /.btn-group -->
                        </div>

                        <div class="float-right col-md-4 mr-0 pr-0">
                            <div class="row">
                                <div class="col-md-5">
                                    <?php
                                    $catModel = new App\Models\Blog\CategoryModel();
                                    $catData = $catModel->where('cat_type', 'post')
                                        ->join('category_content', 'category_content.cat_id = category.id')
                                        ->where('lang_id', $currentLang->id)
                                        ->findAll();
                                    ?>
                                    <div id="chkCategory" class="form-group">
                                        <select class="custom-select form-control" name="category">
                                            <option value="">-- Danh mục --</option>
                                            <?php foreach ($catData as $catItem) :
                                                $selCat = (isset($select_cat) && $select_cat == $catItem->id) ? 'selected' : '';
                                            ?>
                                                <option value='<?= $catItem->id ?>' <?= $selCat ?>><?= $catItem->title ?></option>
                                            <?php endforeach;    ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-7">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <select class="custom-select form-control" name="post_status">
                                                <option value="">-- Trạng thái --</option>
                                                <?php foreach ($config->cmsStatus['status'] as $key => $title) :
                                                    $sttSel = (isset($post_status) && $post_status == $key) ? 'selected' : '';
                                                ?>
                                                    <option value='<?= $key ?>' <?= $sttSel ?>><?= $title ?></option>
                                                <?php endforeach;    ?>
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary float-right" name="filter">
                                                    <i class="fas fa-filter"></i>&nbsp;Lọc</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- /.float-right -->
                    </div>

                    <div class="row table-responsive">
                    <table id="<?php echo $controller . "_" . $method ?>_DataTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th width="2%">
                                    <div class="icheck-primary">
                                        <input type="checkbox" value="" id="post_check">
                                        <label for="post_check"></label>
                                    </div>
                                </th>
                                <th width="35%"><?= lang('Post.title') ?></th>
                                <th><?= lang('Post.image') ?></th>
                                <th><?= lang('Post.author') ?></th>
                                <th><?= lang('Post.category') ?></th>
                                <th><?= lang('Post.created_view') ?></th>
                                <th><?= lang('Post.post_status') ?></th>
                                <th><?= lang('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($data) > 0) :
                                foreach ($data as $row) { //dd($row->images);
                                    $img = (isset($row->images['thumbnail']) && $row->images['thumbnail'] !== null) ? $row->images['thumbnail'] : base_url($config->noimg);
                            ?>
                                    <tr>
                                        <td>
                                            <div class="icheck-primary">
                                                <input type="checkbox" value="<?= $row->id ?>" name="sel[]" id="post_<?= $row->id ?>">
                                                <label for="post_<?= $row->id ?>"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="<?= route_to("edit_post", $row->id) ?>"><?= $row->title ?></a>
                                        </td>
                                        <td>
                                            <img src="<?= $img ?>" class="img-responsive img-thumbnail" style="max-width:150px">
                                        </td>
                                        <td>
                                            <?= $row->author->username ?? "" ?>
                                        </td>
                                        <td>
                                            <?php
                                            if (isset($row->categories) && is_array($row->categories)) {
                                                foreach ($row->categories as $cat) { //echo "<pre>";  print_r($cat);
                                                    if (isset($cat['id'])) echo '<span class="badge badge-primary ml-2">' . $cat['title'] . '</span>';
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td><?= $row->created_at->format('d/m/Y') ?></td>
                                        <td class="todo-list">
                                            <?php
                                            switch ($row->post_status) {
                                                case 'draft':
                                                    echo '<span class="badge badge-info">' . $config->cmsStatus['status']['draft'] . '</span>';
                                                    break;
                                                case 'pending':
                                                    echo '<span class="badge badge-warning">' . $config->cmsStatus['status']['pending'] . '</span>';
                                                    break;
                                                case 'publish':
                                                    echo '<span class="badge badge-primary">' . $config->cmsStatus['status']['publish'] . '</span>';
                                                    break;
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a class="btn btn-primary btn-sm mb-2" href="<?= route_to("edit_post", $row->id) ?>"><i class="fas fa-edit"></i></a>
                                            <?php if ($listtype !== 'deleted') : ?>
                                                <a class="btn btn-danger btn-sm mb-2 acpRmItem" title="Move to Trash" data-id="<?=$row->id?>"
                                                   data-delete="<?= route_to("delete_post") ?>" data-delete-message="Bạn có chắc chắn muốn xoá item này?" ><i class="fas fa-trash"></i></a>
                                            <?php else : ?>
                                                <a class="btn btn-primary btn-sm mb-2" title="Recover Item" href="<?= route_to("recover_post", $row->id) ?>"><i class="fas fa-reply"></i></a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php }
                            else : ?>
                                <tr>
                                    <td colspan="8"><?= lang('Acp.no_item_found') ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    </div>

                </div>

                <div class="card-footer">
                    <?php echo $pager->links('default', 'acp_full') ?>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?php echo $this->section('pageScripts') ?>
<script src="<?= base_url($config->scriptsPath) ?>/acp/blog/vChkPost.js"></script>
<?= $this->endSection() ?>