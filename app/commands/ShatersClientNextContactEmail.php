<?php

use Calc\Model\ShatersClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailer;

class ShatersClientNextContactEmail extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:ShatersClientNextContactEmail';

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

        $clients = ShatersClient::query()
            ->where('next_contact_at', '>', date('Y-m-d'))
            ->where('next_contact_at', '<', date('Y-m-d', time() + 60 * 60 * 24))
            ->get();

        echo " done!\n";

        if (!$clients->isEmpty()) {
            echo "OK, we found " . count($clients) . " clients!\n";

            /** @var ShatersClient $client */
            foreach ($clients as $client) {
                echo "Sending mail to {$client->manager->email} ({$client->manager->present()->fullName})... ";

                Mail::send('calc::emails.remind_shaters_client_call', compact('client'), function ($message) use ($client) {

                    /** @var \Illuminate\Mail\Message $message */
                    $message->to($client->manager->email, $client->manager->present()->fullName);
					$message->subject('Шаттерсы. Напоминание о звонке: ' . date('Y-m-d'));
                });

                echo "done!\n";
            }
        } else {
            echo "No clients for mail send.\n";
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
