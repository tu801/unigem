<?php
/**
 * @author tmtuan
 * created Date: 8/13/2023
 * project: thuthuatonline
 */

namespace Modules\Acp\Enums;


final class ThemeOptionEnum extends BaseEnum
{
    const CONFIG_GROUP = [
        'logo',
        'general',
        'main_slider',
        'utility_block',
        'top_ranking',
        'ads_block',
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
            'input' => 'slider',
            'desc'  => 'slider_image_info'
        ]
    ];

    const UTILITY_BLOCK = [
        'active' => [
            'input' => 'switch',
        ],
        'ship_title' => [
            'input' => 'text'
        ],
        'ship_text' => [
            'input' => 'textarea'
        ],
        'money_back_title' => [
            'input' => 'text'
        ],
        'money_back_text' => [
            'input' => 'textarea'
        ],
        'support_title' => [
            'input' => 'text'
        ],
        'support_text' => [
            'input' => 'textarea'
        ],
    ];

    const TOP_RANKING = [
        'top_ranking_active' => [
            'input' => 'switch',
        ],
        'first_col' => [
            'input' => 'dropdown',
            'desc'  => 'top_ranking_first_col_info',
            'data'  => '__getProductCategories'
        ],
        'second_col' => [
            'input' => 'dropdown',
            'desc'  => 'top_ranking_second_col_info',
            'data'  => '__getProductCategories'
        ],
        'third_col' => [
            'input' => 'dropdown',
            'desc'  => 'top_ranking_third_col_info',
            'data'  => '__getProductCategories'
        ],
        'fourth_col' => [
            'input' => 'dropdown',
            'desc'  => 'top_ranking_fourth_col_info',
            'data'  => '__getProductCategories'
        ],
        'new_arrivals_active' => [
            'input' => 'switch',
            'desc'  => 'new_arrivals_info',
        ],
        'recommended_active' => [
            'input' => 'switch',
            'desc'  => 'recommended_info',
        ],
    ];

    const ADS_BLOCK = [
        'active' => [
            'input' => 'switch',
        ],
        'ads_image' => [
            'input' => 'image',
            'desc'  => 'ads_info',
        ],
    ];
}