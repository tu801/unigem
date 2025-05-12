<?php

namespace App\Entities\Store;

use CodeIgniter\Config\Services;
use CodeIgniter\Entity\Entity;
use CodeIgniter\I18n\Time;
use App\Enums\UploadFolderEnum;
use App\Models\AttachMetaModel;
use App\Models\Blog\CategoryModel;

class Product extends Entity
{
    /**
     * array of attach_meta for product item
     * @var array
     */
    protected $images = [];
    protected $feature_image = [];
    protected $url;

    protected string $display_price;

    /**
     * get product display price
     * @return string
     */
    public function getDisplayPrice()
    {
        $lang = session()->lang;
        helper('ecom');

        $price = ($this->attributes['price_discount'] > 0 && $this->attributes['price_discount'] < $this->attributes['price']) ? $this->attributes['price_discount'] : $this->attributes['price'];
        if ( $price > 0 ) {
            $this->display_price = format_currency($price, $lang->locale );
        } else {
            $this->display_price = lang('Product.contact_price_text'); // contact price
        }

        return $this->display_price;
    }

    public function getFeatureImage()
    {
        if (empty($this->id)) {
            throw new \RuntimeException(lang('Product.product_must_be_created'));
        }
        $config = config('Acp');
        if (!empty($this->attributes['pd_image'])) {
            $mytime       = Time::parse($this->attributes['created_at']);
            $this->feature_image = [
                'full'      => base_url('uploads/'.UploadFolderEnum::PRODUCT.'/'.$mytime->format('Y/m').'/'.$this->attributes['pd_image']),
                'thumbnail' => base_url('uploads/'.UploadFolderEnum::PRODUCT.'/'.$mytime->format('Y/m').'/thumb/'.$this->attributes['pd_image']),
            ];

        } else {
            $this->feature_image = [
                'full'      => base_url($config->noimg),
                'thumbnail' => base_url($config->noimg),
            ];
        }
        return $this->feature_image;
    }

    public function getImages()
    {
        if (empty($this->id)) {
            throw new \RuntimeException(lang('Product.product_must_be_created'));
        }
        helper('ecom');
        $metaAttach     = model(AttachMetaModel::class);

        $images         = $metaAttach->getAttMeta($this->id, 'product_images');
        $imageData      = [];

        if ( !isset($images->id) ) {
            return null; // no image for this product
        }
        
        foreach ($images->images as $item) {
            if ( !isset($item->product_thumb) || empty($item->product_thumb) ) $item->product_thumb = create_product_thumb($item); // create thumb if not exists in image attach

            $imageData[] = $item;
        }
        unset($images->images);
        $this->images = (object)[
            'data' => $imageData,
            'meta' => $images
        ];

        return $this->images;
    }

    public function getCategory()
    {
        if (empty($this->id)) {
            throw new \RuntimeException(lang('Product.product_must_be_created'));
        }
        $session = Services::session();
        $catModel = new CategoryModel();
        return $catModel->getById($this->cat_id, $session->lang->id, 'product');
    }

    public function getUrl() {
        if (empty($this->id)) {
            throw new \RuntimeException(lang('Product.product_must_be_created'));
        }
        $this->url = base_url(route_to('product_detail',$this->attributes['pd_slug'], $this->id ));
        return $this->url;
    }

}