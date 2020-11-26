<?php

use Calc\Model\Client;
use Calc\Model\Calculation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailer;

class CalculationPrePaidNeedMeasureEmail extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:CalculationPrePaidNeedMeasureEmail';

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
        echo "Searching clients...";

        $calculations = Calculation::query()
            ->where('status', '=', Calculation::MAKE_ORDERS_STATUS)
            ->where('updated_at', '>', date('Y-m-d', time() - 60 * 60 * 24))
            ->get();

        echo " done!\n";

        if (!$calculations->isEmpty()) {
            echo "OK, we found " . count($calculations) . " calculations!\n";

            /** @var Calculation $calculation */
            foreach ($calculations as $calculation) {
                echo "Sending mail to {$calculation->manager->email} ({$calculation->manager->present()->fullName})... ";

                Mail::send('calc::emails.prepaid_need_measure', compact('calculation'), function ($message) use ($calculation) {

                    /** @var \Illuminate\Mail\Message $message */
//                    $message->to($calculation->manager->email, $calculation->manager->present()->fullName);
					$message->to('15099711@gmail.com', 'Технолог');
					//copy
//					$message->bcc('mob1125888@gmail.com', 'Ситникова Ольга');
					$message->subject('Напоминание об отправке замерщика, заказ # ' . $calculation->id. ': ' . date('Y-m-d'));
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
