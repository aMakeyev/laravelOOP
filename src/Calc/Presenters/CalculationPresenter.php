<?php namespace Calc\Presenters;

class CalculationPresenter extends Presenter
{
    public function status()
    {
        $statuses = \Config::get("calc::calculation/statuses");

        return $statuses[$this->entity->status];
    }
}
