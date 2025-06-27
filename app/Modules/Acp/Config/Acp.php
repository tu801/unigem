<?php

namespace Modules\Acp\Config;

use CodeIgniter\Config\BaseConfig;

class Acp extends BaseConfig
{
    public $uploadPath =  WRITEPATH . "uploads" . DIRECTORY_SEPARATOR;
    public $uploadFolder =  "uploads";
    public $imageThumb = ['height' => 360, 'width' => 550];
    public $uploadMineType = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg', 'image/webp'];
    public $adminSlug = ADMIN_AREA;
    public $sys;

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
    // Config user group avaiable
    //--------------------------------------------------------------------
    public $available_groups = ['admin', 'user', 'customer', 'content_manager', 'sale_manager'];

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

    public function getUserGroup()
    {
        $groups = config('AuthGroups')->groups;
        $result = [];

        foreach ( $this->available_groups as $group ) {
            $result[$group] = $groups[$group];
        }

        return $result;
    }
}
