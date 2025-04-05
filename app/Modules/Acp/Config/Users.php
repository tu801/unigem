<?php

namespace Modules\Acp\Config;

use CodeIgniter\Config\BaseConfig;

class Users extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Gravatar
     * --------------------------------------------------------------------------
     *
     * If true, will attempt to use gravatar.com for users that do not have
     * avatars saved in the system.
     */
    public $useGravatar = true;

    /**
     * --------------------------------------------------------------------------
     * Gravatar Default image
     * --------------------------------------------------------------------------
     *
     * The default image type to use from Gravatar if they do not have an
     * image for that user.
     */
    public $gravatarDefault = 'identicon';

    /**
     * --------------------------------------------------------------------------
     * Avatar Background Colors
     * --------------------------------------------------------------------------
     *
     * The available colors to use for avatar backgrounds.
     */
    public $avatarPalette = [
        '#84705e', '#506da8', '#5b6885', '#7b94b8', '#6c3208',
        '#b97343', '#d6d3ce', '#b392a6', '#af6a76', '#6c6c94',
        '#c38659',
    ];

    /**
     * --------------------------------------------------------------------------
     * Avatar Upload directory
     * --------------------------------------------------------------------------
     * relative to FCPATH and base_url
     */

    public $avatarDirectory = 'avatars';

    /**
     * --------------------------------------------------------------------------
     * Uploaded Avatar Image Manipulation
     * --------------------------------------------------------------------------
     *
     * Should uploaded avatar be resized? (bool)
     * If so, what is the maximum size (vertical or horizontal, whichever
     * bigger), in px? (int)
     * $avatarResizeFloor is the minimum size of an avatar (set to 32 as required by
     * toolbar avatar size)
     */
    public $avatarResize = false;
    public $avatarSize = 140;
    public $avatarResizeFloor = 32;

    /**
     * --------------------------------------------------------------------------
     * Additional User Fields
     * --------------------------------------------------------------------------
     *
     * This lists the additional fields that are available on a user's
     * profile page. They are listed first in groups, where the group name is
     * used on the form's fieldset legend.
     *
     * Each field can have the following values in it's options array:
     *  - title: the input label. If one is not provided, the field name will be used.
     *  - input: the type of HTML input used. Should be the simpler inputs,
     *      like text, number, email, textarea, etc.
     *      Selects, checkboxes, radios, etc are not supported.
     *  - value: default value for the input field
     */
    public $metaFields = [
        'nationalID' => ['title' => 'Số chứng minh nhân dân', 'input' => 'text'],
        'marital_status' => ['title' => 'Tình trạng hôn nhân', 'input' => 'option', 'value' => ['single' => 'Độc thân', 'married' => 'Đã kết hôn']],
        'bio' => ['title' => 'Tiểu Sử', 'input' => 'textarea'],
        'facebook_link' => ['title' => 'Link Facebook', 'input' => 'text'],
        'instagram_link' => ['title' => 'Link Instagram ', 'input' => 'text'],
    ];
}
