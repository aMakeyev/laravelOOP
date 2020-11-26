<?php

return array(
    'debug' => true,
    'url'   => 'http://calc.local/',
    'providers' => append_config([
        'Barryvdh\Debugbar\ServiceProvider',
        'Darsain\Console\ConsoleServiceProvider',
    ])
);
