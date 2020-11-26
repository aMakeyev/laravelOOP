@section('content')
<h1>
    <span class="glyphicon glyphicon-list-alt"></span> @lang('calc::titles.calculations')
    <a href="/shaters/calculation/create" class="btn btn-success pull-right m-pull-left"><span class="glyphicon glyphicon-plus"></span> Создать новый расчет</a>
</h1>

@if ($user->isAdmin())
<div class="row">
    <div class="col-md-12 m-pull-left d-no-mobile">
        {{ HTML::variable('discount') }}
    </div>
</div>
@endif
<div id="toolbar" class="toolbar">
    Статус <input type="text" id="status"/><div class="d-mobile"></div>
    Менеджер <input type="text" id="manager"/>
    <input type="text" id="search" style="width: 300px"/>
</div>

<table id="shaters_calculations"></table>

<style>
    /* Extra Small Devices, Phones */
    @media only screen and (max-width : 415px) {
        .datagrid-cell-c1-status,
        .datagrid-cell-c1-action,
        .datagrid-cell-c1-delivery,
        .datagrid-cell-c1-install,
        .datagrid-cell-c1-created_at {
            display:none !important;
        }

        .datagrid-cell-c1-id {width:31px !important;}

        .datagrid-cell-c1-title {width:118px !important;}

        .datagrid-cell-c1-status {width:47px !important;}

        .datagrid-cell-c1-users-last_name {width:69px !important;}

        .datagrid-cell-c1-clients-last_name {width:61px !important;}

        .datagrid-cell-c1-cost_final {width:45px !important;}
    }
</style>

@stop
@section('scripts')
<script src="/static/js/src/shaters/calculations.js"></script>
@stop
