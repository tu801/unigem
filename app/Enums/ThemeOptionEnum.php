<?php
/**
 * @author tmtuan
 * created Date: 8/13/2023
 * project: Unigem
 */

namespace App\Enums;


final class ThemeOptionEnum extends BaseEnum
{
    const CONFIG_GROUP = [
        'logo',
        'general',
        'main_slider',
        'jewelry_block',
        'running_text_block',
        'gems_block',
        'design_block',
    ];

    const LOGO_CONFIG = [
        'site_logo' => [
            'input' => 'image'
        ],
        'favicon' => [
            'input' => 'image'
        ],
    ];

    const GENERAL_CONFIGS = [
        'site_title' => [
            'input' => 'text'
        ],
        'site_desc' => [
            'input' => 'textarea'
        ],
        'seo_title' => [
            'input' => 'text'
        ],
        'seo_description' => [
            'input' => 'textarea'
        ],
        'seo_keyword' => [
            'input' => 'textarea'
        ],
        'seo_open_graph_image' => [
            'input' => 'image'
        ],
        'copyright' => [
            'input' => 'text',
            'desc'  => 'copyright_desc'
        ],
        'company_name' => [
            'input' => 'text',
        ],
        'hotline' => [
            'input' => 'text',
        ],
        'address' => [
            'input' => 'text',
        ],
        'email' => [
            'input' => 'text',
        ],
        'mst' => [
            'input' => 'text',
        ],
        'facebook_link' => [
            'input' => 'text',
        ],
        'youtube_link' => [
            'input' => 'text',
        ],
        'tiktok_link' => [
            'input' => 'text',
        ],
        'instagram_link' => [
            'input' => 'text',
        ],
        'twitter_x_link' => [
            'input' => 'text',
        ],
        'pinterest_link' => [
            'input' => 'text',
        ],
    ];

    const MAIN_SLIDER = [
        'main_slider' => [
            'input' => 'slider'
        ]
    ];

    // block danh mục trang sức
    const JEWELRY_BLOCK = [
        'jewelry_cat_active' => [
            'input' => 'switch',
        ],
        'jewelry_cat_list' => [
            'input'             => 'item_list',
            'data'              => '__getProductCategories',
            'desc'              => 'jewelry_cat_info'
        ],
        
    ];

    // block chữ chạy
    const RUNNING_TEXT_BLOCK = [
        'running_text_active' => [
            'input' => 'switch',
        ],
        'running_text_1' => [
            'input' => 'text',
        ],
        'running_text_2' => [
            'input' => 'text',
        ],
        
    ];

    // block danh mục đá quý
    const GEMS_BLOCK = [
        'gems_cat_active' => [
            'input' => 'switch',
        ],
        'gems_cat_title' => [
            'input' => 'text',
            'desc'  => 'gems_cat_title_desc'
        ],
        'gems_cat_list' => [
            'input'             => 'item_list',
            'data'              => '__getProductCategories',
            'desc'              => 'gem_cat_info'
        ],
        
    ];
    
    // block thiết kế trang sức
    const DESIGN_BLOCK = [
        'design_active' => [
            'input' => 'switch',
        ],
        'design_title' => [
            'input' => 'text',
            'desc'  => 'design_title_desc'
        ],
        'design_sub_title' => [
            'input' => 'text',
        ],
        'design_step_1' => [
            'input' => 'text',
        ],        
        'design_step_2' => [
            'input' => 'text',
        ],        
        'design_step_3' => [
            'input' => 'text',
        ],        
        'design_step_4' => [
            'input' => 'text',
        ],        
        'design_intro_video_url' => [
            'input' => 'text',
            'desc'  => 'design_intro_video_desc'
        ],        
    ];

}