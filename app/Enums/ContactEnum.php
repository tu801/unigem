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
    const STATUS_REPLIED = 'replied';
}