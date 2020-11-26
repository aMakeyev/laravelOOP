@section('content')
<h1>{{ $obj->title }}</h1>
<div><a href="{{ URL::previous() }}">&larr; Назад</a></div>
<br/>
<div class="container">
    <table class="table table-hover table-info">
        <tbody>
        <tr>
            <td>Контактное лицо</td>
            <td>{{ $obj->present()->fullName }} ({{ $obj->function }})</td>
        </tr>
        <tr>
            <td>Email</td>
            <td>{{ $obj->email }}</td>
        </tr>
        <tr>
            <td>Телефон</td>
            <td>{{ $obj->phone }}</td>
        </tr>
        <tr>
            <td>Статус</td>
            <td>{{ $obj->present()->status }}</td>
        </tr>
        @if ($obj->address != '')
        <tr>
            <td>Адрес</td>
            <td>{{{ $obj->address }}}</td>
        </tr>
        @endif
        <tr>
            <td>Количество заказов / подрядов</td>
            <td>{{ $obj->orders->count() }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Заметка</td>
            <td>{{{ $obj->description }}}</td>
        </tr>
        </tbody>
    </table>
</div>
@stop
