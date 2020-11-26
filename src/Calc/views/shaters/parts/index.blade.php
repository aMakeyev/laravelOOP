@section('content')
<h1>
    <span class="glyphicon glyphicon-th-large"></span> @lang('calc::titles.parts')
    @if ($user->isAdmin() || Auth::user()->id == 5)
    <button type="button" class="btn btn-success pull-right m-pull-left" onclick="App.part.create()"><span class="glyphicon glyphicon-plus"></span> Добавить материал или комплектующее</button>
    @endif
</h1>

<div class="row">
    @if ($user->isAdmin())
    <div class="col-md-12 d-no-mobile">
        {{ HTML::variable('margin') }}
    </div>
    @endif
</div>

<div id="toolbar" class="toolbar">
    Группа <input id="group" type="text" />
    <input id="search" type="text" style="width:250px"/>
</div>
<table id="shaters_parts"></table>

<style>
    /* Extra Small Devices, Phones */
    @media only screen and (max-width : 415px) {

        .datagrid-cell-c1-id {width:27px !important;}

        .datagrid-cell-c1-article {width:50px !important;}

        .datagrid-cell-c1-title {width:130px !important;}

        .datagrid-cell-c1-unit {width:34px !important;}

        .datagrid-cell-c1-unit_price {width:58px !important;}
    }
</style>

@stop
@section('scripts')
<script src="/static/js/src/shaters/parts.js"></script>
@stop
