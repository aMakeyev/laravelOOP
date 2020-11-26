@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h1>
                <span class="glyphicon glyphicon-user"></span> @lang('calc::titles.clients')
                <button type="button" class="btn btn-success pull-right m-pull-left" onclick="App.client.create()"><span class="glyphicon glyphicon-plus"></span> Добавить заказчика</button>
                @if(Auth::user()->id == 8 || Auth::user()->id == 26 || Auth::user()->id == 25)
                <a class="btn btn-info d-no-mobile" href="clients/xls">Список заказчиков в Excel</a>
                @endif
            </h1>
        </div>
    </div>
<div id="toolbar" class="toolbar">
    Статус <input type="text" id="status"/><div class="d-mobile"></div>
    Тип <input type="text" id="type"/><div class="d-mobile"></div>
    Менеджер <input type="text" id="manager"/>
    <input type="text" id="search" style="width: 250px"/>
</div>
<table id="clients"></table>

<style>
    /* Extra Small Devices, Phones */
    @media only screen and (max-width : 415px) {
        .datagrid-cell-c1-users-last_name,
        .datagrid-cell-c1-created_at {
            display:none !important;
        }

        .datagrid-cell-c1-id {width:31px !important;}

        .datagrid-cell-c1-next_contact_at {width:73px !important;}

        .datagrid-cell-c1-description {width:72px !important;}

        .datagrid-cell-c1-type {width:64px !important;}

        .datagrid-cell-c1-last_name {width:63px !important;}
    }
</style>

@stop
@section('scripts')
<script src="/static/js/src/clients.js"></script>
@stop
