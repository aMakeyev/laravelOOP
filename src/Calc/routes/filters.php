<?php

Route::filter('api', function ()
{
    if (/* ! Request::ajax() ||*/ ! Auth::check())
    {
        return Response::json(['error' => true, 'message' => 'Unauthorized Access'], 401);
    }
});

Route::filter('auth', function ()
{
    if (Auth::guest())
    {
        if (Request::ajax())
        {
            return Response::json([
                'message' => 'Unauthorized'
            ], 401);
        }
        else
        {
            return Redirect::guest('login');
        }
    }
});

Route::filter('guest', function ()
{
    if (Auth::check()) return Redirect::to('/');
});

Route::filter('admin', function ()
{
    if (Auth::check() && ! Auth::user()->isAdmin())
    {
        App::abort(403);
    }
});

Route::filter('csrf', function ()
{
    if (Session::token() !== Input::get('_token'))
    {
        throw new Illuminate\Session\TokenMismatchException;
    }
});


Event::listen('auth.login', function ($user)
{
    if ($user instanceof Calc\Model\User)
    {
        $user->last_login = Carbon::now();
        $user->save();
    }
});
