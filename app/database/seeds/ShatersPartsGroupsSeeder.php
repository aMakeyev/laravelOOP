<?php

use Calc\Model\ShatersPartGroup;

class ShatersPartsGroupsSeeder extends Seeder {

    public function run()
    {
		ShatersPartGroup::create(['title' => 'Комплектующие']);
		ShatersPartGroup::create(['title' => 'Материалы']);
		ShatersPartGroup::create(['title' => 'Элементы обработки']);
    }

}
