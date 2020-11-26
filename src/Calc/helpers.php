<?php

/**
 * Sanitize string
 *
 * @param $value
 *
 * @return string
 */
function sanitize($value)
{
    return e(trim(strip_tags((string) $value)));
}

/**
 * Formatting price
 *
 * @param int $value
 *
 * @return string
 */
function price($value = 0, $round = true)
{
    return number_format($value, ($round || ((string) $value == (string) floor($value)) ? 0 : 2), '.', '&thinsp;');
}

/**
 * Check if array is range
 *
 * @param array $array
 *
 * @return bool
 */
function isRange(array $array)
{
    $i = count($array) - 1;
    if ($i < 1) return false;

    for (; $i > 0; $i--)
    {
        if ($array[$i] - 1 !== $array[$i - 1])
        {
            return false;
        }
    }

    return true;
}

/**
 * @return \Illuminate\Auth\Guard
 */
function auth()
{
    return app('auth');
}

/**
 * @return \Calc\Model\User
 */
function user()
{
    return auth()->user();
}

if ( ! function_exists('request'))
{
    /**
     * @return \Illuminate\Http\Request
     */
    function request()
    {
        return app('request');
    }
}

if ( ! function_exists('input'))
{
    /**
     * @param string $key
     * @param mixed $default
     *
     * @return array
     */
    function input($key = null, $default = null)
    {
        if (is_null($key)) return request()->input();

        return request()->input($key, $default);
    }
}

if ( ! function_exists('input_only'))
{
    /**
     * @return array
     */
    function input_only()
    {
        return request()->only(func_get_args());
    }
}

if ( ! function_exists('bcrypt'))
{
    /**
     * Hash the given value.
     *
     * @param  string $value
     * @param  array $options
     * @return string
     */
    function bcrypt($value, $options = [])
    {
        return app('hash')->make($value, $options);
    }
}

if ( ! function_exists('old'))
{
    /**
     * Retrieve an old input item.
     *
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    function old($key = null, $default = null)
    {
        return request()->old($key, $default);
    }
}

if ( ! function_exists('redirect'))
{
    /**
     * Get an instance of the redirector.
     *
     * @param  string|null  $to
     * @param  int     $status
     * @param  array   $headers
     * @param  bool    $secure
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    function redirect($to = null, $status = 302, $headers = array(), $secure = null)
    {
        if (is_null($to)) return app('redirect');

        return app('redirect')->to($to, $status, $headers, $secure);
    }
}

if ( ! function_exists('view'))
{
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string $view
     * @param  array $data
     * @param  array $mergeData
     * @return \Illuminate\View\View
     */
    function view($view = null, $data = [], $mergeData = [])
    {
        $factory = app('Illuminate\View\Factory');

        if (func_num_args() === 0)
        {
            return $factory;
        }

        return $factory->make($view, $data, $mergeData);
    }
}
function dbg($array, $stop = false) {
	echo '<pre>'.print_r($array,1).'</pre>';
	if(!$stop) {
		exit();
	}
}