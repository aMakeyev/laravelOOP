<?php namespace Calc\Controller;

use Redirect;
use Notification;
use Calc\Validators\UserValidator;
use Calc\Core\Controllers\BaseController;

class ProfileController extends BaseController
{
    public function getIndex()
    {
        $this->title->prepend(trans('calc::titles.profile'));
        $this->layout->content = view('calc::profile.index');
    }

    public function update()
    {
        $data = [
            'first_name'            => input('first_name'),
            'last_name'             => input('last_name'),
            'email'                 => input('email'),
            'phone'                 => input('phone'),
            'password'              => input('password'),
            'password_confirmation' => input('password_confirmation'),
        ];

        $validator = new UserValidator($data, 'self_update');
        UserValidator::$rules['self_update']['email'][2] .= ',' . $this->user->id;

        if ( ! $validator->passes())
        {
            Notification::danger(trans('calc::profile.failed'));

            return Redirect::to('profile')
                ->withInput($data)
                ->withErrors($validator->getErrors());
        }

        // Update user
        $this->user->update($data);

        Notification::success(trans('calc::profile.updated'));

        return Redirect::to('profile');
    }
}
