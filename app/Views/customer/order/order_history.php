<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 11/12/2023
 */

echo $this->extend($configs->viewLayout);
echo $this->section('content');
?>

<!-- breadcrumbs -->
<?php echo view_cell('App\Libraries\BreadCrumb\BreadCrumbCell') ?>
<!-- account -->
<div class="my_account_wrap section_padding_b">
    <div class="container">
        <div class="row">
            <!--  account sidebar  -->
            <div class="col-lg-3">
                <?= view('customer/components/account_sidebar', ['user' => $user]) ?>
            </div>
            <!-- account content -->
            <div class="col-lg-9">
                <div class="account_cont_wrap">
                    <div class="acorder_wrapper">
                        <?php echo view_cell('\App\Cells\Customer\RecentOrdersCell::listOrderHistory') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
