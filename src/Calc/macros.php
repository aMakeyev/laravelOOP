<?php

Form::macro('managerRoles', function ($selected = 0, array $attrs = [])
{
    return Form::select('role', Config::get('calc::manager/roles'), $selected, $attrs);
});

Form::macro('managerStatuses', function ($selected = 0, array $attrs = [])
{
    return Form::select('status', Config::get('calc::manager/statuses'), $selected, $attrs);
});

HTML::macro('variable', function ($name)
{
    static $vars;
    if ( ! isset($vars))
    {
        $vars = \Calc\Model\Variable::all();
    }

    $var = $vars->find($name);

    return view('calc::chunks.variable', compact('var'))->render();
});

Form::macro('character', function ($selected = null, $attrs = [])
{
    $repo = app('Calc\Repo\ElementsRepo');

    return Form::select('character', $repo->getList(), $selected, $attrs);
    //return Form::select('character', Config::get('calc::part/characters_new'), $selected, $attrs);
});
Form::macro('shatersCharacter', function ($selected = null, $attrs = [])
{
	$repo = app('Calc\Repo\ShatersElementsRepo');

	return Form::select('shatersCharacter', $repo->getList(), $selected, $attrs);
});

Form::macro('additional_coefficient', function ($selected = null, $attrs = [])
{
	$repo = app('Calc\Repo\AddCoefListRepo');

	return Form::select('additional_coefficient', $repo->getList(), $selected, $attrs);
});