@section('content')
<h1>Подробная информация о заказчике</h1>
<div><a href="{{ URL::previous() }}">&larr; Назад</a></div>
<br/>
<div class="container">
    <table class="table table-hover table-info">
        <tbody>
        <tr>
            <td>ФИО</td>
            <td>{{ $obj->present()->fullName }}</td>
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
        <tr>
            <td>Тип</td>
            <td>{{ $obj->present()->type }}</td>
        </tr>
        <tr>
            <td>Последний звонок</td>
            <td>{{ $obj->present()->last_contact_at }}</td>
        </tr>
        <tr>
            <td>Следующий звонк</td>
            <td>{{ $obj->present()->next_contact_at }}</td>
        </tr>
        <tr>
            <td>Добавлен</td>
            <td>{{ $obj->present()->created_at }}</td>
        </tr>
        <tr>
            <td>Обновлен</td>
            <td>{{ $obj->present()->updated_at }}</td>
        </tr>
        <tr>
            <td>Расчеты</td>
            <td>{{ $obj->calculations()->count() }}</td>
        </tr>
        <tr>
            <td>Заказы</td>
            <td>{{ $obj->orders()->count() }}</td>
        </tr>
        <tr>
            <td>Кто добавил</td>
            <td>{{ link_to_action('ManagersController@show', $obj->manager->present()->fullName, $obj->manager->id) }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Заметка</td>
            <td>{{{ $obj->description }}}</td>
        </tr>
        <tr>
            <td>Добавлен в рассылку</td>
            <td>{{ $obj->present()->subscribe }}</td>
        </tr>
        </tbody>
    </table>
</div>
@stop
