<?php namespace Calc\Controller;

use Auth;
use Redirect;
use Notification;
use Calc\Validators\UserValidator;
use Calc\Core\Controllers\BaseController;

class AuthController extends BaseController
{
    public function postLogin()
    {
        $login = input('login');
        $credentials[filter_var($login, FILTER_VALIDATE_EMAIL) !== false ? 'email' : 'username'] = $login;
        $credentials['password'] = input('password');

        if (Auth::attempt($credentials, true, true))
        {
            return Redirect::intended(user()->isAdmin() ? '/managers' : '/');
        }

        Notification::danger(trans('calc::auth.error_login'));

        return Redirect::back()->onlyInput('login');
    }

    public function getLogin()
    {
        $this->layout->content = view('calc::dashboard.login');
    }

    public function getLogout()
    {
        Auth::logout();

        return Redirect::to('login')->withMessage(trans('calc::auth.success_logout'));
    }

    public function getProfile()
    {
        $this->layout->content = view('calc::profile');
    }

    public function postProfile()
    {
        $data = [
            'first_name'            => input('first_name'),
            'last_name'             => input('last_name'),
            'email'                 => input('email'),
            'password'              => input('password'),
            'password_confirmation' => input('password_confirmation'),
        ];

        $validator = new UserValidator($data, 'update');

        if ( ! $validator->passes())
        {
            Notification::danger('<b>Ошибка!</b> Исправьте ошибки в форме и повторите попытку');
            return Redirect::to('profile')
                ->withInput($data)
                ->withErrors($validator->getErrors());
        }

        // Update user
        $this->user->update($data);

        Notification::success('Данные успешно обновлены!');

        return Redirect::to('profile');
    }
}
