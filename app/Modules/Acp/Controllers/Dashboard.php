<?php
namespace Modules\Acp\Controllers;

use Config\Modules;
use Modules\Acp\Controllers\Traits\itemDelete;
use Modules\Acp\Enums\PostTypeEnum;
use Modules\Acp\Enums\UserTypeEnum;
use Modules\Acp\Models\Blog\PostModel;
use Modules\Acp\Models\Store\Product\ProductModel;
use Modules\Acp\Models\User\UserModel;

class Dashboard extends AcpController {
    use itemDelete;
    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        $userModel = model(UserModel::class);

        $this->_data['title']= 'Dash Board';
        $thisMonth = date('m');


        $this->_data['monthPosts'] = model(PostModel::class)
            ->select('post.*,post_content.*')
            ->join('post_content', 'post_content.post_id = post.id')
            ->where('Month(created_at)', $thisMonth)
            ->where('post_content.lang_id', $this->_data['curLang']->id)
            ->where('post_type', PostTypeEnum::POST)
            ->orderBy('post.id DESC')
            ->findAll(10);

        $this->_data['products'] = model(ProductModel::class)
            ->select('product.*')
            ->orderBy('product.id DESC')
            ->findAll(10);

        $this->_data['userCount'] = count($userModel->where('user_type', UserTypeEnum::ADMIN)->findAll());
        $this->_render('\dashboard\index', $this->_data);
    }

}