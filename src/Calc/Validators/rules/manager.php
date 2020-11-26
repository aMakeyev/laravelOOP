<?php

return [
    'create_from_console' => [
        'email'      => ['required', 'email', 'unique:users,email'],
        'password'   => ['required', 'min:6', 'max:255'],
        'username'   => ['required', 'min:3', 'max:255', 'alpha_dash', 'unique:users,username'],
        'last_name'  => ['min:3', 'max:255', 'alpha_dash'],
        'first_name' => ['min:3', 'max:255', 'alpha_dash'],
    ],
    'create' => [
        'username'   => ['required', 'min:3', 'max:255', 'alpha_dash', 'unique:users,username'],
        'first_name' => ['required', 'min:3', 'max:255', 'alpha_dash'],
        'last_name'  => ['required', 'min:3', 'max:255', 'alpha_dash'],
        'email'      => ['required', 'email', 'unique:users,email'],
        'phone'      => ['numeric'],
        'rate'       => ['numeric', 'min:1'],
        'role'       => ['required', 'integer', 'min:1'],
        'status'     => ['required', 'integer', 'min:1'],
        'password'   => ['min:6', 'max:255', 'confirmed'],
    ],
    'update' => [
        'username'   => ['required', 'min:3', 'max:255', 'alpha', 'unique:users,username'],
        'first_name' => ['required', 'min:3', 'max:255', 'alpha_dash'],
        'last_name'  => ['required', 'min:3', 'max:255', 'alpha_dash'],
        'email'      => ['required', 'email', 'unique:users,email'],
        'phone'      => ['numeric'],
        'rate'       => ['numeric', 'min:1'],
        'role'       => ['numeric', 'integer', 'min:1'],
        'status'     => ['numeric', 'integer', 'min:1'],
        'password'   => ['min:6', 'max:255', 'confirmed'],
    ],
    'self_update' => [
        'email'      => ['required', 'email', 'unique:users,email'],
        'password'   => ['min:6', 'max:255', 'confirmed'],
        'last_name'  => ['required', 'min:3', 'max:255', 'alpha_dash'],
        'first_name' => ['required', 'min:3', 'max:255', 'alpha_dash'],
        'phone'      => ['numeric'],
    ],
];
