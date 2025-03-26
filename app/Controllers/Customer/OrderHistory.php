<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 11/12/2023
 */

namespace App\Controllers\Customer;


use App\Controllers\BaseController;
use App\Libraries\BreadCrumb\BreadCrumbCell;
use Modules\Acp\Enums\UserTypeEnum;
use Modules\Acp\Models\Store\Order\OrderModel;
use Modules\Acp\Models\Store\Product\ProductModel;
use Modules\Auth\Config\Services;

class OrderHistory extends BaseController
{
    private $_modelProduct;

    public function __construct()
    {
        parent::__construct();
        $this->auth    = Services::authentication();
        $this->_model  = model(OrderModel::class);
        $this->_modelProduct  = model(ProductModel::class);

    }

    public function listOrder()
    {
        if (!$this->auth->check()) {
            return redirect()->route('cus_login');
        }

        if ( $this->user->user_type == UserTypeEnum::ADMIN ) {
            return redirect()->to(base_url());
        }

        //set breadcrumb
        BreadCrumbCell::add('Home', base_url());
        BreadCrumbCell::add(lang('OrderShop.order_history_title'), route_to('order_history'));

        $this->_data['user'] = $this->customer;

        return $this->_render('customer/order_history', $this->_data);
    }

    public function detail($id)
    {
        if (!$this->auth->check()) {
            return redirect()->route('cus_login');
        }

        if ( $this->user->user_type == UserTypeEnum::ADMIN ) {
            return redirect()->to(base_url());
        }

        //set breadcrumb
        BreadCrumbCell::add('Home', base_url());
        BreadCrumbCell::add(lang('OrderShop.order_history_title'), route_to('order_history'));

        $this->_data['order'] = $this->_model->where('order_id', $id)->first();
        $this->_data['orderItem'] = $this->_modelProduct->join('order_items', 'product.id = order_items.product AND order_items.order_id = '.$id)->findAll();
        $this->_data['user'] = $this->customer;
        return $this->_render('customer/order_history_detail', $this->_data);
    }
}