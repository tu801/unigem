<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 11/5/2023
 */

namespace App\Controllers\Order;


use App\Controllers\BaseController;
use App\Libraries\BreadCrumb\BreadCrumbCell;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Modules\Acp\Enums\BankingPaymentEnum;
use Modules\Acp\Enums\Store\Order\EDeliveryType;
use Modules\Acp\Enums\Store\Order\EOrderStatus;
use Modules\Acp\Enums\Store\Order\EPaymentMethod;
use Modules\Acp\Enums\Store\Order\EPaymentStatus;
use Modules\Acp\Enums\Store\Order\EUnitPrice;
use Modules\Acp\Enums\Store\Product\EProductType;
use Modules\Acp\Enums\Store\Promotion\EVoucherStatus;
use Modules\Acp\Enums\Store\Promotion\PromotionEnum;
use Modules\Acp\Enums\Store\ShopEnum;
use Modules\Acp\Models\ConfigModel;
use Modules\Acp\Models\PaymentBankTransferModel;
use Modules\Acp\Models\Store\Customer\CustomerModel;
use Modules\Acp\Models\Store\Order\OrderItemModel;
use Modules\Acp\Models\Store\Order\OrderModel;
use Modules\Acp\Models\Store\Product\ProductModel;
use Modules\Acp\Models\Store\Promotion\PromotionVoucherModel;
use Modules\Acp\Models\Store\ShopModel;

class Order extends BaseController
{
    private $_productModel;
    private $_shopModel;
    private $_customerModel;
    private $_configModel;
    private $_orderItemModel;
    private $_promotionVoucherModel;
    private $_paymentBanktranferModel;


    public function __construct()
    {
        parent::__construct();

        $this->_model = model(OrderModel::class);
        $this->_productModel = model(ProductModel::class);
        $this->_shopModel      = model(ShopModel::class);
        $this->_customerModel  = model(CustomerModel::class);
        $this->_configModel    = model(ConfigModel::class);
        $this->_orderItemModel = model(OrderItemModel::class);
        $this->_promotionVoucherModel = model(PromotionVoucherModel::class);
        $this->_paymentBanktranferModel = model(PaymentBankTransferModel::class);
    }

    public function cart()
    {
        //set breadcrumb
        BreadCrumbCell::add('Home', base_url());
        BreadCrumbCell::add(lang('OrderShop.cart'), route_to('order_cart'));
        return $this->_render('order/cart', $this->_data);
    }

    public function checkout()
    {
        //set breadcrumb
        BreadCrumbCell::add('Home', base_url());
        BreadCrumbCell::add(lang('OrderShop.cart'), route_to('order_cart'));
        BreadCrumbCell::add(lang('OrderShop.checkout'), route_to('order_checkout'));

        $shops                = $this->_shopModel->where('status', ShopEnum::STATUS['active'])->findAll();
        $this->_data['shops'] = $shops;
        $this->_data['customer']  = logged_in() ? $this->_customerModel->where('user_id', user_id())->first() : null;
        return $this->_render('order/checkout', $this->_data);
    }

