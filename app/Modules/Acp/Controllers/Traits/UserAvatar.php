<?php
namespace Modules\Acp\Controllers\Traits;

trait UserAvatar {
    public function uploadAvatar($postData, $avatar)
    {
        $info = [
            'file_name' => $avatar->getRandomName(),
            'sub_folder' => date('Y')
        ];
        $imgPath = $this->upload_image($avatar, $this->getUploadRule(), $info);

        if ( $imgPath['success'] ) {
            return $info['file_name'];
        } else {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
    }

    public function editAvatar(&$user, $avatar)
    {
        $myDateTime = new \DateTime($user->created);
        $info = [
            'file_name' => $avatar->getRandomName(),
            'sub_folder' => $myDateTime->format('Y')
        ];

        $imgPath = $this->upload_image($avatar, $this->getUploadRule(), $info);
        if ( $imgPath['success'] ) {
            // delete old image
            if ($user->avatar) {
                delete_image($user->avatar, $info['sub_folder']);
            }
            $user->avatar = $info['file_name'];
        } else {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
    }

    /**
     * return post upload image validate rule
     * @return array
     */
    public function getUploadRule()
    {
        $mineType = $this->config->sys['default_mime_type'] ?? 'image,image/jpg,image/jpeg,image/gif,image/png';
        $maxUploadSize = ( isset($this->config->sys['default_max_size']) && $this->config->sys['default_max_size'] > 0 )
            ? $this->config->sys['default_max_size'] * 1024
            : 2048;

        $imgRules = [
            'image' => [
                'uploaded[avatar]',
                "mime_in[avatar,{$mineType}]",
                "max_size[avatar,{$maxUploadSize}]",
            ],
        ];
        $imgErrMess = [
            'image' => [
                'mime_in' => lang('Acp.invalid_image_file_type'),
                'max_size' => lang('Acp.image_to_large', [$this->config->sys['default_max_size']]),
            ]
        ];
        return ['validRules' => $imgRules, 'errMess' => $imgErrMess];
    }
}