<?php

namespace App\Database\Seeds\CmsData;

use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Seeder;
use App\Models\Store\ShopModel;
use Faker\Factory;
use App\Enums\CategoryEnum;
use App\Models\Blog\CategoryModel;
use App\Models\LangModel;

class ShopInfor extends Seeder
{
    protected $userModel;
    protected $defaultLang;
    protected $_catModel;

    public function __construct()
    {
        $this->userModel = auth()->getProvider();
        $this->defaultLang = model(LangModel::class)->getPrivLang();
        $this->_catModel = model(CategoryModel::class);
    }

    public function run()
    {
        //get user data
        $user = $this->userModel->findByCredentials(['email' => 'tmtuan801@gmail.com']);

        // create default shop infor with the shop name base on env config site_name and other informations are fake
        $fakerObject = Factory::create();

        $shopName = getenv('app.site_name');
        CLI::write("- Create {$shopName} Shop Infor ");
        $shopModel = model(ShopModel::class);
        
        $checkShop = $shopModel->where('name', $shopName)->first();
        if ($checkShop) {
            CLI::write("- {$shopName} Shop Infor already exists ");

            // start insert category
            CLI::write("----------- Insert Default Categories -----------");
            $this->generateProductCategory($user);
            CLI::write("----------- End Insert Default Categories -----------");

            return;     
        }

        $shopModel->insert([
            'user_init' => $user->id ?? 1,
            'name' => $shopName,
            'phone' => $fakerObject->phoneNumber,
            'address' => $fakerObject->address,
            'status' => 1,
        ]);
    }


    public function generateProductCategory($user)
    {
        helper('acp');

        // add parent category
        $catData = [
            'user_init'         => $user->id??1,
            'user_type'         => get_class($this->userModel),
            'parent_id'         => 0,
            'cat_status'        => CategoryEnum::CAT_STATUS_PUBLISH,
            'cat_type'          => CategoryEnum::CAT_TYPE_PRODUCT,
            'title'             => 'Trang sức',
            'slug'              => 'trang-suc',
        ];
        $checkCat = $this->_catModel->checkSlug($catData['slug'], $this->defaultLang->id);
        if ( $checkCat == 0 ) { 
            $this->_catModel->insertOrUpdate($catData);
            CLI::write("- Insert Category {$catData['title']}");
        }

        $trangsucData = $this->_catModel
                        ->join('category_content', 'category_content.cat_id = category.id')
                        ->where('lang_id', $this->defaultLang->id)
                        ->where('slug', $catData['slug'])
                        ->first();

        $trangsucChildData = ['Nhẫn', 'Bông tai', 'Dây chuyền', 'lắc tay'];
        $this->addChildCategory($trangsucData, $trangsucChildData);

        $daquyData = [
            'user_init'         => $user->id??1,
            'user_type'         => get_class($this->userModel),
            'parent_id'         => 0,
            'cat_status'        => CategoryEnum::CAT_STATUS_PUBLISH,
            'cat_type'          => CategoryEnum::CAT_TYPE_PRODUCT,
            'title'             => 'Đá Quý',
            'slug'              => 'da-quy',
        ];
        $checkCat = $this->_catModel->checkSlug($daquyData['slug'], $this->defaultLang->id);
        if ( $checkCat == 0 ) { 
            $this->_catModel->insertOrUpdate($daquyData);
            CLI::write("- Insert Category {$daquyData['title']}");
        }
        $daquyItem = $this->_catModel
                        ->join('category_content', 'category_content.cat_id = category.id')
                        ->where('lang_id', $this->defaultLang->id)
                        ->where('slug', $daquyData['slug'])
                        ->first();
        $daquyChildData = ['Morganite', 'Aquamarine', 'Tourmaline', 'Heliodor', 'Garnet', 'Peridot', 'Zircon', 'Emerald'];
        $this->addChildCategory($daquyItem, $daquyChildData);

    }

    private function addChildCategory($parentCat, $childCat) {
        foreach ($childCat as  $catTitle) {
            $catData = [
                'user_init'         => $user->id??1,
                'user_type'         => get_class($this->userModel),
                'parent_id'         => $parentCat->id,
                'cat_status'        => CategoryEnum::CAT_STATUS_PUBLISH,
                'cat_type'          => CategoryEnum::CAT_TYPE_PRODUCT,
                'title'             => $catTitle,
                'slug'              => clean_url($catTitle),
            ];
            $checkCat = $this->_catModel->checkSlug($catData['slug'], $this->defaultLang->id);
            if ( $checkCat == 0 ) { 
                $this->_catModel->insertOrUpdate($catData);
                CLI::write("- Insert Category {$catData['title']}");
            }
        }
    }
}