    public function actionCheckout()
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
                'user_init'      => logged_in() ? user_id() : null,
                'customer_id'    => $customerID,
                'code'           => $this->_model->generateCode(),
                'delivery_type'  => $inputData['delivery_type'],
                'shop_id'        => $inputData['shop_id'],
                'status'         => EOrderStatus::OPEN,
                'payment_status' => EPaymentStatus::UNPAID,
                'payment_method' => $inputData['payment_method'] ?? EPaymentMethod::CASH,
            ];

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
                if(isset($voucher) && $voucher->voucher_discount_type != PromotionEnum::DISCOUNT_TYPE_FREE_GIFT && $voucher->voucher_status == EVoucherStatus::UNUSED) {
                    if ($voucher->voucher_discount_type == PromotionEnum::DISCOUNT_TYPE_PERCENT) {
                        $discount = $totalAmount * ($voucher->voucher_discount_value / 100);
                    }
                    if ($voucher->voucher_discount_type == PromotionEnum::DISCOUNT_TYPE_VALUE) {
                        $discount = $voucher->voucher_discount_value;
                    }
                    $totalAmount -= $discount;
                    $dataOrder['discount_amount'] = $discount;
                    $dataOrder['voucher_code']    = $voucherCode;
                    $this->_promotionVoucherModel->where('voucher_code', $voucherCode)->set(['voucher_status' => EVoucherStatus::USED])->update();
                }
            }


            $dataOrder['sub_total']       = $subAmount;
            $dataOrder['total']           = $totalAmount;

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

        if ($inputData['delivery_type'] == EDeliveryType::HOME_DELIVERY) {
            return redirect()->route('order_payment', [$item->code]);
        }else {
            return redirect()->route('order_success', [$item->code]);
        }
    }

    public function getProduct()
    {
        $response          = array();
        $productID = $this->request->getGet('product_id');
        if(isset($productID) && count($productID)) {
            $products = [];
            $data     = $this->_productModel->whereIn('id', $productID)->findAll();
            foreach ($data as $item) {
                $products[] = [
                    'id'             => $item->id,
                    'pd_sku'         => $item->pd_sku,
                    'pd_name'        => $item->pd_name,
                    'pd_slug'        => $item->pd_slug,
                    'price'          => (int) $item->price,
                    'price_discount' => (int) $item->price_discount,
                    'minimum'        => (int) $item->minimum,
                    'product_meta'   => $item->product_meta,
                    'image'          => (isset($item->feature_image['thumbnail']) && $item->feature_image['thumbnail'] !== null) ? $item->feature_image['thumbnail'] : base_url($this->config->noimg),
                ];
            }
            $response['error'] = 0;
            $response['data']  = $products;
        }else {
            $response['error'] = 1;
            $response['message'] = lang('Acp.item_not_found');
        }
        return $this->response->setJSON($response);
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
            'payment_method' => 'required',
            'product'        => 'required',
        ];
    }

    public function orderSuccess($orderCode)
    {
        $order = $this->_model->where('code', $orderCode)->first();
        if (isset($order->order_id)) {
            $this->_data['order'] = $order;
            return $this->_render('order/success', $this->_data);
        } else {
            return $this->_render('errors/404', $this->_data);
        }
    }

    public function orderPayment($orderCode)
    {
        $order = $this->_model->where('code', $orderCode)->first();
        if (isset($order->order_id) && $order->payment_status != EPaymentStatus::DEPOSIT) {
            $this->_data['order'] = $order;
            $bank                 = $this->_configModel->where('group_id', BankingPaymentEnum::CONFIG_GROUP)
                ->where('key', BankingPaymentEnum::CONFIG_KEY)
                ->findAll();
            if (!empty($bank)) {
                $banks = [];
                foreach ($bank as $item) {
                    $value   = json_decode($item->value);
                    $banks[] = $value;
                }
                $this->_data['banks'] = $banks;
            } else {
                $this->_data['banks'] = [];
            }
            return $this->_render('order/payment', $this->_data);
        } else {
            return $this->_render('errors/404', $this->_data);
        }
    }

    public function applyVoucher()
    {
        $response    = array();
        $voucherCode = $this->request->getGet('voucher_code');
        if (empty($voucherCode)) {
            $response['error']   = 1;
            $response['message'] = lang('Vui lòng nhập mã giảm giá');
            return $this->response->setJSON($response);
        }
        $voucher = $this->_promotionVoucherModel
            ->join('customer_voucher', 'customer_voucher.voucher_id = promotion_voucher.voucher_id', 'LEFT')
            ->where('voucher_code', $voucherCode)
            ->first();

        if (empty($voucher)) {
            $response['error']   = 1;
            $response['message'] = lang('Acp.item_not_found');
            return $this->response->setJSON($response);
        }

        if($voucher->voucher_discount_type == PromotionEnum::DISCOUNT_TYPE_FREE_GIFT) {
            $response['error'] = 1;
            $response['message'] = 'mã quà tặng này không thể áp dụng';
            return $this->response->setJSON($response);
        }

        if($voucher->voucher_status == EVoucherStatus::USED) {
            $response['error'] = 1;
            $response['message'] = 'mã quà tặng này đã được sử dụng';
            return $this->response->setJSON($response);
        }


        $response['error'] = 0;
        $response['data'] = [
            'value' => (int) $voucher->voucher_discount_value,
            'type'  => $voucher->voucher_discount_type,
        ];
        return $this->response->setJSON($response);
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

    public function actionOrderPayment($orderCode)
    {
        $order = $this->_model->where('code', $orderCode)->first();
        if (isset($order->order_id) && $order->payment_status != EPaymentStatus::DEPOSIT) {

            $image = $this->request->getFile('image');
            $bank  = $this->request->getPost('bank');

            $dataTransfer = [
                'mod_type'    => PaymentBankTransferModel::class,
                'mod_id'      => $order->order_id,
                'customer_id' => $order->customer_id,
            ];
            if (isset($bank)) {
                $bank                          = $this->_configModel->where('group_id',
                    BankingPaymentEnum::CONFIG_GROUP)
                    ->where('key', BankingPaymentEnum::CONFIG_KEY)
                    ->where('title', $bank)
                    ->first();
                $dataTransfer['bank_receiver'] = $bank->value;
            }

            if (isset($image) && $image->getName()) {
                $rule      = $this->getUploadRule();
                $validated = $this->validate($rule['validRules'], $rule['errMess']);
                if (!$validated) {
                    return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
                }
                $fileName   = $image->getRandomName();
                $folderName = 'image_payment';
                $uploadPath =  WRITEPATH . "uploads" . DIRECTORY_SEPARATOR;
                if (!$image->move($uploadPath.$folderName, $fileName, true)) {
                    return redirect()->back()->withInput()->with('errors', $image->getError());
                }
                $imgPath                            = "{$folderName}/{$fileName}";
                $dataTransfer['pmb_transfer_image'] = $imgPath;
            }
            $this->_paymentBanktranferModel->insert($dataTransfer);
            $this->_model->where('order_id', $order->order_id)
                ->set([
                    'payment_status' => EPaymentStatus::DEPOSIT,
                    'customer_paid'  => ($order->sub_total * (10 / 100)) + $order->shipping_amount,
                ])
                ->update();
            return redirect()->route('order_success', [$order->code]);
        } else {
            return $this->_render('errors/404', $this->_data);
        }
    }

    public function getUploadRule()
    {
        $mineType = $this->config->sys['default_mime_type'] ?? 'image,image/jpg,image/jpeg,image/gif,image/png';
        $maxUploadSize = ( isset($this->config->sys['default_max_size']) && $this->config->sys['default_max_size'] > 0 )
            ? $this->config->sys['default_max_size'] * 1024
            : 2048;

        $imgRules = [
            'image' => [
                'uploaded[image]',
                "mime_in[image,{$mineType}]",
                "max_size[image,{$maxUploadSize}]",
            ],
        ];
        $imgErrMess = [
            'image' => [
                'mime_in' => lang('Acp.invalid_image_file_type'),
                'max_size' => lang('Acp.image_to_large'),
            ]
        ];
        return ['validRules' => $imgRules, 'errMess' => $imgErrMess];
    }
}