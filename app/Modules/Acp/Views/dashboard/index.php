<?php
echo $this->extend($config->viewLayout);
echo $this->section('content');
?>

<div class="row">
    <div class="col-12">
        <div class="dashboard p-3 mb-3" style="background-color: #FFFFFF">
            <div class="row">
                <div class="col-12">
                    <h4>
                        <i class="fas fa-globe"></i> <?=lang('Acp.welcom')?> <?=$config->sys['default_site_name']??''?>
                        <small class="float-right">Hôm nay là: <?=date('d/m/Y')?></small>
                    </h4>
                    <small>Chào mừng bạn đến với trang quản trị. Hãy bắt đầu quản lý website của bạn</small>
                </div>
                <!-- /.col -->
            </div>

            <div class="row">
                <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                        <a class="btn btn-primary btn-sm " href="<?=route_to('add_post')?>" >
                            New Post</a>

                    </div>
                    <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                        <h5 class="description-header"><?=(isset($monthPosts) ) ? count($monthPosts):0?></h5>
                        <span class="description-text">Tin Mới Trong Tháng</span>
                    </div>
                    <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-6">
                    <div class="description-block border-right">

                        <h5 class="description-header"><?=(isset($pendingPosts))?count($pendingPosts):0?></h5>
                        <span class="description-text">Tin Chưa được duyệt</span>
                    </div>
                    <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-6">
                    <div class="description-block">

                        <h5 class="description-header"><?=$userCount?></h5>
                        <span class="description-text">User</span>
                    </div>
                    <!-- /.description-block -->
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ecommerce analytic-->
<div class="row">
    <div class="col-6 col-lg-6">
        <?= view_cell('EcommerceOverviewCell') ?>
    </div>
    <div class="col-6 col-lg-6">
        <?= view_cell('SaleChartOverTimeCell', ['configs' => $config]) ?>
    </div>
</div>
    <!-- ecommerce analytic-->

<div class="row">
<?php if ( isset($monthPosts) ) : ?>
    <div class="col-6">
        <div class="card card-outline card-primary">
            <div class="card-header border-transparent">
                <h3 class="card-title">Tin Mới</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table m-0">
                        <thead>
                        <tr>
                            <th width="3%">#</th>
                            <th width="75%"><?=lang('Post.title')?></th>
                            <th ><?=lang('Post.created_view')?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i= 0;
                        foreach ($monthPosts as $row) {
                            $i++;
                            echo view($config->view.'\dashboard\components\_listPostItem', ['num' => $i, 'row' => $row, 'login_user' => $login_user, 'action' => $currentAct]);
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
            <div class="card-footer clearfix">
                <a href="<?=route_to('add_post')?>" class="btn btn-xs btn-primary float-left">Thêm Bài Viết Mới</a>
                <a href="<?=route_to('post')?>" class="btn btn-xs btn-dark float-right">View All Posts</a>
            </div>

        </div>
    </div>
<?php endif; ?>

<?php
if ( isset($monthPosts) ) {
    echo '<div class="col-6">';
    echo view($config->view.'\dashboard\components\_listProducts', ['products' => $products, 'action' => $currentAct]);
    echo '</div>';
}
?>

</div>


<?= $this->endSection() ?>