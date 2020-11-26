<?php

use Calc\Model\Client;
use Calc\Model\Calculation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailer;

class CalculationOrderEndNoticeEmail extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:CalculationOrderEndNoticeEmail';

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
            ->where('make_at', '<', date('Y-m-d', time() + 60 * 60 * 24 * 8))
            ->where('make_at', '>=', date('Y-m-d', time() + 60 * 60 * 24 * 7))
            ->get();

        echo " done!\n";

        if (!$calculations->isEmpty()) {
            echo "OK, we found " . count($calculations) . " calculations!\n";

            /** @var Calculation $calculation */
            foreach ($calculations as $calculation) {
                echo "Sending mail to {$calculation->manager->email} ({$calculation->manager->present()->fullName}), 15099711@gmail.com (технолог) и 1125888@gmail.com ... ";

                Mail::send('calc::emails.order_end_notice', compact('calculation'), function ($message) use ($calculation) {

                    /** @var \Illuminate\Mail\Message $message */
                    $message->to('15099711@gmail.com', 'Технолог');
                    $message->bcc('1125888@gmail.com', 'Ситникова Ольга');
                    $message->bcc($calculation->manager->email, $calculation->manager->present()->fullName);
                    $message->subject('Напоминание о сроке сдачи заказа: ' . date('Y-m-d'));
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
