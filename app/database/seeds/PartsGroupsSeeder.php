<?php

use Manager\Models\PartGroup;

class PartsGroupsSeeder extends Seeder {

    public function run()
    {
        PartGroup::create(['title' => 'Комплектующие']);
        PartGroup::create(['title' => 'Материалы']);
    }

}
