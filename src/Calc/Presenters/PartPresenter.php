<?php namespace Calc\Presenters;

class PartPresenter extends Presenter
{
    public function unit()
    {
        if ( ! $this->entity->exists) return '';

        $units = \Config::get("calc::part/units");

        return $units[$this->entity->unit];
    }

}
