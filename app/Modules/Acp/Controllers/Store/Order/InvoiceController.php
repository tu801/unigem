<?php

namespace Modules\Acp\Controllers\Store\Order;

use App\Models\LangModel;

class InvoiceController extends OrderController
{
    public function __construct()
    {
        parent::__construct();

        $this->_data['title'] = lang("Order.invoice_title");
    }

    /**
     * Show invoice for order
     *
     * @param int $id
     * @return string
     */
    public function invoice($orderID)
    {
        return $this->_handleOrder($orderID, '\store\order\invoice');
    }

    /**
     * Print invoice for order
     *
     * @param int $orderID
     * @return string
     */
    public function invoicePrint($orderID)
    {
        return $this->_handleOrder($orderID, '\store\order\invoice_print');
    }

    /**
     * Handle order details and render the view
     *
     * @param int $orderID
     * @param string $view
     * @return \CodeIgniter\HTTP\RedirectResponse|\CodeIgniter\View\View
     */
    private function _handleOrder($orderID, $view)
    {
        $order = $this->_model->where('order_id', $orderID)->join('customer', 'customer.id = order.customer_id')->first();

        if (!isset($order->order_id)) {
            return redirect()->route('order')->with('error', lang('Order.order_not_exist'));
        }

        $dataOrderItem      = [];
        $orderItem = $this->_orderItemModel->where('order_id', $orderID)->findAll();
        $lang = model(LangModel::class)->find($order->lang_id);
        foreach ($orderItem as $item) {
            $product               = $this->_productModel->getProductItemById($item->product_id, $lang);
            $product->quantity     = (int) $item->quantity;
            $product->product_meta = $product->product_meta;
            $product->order_item_sub_total = $item->total;
            $dataOrderItem[]       = $product;
        }

        $this->_data['order'] = $order;
        $this->_data['order_items'] = $dataOrderItem;
        $this->_render($view, $this->_data);
    }
}
