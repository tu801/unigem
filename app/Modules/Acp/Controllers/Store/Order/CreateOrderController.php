<?php

namespace Modules\Acp\Controllers\Store\Order;

use App\Enums\Store\Order\EDeliveryType;
use App\Enums\Store\Order\EOrderStatus;
use App\Enums\Store\Order\EPaymentMethod;
use App\Enums\Store\Order\EPaymentStatus;
use App\Enums\Store\Product\EProductType;
use App\Enums\Store\ShopEnum;
use App\Models\Store\Order\OrderModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Modules\Acp\Controllers\Store\Order\OrderController;
use App\Traits\Store\ShippingFee;
use App\Traits\Store\UseVoucher;

class CreateOrderController extends OrderController
{
    use ShippingFee, UseVoucher;

    public function __construct()
    {
        parent::__construct();

        $this->_data['title'] = lang("Order.add_title");
    }

    /**
     * show add order form
     */
    public function addOrder()
    {
        $shops = $this->_shopModel->where('status', ShopEnum::STATUS['active'])->findAll();
        $exchangeRate = $this->_exchangeRateModel->getExchangeRate($this->currentLang->currency_code);

        $this->_data['shops']    = $shops;
        $this->_data['title'] = lang("Order.add_title");
        $this->_data['exchangeRate'] = isset($exchangeRate->rate) ? $exchangeRate->rate : 1; // Default exchange rate is 1 if not set
        $this->_data['countries'] = $this->_countryModel->getCountries();
        $this->_render('\store\order\add', $this->_data);
    }

    /**
     * handle add order form submission
     */
    public function addAction()
    {
        $inputData = $this->request->getPost();
        $rules     = $this->ruleValidate();
        $errMess   = $this->messageValidate();

        if (isset($inputData['delivery_type']) && $inputData['delivery_type'] == EDeliveryType::HOME_DELIVERY) {
            $rules['ship_full_name'] = 'required';
            $rules['ship_telephone'] = 'required';
            $rules['province_id'] = 'required';
            $rules['district_id'] = 'required';
            $rules['ward_id']     = 'required';
            $rules['address']     = 'required';

            $errMess['ship_full_name'] = [
                'required' => lang('Order.ship_full_name_required'),
            ];
            $errMess['ship_telephone'] = [
                'required' => lang('Order.ship_telephone_required'),
            ];
        }
        if ($inputData['customer_id'] == 0) {
            $rules['phone']     = 'required|is_unique[customer.cus_phone]';
            $rules['email']     = 'permit_empty|valid_email|is_unique[customer.cus_email]';
        }

        //validate the input
        if (!$this->validate($rules, $errMess)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $this->db->transBegin();

            // create customer if not exists
            if ($inputData['customer_id'] > 0) {
                $customerData = $this->_customerModel->find($inputData['customer_id']);
            } else {
                $customerData = $this->_customerModel->createOrderCustomer($inputData);
            }

            // prepare data order
            $dataOrder = [
                'user_init'      => $this->user->id,
                'lang_id'        => $this->currentLang->id,
                'customer_id'    => $customerData->id,
                'shop_id'        => $inputData['shop_id'],
                'code'           => $this->_model->generateCode(),
                'title'          => $inputData['title'],
                'note'           => $inputData['note'],
                'delivery_type'  => $inputData['delivery_type'],
                'status'         => $inputData['status'] ?? EOrderStatus::OPEN,
                'payment_status' => $inputData['payment_status'] ?? EPaymentStatus::UNPAID,
                'payment_method' => $inputData['payment_method'] ?? EPaymentMethod::BANK_TRANSFER,
                'currency'       => $this->currentLang->currency_code,
            ];

            if ($inputData['status'] == EOrderStatus::COMPLETE && $inputData['payment_status'] != EPaymentStatus::PAID) {
                $this->db->transRollback();
                return redirect()->back()->withInput()->with('errors', ['status' => lang('Order.success_if_payment_paid')]);
            }

            if ($inputData['payment_status'] == EPaymentStatus::DEPOSIT) {
                $dataOrder['customer_paid'] = $inputData['customer_paid'];
            }

            if ($inputData['delivery_type'] == EDeliveryType::HOME_DELIVERY) {
                $dataOrder['delivery_info'] = json_encode([
                    'ship_full_name'    => $inputData['ship_full_name'],
                    'ship_telephone'    => $inputData['ship_telephone'],
                    'ship_email'        => $inputData['ship_email'] ?? null,
                    'country_id'        => $inputData['country_id'] ?? 0,
                    'province_id'       => $inputData['province_id'] ?? 0,
                    'district_id'       => $inputData['district_id'] ?? 0,
                    'ward_id'           => $inputData['ward_id'] ?? 0,
                    'cus_address'       => $inputData['address'],
                ]);
            }

            // customer
            $dataOrder['customer_info'] = json_encode([
                'name'          => $customerData->cus_full_name,
                'phone'         => $customerData->cus_phone,
                'email'         => $customerData->cus_email,
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

            if ($inputData['payment_status'] == EPaymentStatus::PAID && $inputData['customer_paid'] != $totalAmount) {
                $this->db->transRollback();
                return redirect()->back()->withInput()->with('errors', ['customer_paid' => lang('Order.payment_paid_if_customer_paid', [number_format($totalAmount)])]);
            }
            $dataOrder['customer_paid'] = $inputData['customer_paid'] ?? 0;
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
                'title'        => 'Create order #' . $dataOrder['code'],
                'description'  => lang('Order.create_order_log_desc', [$this->user->username, $dataOrder['code']]),
                'properties'   => $item->toArray(),
                'subject_id'   => $item->order_id,
                'subject_type' => OrderModel::class,
            ];
            $this->logAction($logData);

            $this->db->transCommit();

            if (isset($inputData['save'])) return redirect()->route('edit_order', [$item->order_id])->with('message', lang('Order.addSuccess', [$item->code]));
            else if (isset($inputData['save_exit'])) return redirect()->route('order')->with('message', lang('Order.addSuccess', [$item->code]));
            else if (isset($inputData['save_addnew'])) return redirect()->route('add_order')->with('message', lang('Order.addSuccess', [$item->code]));
        } catch (DatabaseException $e) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('errors', $this->_model->errors());
        }
    }
}
