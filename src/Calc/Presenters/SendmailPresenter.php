<?php namespace Calc\Presenters;

use Calc\Model\Client;
use Config;

class SendmailPresenter extends Presenter
{
    public function status()
    {
        $statuses = Config::get('calc::sendmail/statuses');

        return $statuses[$this->entity->status];
    }

    public function target()
    {
        $targets = Config::get('calc::client/types');

        return $targets[$this->entity->target];
    }

    public function created_at()
    {
        if ( ! is_object($this->entity->created_at)) return '';

        return $this->entity->created_at->format(Config::get('calc::app.date_format'));
    }

    public function updated_at()
    {
        if ( ! is_object($this->entity->updated_at)) return '';

        return $this->entity->updated_at->format(Config::get('calc::app.date_format'));
    }

    public function fullSubject(Client $client)
    {
        return $this->convertTexts($this->entity->subject, $client);
    }

    public function fullBody(Client $client)
    {
        return $this->convertTexts($this->entity->body, $client);
    }

    protected function convertTexts($text, Client $client) {
        $text = str_replace('%FULLNAME%', $client->present()->fullName, $text);

        return $text;
    }
}
