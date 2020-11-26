<?php

use Manager\Models\User;

class UsersSeeder extends Seeder {

    public function run()
    {
        User::create([
            'username' => 'admin',
            'password' => 'admin',
            'email' => 'brezhnev.ivan@yahoo.com',
            'phone' => '+380977357170',
            'first_name' => 'Иван',
            'last_name' => 'Брежнев',
            'role' => 1,
        ]);
        User::create([
            'username' => 'bigler',
            'password' => 'bigler@yandex.ru',
            'email' => 'bigler@yandex.ru',
            'phone' => '',
            'first_name' => 'Артур',
            'last_name' => 'Биглер',
            'role' => 1,
        ]);
    }

}
