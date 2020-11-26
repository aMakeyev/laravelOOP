<?php namespace Calc\Presenters;

class ContractorPresenter extends Presenter
{
    public function fullName()
    {
        return $this->entity->last_name . ' ' . $this->entity->first_name;
    }

    public function status()
    {
        $statuses = \Config::get("calc::contractor/statuses");

        return $statuses[$this->entity->status];
    }
}
