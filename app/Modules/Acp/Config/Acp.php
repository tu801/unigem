<?php

namespace Modules\Acp\Config;

use CodeIgniter\Config\BaseConfig;

class Acp extends BaseConfig
{
    public $uploadPath =  WRITEPATH . "uploads" . DIRECTORY_SEPARATOR;
    public $uploadFolder =  "uploads";
    public $imageThumb = ['height' => 360, 'width' => 550];
    public $adminSlug = ADMIN_AREA;

    //--------------------------------------------------------------------
    // Layout for the views to extend
    //--------------------------------------------------------------------
    public $view = 'Modules\Acp\Views';
    public $viewLayout = 'Modules\Acp\Views\layout';
    public $templatePath = "themes/acp/";
    public $scriptsPath = "scripts/";
    public $noimg = "themes/acp/assets/img/default-150x150.png";
    public $header_css;
    public $footer_js;

    //--------------------------------------------------------------------
    // Config permission group
    //--------------------------------------------------------------------
    public $permission_groups = [
        'admin' => [
            'title' => 'Trang Quản Trị',
            'type' => 'default',
            'module' => 'acp',
            'objects' => [
                'config' => 'Cấu hình',
                'user' => 'Nhân viên',
                'usergroup' => 'Nhóm người dùng',
                'themeoptioncontroller' => 'Quản Lý Giao Diện',
                'menu'  => 'Quản lý menu',
                'category' => 'Quản lý danh mục',
                'page' => 'Quản Lý Trang',
                'post'  => 'Quản lý tin'
            ]
        ]
    ];

    //--------------------------------------------------------------------
    // Config User Meta
    //--------------------------------------------------------------------
    public $user_meta = [
        'fullname'       => ['title' => 'Tên đầy đủ', 'input' => 'text'],
        'mobile'         => ['title' => 'Số điện thoại', 'input' => 'text'],
        'birthday'       => ['title' => 'Ngày sinh', 'input' => 'date'],
        'nationalID'     => ['title' => 'Số chứng minh nhân dân', 'input' => 'text'],
        'marital_status' => ['title' => 'Tình trạng hôn nhân', 'input' => 'option', 'value' => ['single' => 'Độc thân', 'married' => 'Đã kết hôn']],
        'address'        => ['title' => 'Địa chỉ', 'input' => 'textarea'],
    ];

    //--------------------------------------------------------------------
    // CMS Status - Quản lý danh sách tất cả các status của content
    //--------------------------------------------------------------------
    public $cmsStatus = [
        'status' => [
            'draft'     => 'Bản nháp',
            'pending'   => 'Chờ duyệt',
            'publish'   => 'Xuất bản'
        ],
        'publish_check' => true
    ];

    //--------------------------------------------------------------------
    // Category
    //-------------------------------------------------------------------
    public $catGroup = [
        'post'    => 'Danh mục tin',
        'product' => 'Danh mục sản phẩm',
    ];

    public $cat_type = ['post', 'product'];

    public $category_seo_meta = [
        'seo_title' => ['title' => 'seo_title', 'input' => 'text'],
        'seo_description' => ['title' => 'seo_description', 'input' => 'textarea'],
        'seo_keyword' => ['title' => 'seo_keyword', 'input' => 'textarea'],
    ];

    //--------------------------------------------------------------------
    // Post
    //--------------------------------------------------------------------
    public $post_seo_meta = [
        'seo_title' => ['title' => 'seo_title', 'input' => 'text'],
        'seo_description' => ['title' => 'seo_description', 'input' => 'textarea'],
        'seo_keyword' => ['title' => 'seo_keyword', 'input' => 'textarea'],
    ];


    //--------------------------------------------------------------------
    // Config Group Info
    //--------------------------------------------------------------------
    public $cfGroup = [
        'default' => [
            'title' => 'Cấu hình chung',
            'type' => 'default'
        ],
        'email' => [
            'title' => 'Cấu hình Email',
            'type' => 'default'
        ],
    ];

    //--------------------------------------------------------------------
    //  System's module
    //--------------------------------------------------------------------
    public $sysModule = [
        ['name' => "Mô-đun Acp", 'val' => 'acp'],
        ['name' => 'Mô-đun Site', 'val' => 'site'],
    ];
}
