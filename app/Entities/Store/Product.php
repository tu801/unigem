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
        $metaAttach     = model(AttachMetaModel::class);
        $shopConfig     = config('Shop');

        $images         = $metaAttach->getAttMeta($this->id, 'product_images');
        $imageData      = [];
        $myTime         = Time::parse($this->attributes['created_at']);

        if ( !isset($images->id) ) {
            return null; // no image for this product
        }
        
        foreach ($images->images as $item) {
            $productThumbName = $shopConfig->productThumbSize['height'].'-'.$shopConfig->productThumbSize['width'].'-'.$item->file_name;
            $productThumbFile = 'uploads/attach/' . $myTime->format('Y/m').'/thumb/'.$productThumbName;

            // check if thumb image exist
            $productThumbFilePath = FCPATH . str_replace('/', DIRECTORY_SEPARATOR, $productThumbFile);
            if ( !file_exists($productThumbFilePath) ) {
                // create product thumbnail
                \Config\Services::image()
                    ->withFile(FCPATH . str_replace('/', DIRECTORY_SEPARATOR, $item->full_image))
                    ->fit($shopConfig->productThumbSize['width'], $shopConfig->productThumbSize['height'], 'center')
                    ->save($productThumbFilePath);
                $item->product_thumb = $productThumbFile;

                $imageData[] = $item;
            } else {
                $item->product_thumb = $productThumbFile;

                $imageData[] = $item;
            }
        }
        $this->images = $imageData;
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