<?php

return [
    'user'       => [
        'create_from_console' => [
            'email'      => ['required', 'email', 'unique:users,email'],
            'password'   => ['required', 'min:6', 'max:255'],
            'username'   => ['required', 'min:3', 'max:255', 'alpha', 'unique:users,username'],
            'last_name'  => ['min:3', 'max:255', 'alpha_dash'],
            'first_name' => ['min:3', 'max:255', 'alpha_dash'],
        ],
        'create' => [
            'email'      => ['required', 'email', 'unique:users,email'],
            'password'   => ['required', 'min:6', 'max:255', 'confirmed'],
            'username'   => ['required', 'min:3', 'max:255', 'alpha', 'unique:users,username'],
            'last_name'  => ['min:3', 'max:255', 'alpha_dash'],
            'first_name' => ['min:3', 'max:255', 'alpha_dash'],
            'rate'       => ['required', 'numeric'],
            'phone'      => ['required', 'numeric'],
        ],
        'update' => [
            'email'      => ['required', 'email'],
            'password'   => ['min:6', 'max:255', 'confirmed'],
            'last_name'  => ['required', 'min:3', 'max:255', 'alpha_dash'],
            'first_name' => ['required', 'min:3', 'max:255', 'alpha_dash'],
            'rate'       => ['required', 'numeric'],
            'phone'      => ['required', 'numeric'],
        ],
    ],
];
