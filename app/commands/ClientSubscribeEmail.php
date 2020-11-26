<?php

use Calc\Model\Client;
use Calc\Model\Sendmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailer;

class ClientSubscribeEmail extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:ClientSubscribeEmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send subscribed clients mails';

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
        $this->info('Ищем очередную рассылку...');

        /** @var Sendmail $sendmail */
        $sendmail = Sendmail::query()
            ->where('status', '=', 1)
            ->first();

//        if($sendmail->exists) {
        if(!empty($sendmail)) {
            $this->info('Готово. Итак, у нас есть письмо, которое нужно разослать.');

            $sendmail->status = 2;
            $sendmail->save();

            $this->info('Поищем клиентов...');

            $clients = Client::query()
                ->where('type', '=', $sendmail->target)
                ->where('subscribe', '=', 2)
                ->get();

            $this->info('Поиск завершён!');

            if (!$clients->isEmpty()) {
                $this->info("Прекрасно, мы нашли " . count($clients) . " клиентов!");

                /** @var Client $client */
                foreach ($clients as $client) {
                    $this->info("Шлём мыло на {$client->email} ({$client->present()->fullName})... ");

                    Mail::send('calc::emails.subscribe_mail', compact('client', 'sendmail'), function ($message) use ($client, $sendmail) {
                        /** @var \Illuminate\Mail\Message $message */
                        $message->to($client->email, $client->present()->fullName);
                        if(!empty($sendmail->file))
                            $message->attach(public_path('files/sendmails') . '/' . basename($sendmail->file));
                        $message->subject($sendmail->present()->fullSubject($client));
                    });

                    $this->info("Всё :)");
                }
            } else {
                $this->error('Клиенты не найдены!');
            }

            $sendmail->status = 3;
            $sendmail->save();

            $this->info("Рассылка завершена!");
        } else {
            $this->error('Рассылки не найдены!');
        }
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
