<?php

Route::pattern('id', '\d+');
Route::pattern('all', 'all');

/**
 * Default routes
 */
Route::group(['before' => 'guest'], function ()
{
    Route::get('/login', ['as' => 'login', 'uses' => 'AuthController@getLogin']);
    Route::post('/login', 'AuthController@postLogin');
});

Route::group(['prefix' => '/', 'before' => 'auth'], function ()
{
    Route::get('logout', ['as' => 'logout', 'uses' => 'AuthController@getLogout']);
    Route::get('profile', ['as' => 'profile', 'uses' => 'ProfileController@getIndex']);
    Route::post('profile', 'ProfileController@update');


    if (Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isHeadManager()))
    {
        Route::get('managers', 'ManagersController@index');
        Route::get('managers/{id}', 'ManagersController@show');
        Route::get('parts', 'PartsController@index');
        Route::get('coefficients', 'CoefficientsController@index');
        Route::get('elements', 'ElementsController@index');

//        Route::get('shaters/coefficients', 'Calc\Controller\ShatersCoefficientsController@index');
		Route::get('shaters/parts', 'Calc\Controller\ShatersPartsController@index');
		Route::get('shaters/elements', 'Calc\Controller\ShatersElementsController@index');
    }
	if (Auth::check() && Auth::user()->isNotShatersManager())
	{
    Route::get('calculation', 'CalculationController@index');
    Route::get('calculation/order/{id}', 'MakeOrderController@show');
    Route::get('calculation/contract/{id}', 'MakeOrderController@contract');
    Route::get('calculation/check/{id}', 'MakeOrderController@check');
    Route::get('calculation/spec/{id}', 'MakeOrderController@spec');
    Route::get('calculation/certificate/{id}', 'MakeOrderController@certificate');
    Route::get('calculation/acceptance/{id}', 'MakeOrderController@acceptance');
    Route::get('calculation/work/{id}', 'MakeOrderController@work');
    Route::get('calculation/addagree/{id}', 'MakeOrderController@addagree');
	Route::get('calculation/create', 'CalculationController@create');
    Route::get('calculation/{id}', 'CalculationController@show');
    Route::get('calculation/{id}/edit', 'CalculationController@edit');
    Route::put('calculation/{id}', 'CalculationController@update');
    Route::post('calculation/merge', 'Calc\Controller\CalculationController@merge');
    Route::get('calculation/{id}/addagree', 'Calc\Controller\CalculationController@addagree');

    Route::get('orders', 'OrdersController@index');
    Route::get('orders/edit/{id}', 'OrdersController@edit');
    Route::get('orders/{id}', 'OrdersController@show');
    Route::get('orders/addcontractor/{id}', 'OrdersController@addContractor');
	Route::post('orders/{id}', 'OrdersController@destroy');
	Route::get('orders/edit/statusDone/{id}', 'OrdersController@statusDone');
	Route::get('contractors', 'ContractorsController@index');
    Route::get('contractors/{id}', 'ContractorsController@show');
    Route::get('clients', 'ClientsController@index');
    Route::get('clients/{id}', 'ClientsController@show');
    Route::get('clients/xls', 'ClientsController@clientsXls');


    Route::get('sendmails', 'SendmailsController@index');
    Route::get('sendmails/{id}', 'SendmailsController@show');
//	Route::get('testemail', 'SendmailsController@testEmail');

	Route::get('docs', 'Calc\Controller\DocsController@index');

	}

	if (Auth::check() && Auth::user()->isNotManager())
	{
		Route::get('shaters', 'Calc\Controller\ShatersDashboardController@getIndex');
		Route::get('shaters/clients', 'Calc\Controller\ShatersClientsController@index');
		Route::get('shaters/clients/{id}', 'Calc\Controller\ShatersClientsController@show');

		Route::get('shaters/calculation', 'Calc\Controller\ShatersCalculationController@index');
		Route::get('shaters/calculation/create', 'Calc\Controller\ShatersCalculationController@create');
		Route::get('shaters/calculation/{id}', 'Calc\Controller\ShatersCalculationController@show');
		Route::get('shaters/calculation/{id}/edit', 'Calc\Controller\ShatersCalculationController@edit');
		Route::put('shaters/calculation/{id}', 'Calc\Controller\ShatersCalculationController@update');
		Route::post('shaters/calculation/merge', 'Calc\Controller\ShatersCalculationController@merge');
		Route::get('shaters/calculation/{id}/addagree', 'Calc\Controller\ShatersCalculationController@addagree');

		Route::get('shaters/calculation/order/{id}', 'Calc\Controller\ShatersMakeOrderController@show');
		Route::get('shaters/calculation/contract/{id}', 'Calc\Controller\ShatersMakeOrderController@contract');
		Route::get('shaters/calculation/check/{id}', 'Calc\Controller\ShatersMakeOrderController@check');
		Route::get('shaters/calculation/spec/{id}', 'Calc\Controller\ShatersMakeOrderController@spec');
		Route::get('shaters/calculation/certificate/{id}', 'Calc\Controller\ShatersMakeOrderController@certificate');
		Route::get('shaters/calculation/acceptance/{id}', 'Calc\Controller\ShatersMakeOrderController@acceptance');
		Route::get('shaters/calculation/work/{id}', 'Calc\Controller\ShatersMakeOrderController@work');
		Route::get('shaters/calculation/addagree/{id}', 'Calc\Controller\ShatersMakeOrderController@addagree');

		Route::get('shaters/orders', 'Calc\Controller\ShatersOrdersController@index');
		Route::get('shaters/orders/edit/{id}', 'Calc\Controller\ShatersOrdersController@edit');
		Route::get('shaters/orders/{id}', 'Calc\Controller\ShatersOrdersController@show');
		Route::get('shaters/orders/addcontractor/{id}', 'Calc\Controller\ShatersOrdersController@addContractor');
		Route::post('shaters/orders/{id}', 'OrdersController@destroy');
		Route::get('shaters/orders/edit/statusDone/{id}', 'Calc\Controller\ShatersOrdersController@statusDone');
	}

	Route::get('/', ['as' => 'root', 'uses' => 'DashboardController@getIndex']);
});

