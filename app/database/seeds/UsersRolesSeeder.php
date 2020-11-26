<?php

use Manager\Models\UserRole;

class UsersRolesSeeder extends Seeder {

    public function run()
    {
        UserRole::create(['title' => 'Руководитель']);
        UserRole::create(['title' => 'Менеджер']);
        UserRole::create(['title' => 'Кто-то еще']);
    }

}
