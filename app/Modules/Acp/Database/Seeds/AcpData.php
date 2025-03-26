<?php
namespace Modules\Acp\Database\Seeds;

use CodeIgniter\CLI\CLI;
use CodeIgniter\Test\Fabricator;
use Modules\Acp\Models\Blog\CategoryModel;
use Modules\Acp\Models\LangModel;
use Modules\Acp\Models\User\UsergModel;
use Modules\Acp\Models\User\UserModel;
use Modules\Auth\Password;
use CodeIgniter\I18n\Time;

class AcpData extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        //insert defaut group
        $_userGroup = model(UsergModel::class);
        $data = [
            'name'              => 'Administrator',
            'description'       => 'Nhóm Admin Cấp Cao',
        ];
        $_userGroup->insertOrUpdate($data);
        $data = [
            'name'              => 'Sale Manager',
            'description'       => 'Nhóm Kinh doanh',
        ];
        $_userGroup->insertOrUpdate($data);
        $data = [
            'name'              => 'Content',
            'description'       => 'Nhóm Quản Lý Nội Dung',
        ];
        $_userGroup->insertOrUpdate($data);

        //insert default user data
        $_userModel = new UserModel();

        helper(['text']);
        $faker = new Fabricator(UserModel::class);
        $expires = Time::now();
        $expires->addDays(config('Auth')->resetTime);
        $override = [
            'gid'              => random_int(1, 3),
            'active'           => 0,
            'activate_hash'    => null,
            'force_pass_reset' => 1,
            'reset_at'         => null,
            'reset_expires'    => $expires->format('Y-m-d H:i:s'),
            'reset_hash'       => bin2hex(random_bytes(16)),
            'status'           => null,
            'status_message'   => null,
            'avatar'           => null,
        ];
        $faker->setOverrides($override);
        $users = $faker->make(6);

        foreach ($users as $user) {
            $user->username = str_replace('.','',$user->username);
            $user->password_hash = Password::hash('123123');
            $_userModel->insert($user);
        }
        CLI::write("- Insert Default Users Groups ");
        
        //insert defaut user with pw 1234qwer@#$
        $userData = [
            'username'          => 'admin',
            'email'             => 'admin@yahoo.com',
            'password_hash'     => Password::hash('1234qwer@#$'),
            'fullname'          => 'admin',
            'birthday'          => date('Y-m-d'),
            'mobile'            => '123456',
            'address'           => 'demo',
            'reset_hash'        => md5('demo'),
            'gid'               => 1,
            'root_user'         => 1,
            'active'            => 1,
            'active_at'         => date('Y-m-d H:i:s'),
            'force_pass_reset'  => 0,
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ];
        $usrCheck = $_userModel->where('username', 'admin')->first();
        if (!isset($usrCheck->id)) $_userModel->insert($userData);
        //insert defaut user with pw 123qwe!@#
        $userData = [
            'username'          => 'tmtuan',
            'email'             => 'tmtuan801@gmail.com',
            'password_hash'     => Password::hash('123qwe!@#'),
            'fullname'          => 'Trần Mạnh Tuấn',
            'birthday'          => date('Y-m-d'),
            'mobile'            => '123456',
            'address'           => 'demo',
            'reset_hash'        => md5('demo'),
            'gid'               => 1,
            'root_user'         => 1,
            'active'            => 1,
            'active_at'         => date('Y-m-d H:i:s'),
            'force_pass_reset'  => 0,
            'created_at'        => date('Y-m-d H:i:s'),
        ];
        $usrCheck = $_userModel->where('username', 'tmtuan')->first();
        if (!isset($usrCheck->id)) $_userModel->insert($userData);
        CLI::write("- Insert Default Users ");

        //get user data
        $user = $_userModel->where('username', 'admin')->first();

        //insert language
        $this->__setLanguages($user, $_userModel);
        $lang = model(LangModel::class)->getPrivLang();

        //insert default category
        $_catModel = model(CategoryModel::class);

        $catData = [
            'user_init'         => $user->id??1,
            'user_type'         => get_class($_userModel),
            'parent_id'         => 0,
            'cat_status'        => 'draft',
            'title'             => 'Uncategory',
            'slug'              => 'uncategory',
            'lang_id'           => $lang->id??1,
        ];
        $_catModel->insertOrUpdate($catData);
        CLI::write("- Insert Default Categories ");

    }

    /**
     * insert supported language
     * @param $user
     * @param $_userModel
     */
    private function __setLanguages($user, $_userModel)
    {
        CLI::write("- Insert Supported Languages");
        $_langModel = model(LangModel::class);
        $langData = [
            0 => [
                'user_init'         => $user->id??1,
                'user_type'         => get_class($_userModel),
                'name'              => 'Tiếng Việt',
                'locale'            => 'vi',
                'lang_code'         => 'vi-VN',
                'flag'              => 'vn.svg',
                'order'             => 1,
                'is_activated'      => 1,
                'is_default'        => 1
            ],
            1 => [
                'user_init'         => $user->id??1,
                'user_type'         => get_class($_userModel),
                'name'              => 'Tiếng Anh',
                'locale'            => 'en',
                'lang_code'         => 'en-US',
                'flag'              => 'us.svg',
                'order'             => 2,
                'is_activated'      => 0,
                'is_default'        => 0
            ],
        ];

        try {
            foreach ($langData as $lang) {
                $_langModel->insertOrUpdate($lang);
            }
        } catch (\Exception $e) {
            log_message('error', '[ERROR] {exception}', ['exception' => $e]);
        }
        
    }
}
