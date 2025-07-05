<?php

/**
 * @author tmtuan
 * created Date: 10/23/2023
 * Project: Unigem
 */

namespace Modules\Acp\Controllers\Store\Order;


use Config\Database;
use Modules\Acp\Controllers\AcpController;
use App\Enums\Store\Order\EPaymentStatus;
use App\Enums\Store\ShopEnum;
use App\Models\ConfigModel;
use App\Models\Country;
use App\Models\Store\Customer\CustomerModel;
use App\Models\Store\ExchangeRateModel;
use App\Models\Store\Order\OrderItemModel;
use App\Models\Store\Order\OrderModel;
use Modules\Acp\Models\Store\Product\ProductModel;
use App\Models\Store\ShopModel;
use Modules\Acp\Traits\deleteItem;

class OrderController extends AcpController
{
    use deleteItem;

    protected $db;
    protected $_shopModel;
    protected $_customerModel;
    protected $_productModel;
    protected $_configModel;
    protected $_orderItemModel;
    protected $_exchangeRateModel;
    protected $_countryModel;

    public function __construct()
    {
        parent::__construct();
        $this->_model          = model(OrderModel::class);
        $this->_shopModel      = model(ShopModel::class);
        $this->_customerModel  = model(CustomerModel::class);
        $this->_productModel   = model(ProductModel::class);
        $this->_configModel    = model(ConfigModel::class);
        $this->_orderItemModel = model(OrderItemModel::class);
        $this->_exchangeRateModel = model(ExchangeRateModel::class);
        $this->_countryModel   = model(Country::class);
        $this->db              = Database::connect(); //Load database connection
    }

    public function index()
    {
        $this->_data['title'] = lang("Order.page_title");
        $inputData = $this->request->getGet();

        switch ($inputData['listType'] ?? '') {
            case 'all':
                $this->_data['listType'] = 'all';
                break;
            case 'deleted':
                $this->_model->onlyDeleted();
                $this->_data['listType'] = 'deleted';
                break;
            case 'user':
            default:
                $this->_model->where("user_init", $this->user->id);
                $this->_data['listType'] = 'user';
                break;
        }


        // get order
        if (isset($inputData['keyword']) && $inputData['keyword'] != '') {
            $keyword = esc($inputData['keyword']);
            $this->_model->like('order.code', $keyword)
                ->orLike('order.title', $keyword)
                ->orLike('customer.cus_phone', $keyword)
                ->orLike('customer.cus_full_name', $keyword);
            $this->_data['search_title'] = $keyword;
        }

        if (!empty($inputData['shop_id'])) {
            $shopID = $inputData['shop_id'];
            $this->_model->where('shop_id', $shopID);
        }

        if (!empty($inputData['status'])) {
            $status = $inputData['status'];
            $this->_model->where('status', $status);
        }
        if (!empty($inputData['payment_status'])) {
            $paymentStatus = $inputData['payment_status'];
            $this->_model->where('payment_status', $paymentStatus);
        }

        if (isset($postData) && !empty($postData)) {
            if (!empty($postData['sel'])) {
                $this->_model->delete($postData['sel']);
            } else return redirect()->back()->with('error', lang('Acp.no_item_to_delete'));
        }
        $this->_model
            ->join('customer', 'customer.id = order.customer_id')
            ->join('order_items', 'order.order_id = order_items.order_id')
            ->groupBy('order.order_id')
            ->select('order.*, COUNT(order_items.order_id) as count_product, customer.cus_full_name, customer.cus_phone')
            ->orderBy('order_id', 'desc');

        $this->_data['data']     = $this->_model->paginate();
        $this->_data['pager']    = $this->_model->pager;
        $this->_data['countAll'] = $this->_model->countAll();
        $this->_render('\store\order\index', $this->_data);
    }

    public function ajxRemove()
    {
        $response = [];
        $postData = $this->request->getPost();
        if (!isset($postData['id']) || empty($postData['id'])) return $this->response->setJSON(['error' => 1, 'message' => lang('Acp.invalid_request')]);

        $item = $this->_model->where('order_id', $postData['id'])->first();
        if (!isset($item->order_id) || empty($item)) {
            $response['error'] = 1;
            $response['message'] = lang('Acp.no_item');
        } else {
            if ($this->_model->where('order_id', $item->order_id)->delete()) {
                //log Action
                if (method_exists(__CLASS__, 'logAction')) {
                    $prop    = method_exists(get_class($item), 'toArray') ? $item->toArray() : (array) $item;
                    $logData = [
                        'title'        => 'Delete',
                        'description'  => lang('Acp.delete_success', [$item->order_id]),
                        'properties'   => $prop,
                        'subject_id'   => $item->order_id,
                        'subject_type' => get_class($this->_model),
                    ];
                    $this->logAction($logData);
                }
                $response['error']   = 0;
                $response['message'] = lang('Acp.delete_success', [$item->order_id]);
            } else {
                $response['error']   = 1;
                $response['message'] = lang('Acp.delete_fail');
            }
        }
        return $this->response->setJson($response);
    }

