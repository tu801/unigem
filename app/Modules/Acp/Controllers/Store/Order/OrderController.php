<?php
/**
 * @author tmtuan
 * created Date: 10/23/2023
 * Project: Unigem
 */

namespace Modules\Acp\Controllers\Store\Order;


use CodeIgniter\Database\Exceptions\DatabaseException;
use Config\Database;
use Modules\Acp\Controllers\AcpController;
use App\Enums\Store\Order\EDeliveryType;
use App\Enums\Store\Order\EOrderStatus;
use App\Enums\Store\Order\EPaymentMethod;
use App\Enums\Store\Order\EPaymentStatus;
use App\Enums\Store\Order\EUnitPrice;
use App\Enums\Store\Product\EProductType;
use App\Enums\Store\Promotion\EVoucherStatus;
use App\Enums\Store\Promotion\PromotionEnum;
use App\Enums\Store\ShopEnum;
use App\Models\ConfigModel;
use App\Models\PaymentBankTransferModel;
use App\Models\Store\Customer\CustomerModel;
use App\Models\Store\Order\OrderItemModel;
use App\Models\Store\Order\OrderModel;
use App\Models\Store\Product\ProductModel;
use App\Models\Store\Promotion\PromotionVoucherModel;
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
    private   $_promotionVoucherModel;
    private $_paymentBanktranferModel;

    public function __construct()
    {
        parent::__construct();
        $this->_model          = model(OrderModel::class);
        $this->_shopModel      = model(ShopModel::class);
        $this->_customerModel  = model(CustomerModel::class);
        $this->_productModel   = model(ProductModel::class);
        $this->_configModel    = model(ConfigModel::class);
        $this->_orderItemModel = model(OrderItemModel::class);
        $this->_promotionVoucherModel = model(PromotionVoucherModel::class);
        $this->db              = Database::connect(); //Load database connection
        $this->_paymentBanktranferModel = model(PaymentBankTransferModel::class);

    }

    public function index()
    {
        $this->_data['title']= lang("Order.page_title");
        $inputData = $this->request->getGet();

        if (isset($inputData['listtype'])) {
            switch ($inputData['listtype']) {
                case 'all':
                    $this->_data['listtype'] = 'all';
                    break;
                case 'deleted':
                    $this->_model->onlyDeleted();
                    $this->_data['listtype'] = 'deleted';
                    break;
                case 'user':
                    $this->_model->where("user_init", $this->user->id);
                    $this->_data['listtype'] = 'user';
                    break;
            }
        } else {
            $this->_model->where("user_init", $this->user->id);
            $this->_data['listtype'] = 'user';
        }


        // get order
        if ( isset($inputData['keyword']) && $inputData['keyword'] != '') {
            $keyword = esc($inputData['keyword']);
            $this->_model->like('order.code', $keyword)
                         ->orLike('order.title', $keyword)
                         ->orLike('customer.cus_phone', $keyword)
                         ->orLike('customer.cus_full_name', $keyword);
            $this->_data['search_title'] = $keyword;
        }

        if ( !empty($inputData['shop_id'])) {
            $shopID = $inputData['shop_id'];
            $this->_model->where('shop_id', $shopID);
        }

        if ( !empty($inputData['status'])) {
            $status = $inputData['status'];
            $this->_model->where('status', $status);
        }
        if ( !empty($inputData['payment_status'])) {
            $paymentStatus = $inputData['payment_status'];
            $this->_model->where('payment_status', $paymentStatus);
        }

        if ( isset($postData) && !empty($postData) ) {
            if ( !empty($postData['sel']) ) {
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

    public function ajxRemove() {
        $response = [];
        $postData = $this->request->getPost();
        if ( !isset($postData['id']) || empty($postData['id']) ) return $this->response->setJSON(['error' => 1, 'message' => lang('Acp.invalid_request')]);

        $item = $this->_model->where('order_id',$postData['id'])->first();
        if ( !isset($item->order_id) || empty($item) ) {
            $response['error'] = 1;
            $response['message'] = lang('Acp.no_item');
        }
        else {
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
    public function recover($idItem){
        $item = $this->_model->withDeleted()->where('order_id', $idItem)->first();

        if ( isset($item->order_id) ) {
            //check permission
            if (!$this->user->inGroup('superadmin', 'admin')) return redirect()->route('dashboard')->with('error', lang('Acp.no_permission'));

            if ( $this->_model->recover($item->order_id) ) {
                //log Action
                if ( method_exists(__CLASS__,'logAction') ) {
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
            }
            else return redirect()->back()->with('error', lang('Acp.recover_fail'));

        } else return redirect()->back()->with('error', lang('Acp.invalid_request'));
    }

    public function addOrder()
    {
        $shops = $this->_shopModel->where('status', ShopEnum::STATUS['active'])->findAll();
        $this->_data['shops']    = $shops;
        $this->_data['title']= lang("Order.add_title");
        $this->_render('\store\order\add', $this->_data);
    }

    public function createOrder() {
        $shops = $this->_shopModel->where('status', ShopEnum::STATUS['active'])->findAll();
        $this->_data['shops']    = $shops;
        $this->_data['title']= lang("Order.add_title");
        $this->_render('\store\order\create', $this->_data);
    }

    public function addAction()
    {
        $inputData = $this->request->getPost();
        $rules     = $this->ruleValidate();
        $errMess   = $this->messageValidate();

        if(isset($inputData['delivery_type']) && $inputData['delivery_type'] == EDeliveryType::HOME_DELIVERY) {
            $rules['province_id'] = 'required';
            $rules['district_id'] = 'required';
            $rules['ward_id']     = 'required';
            $rules['address']     = 'required';
        }

        //validate the input
        if (!$this->validate($rules, $errMess)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        try {
            $this->db->transBegin();

            // create customer
            $customer = $this->_customerModel->where('cus_phone', $inputData['phone'])->first();
            if (isset($customer->id)) {
                $customerID = $customer->id;
            }else {
                $customerData = [
                    'cus_code'      => $this->_customerModel->generateCode(),
                    'cus_full_name' => $inputData['full_name'],
                    'cus_phone'     => $inputData['phone'],
                    'cus_email'     => $inputData['email'] ?? null,
                ];
                $customerID = $this->_customerModel->insert($customerData);
            }

            // prepare data order
            $dataOrder = [
                'user_init'      => $this->user->id,
                'customer_id'    => $customerID,
                'code'           => $this->_model->generateCode(),
                'title'          => $inputData['title'],
                'note'           => $inputData['note'],
                'delivery_type'  => $inputData['delivery_type'],
                'shop_id'        => $inputData['shop_id'],
                'status'         => $inputData['status'] ?? EOrderStatus::OPEN,
                'payment_status' => $inputData['payment_status'] ?? EPaymentStatus::UNPAID,
                'payment_method' => $inputData['payment_method'] ?? EPaymentMethod::BANK_TRANSFER,
            ];

            if ($inputData['status'] == EOrderStatus::COMPLETE && $inputData['payment_status'] != EPaymentStatus::PAID) {
                $this->db->transRollback();
                return redirect()->back()->withInput()->with('errors', ['status' => lang('Order.success_if_payment_paid')]);
            }

            if ($inputData['payment_status'] == EPaymentStatus::DEPOSIT) {
                $dataOrder['customer_paid'] = $inputData['customer_paid'];
            }

            if ($inputData['delivery_type'] == EDeliveryType::HOME_DELIVERY) {
                $deliveryInfo = json_encode([
                    'name'        => $inputData['full_name'],
                    'phone'       => $inputData['phone'],
                    'province_id' => $inputData['province_id'],
                    'district_id' => $inputData['district_id'],
                    'ward_id'     => $inputData['ward_id'],
                    'address'     => $inputData['address'],
                ]);
                $dataOrder['delivery_info'] = $deliveryInfo;
            }

            // customer
            $customerInfo = json_encode([
                'name'        => $inputData['full_name'] ?? '',
                'phone'       => $inputData['phone'] ?? '',
                'email'       => $inputData['email'] ?? '',
                'province_id' => $inputData['province_id'],
                'district_id' => $inputData['district_id'],
                'ward_id'     => $inputData['ward_id'],
                'address'     => $inputData['address'],
            ]);
            $dataOrder['customer_info'] = $customerInfo;

            $totalAmount        = 0;
            $priceProductTotal  = 0;
            $weightProductTotal = 0;
            $orderItems          = [];
            // product bill
            foreach ($inputData['product'] as $item) {
                $quantity  = $item['quantity'];
                $productID = $item['product_id'];
                $product   = $this->_productModel->find($productID);
                if(isset($product->id)) {
                    $priceProduct       = ($product->price_discount > 0 && $product->price_discount < $product->price) ? $product->price_discount : $product->price * $quantity;
                    $priceProductTotal  += $priceProduct;
                    $weightProductTotal += $product->product_meta['weight'] * $quantity;

                    // order item
                    $orderItems[] = [
                        'product'    => $productID,
                        'unit_price' => EUnitPrice::VND,
                        'quantity'   => $quantity,
                        'total'      => $priceProduct,
                        'pd_type'    => EProductType::PRODUCT,
                    ];
                }
            }
            $totalAmount += $priceProductTotal;
            $subAmount   = $totalAmount;

            // shipping bill
            if ($inputData['delivery_type'] == EDeliveryType::HOME_DELIVERY) {
                $shipFeeProvince = $this->_configModel->getShipFee($inputData['province_id']);
                $shipFeeOnWeight = $this->_configModel->getShipFeeOnWeight();
                $totalShipFee    = ($weightProductTotal * $shipFeeOnWeight) + $shipFeeProvince;
                $totalAmount     += $totalShipFee;
                $dataOrder['shipping_amount'] = $totalShipFee;
            }

            // discount
            if(isset($inputData['voucher_code'])) {
                $voucherCode = $inputData['voucher_code'];
                $voucher = $this->_promotionVoucherModel
                    ->join('customer_voucher', 'customer_voucher.voucher_id = promotion_voucher.voucher_id', 'LEFT')
                    ->where('voucher_code', $voucherCode)
                    ->first();
                if(isset($voucher) && $voucher->voucher_discount_type != PromotionEnum::DISCOUNT_TYPE_FREE_GIFT &&
                    $voucher->voucher_status == EVoucherStatus::UNUSED) {
                    if ($voucher->voucher_discount_type == PromotionEnum::DISCOUNT_TYPE_PERCENT) {
                        $discount = $totalAmount * ($voucher->voucher_discount_value / 100);
                    }
                    if ($voucher->voucher_discount_type == PromotionEnum::DISCOUNT_TYPE_VALUE) {
                        $discount = $voucher->voucher_discount_value;
                    }
                    $totalAmount -= $discount;
                    $dataOrder['discount_amount'] = $discount;
                    $dataOrder['voucher_code'] = $voucherCode;
                    $this->_promotionVoucherModel->where('voucher_code', $voucherCode)->set(['voucher_status' => EVoucherStatus::USED])->update();
                }
            }

            $dataOrder['sub_total']       = $subAmount;
            $dataOrder['total']           = $totalAmount;

            if ($inputData['payment_status'] == EPaymentStatus::PAID && $inputData['customer_paid'] != $totalAmount) {
                $this->db->transRollback();
                return redirect()->back()->withInput()->with('errors', ['customer_paid' => lang('Order.payment_paid_if_customer_paid', [number_format($totalAmount)])]);
            }
            $dataOrder['customer_paid'] = $inputData['customer_paid'] ?? 0;

            $orderID = $this->_model->insert($dataOrder);

            // save order items
            foreach ($orderItems as $item) {
                $item['order_id'] = $orderID;
                $this->_orderItemModel->insert($item);
            }

            $this->db->transCommit();
        } catch (DatabaseException $e) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('errors', $this->_model->errors());
        }

        $item = $this->_model->where('order_id', $orderID)->first();
        //log Action
        $logData = [
            'title'        => 'Add Order',
            'description'  => "#{$this->user->username} đã thêm order #{$item->order_id}",
            'properties'   => $item->toArray(),
            'subject_id'   => $item->order_id,
            'subject_type' => OrderModel::class,
        ];
        $this->logAction($logData);
        if (isset($inputData['save'])) return redirect()->route('edit_order', [$item->order_id])->with('message', lang('Order.addSuccess', [$item->order_id]));
        else if (isset($inputData['save_exit'])) return redirect()->route('order')->with('message', lang('Order.addSuccess', [$item->order_id]));
        else if (isset($inputData['save_addnew'])) return redirect()->route('add_order')->with('message', lang('Order.addSuccess', [$item->order_id]));

    }

    private function ruleValidate(){
        return [
            'full_name'      => 'required',
            'phone'          => 'required',
            'email'          => 'permit_empty',
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
            'product'        => 'required',
        ];
    }

    private function messageValidate()
    {
        return [
            'full_name'      => [
                'required' => lang('Order.full_name_required'),
            ],
            'phone'          => [
                'required' => lang('Order.phone_required'),
            ],
            'email'          => [],
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
            'product'        => [
                'required' => lang('Order.product_required'),
            ],
        ];
    }

    public function editOrder($id)
    {
        $shops = $this->_shopModel->where('status', ShopEnum::STATUS['active'])->findAll();
        $order = $this->_model->where('order_id', $id)->join('customer', 'customer.id = order.customer_id')->first();

        $this->_data['voucher'] = null;
        if (isset($order->voucher_code)) {
            $voucher                = $this->_promotionVoucherModel->where('voucher_code', $order->voucher_code)->first();
            $this->_data['voucher'] = $voucher;
        }
        $this->_data['shops'] = $shops;
        $this->_data['title'] = lang('Order.edit_title');
        if (isset($order->order_id)) {
            $this->_data['order'] = $order;
            $this->_render('\store\order\edit', $this->_data);
        } else {
            return redirect()->route('order')->with('error', lang('Order.order_not_exist'));
        }
    }

    public function editAction($orderID)
    {
        $inputData = $this->request->getPost();
        $rules     = $this->ruleValidate();
        $errMess   = $this->messageValidate();

        if(isset($inputData['delivery_type']) && $inputData['delivery_type'] == EDeliveryType::HOME_DELIVERY) {
            $rules['province_id'] = 'required';
            $rules['district_id'] = 'required';
            $rules['ward_id']     = 'required';
            $rules['address']     = 'required';
        }
        //validate the input
        if (!$this->validate($rules, $errMess)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $order                = $this->_model->where('order_id', $orderID)->join('customer', 'customer.id = order.customer_id')->first();

        if (!isset($order->order_id)) {
            return redirect()->route('order')->with('error', lang('Order.order_not_exist'));
        }

        try {
            $this->db->transBegin();

            // prepare data order
            $dataOrder = [
                'title'          => $inputData['title'],
                'note'           => $inputData['note'],
                'delivery_type'  => $inputData['delivery_type'],
                'shop_id'        => $inputData['shop_id'],
                'status'         => $inputData['status'] ?? EOrderStatus::PROCESSED,
                'payment_status' => $inputData['payment_status'] ?? EPaymentStatus::UNPAID,
                'payment_method' => $inputData['payment_method'] ?? EPaymentMethod::BANK_TRANSFER,
            ];

            if ($inputData['status'] == EOrderStatus::COMPLETE && $inputData['payment_status'] != EPaymentStatus::PAID) {
                $this->db->transRollback();
                return redirect()->back()->withInput()->with('errors', ['status' => lang('Order.success_if_payment_paid')]);
            }

            if ($inputData['payment_status'] == EPaymentStatus::DEPOSIT) {
                $dataOrder['customer_paid'] = $inputData['customer_paid'];
            }

            if ($inputData['delivery_type'] == EDeliveryType::HOME_DELIVERY) {
                $deliveryInfo = json_encode([
                    'name'        => $order->customer_info->name ?? '',
                    'phone'       => $order->customer_info->phone ?? '',
                    'province_id' => $inputData['province_id'],
                    'district_id' => $inputData['district_id'],
                    'ward_id'     => $inputData['ward_id'],
                    'address'     => $inputData['address'],
                ]);
                $dataOrder['delivery_info'] = $deliveryInfo;
            }else {
                $dataOrder['delivery_info'] = null;
            }

            $totalAmount        = 0;
            $priceProductTotal  = 0;
            $weightProductTotal = 0;
            $orderItems         = [];
            // product bill
            foreach ($inputData['product'] as $item) {
                $quantity  = $item['quantity'];
                $productID = $item['product_id'];
                $product   = $this->_productModel->find($productID);
                if(isset($product->id)) {
                    $priceProduct       = ($product->price_discount > 0 && $product->price_discount < $product->price) ? $product->price_discount : $product->price * $quantity;
                    $priceProductTotal  += $priceProduct;
                    $weightProductTotal += $product->product_meta['weight'] * $quantity;

                    // order item
                    $orderItems[] = [
                        'product'    => $productID,
                        'unit_price' => EUnitPrice::VND,
                        'quantity'   => $quantity,
                        'total'      => $priceProduct,
                        'pd_type'    => EProductType::PRODUCT,
                    ];
                }
            }
            $totalAmount += $priceProductTotal;
            $subAmount   = $totalAmount;

            // shipping bill
            if ($inputData['delivery_type'] == EDeliveryType::HOME_DELIVERY) {
                $shipFeeProvince = $this->_configModel->getShipFee($inputData['province_id']);
                $shipFeeOnWeight = $this->_configModel->getShipFeeOnWeight();
                $totalShipFee    = ($weightProductTotal * $shipFeeOnWeight) + $shipFeeProvince;
                $totalAmount     += $totalShipFee;
                $dataOrder['shipping_amount'] = $totalShipFee;
            }

            // discount
            if(isset($inputData['voucher_code'])) {
                $voucherCode = $inputData['voucher_code'];
                $voucher = $this->_promotionVoucherModel
                    ->join('customer_voucher', 'customer_voucher.voucher_id = promotion_voucher.voucher_id', 'LEFT')
                    ->where('voucher_code', $voucherCode)
                    ->first();
                if(isset($voucher) && $voucher->voucher_discount_type != PromotionEnum::DISCOUNT_TYPE_FREE_GIFT && ($voucher->voucher_status == EVoucherStatus::UNUSED || $voucher->voucher_code ==  $voucherCode)) {
                    if ($voucher->voucher_discount_type == PromotionEnum::DISCOUNT_TYPE_PERCENT) {
                        $discount = $totalAmount * ($voucher->voucher_discount_value / 100);
                    }
                    if ($voucher->voucher_discount_type == PromotionEnum::DISCOUNT_TYPE_VALUE) {
                        $discount = $voucher->voucher_discount_value;
                    }
                    $totalAmount -= $discount;
                    $dataOrder['discount_amount'] = $discount;
                    $dataOrder['voucher_code']    = $voucherCode;
                    if($voucher->voucher_status != EVoucherStatus::USED) {
                        $this->_promotionVoucherModel->where('voucher_code', $voucherCode)->set(['voucher_status' => EVoucherStatus::USED])->update();
                    }
                }
            }

            $dataOrder['sub_total']       = $subAmount;
            $dataOrder['total']           = $totalAmount;

            if ($inputData['payment_status'] == EPaymentStatus::PAID && $inputData['customer_paid'] != $totalAmount) {
                $this->db->transRollback();
                return redirect()->back()->withInput()->with('errors', ['customer_paid' => lang('Order.payment_paid_if_customer_paid', [number_format($totalAmount)])]);
            }
            $dataOrder['customer_paid'] = $inputData['customer_paid'] ?? 0;

            $order->fill($dataOrder);
            $this->_model->where('order_id', $orderID)->update(null, $order);

            // save order items
            $this->_orderItemModel->where('order_id', $orderID)->delete();

            foreach ($orderItems as $item) {
                $item['order_id'] = $orderID;
                $this->_orderItemModel->insert($item);
            }

            $this->db->transCommit();
        } catch (DatabaseException $e) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('errors', $this->_model->errors());
        }

        $item = $this->_model->where('order_id', $orderID)->first();
        //log Action
        $logData = [
            'title'        => 'Add Product',
            'description'  => "#{$this->user->username} đã thêm order #{$item->order_id}",
            'properties'   => $item->toArray(),
            'subject_id'   => $item->order_id,
            'subject_type' => OrderModel::class,
        ];
        $this->logAction($logData);
        if (isset($inputData['save'])) return redirect()->route('edit_order', [$item->order_id])->with('message', lang('Order.editSuccess', [$item->order_id]));
        else if (isset($inputData['save_exit'])) return redirect()->route('order')->with('message', lang('Order.editSuccess', [$item->order_id]));
        else if (isset($inputData['save_addnew'])) return redirect()->route('add_order')->with('message', lang('Order.editSuccess', [$item->order_id]));

    }

    public function getOrderItem($id)
    {
        $response = [];
        $order    = $this->_model->where('order_id', $id)->first();
        if (isset($order->order_id)) {
            $data      = [];
            $orderItem = $this->_orderItemModel->where('order_id', $id)->findAll();
            foreach ($orderItem as $item) {
                $product               = $this->_productModel->find($item->product);
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

    public function invoice($orderID)
    {
        $order = $this->_model->where('order_id', $orderID)->join('customer', 'customer.id = order.customer_id')->first();

        if (!isset($order->order_id)) {
            return redirect()->route('order')->with('error', lang('Order.order_not_exist'));
        }

        $dataOrderItem      = [];
        $orderItem = $this->_orderItemModel->where('order_id', $orderID)->findAll();
        foreach ($orderItem as $item) {
            $product               = $this->_productModel->find($item->product);
            $product->quantity     = (int) $item->quantity;
            $product->product_meta = $product->product_meta;
            $product->order_item_sub_total = $item->total;
            $dataOrderItem[]       = $product;
        }

        $this->_data['order'] = $order;
        $this->_data['order_items'] = $dataOrderItem;
        $this->_data['title']= lang("Order.invoice_title");
        $this->_render('\store\order\invoice', $this->_data);
    }

    public function viewDeposit($orderID)
    {
        $order = $this->_model->where('order_id', $orderID)->first();

        if (!isset($order->order_id) && $order->payment_status != EPaymentStatus::DEPOSIT) {
            return redirect()->route('order')->with('error', lang('Order.order_not_exist'));
        }
        $orderDeposit = $this->_paymentBanktranferModel->where('mod_id', $order->order_id)->first();
        if(isset($orderDeposit)) {
            $orderDepositDetail           = [
                'bank'  => json_decode($orderDeposit->bank_receiver),
                'image' => $orderDeposit->pmb_transfer_image,
            ];
            $this->_data['order_deposit'] = $orderDepositDetail;
        }

        $this->_data['order'] = $order;
        $this->_data['title']= lang("Order.deposit_title");
        $this->_render('\store\order\view_deposit', $this->_data);
    }
}