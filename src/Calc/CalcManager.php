<?php namespace Calc;

use Illuminate\Container\Container;

class CalcManager
{
    /**
     * @var \Illuminate\Container\Container
     */
    protected $app;

    function __construct(Container $app)
    {
        $this->app = $app;
    }
}
