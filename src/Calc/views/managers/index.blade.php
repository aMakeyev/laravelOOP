@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h1>
                <span class="glyphicon glyphicon-user"></span> @lang('calc::titles.managers')
                <button type="button" class="btn btn-success pull-right m-pull-left" onclick="App.user.create()"><span class="glyphicon glyphicon-plus"></span> Добавить менеджера</button>
            </h1>
        </div>
    </div>
<div id="toolbar" class="toolbar">
    Роль <input type="text" id="role"/><div class="d-mobile"></div>
    Статус <input type="text" id="status"/>
</div>

<table id="managers"></table>

<style>
    /* Extra Small Devices, Phones */
    @media only screen and (max-width : 415px) {
        .datagrid-cell-c1-email,
        .datagrid-cell-c1-rate,
        .datagrid-cell-c1-created_at,
        .datagrid-cell-c1-calculations_count {
            display:none !important;
        }

        .datagrid-cell-c1-phone {width:86px !important;}

        .datagrid-cell-c1-last_activity {width:77px !important;}

        .datagrid-cell-c1-last_name {width:64px !important;}

        .datagrid-cell-c1-id {width:19px !important;}

        .datagrid-cell-c1-first_name {width:48px !important;}

    }

</style>

@stop
@section('scripts')
<script src="/static/js/src/managers.js"></script>
@stop
