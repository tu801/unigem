<?php

namespace App\Database\Seeds\CmsData;

use CodeIgniter\CLI\CLI;
use App\Models\LangModel;
use Faker\Factory;
use CodeIgniter\Shield\Entities\User;
use Modules\Acp\Models\Blog\CategoryModel;

class AcpData extends \CodeIgniter\Database\Seeder
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = auth()->getProvider();
    }

    public function run()
    {
        //insert default user data
        $this->generateDefaultUsers();

        //get user data
        $user = $this->userModel->findByCredentials(['email' => 'tmtuan801@gmail.com']);

        //insert language
        $this->__setLanguages($user, $this->userModel);
        $lang = model(LangModel::class)->getPrivLang();

        //insert default category
        $_catModel = model(CategoryModel::class);

        $catData = [
            'user_init'         => $user->id ?? 1,
            'user_type'         => get_class($this->userModel),
            'parent_id'         => 0,
            'cat_status'        => 'draft',
            'title'             => 'Uncategory',
            'slug'              => 'uncategory',
            'lang_id'           => $lang->id ?? 1,
        ];
        $checkCat = $_catModel->checkSlug($catData['slug'], $lang->id);
        if ($checkCat == 0) {
            $_catModel->insertOrUpdate($catData);
            CLI::write("- Insert Default Categories ");
        }
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
                'user_init'         => $user->id ?? 1,
                'user_type'         => get_class($_userModel),
                'name'              => 'Tiếng Việt',
                'locale'            => 'vi',
                'lang_code'         => 'vi-VN',
                'flag'              => 'vn.svg',
                'order'             => 1,
                'is_activated'      => 1,
                'is_default'        => 1,
                'currency_code'     => 'VND',
                'currency_symbol'   => '₫',
            ],
            1 => [
                'user_init'         => $user->id ?? 1,
                'user_type'         => get_class($_userModel),
                'name'              => 'English',
                'locale'            => 'en',
                'lang_code'         => 'en-US',
                'flag'              => 'us.svg',
                'order'             => 2,
                'is_activated'      => 0,
                'is_default'        => 0,
                'currency_code'     => 'USD',
                'currency_symbol'   => '$',
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

    /**
     * generate default users
     */
    public function generateDefaultUsers()
    {
        //insert default user data
        CLI::write("----- insert default User Data -----");

        if ( getenv('CI_ENVIRONMENT') === 'development' ) {
           
            for ($i = 0; $i < 8; $i++) {
                $userData = $this->generateFakerUser('123123');
                $userData['username'] = str_replace('.', '', $userData['username']);

                CLI::write("- Create Users {$userData['username']} | {$userData['email']} ");
                $user = new User($userData);
                $this->userModel->save($user);
                $newUser = $this->userModel->findById($this->userModel->getInsertID());

                $this->userModel->addToDefaultGroup($newUser);
                $newUser->forcePasswordReset();
            }
        }

        //insert defaut user admin with pw 1234qwer@#$
        $userData = [
            'username'          => 'admin',
            'email'             => 'admin@yahoo.com',
            'password'          => '1234qwer@#$',
        ];
        $usrCheck = $this->userModel->where('username', 'admin')->first();
        if (!isset($usrCheck->id)) {
            $this->saveUser($userData, 'admin');
        }

        //insert defaut user superadmin with pw 123qwe!@#
        $userData = [
            'username'          => 'tmtuan',
            'email'             => 'tmtuan801@gmail.com',
            'password'          => '123qwe!@#',
        ];
        $usrCheck = $this->userModel->where('username', 'tmtuan')->first();
        if (!isset($usrCheck->id)) {
            $this->saveUser($userData, 'superadmin');
        }
    }

    private function generateFakerUser($defaultPass = null)
    {
        $fakerObject = Factory::create();

        return [
            'username'          => $fakerObject->userName(),
            'email'             => $fakerObject->unique()->email(),
            'password'          => $defaultPass,
            'avatar'            => "https://i.pravatar.cc/400?u=" . time(),
        ];
    }

    private function saveUser($userData, $group)
    {
        $user = new User($userData);
        CLI::write("- Create Users {$user->username} | {$user->email} ");
        $this->userModel->save($user);
        $newUser = $this->userModel->findById($this->userModel->getInsertID());
        $newUser->addGroup($group);
    }
}
