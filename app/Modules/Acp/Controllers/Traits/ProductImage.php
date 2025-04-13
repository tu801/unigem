<?php
namespace Modules\Acp\Controllers\Traits;

use CodeIgniter\I18n\Time;
use App\Enums\UploadFolderEnum;

trait ProductImage {

    /**
     * upload the post image and create thumb for the product
     * @param $postData
     * @param $image
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function uploadProductImage($postData, $image)
    {
        $shopConfigs = config('Shop');
        $info = [
            'file_name' => clean_url($postData['pd_name']).'-'.time().'.'.$image->getClientExtension(),
            'sub_folder' => UploadFolderEnum::PRODUCT . '/' . date('Y/m')
        ];

        $imgPath = $this->upload_image($image, $this->getUploadRule(), $info);
        if ( $imgPath['success'] ) {
            //create thumb
            $imgThumb = [
                'file_name' => $info['file_name'],
                'original_image' => $this->config->uploadFolder.'/'.$info['sub_folder']."/{$info['file_name']}",
                'path' => $this->config->uploadFolder.'/'.$info['sub_folder']."/thumb"
            ];
            create_thumb($imgThumb, $shopConfigs->productThumbSize);
            return $info['file_name'];
        } else {
            return redirect()->back()->withInput()->with('errors', $imgPath['error']);
        }
    }

    public function editProductImage(&$postData, $image, $post)
    {
        $myTime = Time::parse($post->created_at);

        $info = [
            'file_name' => clean_url($postData['pd_name']).'-'.time().'.'.$image->getClientExtension(),
            'sub_folder' => UploadFolderEnum::PRODUCT . '/' . $myTime->format( 'Y/m')
        ];

        $imgPath = $this->upload_image($image, $this->getUploadRule(), $info);
        if ( $imgPath['success'] ) {
            //create thumb
            $imgThumb = [
                'file_name' => $info['file_name'],
                'original_image' => WRITEPATH . $this->config->uploadFolder.'/'.$info['sub_folder']."/{$info['file_name']}",
                'path' => WRITEPATH . $this->config->uploadFolder.'/'.$info['sub_folder']."/thumb"
            ];
            create_thumb($imgThumb);
            delete_image($post->pd_image, $info['sub_folder']);
            $postData['pd_image'] = $info['file_name'];
        } else {
            return redirect()->back()->with('errors', $imgPath['error']);
        }
    }

    /**
     * return post upload image validate rule
     * @return array
     */
    public function getUploadRule()
    {
        $mineType = $this->config->sys['default_mime_type'] ?? 'image,image/jpg,image/jpeg,image/gif,image/png,image/webp';
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