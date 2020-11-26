<?php namespace Calc\Presenters;

class UserPresenter extends Presenter
{
    public function fullName()
    {
        if ( ! empty($this->entity->last_name) && ! empty($this->entity->first_name))
        {
            return $this->entity->last_name . ' ' . $this->entity->first_name;
        }

        return $this->entity->username;
    }

    public function role()
    {
        $roles = \Config::get("calc::manager/roles");

        return $roles[$this->entity->role];
    }

    public function status()
    {
        $statuses = \Config::get("calc::manager/statuses");

        return $statuses[$this->entity->status];
    }
}
