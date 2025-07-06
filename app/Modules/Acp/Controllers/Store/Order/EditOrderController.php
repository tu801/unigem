<?php

namespace Modules\Acp\Controllers\Store\Order;

use App\Enums\Store\Order\EDeliveryType;
use App\Enums\Store\Order\EOrderStatus;
use App\Enums\Store\Order\EPaymentStatus;
use App\Enums\Store\Product\EProductType;
use App\Enums\Store\ShopEnum;
use App\Models\Store\Order\OrderModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Modules\Acp\Controllers\Traits\ShippingFee;
use Modules\Acp\Controllers\Traits\UseVoucher;

class EditOrderController extends OrderController
{
    use ShippingFee, UseVoucher;

    protected $pageTitle = 'Order.edit_page_title';

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = lang($this->pageTitle);
    }

    /**
     * show edit order form
     * @param int $id
     */
    public function editOrder($id)
    {
        $shops = $this->_shopModel->where('status', ShopEnum::STATUS['active'])->findAll();
        $order = $this->_model->where('order_id', $id)->join('customer', 'customer.id = order.customer_id')->first();

        $this->_data['shops'] = $shops;
        $this->_data['title'] = lang('Order.edit_title');
        if (isset($order->order_id)) {
            $this->_data['countries'] = $this->_countryModel->getCountries();

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
                'status'         => $inputData['status'] ?? $order->status,
                'payment_status' => $inputData['payment_status'] ?? $order->payment_status,
                'payment_method' => $inputData['payment_method'] ?? $order->payment_method,
                'currency'       => $order->currency,
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
                    'ship_full_name'    => $inputData['ship_full_name'] ?? $order->delivery_info->ship_full_name,
                    'ship_telephone'    => $inputData['ship_telephone'] ?? $order->delivery_info->ship_telephone,
                    'ship_email'        => $inputData['ship_email'] ?? $order->delivery_info->ship_email,
                    'country_id'        => $inputData['country_id'] ?? $order->delivery_info->country_id ?? 0,
                    'province_id'       => $inputData['province_id'] ?? $order->delivery_info->province_id ?? 0,
                    'district_id'       => $inputData['district_id'] ?? $order->delivery_info->district_id ?? 0,
                    'ward_id'           => $inputData['ward_id'] ?? $order->delivery_info->ward_id ?? 0,
                    'cus_address'       => $inputData['address'] ?? $order->delivery_info->cus_address,
                ]);
            } else {
                $dataOrder['delivery_info'] = null;
            }

            $totalAmount        = 0;
            $priceProductTotal  = 0;
            $weightProductTotal = 0;
            $orderItems         = [];
            $lang = model(\App\Models\LangModel::class)->find($order->lang_id);
            // product bill
            foreach ($inputData['product'] as $item) {
                $quantity  = $item['quantity'];
                $productID = $item['product_id'];
                $product   = $this->_productModel->getProductItemById($productID, $lang);
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

            if ($order->lang_id > 1) {
                $dataOrder['sub_total']        = $subAmount;
                $dataOrder['total']            = $totalAmount;
                $dataOrder['total_amount_vnd'] = round($totalAmount * ($order->exchange_rate ?? 1), 2);
            } else {
                $dataOrder['sub_total']       = $subAmount;
                $dataOrder['total']           = $totalAmount;
                $dataOrder['total_amount_vnd'] = round($totalAmount, 2);
            }

            if ($inputData['payment_status'] == EPaymentStatus::PAID && $inputData['customer_paid'] != $totalAmount) {
                $this->db->transRollback();
                return redirect()->back()->withInput()->with('errors', ['customer_paid' => lang('Order.payment_paid_if_customer_paid', [number_format($totalAmount)])]);
            }
            if ($inputData['payment_status'] == EPaymentStatus::PAID) {
                $dataOrder['customer_paid'] = $inputData['customer_paid'] ?? $totalAmount;
            }

            // record log info
            $recordLogData = [
                'old_data'    => $order->toArray(),
            ];

            $order->fill($dataOrder);
            $this->_model->where('order_id', $orderID)->update(null, $order);

            // save order items
            $oldOrderItems = $this->_orderItemModel->where('order_id', $orderID)->findAll();
            $recordLogData['old_data']['order_items'] = $oldOrderItems;
            $this->_orderItemModel->where('order_id', $orderID)->delete();
            foreach ($orderItems as $item) {
                $item['order_id'] = $orderID;
                $this->_orderItemModel->insert($item);
            }

            //log Action
            $recordLogData['new_data'] = $order->toArray();
            $recordLogData['new_data']['order_items'] = $orderItems;
            $logData = [
                'title'        => 'Edit Order #' . $order->order_id,
                'description'  => "#{$this->user->username} đã chỉnh sửa order #{$order->order_id}",
                'properties'   => $recordLogData,
                'subject_id'   => $order->order_id,
                'subject_type' => OrderModel::class,
            ];
            $this->logAction($logData);
            $this->db->transCommit();

            if (isset($inputData['save'])) return redirect()->route('edit_order', [$order->order_id])->with('message', lang('Order.editSuccess', [$order->code]));
            else if (isset($inputData['save_exit'])) return redirect()->route('order')->with('message', lang('Order.editSuccess', [$order->code]));
            else if (isset($inputData['save_addnew'])) return redirect()->route('add_order')->with('message', lang('Order.editSuccess', [$order->code]));
            else return redirect()->route('order')->with('message', lang('Order.editSuccess', [$order->code]));
        } catch (DatabaseException $e) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('errors', $this->_model->errors());
        }
    }
}
