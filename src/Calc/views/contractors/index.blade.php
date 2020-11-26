@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h1>
                <span class="glyphicon glyphicon-tower"></span> @lang('calc::titles.contractors')
                <button type="button" class="btn btn-success pull-right m-pull-left" onclick="App.contractor.create()"><span class="glyphicon glyphicon-plus"></span> Добавить подрядчика</button>
            </h1>
        </div>
    </div>

<div id="toolbar" class="toolbar">
    Статус <input type="text" id="status"/>
    <input type="text" id="search" style="width: 250px"/>
</div>

<table id="contractors"></table>

    <style>
        /* Extra Small Devices, Phones */
        @media only screen and (max-width : 415px) {
            .datagrid-cell-c1-id {width:18px !important;}
        }
    </style>

@stop
@section('scripts')
<script src="/static/js/src/contractors.js"></script>
@stop
