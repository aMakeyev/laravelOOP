<?php namespace Calc\Controller\Api;

use Config;
use Input;
use Response;
use Calc\Helpers\Lists;

class HelpersController extends BaseController
{
    public function managersRoles($all = null)
    {
        $response = [];

        if ($all == 'all')
        {
            $response[] = ['id' => 0, 'title' => 'Все'];
        }

        foreach (Config::get('calc::manager/roles') as $id => $title)
        {
            $response[] = ['id' => $id, 'title' => $title];
        }

        return $response;
    }

    public function clientsStatuses($all = null)
    {
        $response = [];

        if ($all == 'all')
        {
            $response[] = ['id' => 0, 'title' => 'Все'];
        }

        foreach (Config::get('calc::client/statuses') as $id => $title)
        {
            $response[] = ['id' => $id, 'title' => $title];
        }

        return $response;
    }

    public function clientsTypes($all = null)
    {
        $response = [];

        if ($all == 'all')
        {
            $response[] = ['id' => 0, 'title' => 'Все'];
        }

        foreach (Config::get('calc::client/types') as $id => $title)
        {
            $response[] = ['id' => $id, 'title' => $title];
        }

        return $response;
    }

    public function contractorsStatuses($all = null)
    {
        $response = [];

        if ($all == 'all')
        {
            $response[] = ['id' => 0, 'title' => 'Все'];
        }

        foreach (Config::get('calc::contractor/statuses') as $id => $title)
        {
            $response[] = ['id' => $id, 'title' => $title];
        }

        return $response;
    }

    public function ordersStatuses($all = null)
    {
        $response = [];

        if ($all == 'all')
        {
            $response[] = ['id' => 0, 'title' => ''];
        }

        foreach (Config::get('calc::order/statuses') as $id => $title)
        {
            $response[] = ['id' => $id, 'title' => $title];
        }

        return $response;
    }

    public function calculationsStatuses($all = null)
    {
        $response = [];

        if ($all == 'all')
        {
            $response[] = ['id' => 0, 'title' => 'Все'];
        }

        foreach (Config::get('calc::calculation/statuses') as $id => $title)
        {
            $response[] = ['id' => $id, 'title' => $title];
        }

        return $response;
    }

    public function managersStatuses($all = null)
    {
        $response = [];

        if ($all == 'all')
        {
            $response[] = ['id' => 0, 'title' => 'Все'];
        }

        foreach (Config::get('calc::manager/statuses') as $id => $title)
        {
            $response[] = ['id' => $id, 'title' => $title];
        }

        return $response;
    }

    public function managers($all = null)
    {
        return Response::json(
            Lists::managers($all,  trim(Input::get('term'))), 200, [], JSON_UNESCAPED_UNICODE
        );
    }

    public function clients($all = null)
    {
        return Response::json(
            Lists::clients($all,  trim(Input::get('term'))), 200, [], JSON_UNESCAPED_UNICODE
        );
    }

    public function contractors($all = null)
    {
        return Response::json(
            Lists::contractors($all,  trim(Input::get('term'))), 200, [], JSON_UNESCAPED_UNICODE
        );
    }

    public function parts($all = null)
    {
        return Response::json(
            Lists::parts($all,  trim(Input::get('term'))), 200, [], JSON_UNESCAPED_UNICODE
        );
    }

    public function sendmailsStatuses($all = null)
    {
        $response = [];

        if ($all == 'all')
        {
            $response[] = ['id' => 0, 'title' => 'Все'];
        }

        foreach (Config::get('calc::sendmail/statuses') as $id => $title)
        {
            $response[] = ['id' => $id, 'title' => $title];
        }

        return $response;
    }

    //Шаттерсы
	public function shatersClients($all = null)
	{
		return Response::json(
			Lists::shatersClients($all,  trim(Input::get('term'))), 200, [], JSON_UNESCAPED_UNICODE
		);
	}

	public function shatersParts($all = null)
	{
		return Response::json(
			Lists::shatersParts($all,  trim(Input::get('term'))), 200, [], JSON_UNESCAPED_UNICODE
		);
	}

	public function shatersManagers($all = null)
	{
		return Response::json(
			Lists::shatersManagers($all,  trim(Input::get('term'))), 200, [], JSON_UNESCAPED_UNICODE
		);
	}
}