    /**
     * Recover an item
     */
    public function recover($idItem)
    {
        $item = $this->_model->withDeleted()->where('order_id', $idItem)->first();

        if (isset($item->order_id)) {
            //check permission
            if (!$this->user->inGroup('superadmin', 'admin')) return redirect()->route('dashboard')->with('error', lang('Acp.no_permission'));

            if ($this->_model->recover($item->order_id)) {
                //log Action
                if (method_exists(__CLASS__, 'logAction')) {
                    $prop = method_exists(get_class($item), 'toArray') ? $item->toArray() : (array)$item;
                    $logData = [
                        'title' => 'Recover',
                        'description' => lang('Acp.recover_success', [$item->order_id]),
                        'properties' => $prop,
                        'subject_id' => $item->order_id,
                        'subject_type' => get_class($this->_model),
                    ];
                    $this->logAction($logData);
                }
                return redirect()->back()->with('message', lang('Acp.recover_success', [$item->order_id]));
            } else return redirect()->back()->with('error', lang('Acp.recover_fail'));
        } else return redirect()->back()->with('error', lang('Acp.invalid_request'));
    }

    public function createOrder()
    {
        $shops = $this->_shopModel->where('status', ShopEnum::STATUS['active'])->findAll();
        $this->_data['shops']    = $shops;
        $this->_data['title'] = lang("Order.add_title");

        $this->_render('\store\order\create', $this->_data);
    }

    public function ruleValidate($isUpdate = false)
    {
        $validRules = [
            'delivery_type'  => 'required',
            'shop_id'        => 'required',
            'province_id'    => 'permit_empty',
            'district_id'    => 'permit_empty',
            'ward_id'        => 'permit_empty',
            'address'        => 'permit_empty',
            'title'          => 'permit_empty',
            'note'           => 'permit_empty',
            'status'         => 'required',
            'payment_status' => 'required',
            'payment_method' => 'required',
            'voucher_code'   => 'permit_empty',
            'customer_paid'  => 'permit_empty',
        ];

        if ( !$isUpdate ) {
            $validRules['full_name'] = 'required';
            $validRules['phone']     = 'required|is_unique[customer.cus_phone]';
            $validRules['email']     = 'permit_empty|valid_email|is_unique[customer.cus_email]';
        } 
        
        return $validRules;
    }

    public function messageValidate()
    {
        return [
            'full_name'      => [
                'required' => lang('Order.full_name_required'),
            ],
            'phone'          => [
                'required' => lang('Order.phone_required'),
                'is_unique' => lang('Customer.phone_is_unique'),
            ],
            'email'          => [
                'valid_email' => lang('Customer.valid_email'),
                'is_unique' => lang('Customer.email_exits')
            ],
            'delivery_type'  => [
                'required' => lang('Order.delivery_type_required'),
            ],
            'shop_id'        => [
                'required' => lang('Order.delivery_type_required'),
            ],
            'province_id'    => [
                'required' => lang('Order.province_id_required'),
            ],
            'district_id'    => [
                'required' => lang('Order.district_id_required'),
            ],
            'ward_id'        => [
                'required' => lang('Order.ward_id_required'),
            ],
            'address'        => [
                'required' => lang('Order.address_required'),
            ],
            'title'          => [],
            'note'           => [],
            'customer_paid'  => [],
            'status'         => [
                'required' => lang('Order.status_required'),
            ],
            'payment_status' => [
                'required' => lang('Order.payment_status_required'),
            ],
            'payment_method' => [
                'required' => lang('Order.payment_method_required'),
            ],
            'voucher_code'   => [],
        ];
    }

    public function getOrderItem($id)
    {
        $response = [];
        $order    = $this->_model->find($id);
        if (isset($order->order_id)) {
            $data      = [];
            $orderItem = $this->_orderItemModel->where('order_id', $id)->findAll();
            foreach ($orderItem as $item) {
                $product               = $this->_productModel->getProductItemById($item->product_id);
                $product->quantity     = (int) $item->quantity;
                $product->product_meta = $product->product_meta;
                $data[]                = $product;
            }

            $response['error'] = 0;
            $response['data']  = $data;
        } else {
            $response['error']   = 1;
            $response['message'] = lang('Order.order_not_exist');
        }
        return $this->response->setJSON($response);
    }


    public function viewDeposit($orderID)
    {
        $order = $this->_model->where('order_id', $orderID)->first();

        if (!isset($order->order_id) && $order->payment_status != EPaymentStatus::DEPOSIT) {
            return redirect()->route('order')->with('error', lang('Order.order_not_exist'));
        }
        $orderDeposit = $this->_paymentBanktranferModel->where('mod_id', $order->order_id)->first();
        if (isset($orderDeposit)) {
            $orderDepositDetail           = [
                'bank'  => json_decode($orderDeposit->bank_receiver),
                'image' => $orderDeposit->pmb_transfer_image,
            ];
            $this->_data['order_deposit'] = $orderDepositDetail;
        }

        $this->_data['order'] = $order;
        $this->_data['title'] = lang("Order.deposit_title");
        $this->_render('\store\order\view_deposit', $this->_data);
    }
}
