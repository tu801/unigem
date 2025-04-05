<?php

declare(strict_types=1);

/**
 * This file is part of CodeIgniter Shield.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    /**
     * --------------------------------------------------------------------
     * Default Group
     * --------------------------------------------------------------------
     * The group that a newly registered user is added to.
     */
    public string $defaultGroup = 'user';

    /**
     * --------------------------------------------------------------------
     * Groups
     * --------------------------------------------------------------------
     * An associative array of the available groups in the system, where the keys
     * are the group names and the values are arrays of the group info.
     *
     * Whatever value you assign as the key will be used to refer to the group
     * when using functions such as:
     *      $user->addGroup('superadmin');
     *
     * @var array<string, array<string, string>>
     *
     * @see https://codeigniter4.github.io/shield/quick_start_guide/using_authorization/#change-available-groups for more info
     */
    public array $groups = [
        'superadmin' => [
            'title'       => 'Super Admin',
            'description' => 'Complete control of the site.',
        ],
        'admin' => [
            'title'       => 'Admin',
            'description' => 'Day to day administrators of the site.',
        ],
        'developer' => [
            'title'       => 'Developer',
            'description' => 'Site programmers.',
        ],
        'user' => [
            'title'       => 'User',
            'description' => 'General users of the site. Often customers.',
        ],
        'beta' => [
            'title'       => 'Beta User',
            'description' => 'Has access to beta-level features.',
        ],
        'customer' => [
            'title'       => 'Customer',
            'description' => 'Has access to customer-level features.',
        ],
        'content_manager' => [
            'title'       => 'Content Manager',
            'description' => 'Nhóm quản lý nội dung sẽ có quyền thêm thông tin trên website như post, page, product',
        ],
        'sale_manager' => [
            'title'       => 'Sale Manager',
            'description' => 'Quản lý sản phẩm và đơn hàng',
        ],
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions
     * --------------------------------------------------------------------
     * The available permissions in the system.
     *
     * If a permission is not listed here it cannot be used.
     */
    public array $permissions = [
        'admin.access'        => 'Can access the sites admin area',
        'admin.settings'      => 'Can access the main site settings',
        'users.manage-admins' => 'Can manage other admins',
        'users.create'        => 'Can create new non-admin users',
        'users.edit'          => 'Can edit existing non-admin users',
        'users.delete'        => 'Can delete existing non-admin users',
        'config.index'        => 'Can manage config',
        'config.create'       => 'Can create new config',
        'config.edit'         => 'Can edit existing config',
        'config.delete'       => 'Can delete existing config',
        'theme-option.index'  => 'Can manage theme option',
        'theme-option.create' => 'Can create new theme option',
        'theme-option.edit'   => 'Can edit existing theme option',
        'theme-option.delete' => 'Can delete existing theme option',
        'beta.access'         => 'Can access beta-level features',
        'customer.access'     => 'Can access customer-level in frontend',
        'category.index'      => 'Can manage category',
        'category.create'     => 'Can create new category',
        'category.edit'       => 'Can edit existing category',
        'category.delete'     => 'Can delete existing category',
        'post.index'          => 'Can manage post',
        'post.create'         => 'Can create new posts',
        'post.edit'           => 'Can edit existing posts',
        'post.delete'         => 'Can delete existing posts',
        'page.index'          => 'Can manage page',
        'page.create'         => 'Can create new page',
        'page.edit'           => 'Can edit existing page',
        'page.delete'         => 'Can delete existing page',
        'menu.index'          => 'Can manage menu',
        'menu.create'         => 'Can create new menu',
        'menu.edit'           => 'Can edit existing menu',
        'menu.delete'         => 'Can delete existing menu',
        'product.index'       => 'Can manage products',
        'product.create'      => 'Can create new products',
        'product.edit'        => 'Can edit existing products',
        'product.delete'      => 'Can delete existing products',
        'shop.index'          => 'Can manage shop',
        'shop.create'         => 'Can create new shop',
        'shop.edit'           => 'Can edit existing shop',
        'shop.delete'         => 'Can delete existing shop',
        'customer.index'      => 'Can manage customer',
        'customer.create'     => 'Can create new customer',
        'customer.edit'       => 'Can edit existing customer',
        'customer.delete'     => 'Can delete existing customer',
        'order.index'         => 'Can manage order',
        'order.create'        => 'Can create new order',
        'order.edit'          => 'Can edit existing order',
        'order.delete'        => 'Can delete existing order',
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions Matrix
     * --------------------------------------------------------------------
     * Maps permissions to groups.
     *
     * This defines group-level permissions.
     */
    public array $matrix = [
        'superadmin' => [
            'admin.*',
            'users.*',
            'config.*',
            'theme-option.*',
            'beta.*',
            'category.*',
            'post.*',
            'page.*',
            'menu.*',
            'product.*',
            'shop.*',
            'customer.*',
            'order.*',
        ],
        'admin' => [
            'admin.*',
            'users.*',
            'theme-option.*',
            'category.*',
            'post.*',
            'page.*',
            'menu.*',
            'product.*',
            'shop.*',
            'customer.*',
            'order.*',
        ],
        'developer' => [
            'admin.access',
            'admin.settings',
            'users.create',
            'users.edit',
            'beta.access',
        ],
        'user' => [],
        'beta' => [
            'beta.access',
        ],
        'content_manager' => [
            'admin.access',
            'category.*',
            'post.*',
            'page.*',
            'menu.*',
        ],
        'sale_manager' => [
            'admin.access',
            'product.*',
            'shop.*',
            'customer.*',
            'order.*',
        ],
    ];
}