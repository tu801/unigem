<?php

namespace App\Entities;

use CodeIgniter\Config\Services;
use CodeIgniter\Entity\Entity;
use CodeIgniter\I18n\Time;
use App\Enums\UploadFolderEnum;
use App\Models\AttachMetaModel;
use App\Models\Blog\CategoryModel;
use App\Models\Store\Product\ProductManufacturer;

class Product extends Entity
{
    protected $images = [];
    protected $feature_image = [];
    protected $url;

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
        $metaAttach   = model(AttachMetaModel::class);
        $images         = $metaAttach->getAttMeta($this->id, 'product_images');
        $this->images = $images;
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

    public function getBrand()
    {
        if (empty($this->id)) {
            throw new \RuntimeException(lang('Product.product_must_be_created'));
        }
        $_productManufacturerModel = model(ProductManufacturer::class);
        return $_productManufacturerModel->where('manufacturer_id', $this->manufacture_id)->first();
    }


    public function getUrl() {
        if (empty($this->id)) {
            throw new \RuntimeException(lang('Product.product_must_be_created'));
        }
        $this->url = base_url('product/'.$this->attributes['pd_slug']);
        return $this->url;
    }
}