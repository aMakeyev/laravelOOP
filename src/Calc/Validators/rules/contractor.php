<?php

return [
    'create' => [
        'title'       => ['required', 'min:3', 'max:255', 'eng_rus_text'],
        'first_name'  => ['required', 'min:3', 'max:255', 'alpha_dash'],
        'last_name'   => ['required', 'min:3', 'max:255', 'alpha_dash'],
        'function'    => ['required', 'min:3', 'max:255', 'alpha_dash'],
        'email'       => ['required', 'email', 'unique:contractors,email'],
        'phone'       => ['required', 'max:200'],
        'status'      => ['required', 'integer', 'min:1'],
        'address'     => ['max:500'],
        'description' => ['required', 'max:1000'],
    ],
    'update' => [
        'title'       => ['required', 'min:3', 'max:255', 'eng_rus_text'],
        'first_name'  => ['required', 'min:3', 'max:255', 'alpha_dash'],
        'last_name'   => ['required', 'min:3', 'max:255', 'alpha_dash'],
        'function'    => ['required', 'min:3', 'max:255', 'alpha_dash'],
        'email'       => ['required', 'email', 'unique:contractors,email'],
        'phone'       => ['required', 'max:200'],
        'status'      => ['required', 'integer', 'min:1'],
        'address'     => ['max:500'],
        'description' => ['required', 'max:1000'],
    ],
];
