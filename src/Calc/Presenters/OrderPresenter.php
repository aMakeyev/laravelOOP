<?php namespace Calc\Presenters;

class OrderPresenter extends Presenter
{

    public function status()
    {
        $roles = \Config::get("calc::order/statuses");

        return $roles[$this->entity->status];
    }

}
