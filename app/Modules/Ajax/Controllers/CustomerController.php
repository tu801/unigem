<?php
namespace Modules\Ajax\Controllers;

use App\Models\Store\Customer\CustomerModel;

class CustomerController extends AjaxBaseController {
    public function __construct()
    {
        parent::__construct();

        $this->_model = model(CustomerModel::class);
    }

    public function register() {
        $postData = $this->request->getPost();

        print_r($postData);exit;
    }
}