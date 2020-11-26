<?php

use Calc\Model\Sendmail;

class SendmailsSeeder extends Seeder {

    public function run()
    {
		Sendmail::create([
			'subject' => 'Тест',
			'body' => 'Тест',
			'target' => 1,
			'status' => 2,
		]);

    }

}
