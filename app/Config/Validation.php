<?php

namespace Config;

use App\Validations\ProductNameValidation;
use App\Validations\PhoneValidation;
use App\Validations\ShippingFeeConfigValidation;
use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var list<string>
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
        PhoneValidation::class,
        ShippingFeeConfigValidation::class,
        ProductNameValidation::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Other Rules
    // --------------------------------------------------------------------

    // --------------------------------------------------------------------
    // Auth Rules
    // --------------------------------------------------------------------
    public $login = [
        'username' => [
            'label' => 'Auth.username',
            'rules' => [
                'required',
                'max_length[30]',
                'min_length[3]',
                'regex_match[/\A[a-zA-Z0-9\.]+\z/]',
            ],
        ],
        // 'email' => [
        //     'label' => 'Auth.email',
        //     'rules' => [
        //         'required',
        //         'max_length[254]',
        //         'valid_email'
        //     ],
        // ],
        'password' => [
            'label' => 'Auth.password',
                'rules' => [
                    'required',
                    'max_byte[72]',
                ],
            'errors' => [
                'max_byte' => 'Auth.errorPasswordTooLongBytes',
            ]
        ],
    ];
}
