<!-- Brand Logo -->
<a href="<?= base_url($config->adminSlug . '/dashboard') ?>" class="brand-link bg-danger">
    <img src="/<?= $config->templatePath ?>assets/img/logo.png" alt="TMT" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light"><?=lang('Acp.cms_brand_text')?></span>
</a>

<!-- Sidebar -->
<div class="sidebar">

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="<?= base_url($config->adminSlug . '/dashboard') ?>" class="nav-link <?= (in_array($controller, array('dashboard'))) ? "active" : "" ?>">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>

            <!-- Page -->
            <li class="nav-item">
                <a href="<?= route_to('page') ?>" class="nav-link <?= (in_array($controller, array('page'))) ? "active" : "" ?>">
                    <i class="nav-icon far fa-file-alt"></i>
                    <p><?= lang('Acp.page_manager') ?></p>
                </a>
            </li>

            <!-- Post, News -->
            <?php
            if (isset($cat_type) && $cat_type == 'post') $ctlArr = array('category', 'post', 'tags');
            else $ctlArr = array('post', 'tags');
            ?>
            <li class="nav-item has-treeview <?= (in_array($controller, $ctlArr)) ? "menu-open" : "" ?>">
                <a href="#" class="nav-link <?= (in_array($controller, $ctlArr)) ? "active" : "" ?>">
                    <i class="nav-icon fab fa-blogger"></i>
                    <p>
                        <?= lang('Acp.posts_managerment') ?>
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">

                    <li class="nav-item">
                        <a href="<?= route_to('post') ?>" class="nav-link <?= (($controller == 'post') && in_array($method, array('index', 'edit', 'add'))) ? "active" : '' ?>">
                            <i class="far fa-circle nav-icon"></i>
                            <p><?= lang('Acp.post_list'); ?></p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url($config->adminSlug . '/category/post') ?>" class="nav-link <?= (($controller == 'category') && in_array($method, array('index', 'edit'))) ? "active" : '' ?>">
                            <i class="far fa-circle nav-icon"></i>
                            <p><?= lang('Acp.category_list'); ?></p>
                        </a>
                    </li>

                </ul>
            </li>

            <!-- Shop -->
            <li class="nav-item has-treeview <?= (in_array($controller, array('shopcontroller', 'contactcontroller'))) ? "menu-open" : "" ?>">
                <a href="#" class="nav-link <?= (in_array($controller, array('shopcontroller', 'contactcontroller'))) ? "active" : "" ?>">
                    <i class="nav-icon fas fa-store"></i>
                    <p>
                        <?= lang('Acp.shop_manager') ?>
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="<?= route_to('list_shop') ?>" class="nav-link <?= ($controller == 'shopcontroller') ? "active" : '' ?>">
                            <i class="far fa-circle  nav-icon"></i>
                            <p><?= lang('Acp.shop_manager'); ?></p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= route_to('list_contact') ?>" class="nav-link <?= ($controller == 'contactcontroller') ? "active" : '' ?>">
                            <i class="far fa-id-badge  nav-icon"></i>
                            <p><?= lang('Acp.contact_manager'); ?></p>
                        </a>
                    </li>
                </ul>
            </li>

             <!-- Product -->
             <?php
            if (isset($cat_type) && $cat_type == 'product') $ctlArr = array('category', 'productcontroller');
            else $ctlArr = array('productcontroller', 'productmanufacturercontroller');
            ?>
            <li class="nav-item has-treeview <?= (in_array($controller, $ctlArr)) ? "menu-open" : "" ?>">
                <a href="#" class="nav-link <?= (in_array($controller, $ctlArr)) ? "active" : "" ?>">
                    <i class="nav-icon fas fa-box"></i>
                    <p>
                        <?= lang('Acp.manager_product') ?>
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="<?= base_url($config->adminSlug . '/product/') ?>" class="nav-link <?= ($controller == 'productcontroller' && in_array($method, array('index', 'addProduct', 'editProduct'))) ? "active" : '' ?>">
                            <i class="far fa-circle nav-icon"></i>
                            <p><?= lang('Acp.product'); ?></p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url($config->adminSlug . '/category/product') ?>" class="nav-link <?= (($controller == 'category') && in_array($method, array('index', 'edit'))) && isset($cat_type) && $cat_type == 'product' ? "active" : '' ?>">
                            <i class="far fa-circle nav-icon"></i>
                            <p><?= lang('Acp.category_product'); ?></p>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- order -->
            <!-- <li class="nav-item">
                <a href="<?= route_to('order') ?>" class="nav-link <?= (in_array($controller, array('ordercontroller'))) ? "active" : "" ?>">
                    <i class="nav-icon fas fa-cart-arrow-down"></i>
                    <p><?= lang('Order.page_title') ?></p>
                </a>
            </li> -->

            <!-- Users -->
            <li class="nav-item has-treeview <?= (in_array($controller, array('user', 'usergroup', 'customercontroller'))) ? "menu-open" : "" ?>">
                <a href="#" class="nav-link <?= (in_array($controller, array('user', 'usergroup', 'customercontroller'))) ? "active" : "" ?>">
                    <i class="nav-icon fas fa-users-cog"></i>
                    <p>
                        <?= lang('Acp.user_manager') ?>
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">

                    <li class="nav-item">
                        <a href="<?= base_url($config->adminSlug . '/user/') ?>" class="nav-link <?= (($controller == 'user' || $controller == 'usergroup') && in_array($method, array('index', 'add', 'edit'))) ? "active" : '' ?>">
                            <i class="fas fa-users-cog"></i>
                            <p><?= lang('Acp.admin_manager'); ?></p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url($config->adminSlug . '/customer/') ?>" class="nav-link <?= (($controller == 'customercontroller') && in_array($method, array('index', 'addCustomer', 'editCustomer', 'detail', 'createCustomerAccount'))) ? "active" : '' ?>">
                            <i class="fas fa-user-tag"></i>
                            <p><?= lang('Acp.customer_manager'); ?></p>
                        </a>
                    </li>

                </ul>
            </li>


            <!-- Config -->
            <li class="nav-item has-treeview <?= in_array($controller, array('config', 'menucontroller', 'log', 'themeoptioncontroller')) ? "menu-open" : "" ?>">
                <a href="#" class="nav-link <?= in_array($controller, array('config', 'menucontroller', 'log', 'themeoptioncontroller')) ? "active" : "" ?>">
                    <i class="nav-icon fas fa-cogs"></i>
                    <p>
                        <?= lang('Acp.system'); ?>
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="<?= route_to('theme-option') ?>" class="nav-link <?= ($controller == 'themeoptioncontroller' && in_array($method, array('index'))) ? "active" : ''; ?>">
                            <i class="fa fa-paint-brush"></i>
                            <p><?= lang('Acp.theme_option'); ?></p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url($adminSlug . '/menu') ?>" class="nav-link <?= $controller == 'menucontroller' ? "active" : ''; ?>">
                            <i class="fas fa-bars"></i>
                            <p><?= lang('Acp.menu_setup'); ?></p>
                        </a>
                    </li>
                    <?php if ($login_user->can('config.*')) : ?>
                        <li class="nav-item">
                            <a href="<?= base_url($config->adminSlug . '/log') ?>" class="nav-link <?= ($controller == 'log' && in_array($method, array('index', 'detail'))) ? "active" : ''; ?>">
                                <i class="fa fa-history"></i>
                                <p><?= lang('Acp.module_log_activity'); ?></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url($config->adminSlug . '/config') ?>" class="nav-link <?= ($controller == 'config' && in_array($method, array('index', 'add', 'edit'))) ? "active" : ''; ?>">
                                <i class="nav-icon fas fa-cog"></i>
                                <p><?= lang('Acp.module_configure'); ?></p>
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>
            </li>


        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->