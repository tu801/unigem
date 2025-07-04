<?php
namespace Modules\Acp\Controllers\Store\Order;

use App\Enums\Store\ShopEnum;

class EditOrderController extends OrderController
{
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

        $this->_data['voucher'] = null;
        if (isset($order->voucher_code)) {
            $voucher                = $this->_promotionVoucherModel->where('voucher_code', $order->voucher_code)->first();
            $this->_data['voucher'] = $voucher;
        }
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
            } else {
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
                if (isset($product->id)) {
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
            if (isset($inputData['voucher_code'])) {
                $voucherCode = $inputData['voucher_code'];
                $voucher = $this->_promotionVoucherModel
                    ->join('customer_voucher', 'customer_voucher.voucher_id = promotion_voucher.voucher_id', 'LEFT')
                    ->where('voucher_code', $voucherCode)
                    ->first();
                if (isset($voucher) && $voucher->voucher_discount_type != PromotionEnum::DISCOUNT_TYPE_FREE_GIFT && ($voucher->voucher_status == EVoucherStatus::UNUSED || $voucher->voucher_code ==  $voucherCode)) {
                    if ($voucher->voucher_discount_type == PromotionEnum::DISCOUNT_TYPE_PERCENT) {
                        $discount = $totalAmount * ($voucher->voucher_discount_value / 100);
                    }
                    if ($voucher->voucher_discount_type == PromotionEnum::DISCOUNT_TYPE_VALUE) {
                        $discount = $voucher->voucher_discount_value;
                    }
                    $totalAmount -= $discount;
                    $dataOrder['discount_amount'] = $discount;
                    $dataOrder['voucher_code']    = $voucherCode;
                    if ($voucher->voucher_status != EVoucherStatus::USED) {
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
}