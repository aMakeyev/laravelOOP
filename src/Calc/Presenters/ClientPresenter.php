<?php namespace Calc\Presenters;

use Config;

class ClientPresenter extends Presenter
{
    public function fullName()
    {
        return $this->entity->last_name . ' ' . $this->entity->first_name;
    }

    public function status()
    {
        $statuses = Config::get('calc::client/statuses');

        return $statuses[$this->entity->status];
    }

    public function type()
    {
        $types = Config::get('calc::client/types');

        return $types[$this->entity->type];
    }

    public function subscribe()
    {
        $subscribes = Config::get('calc::client/subscribes');

        return $subscribes[$this->entity->subscribe];
    }

    public function last_contact_at()
    {
        if ( ! is_object($this->entity->last_contact_at)) return '';

        return $this->entity->last_contact_at->format(Config::get('calc::app.date_format'));
    }

    public function next_contact_at()
    {
        if ( ! is_object($this->entity->next_contact_at)) return '';

        return $this->entity->next_contact_at->format(Config::get('calc::app.date_format'));
    }

    public function handled_at()
    {
        if ( ! is_object($this->entity->handled_at)) return '';

        return $this->entity->handled_at->format(Config::get('calc::app.date_format'));
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
}
