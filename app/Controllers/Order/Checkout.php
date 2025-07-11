<?php

namespace App\Controllers\Order;

use App\Enums\Store\Order\EDeliveryType;
use App\Enums\Store\Order\EOrderStatus;
use App\Enums\Store\Order\EPaymentMethod;
use App\Enums\Store\Order\EPaymentStatus;
use App\Enums\Store\Product\EProductType;
use App\Models\Country;
use App\Models\Store\ExchangeRateModel;
use App\Models\Store\Order\OrderItemModel;
use App\Models\Store\Order\OrderModel;
use App\Models\Store\Product\ProductModel;
use App\Models\Store\ShopModel;
use App\Traits\Store\PlaceOrderEmail;
use App\Traits\Store\ShippingFee;
use App\Traits\Store\UseVoucher;
use CodeIgniter\Database\Exceptions\DatabaseException;

class Checkout extends \App\Controllers\BaseController
{
    use ShippingFee, UseVoucher, PlaceOrderEmail;

    protected $_productModel;
    protected $_exchangeRateModel;
    protected $_orderItemModel;

    public function __construct()
    {
        parent::__construct();
        // Load necessary models or libraries if needed
        $this->page_title = lang('Order.checkout');

        $this->_model = model(OrderModel::class);
        $this->_productModel = model(ProductModel::class);
        $this->_exchangeRateModel = model(ExchangeRateModel::class);
        $this->_orderItemModel = model(OrderItemModel::class);

        // check customer logged in
        return $this->checkCustomerLoggedIn();
    }

    /**
     * Show the checkout page.
     */
    public function index()
    {
        $this->page_title = lang('Order.checkout');
        $this->_data['page_title'] = $this->page_title;

        // Check if the user is logged in
        if (!auth()->loggedIn()) {
            return redirect()->to(route_to('cus_login'))->with('error', lang('Order.loginRequired'));
        }

        if ($this->request->getPost()) {
            // Handle the checkout form submission
            $this->handleCheckoutFormSubmit();
        }


        $this->_data['countries'] = model(Country::class)->getCountries();
        // Render the checkout view
        return $this->_render('order/checkout', $this->_data);
    }

