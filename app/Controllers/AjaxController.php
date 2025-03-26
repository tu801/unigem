<?php
/**
 * @author tmtuan
 * @github https://github.com/tu801
 * created Date: 11/11/2023
 */

namespace App\Controllers;

use Modules\Acp\Controllers\Store\AjaxController as BaseAjax;
use Modules\Acp\Models\Store\Product\ProductModel;

class AjaxController extends BaseAjax
{
    public function searchProduct()
    {
        $_productModel = model(ProductModel::class);
        $postData = $this->request->getPost();
        $response = [
            'success' => true,
            'data' => []
        ];
        $config = config('Site');

        if ( empty($postData['keyword']) ) {
            return $this->response->setJSON($response);
        } else {
            // get product
            if ( isset($postData['category']) && $postData['category'] > 0 ) {
                $_productModel->where('cat_id', esc($postData['category']));
            }
            $pdTitle =esc($postData['keyword']);
            $products = $_productModel->like('pd_name', $pdTitle)
                ->select('product.*')
                ->orderBy('product.id DESC')
                ->findAll();

            $pdData = [];
            if ( count($products) > 0 ) {
                foreach ($products as $product) {
                    $img = (isset($product->feature_image['thumbnail']) && $product->feature_image['thumbnail'] !== null)
                        ? $product->feature_image['thumbnail']
                        : base_url($config->noimg);
                    $pdUrl = base_url(route_to('product_detail', $product->pd_slug, $product->id));
                    $pdData[] = [
                        'id' => $product->id,
                        'cat_id' => $product->cat_id,
                        'pd_sku' => $product->pd_sku,
                        'pd_name' => $product->pd_name,
                        'pd_slug' => $product->pd_slug,
                        'pd_image' => $img,
                        'price' => $product->price,
                        'price_discount' => $product->price_discount,
                        'pd_url' => $pdUrl
                    ];
                }
            }
            $response['data'] = $pdData;
            return $this->response->setJSON($response);
        }

    }
}