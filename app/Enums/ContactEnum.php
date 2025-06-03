<?php

namespace App\Enums;

class ContactEnum extends BaseEnum
{
    const FORM_CONTACT_TYPE = 'contact_form';
    const SUBSCRIBE_CONTACT_TYPE = 'subscribe';

    const CONTACT_SUBJECT_LIST = [
        'purchase_advice',
        'warranty_advice',
        'other'
    ];

    const STATUS_NEW = 'new';
    const STATUS_READ = 'read';
    const STATUS_RESOLVE = 'resolve';

    public static function getContactStatusList(){
        return [
            self::STATUS_NEW,
            self::STATUS_READ,
            self::STATUS_RESOLVE    
        ];
    }
}