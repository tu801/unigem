<?php
/**
 * @author tmtuan
 * created Date: 08-May-21
 */

foreach ($category_list  as $key => $catItem ) : ?>
<div class="card card-primary card-outline collapsed-card">
    <div class="card-header">
        <h3 class="card-title"><?=$catItem['title']?></h3>
        <div class="card-tools">
            <button type="button" class="btn bg-primary btn-xs" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
            </button>
            <button type="button" class="btn bg-primary btn-xs" data-card-widget="remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table class="table no-margin">
            <thead>
            <tr>
                <th>ID</th>
                <th>Tên danh mục</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php if ( count($catItem['data']) > 0 ):
                foreach ($catItem['data'] as $i=>$row) {
                $checkCat = false;
                //check if current cat exist in menu
                if ( isset($menuItem->id)  ) {
                    $checkCat = model(\Modules\Acp\Models\Blog\MenuItemsModel::class)->checkCatExistInMenu( $row->id, $menuItem->id);
                }
                $parent_data = '';

                ?>
                <tr>

                    <td><?=$row->id?></td>
                    <td><?=$row->title?></td>
                    <td>
                        <?php if ( $checkCat == false && !empty($menuItem) ) { ?>
                            <a class="btn btn-primary btn-sm" href="<?=base_url("{$adminSlug}/menu/add_item/{$row->id}?key={$menuItem->slug}") ?>" >
                                <i class="fa fa-plus-square"></i></a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } else : ?>
                <tr>
                    <td colspan="2">
                        <p>Hiện chưa có danh mục nào! Vui lòng thêm danh mục trước</p>
                        <a class="btn btn-primary" href="<?=route_to('category', $key)?>">Add Category</a>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

    </div>

</div>
<?php endforeach; ?>