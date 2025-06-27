<?php

namespace App\Traits\Customer;

trait CustomerValidationRules
{
    /**
     * Returns the rules that should be used for customer login validation.
     *
     * @return array<string, array<string, list<string>|string>>
     */
    protected function getLoginValidationRules(): array
    {
        return [
            'username' => [
                'label' => 'Auth.username',
                'rules' => [
                    'required',
                    'max_length[30]',
                    'min_length[3]',
                ],
            ],
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
}