/**
 * API
 *
 * Инициализируем роуты только для авторизованных
 */
if (Auth::check())
{
    Route::group(['prefix' => 'shaters/api', 'before' => 'shaters/api'], function ()
	{
		if (Auth::user()->isNotManager()) {

			Route::get('clients', 'Calc\Controller\Api\ShatersClientsController@index');
			Route::get('clients/create', 'Calc\Controller\Api\ShatersClientsController@create');
			Route::post('clients', 'Calc\Controller\Api\ShatersClientsController@store');
			Route::get('clients/{id}', 'Calc\Controller\Api\ShatersClientsController@show');
			Route::get('clients/{id}/edit', 'Calc\Controller\Api\ShatersClientsController@edit');
			Route::put('clients/{id}', 'Calc\Controller\Api\ShatersClientsController@update');
			Route::delete('clients/{id}', 'Calc\Controller\Api\ShatersClientsController@destroy');

			/** Материалы / Комплектующие */
			Route::get('parts', 'Calc\Controller\Api\ShatersPartsController@index');
			Route::get('parts/create', 'Calc\Controller\Api\ShatersPartsController@create');
			Route::get('parts/{id}', 'Calc\Controller\Api\ShatersPartsController@show');

			Route::get('groups-parts', 'Calc\Controller\Api\ShatersGroupsPartsController@index');

			Route::get('managers-roles/{all?}', 'Api\HelpersController@managersRoles');
			Route::get('clients-statuses/{all?}', 'Api\HelpersController@clientsStatuses');
			Route::get('clients-types/{all?}', 'Api\HelpersController@clientsTypes');
			Route::get('contractors-statuses/{all?}', 'Api\HelpersController@contractorsStatuses');
			Route::get('orders-statuses/{all?}', 'Api\HelpersController@ordersStatuses');
			Route::get('calculations-statuses/{all?}', 'Api\HelpersController@calculationsStatuses');
			Route::get('managers-statuses/{all?}', 'Api\HelpersController@managersStatuses');
			Route::get('sendmails-statuses/{all?}', 'Api\HelpersController@sendmailsStatuses');

			/** Списки клиентов, менеджеров, подрядчиков, комплектующих */
			Route::get('managers-list/{all?}', 'Api\HelpersController@shatersManagers');
			Route::get('clients-list/{all?}', 'Api\HelpersController@shatersClients');
			Route::get('parts-list/{all?}', 'Api\HelpersController@shatersParts');

			/** Расчеты */
			Route::get('calculations', 'Calc\Controller\Api\ShatersCalculationsController@index');
			Route::post('calculations', 'Calc\Controller\Api\ShatersCalculationsController@create');
			Route::get('calculations/duplicate/{id}', 'Calc\Controller\Api\ShatersCalculationsController@duplicate');
			Route::delete('calculations/income/{id}', 'Calc\Controller\Api\ShatersCalculationsController@destroyIncome');
			Route::post('calculations/{id}/income', 'Calc\Controller\Api\ShatersCalculationsController@createIncome');
			Route::get('calculations/{id}', 'Calc\Controller\Api\ShatersCalculationsController@show');
			Route::put('calculations/order/{id}', 'Calc\Controller\Api\ShatersCalculationsController@updateFromOrders');
			Route::put('calculations/{id}', 'Calc\Controller\Api\ShatersCalculationsController@update');
			Route::delete('calculations/{id}', 'Calc\Controller\Api\ShatersCalculationsController@destroy');

			/** Заказы / Подряды */
			Route::get('orders', 'Calc\Controller\Api\ShatersOrdersController@index');
			Route::get('orders/create', 'Calc\Controller\Api\ShatersOrdersController@create');
			Route::post('orders', 'Calc\Controller\Api\ShatersOrdersController@store');
			Route::get('orders/{id}', 'Calc\Controller\Api\ShatersOrdersController@show');
			Route::post('orders/{id}/outlay/{type}', 'Calc\Controller\Api\ShatersOrdersController@createOutlay')->where('type', 'contractor_outlay|constructor_outlay');
			Route::get('orders/{id}/edit', 'Calc\Controller\Api\ShatersOrdersController@edit');
			Route::put('orders/{id}', 'Calc\Controller\Api\ShatersOrdersController@updateFromOrders');
			Route::delete('orders/outlay/{id}/{type}', 'Calc\Controller\Api\ShatersOrdersController@destroyOutlay');
			Route::delete('orders/{id}', 'Calc\Controller\Api\ShatersOrdersController@destroy');
			Route::get('orders/addcontractor/{id}', 'Calc\Controller\Api\ShatersOrdersController@addContractor');


		}
		if (Auth::user()->isAdmin() || Auth::user()->id == 5) {

			/** Ставки конструкторов */
			Route::get('constructors-rates', 'Api\ConstructorsRatesController@index');
			Route::post('constructors-rates', 'Api\ConstructorsRatesController@store');

			/** Коэффициенты */
			Route::get('coefficients', 'Api\CoefficientsController@index');
			Route::post('coefficients', 'Api\CoefficientsController@store');

			/** Дополнительные коэффициенты */
			Route::get('additional-coefficients', 'Api\AdditionalCoefficientsController@index');
			Route::post('additional-coefficients', 'Api\AdditionalCoefficientsController@store');

			/** Переменные */
			Route::put('variables/{name}', 'Api\VariablesController@update');

			/** Материалы / Комплектующие */
			Route::get('parts/{id}/edit', 'Calc\Controller\Api\ShatersPartsController@edit');
			Route::put('parts/{id}', 'Calc\Controller\Api\ShatersPartsController@update');
			Route::delete('parts/{id}', 'Calc\Controller\Api\ShatersPartsController@destroy');
			Route::get('parts/duplicate/{id}', 'Calc\Controller\Api\ShatersPartsController@duplicate');
			Route::post('parts', 'Calc\Controller\Api\ShatersPartsController@store');

			/** Элементы / Категории */
			Route::get('elements', 'Calc\Controller\Api\ShatersElementsController@index');
			Route::post('elements/category', 'Calc\Controller\Api\ShatersElementsController@category');
			Route::post('elements/element', 'Calc\Controller\Api\ShatersElementsController@element');
			Route::post('elements', 'Calc\Controller\Api\ShatersElementsController@store');
			Route::delete('elements/category/{id}', 'Calc\Controller\Api\ShatersElementsController@deleteCategory');
			Route::delete('elements/element/{id}', 'Calc\Controller\Api\ShatersElementsController@deleteElement');


		}
	});
    Route::group(['prefix' => 'api', 'before' => 'api'], function ()
    {
        if (Auth::user()->isAdmin() || Auth::user()->isHeadManager())
        {
            /** Менеджеры */
            Route::get('managers', 'Api\ManagersController@index');
        }

        if (Auth::user()->isAdmin())
        {
            Route::post('managers', 'Api\ManagersController@store');
            Route::get('managers/create', 'Api\ManagersController@create');
            Route::get('managers/{id}', 'Api\ManagersController@show');
            Route::put('managers/{id}', 'Api\ManagersController@update');
            Route::get('managers/{id}/edit', 'Api\ManagersController@edit');
            Route::delete('managers/{id}', 'Api\ManagersController@destroy');

            /** Ставки конструкторов */
            Route::get('constructors-rates', 'Api\ConstructorsRatesController@index');
            Route::post('constructors-rates', 'Api\ConstructorsRatesController@store');

            /** Коэффициенты */
            Route::get('coefficients', 'Api\CoefficientsController@index');
            Route::post('coefficients', 'Api\CoefficientsController@store');

            /** Дополнительные коэффициенты */
            Route::get('additional-coefficients', 'Api\AdditionalCoefficientsController@index');
            Route::post('additional-coefficients', 'Api\AdditionalCoefficientsController@store');

            /** Переменные */
            Route::put('variables/{name}', 'Api\VariablesController@update');

            /** Материалы / Комплектующие */
            Route::get('parts/{id}/edit', 'Api\PartsController@edit');
            Route::put('parts/{id}', 'Api\PartsController@update');
            Route::delete('parts/{id}', 'Api\PartsController@destroy');
            Route::get('parts/duplicate/{id}', 'Api\PartsController@duplicate');
            Route::post('parts', 'Api\PartsController@store');

            /** Элементы / Категории */
            Route::get('elements', 'Api\ElementsController@index');
            Route::post('elements/category', 'Api\ElementsController@category');
            Route::post('elements/element', 'Api\ElementsController@element');
            Route::post('elements', 'Api\ElementsController@store');
            Route::delete('elements/category/{id}', 'Api\ElementsController@deleteCategory');
            Route::delete('elements/element/{id}', 'Api\ElementsController@deleteElement');

            Route::get('sendmails', 'Api\SendmailsController@index');
        }

		if (Auth::user()->isNotShatersManager())
		{

			/** Расчеты */
			Route::get('calculations', 'Api\CalculationsController@index');
			//Route::get('calculations/create', 'Api\CalculationsController@create');
			Route::post('calculations', 'Api\CalculationsController@create');
			Route::get('calculations/duplicate/{id}', 'Api\CalculationsController@duplicate');
			Route::delete('calculations/income/{id}', 'Api\CalculationsController@destroyIncome');
			Route::post('calculations/{id}/income', 'Api\CalculationsController@createIncome');
			Route::get('calculations/{id}', 'Api\CalculationsController@show');
			//Route::get('calculations/{id}/edit', 'Api\CalculationsController@edit');
			Route::put('calculations/order/{id}', 'Api\CalculationsController@updateFromOrders');
			Route::put('calculations/{id}', 'Api\CalculationsController@update');
			Route::delete('calculations/{id}', 'Api\CalculationsController@destroy');

			/** Подрядчики */
			Route::get('contractors', 'Api\ContractorsController@index');
			Route::get('contractors/create', 'Api\ContractorsController@create');
			Route::post('contractors', 'Api\ContractorsController@store');
			Route::get('contractors/{id}', 'Api\ContractorsController@show');
			Route::get('contractors/{id}/edit', 'Api\ContractorsController@edit');
			Route::put('contractors/{id}', 'Api\ContractorsController@update');
			Route::delete('contractors/{id}', 'Api\ContractorsController@destroy');

			/** Переменные */
			Route::get('variables', 'Api\VariablesController@index');
			Route::get('variables/{name}', 'Api\VariablesController@show');

			/** Заказы / Подряды */
			Route::get('orders', 'Api\OrdersController@index');
			Route::get('orders/create', 'Api\OrdersController@create');
			Route::post('orders', 'Api\OrdersController@store');
			Route::get('orders/{id}', 'Api\OrdersController@show');
			Route::post('orders/{id}/outlay/{type}', 'Api\OrdersController@createOutlay')->where('type', 'contractor_outlay|constructor_outlay');
			Route::get('orders/{id}/edit', 'Api\OrdersController@edit');
			Route::put('orders/{id}', 'Api\OrdersController@updateFromOrders');
			Route::delete('orders/outlay/{id}/{type}', 'Api\OrdersController@destroyOutlay');
			Route::delete('orders/{id}', 'Api\OrdersController@destroy');
			Route::get('orders/addcontractor/{id}', 'Api\OrdersController@addContractor');

			/** Клиенты */
			Route::get('clients', 'Api\ClientsController@index');
			Route::get('clients/create', 'Api\ClientsController@create');
			Route::post('clients', 'Api\ClientsController@store');
			Route::get('clients/{id}', 'Api\ClientsController@show');
			Route::get('clients/{id}/edit', 'Api\ClientsController@edit');
			Route::put('clients/{id}', 'Api\ClientsController@update');
			Route::delete('clients/{id}', 'Api\ClientsController@destroy');

			Route::get('sendmails', 'Api\SendmailsController@index');
			Route::get('sendmails/create', 'Api\SendmailsController@create');
			Route::post('sendmails', 'Api\SendmailsController@store');
			Route::get('sendmails/{id}', 'Api\SendmailsController@show');
			Route::get('sendmails/{id}/edit', 'Api\SendmailsController@edit');
			Route::put('sendmails/{id}', 'Api\SendmailsController@update');
			Route::post('sendmails/{id}', 'Api\SendmailsController@update');
			Route::delete('sendmails/{id}', 'Api\SendmailsController@destroy');
			Route::delete('sendmails/file/{id}', 'Api\SendmailsController@fileDelete');

			/** Материалы / Комплектующие */
			Route::get('parts', 'Api\PartsController@index');
			Route::get('parts/create', 'Api\PartsController@create');
			Route::get('parts/{id}', 'Api\PartsController@show');

			Route::get('groups-parts', 'Api\GroupsPartsController@index');

			Route::get('managers-roles/{all?}', 'Api\HelpersController@managersRoles');
			Route::get('clients-statuses/{all?}', 'Api\HelpersController@clientsStatuses');
			Route::get('clients-types/{all?}', 'Api\HelpersController@clientsTypes');
			Route::get('contractors-statuses/{all?}', 'Api\HelpersController@contractorsStatuses');
			Route::get('orders-statuses/{all?}', 'Api\HelpersController@ordersStatuses');
			Route::get('calculations-statuses/{all?}', 'Api\HelpersController@calculationsStatuses');
			Route::get('managers-statuses/{all?}', 'Api\HelpersController@managersStatuses');
			Route::get('sendmails-statuses/{all?}', 'Api\HelpersController@sendmailsStatuses');

			/** Списки клиентов, менеджеров, подрядчиков, комплектующих */
			Route::get('clients-list/{all?}', 'Api\HelpersController@clients');
			Route::get('managers-list/{all?}', 'Api\HelpersController@managers');
			Route::get('contractors-list/{all?}', 'Api\HelpersController@contractors');
			Route::get('parts-list/{all?}', 'Api\HelpersController@parts');

		}

		/** Работа с файлами */
		if (Auth::check())
		{
			Route::post('files/upload/{fileable_type}/{fileable_id}', 'Api\FilesController@upload')->where('fileable_type', 'calculation|order|shaterscalculation|shatersorder|client|shatersclient')->where('fileable_id', '\d+');
			Route::delete('files/{id}', 'Api\FilesController@destroy');
		}
    });
}

