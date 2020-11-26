<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories([

    app_path() . '/commands',
    app_path() . '/database/seeds',

]);

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useFiles(storage_path() . '/logs/' . date('d-m-Y') . '.log');

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

/*App::error(function(Exception $exception, $code)
{
	Log::error($exception);
});*/

/*App::error(function(Exception $exception, $code)
{
    return $exception->getMessage();
});

App::error(function(\Illuminate\Database\Eloquent\ModelNotFoundException $exception)
{
    return Response::view('index', array(), 404);
});

App::error(function(PDOException $exception)
{
    return Response::view('index', array('error' => 'No DB connection'));
});

App::missing(function($exception)
{
    return Response::view('index', array(), 404);
});

throw new Exception('Ошибка');*/

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function ()
{
    return Response::make("Сайт на тех. обслуживании. Попробуйте зайти через 10 мин.", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/
