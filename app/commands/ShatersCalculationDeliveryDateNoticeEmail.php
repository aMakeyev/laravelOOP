<?php

use Calc\Model\ShatersCalculation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailer;

class ShatersCalculationDeliveryDateNoticeEmail extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:ShatersCalculationDeliveryDateNoticeEmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders for managers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        echo "Searching orders...";

        $calculations = ShatersCalculation::query()
            ->where('delivery_at', '<', date('Y-m-d', time() + 60 * 60 * 24 * 4))
            ->where('delivery_at', '>=', date('Y-m-d', time() + 60 * 60 * 24 * 3))
            ->get();

        echo " done!\n";

        if (!$calculations->isEmpty()) {
            echo "OK, we found " . count($calculations) . " calculations!\n";

            /** @var Calculation $calculation */
            foreach ($calculations as $calculation) {
                echo "Sending mail to 15099711@gmail.com (технолог)... ";

                Mail::send('calc::emails.delivery_date_notice', compact('calculation'), function ($message) use ($calculation) {

                    /** @var \Illuminate\Mail\Message $message */
                    $message->to('15099711@gmail.com', 'Технолог');
					$message->subject('Шаттерсы. Напоминание о доставке заказа: ' . date('Y-m-d'));
                });

                echo "done!\n";
            }
        } else {
            echo "No calculations for mail send.\n";
        }
        //
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }

}
