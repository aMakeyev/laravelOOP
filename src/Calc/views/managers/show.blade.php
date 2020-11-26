@section('content')
<h1>@lang('calc::titles.manager')</h1>
<div><a href="{{ URL::previous() }}">&larr; Назад</a></div>
<br/>
<div class="row">
    <table class="table table-hover table-info">
        <tr>
            <td>Имя</td>
            <td>{{ $obj->present()->fullName }}</td>
        </tr>
        <tr>
            <td>Роль</td>
            <td>{{ $obj->present()->role }}</td>
        </tr>
        <tr>
            <td>Статус</td>
            <td>{{ $obj->present()->status }}</td>
        </tr>
        <tr>
            <td>Email</td>
            <td>{{ $obj->email }}</td>
        </tr>
        @if ($obj->phone != '')
        <tr>
            <td>Телефон</td>
            <td>{{ $obj->phone }}</td>
        </tr>
        @endif
        <tr>
            <td>Расчеты</td>
            <td>{{ $obj->calculations_count }}</td>
        </tr>
        <tr>
            <td>Заказы</td>
            <td>{{ $obj->orders->count() }}</td>
        </tr>
        <tr>
            <td>Добавил клиентов</td>
            <td>{{ $obj->created_clients->count() }}</td>
        </tr>
    </table>
</div>
@stop
