<?php namespace Calc;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Exception;
use Request;
use Mail;

class CalcServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->package('', 'calc', __DIR__);

        $this->registerAliases($this->app['config']->get('calc::app.aliases'));

        $this->registerRepositories();

        require __DIR__ . '/helpers.php';
        require __DIR__ . '/macros.php';
        require __DIR__ . '/routes/routes.php';
        require __DIR__ . '/routes/filters.php';
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerErrorHandler();

        $this->registerBindings();

        $this->app->after(function ()
        {
            if ($this->app['auth']->check())
            {
                $this->app['auth']->user()->touchActivity();
            }
        });
    }

    protected function registerBindings()
    {
        $this->app->bind('Title', 'Calc\Helpers\Title');
    }

    /**
     * Register Module Error Handler
     */
    protected function registerErrorHandler()
    {
        if ($this->app['config']->get('app.debug'))
        {
            return;
        }

        $this->app->error(function(Exception $e, $code)
        {
            $view = $code == 404 ? 'calc::404' : 'calc::500';

            if ($code !== 404) $this->sendErrorEmail($e, $code);

            $response = new Response(Request::ajax()
                ? ['error' => true, 'code' => $code,]
                : view($view, compact('code'))->render(), $code);

            return $response;
        });

        $this->app->error(function (ModelNotFoundException $e, $code)
        {
            $response = new Response(Request::ajax()
                ? ['error' => true, 'code' => 404, 'message' => 'Not Found!',]
                : view('calc::404', ['code' => 404,])->render(), $code);

            return $response;
        });
    }

    protected function registerRepositories()
    {
        foreach ($this->app['config']->get('calc::app.repositories') as $alias => $repo)
        {
            $this->app->singleton($this->getClassName($repo));
            $this->app->bind('repo.' . $alias, $this->getClassName($repo));
        }
    }

    protected function registerAliases(array $aliases)
    {
        foreach ($aliases as & $class)
        {
            $class = $this->getClassName($class);
        }

        AliasLoader::getInstance($aliases);
    }

    protected function getClassName($class)
    {
        return __NAMESPACE__ . '\\' . $class;
    }

    protected function sendErrorEmail(Exception $e, $code)
    {
        Mail::send('calc::emails.error', [
            'error' => $e,
            'code' => $code,
        ], function ($message) {
            $message
                ->to('', '')
                ->subject('Ошибка на сайте :: Калькулятор');
        });
    }
}
