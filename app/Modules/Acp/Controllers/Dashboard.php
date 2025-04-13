<?php
namespace Modules\Acp\Controllers;

use App\Models\User\UserModel;
use Modules\Acp\Controllers\Traits\itemDelete;
use App\Enums\Post\PostTypeEnum;
use App\Enums\UserTypeEnum;
use App\Models\Blog\PostModel;
use App\Models\Store\Product\ProductModel;

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
            ->select('product.*, pdc.pd_name ')
            ->join('product_content AS pdc', 'pdc.product_id = product.id')
            ->where('pdc.lang_id', $this->_data['curLang']->id)
            ->orderBy('product.id DESC')
            ->findAll(10);

        $this->_data['userCount'] = count($userModel->where('user_type', UserTypeEnum::ADMIN)->findAll());
        $this->_render('\dashboard\index', $this->_data);
    }

}