<?php
namespace Modules\Acp\Database\Seeds;

use CodeIgniter\CLI\CLI;
use Modules\Acp\Enums\Store\ShopEnum;
use Modules\Acp\Models\ConfigModel;

class Configs extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        CLI::write('- Thêm cấu hình mặc định cho website');
        //insert default config
        $_cfModel = model(ConfigModel::class);

        $data = [
            'group_id'          => 'default',
            'title'             => 'Tổng số trang trong 1 page',
            'key'               => 'page_number',
            'value'             => '20',
        ];
        $_cfModel->insertOrUpdate($data);

        $data = [
            'group_id'          => 'default',
            'title'             => 'Tên giao diện',
            'key'               => 'theme_name',
            'value'             => 'site',
        ];
        $_cfModel->insertOrUpdate($data);

        $data = [
            'group_id'          => 'default',
            'title'             => 'Tên website',
            'key'               => 'site_name',
            'value'             => '',
        ];
        $_cfModel->insertOrUpdate($data);

        $data = [
            'group_id'          => 'default',
            'title'             => 'Menu Chính',
            'key'               => 'menu_location',
            'value'             => 'main_menu',
        ];
        $_cfModel->insertOrUpdate($data);

        $data = [
            'group_id'          => 'default',
            'title'             => 'Allow upload file type',
            'key'               => 'mime_type',
            'value'             => 'image,image/jpg,image/jpeg,image/gif,image/png,image/webp',
        ];
        $_cfModel->insertOrUpdate($data);

        $data = [
            'group_id'          => 'default',
            'title'             => 'Maximum upload file size',
            'key'               => 'max_size',
            'value'             => 2,
        ];
        $_cfModel->insertOrUpdate($data);

        // Create Email API config
        CLI::write('- Thêm cấu hình Email API');
        $emailConfigData = [
            [
                'group_id'          => 'email',
                'title'             => 'Email Protocol',
                'key'               => 'protocol',
                'value'             => 'smtp',
            ],
            [
                'group_id'          => 'email',
                'title'             => 'SMTP Host',
                'key'               => 'SMTPHost',
                'value'             => 'sandbox.smtp.mailtrap.io',
            ],
            [
                'group_id'          => 'email',
                'title'             => 'SMTP Port',
                'key'               => 'SMTPPort',
                'value'             => '2525',
            ],
            [
                'group_id'          => 'email',
                'title'             => 'SMTP User',
                'key'               => 'SMTPUser',
                'value'             => '2b7fa8391b51ac',
            ],
            [
                'group_id'          => 'email',
                'title'             => 'SMTP Pass',
                'key'               => 'SMTPPass',
                'value'             => '********301e',
            ]
        ];

        foreach ( $emailConfigData as $configItem ) {
            $_cfModel->insertOrUpdate($configItem);
        }

        /**
         * Default config for e-commerce
         */
        $this->shopConfig();
    }

    public function shopConfig()
    {
        CLI::write('- Thêm cấu hình mặc định cho e-commerce');
        $_cfModel = model(ConfigModel::class);

        $shipConfig = [
            'group_id'          => ShopEnum::CONFIG_GROUP,
            'title'             => 'Phí giao hàng mặc định theo tỉnh thành',
            'key'               => ShopEnum::SHIP_CONFIG_KEY,
            'value'             => 0,
        ];
        $_cfModel->insertOrUpdate($shipConfig);

        $shipConfig = [
            'group_id'          => ShopEnum::CONFIG_GROUP,
            'title'             => 'Phí giao hàng mặc định theo cân nặng',
            'key'               => ShopEnum::WEIGHT_SHIP_CONFIG,
            'value'             => 0,
        ];
        $_cfModel->insertOrUpdate($shipConfig);

        $menuConfig = [
            'group_id'          => 'default',
            'title'             => 'Menu Sản phẩm',
            'key'               => 'menu_location',
            'value'             => 'hero_menu',
        ];
        $check = $_cfModel
            ->where('group_id', $menuConfig['group_id'])
            ->where('key', $menuConfig['key'])
            ->where('value', $menuConfig['value'])
            ->first();
        if ( !isset($check->id) ) $_cfModel->insert($menuConfig);


        $menuConfig = [
            'group_id'          => 'default',
            'title'             => 'Menu footer 1',
            'key'               => 'menu_location',
            'value'             => 'footer_menu_1',
        ];
        $check = $_cfModel
            ->where('group_id', $menuConfig['group_id'])
            ->where('key', $menuConfig['key'])
            ->where('value', $menuConfig['value'])
            ->first();
        if ( !isset($check->id) ) $_cfModel->insert($menuConfig);

        $menuConfig = [
            'group_id'          => 'default',
            'title'             => 'Menu footer 2',
            'key'               => 'menu_location',
            'value'             => 'footer_menu_2',
        ];
        $check = $_cfModel
            ->where('group_id', $menuConfig['group_id'])
            ->where('key', $menuConfig['key'])
            ->where('value', $menuConfig['value'])
            ->first();
        if ( !isset($check->id) ) $_cfModel->insert($menuConfig);
    }
}