    public function handleCheckoutFormSubmit()
    {
        // Handle the form submission logic here
        // Validate the input, process the order, etc.
        // Redirect or return a response as needed
        $inputData = $this->request->getPost();
        [$rules, $errMess] = $this->getValidationRules();

        if (isset($inputData['delivery_type']) && $inputData['delivery_type'] == EDeliveryType::HOME_DELIVERY) {
            $rules['ship_full_name'] = 'required';
            $rules['ship_telephone'] = 'required';
            $rules['country_id'] = 'required';

            $errMess['ship_full_name'] = [
                'required' => lang('Order.ship_full_name_required'),
            ];
            $errMess['ship_telephone'] = [
                'required' => lang('Order.ship_telephone_required'),
            ];
        }

        //validate the input
        if (!$this->validate($rules, $errMess)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $this->db->transBegin();
            $customerData = $this->_data['customer'];

            // prepare data order
            $shop = model(ShopModel::class)->getDefaultShop();
            $orderCode = $this->_model->generateCode();

            $dataOrder = [
                'user_init'      => 0,
                'lang_id'        => $this->currentLang->id,
                'customer_id'    => $customerData->id,
                'shop_id'        => $shop->id,
                'code'           => $orderCode,
                'title'          => $inputData['title'] ?? $orderCode,
                'note'           => $inputData['note'] ?? '',
                'delivery_type'  => $inputData['delivery_type'] ?? EDeliveryType::PICK_UP,
                'status'         => $inputData['status'] ?? EOrderStatus::OPEN,
                'payment_status' => $inputData['payment_status'] ?? EPaymentStatus::UNPAID,
                'payment_method' => $inputData['payment_method'] ?? EPaymentMethod::CASH,
                'currency'       => $this->currentLang->currency_code,
            ];

            if ($inputData['delivery_type'] == EDeliveryType::HOME_DELIVERY) {
                $dataOrder['delivery_info'] = json_encode([
                    'ship_full_name'    => $inputData['ship_full_name'],
                    'ship_telephone'    => $inputData['ship_telephone'],
                    'ship_email'        => $inputData['ship_email'] ?? null,
                    'country_id'        => $inputData['country_id'] ?? 0,
                    'province_id'       => $inputData['province_id'] ?? 0,
                    'district_id'       => $inputData['district_id'] ?? 0,
                    'ward_id'           => $inputData['ward_id'] ?? 0,
                    'cus_address'       => $inputData['ship_address'],
                ]);
            }

            // customer
            $dataOrder['customer_info'] = json_encode([
                'name'          => $inputData['customer_name'] ?? $customerData->cus_full_name,
                'phone'         => $inputData['customer_phone'] ?? $customerData->cus_phone,
                'email'         => $inputData['customer_email'] ?? $customerData->cus_email,
                'country_id'    => $customerData->country_id,
                'province_id'   => $customerData->province_id,
                'district_id'   => $customerData->district_id,
                'ward_id'       => $customerData->ward_id,
                'cus_address'   => $customerData->cus_address,
            ]);

            $totalAmount        = 0;
            $priceProductTotal  = 0;
            $weightProductTotal = 0;
            $orderItems          = [];
            // product bill
            foreach ($inputData['product'] as $item) {
                $quantity  = $item['quantity'];
                $productID = $item['product_id'];
                $product   = $this->_productModel->getProductItemById($productID, $this->currentLang);
                if (isset($product->id)) {
                    $unitPrice          = ($product->price_discount > 0 && $product->price_discount < $product->price) ? $product->price_discount : $product->price;
                    $priceProduct       =  $unitPrice * $quantity;
                    $priceProductTotal  += $priceProduct;
                    $weightProductTotal += $product->pd_weight * $quantity;

                    // order item
                    $orderItems[] = [
                        'product_id'    => $productID,
                        'currency_type' => $product->product_meta['lang']->currency_code,
                        'unit_price'    => $unitPrice,
                        'quantity'      => $quantity,
                        'total'         => $priceProduct,
                        'pd_type'       => EProductType::PRODUCT,
                    ];
                }
            }
            $totalAmount += $priceProductTotal;
            $subAmount   = $totalAmount;

            // shipping bill
            if ($inputData['delivery_type'] == EDeliveryType::HOME_DELIVERY) {
                $totalShipFee  = $this->calculateShippingFee(
                    $inputData['province_id'] ?? 0,
                    $weightProductTotal
                );
                $totalAmount     += $totalShipFee;
                $dataOrder['shipping_amount'] = $totalShipFee;
            }

            // discount
            if (isset($inputData['voucher_code']) && !empty($inputData['voucher_code'])) {
                $this->useVoucher($inputData['voucher_code'], $dataOrder, $totalAmount);
                if (isset($dataOrder['discount_amount']) && $dataOrder['discount_amount'] > 0) {
                    $totalAmount -= $dataOrder['discount_amount'];
                }
            }

            // handle exchange rate
            if ($this->currentLang->id > 1) {
                $exchangeRate = $this->_exchangeRateModel->getExchangeRate($this->currentLang->currency_code);
                $dataOrder['sub_total']        = $subAmount;
                $dataOrder['total']            = $totalAmount;
                $dataOrder['exchange_rate']    = $exchangeRate->rate ?? 1;
                $dataOrder['exchange_rate_id'] = $exchangeRate->id ?? 0;
                $dataOrder['total_amount_vnd'] = round($totalAmount * ($exchangeRate->rate ?? 1), 2);
            } else {
                $dataOrder['sub_total']       = $subAmount;
                $dataOrder['total']           = $totalAmount;
                $dataOrder['exchange_rate']   = 1;
                $dataOrder['exchange_rate_id'] = 0;
                $dataOrder['total_amount_vnd'] = round($totalAmount, 2);
            }

            // save order
            $orderID = $this->_model->insert($dataOrder);
            // save order items
            foreach ($orderItems as $item) {
                $item['order_id'] = $orderID;
                $this->_orderItemModel->insert($item);
            }

            $item = $this->_model->where('order_id', $orderID)->first();

            //log Action
            $logData = [
                'title'        => 'Customer #' . $customerData->cus_code . ' placed order #' . $dataOrder['code'],
                'description'  => lang('Order.create_order_log_desc', [$customerData->cus_code, $dataOrder['code']]),
                'properties'   => $item->toArray(),
                'subject_id'   => $item->order_id,
                'subject_type' => OrderModel::class,
            ];
            $this->logAction($logData);

            $this->db->transCommit();

            // send email 
            $this->sendOrderEmail($item, $customerData);

            return redirect()->route('order_success', [$item->code])->with('message', lang('Order.placeOrderSuccess', [$item->code]));
        } catch (DatabaseException $e) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('errors', $this->_model->errors());
        }
    }

    public function getValidationRules()
    {
        $rules = [
            'customer_name' => 'required',
            'customer_phone' => 'required',
            'customer_email' => 'permit_empty|valid_email',
            'delivery_type' => 'required',
        ];

        $errMess = [
            'customer_name' => [
                'required' => lang('Order.customer_name_required'),
            ],
            'customer_phone' => [
                'required' => lang('Order.customer_phone_required'),
            ],
            'customer_email' => [
                'valid_email' => lang('Order.customer_email_valid'),
            ],
            'delivery_type' => [
                'required' => lang('Order.delivery_type_required'),
            ],
        ];
        return [$rules, $errMess];
    }
}
