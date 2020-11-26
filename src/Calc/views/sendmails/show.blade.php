@section('content')
<h1>Подробная информация о рассылке</h1>
<div><a href="{{ URL::previous() }}">&larr; Назад</a></div>
<br/>
<div class="container">
    <table class="table table-hover table-info">
        <tbody>
        <tr>
            <td>Статус</td>
            <td>{{ $obj->present()->status }}</td>
        </tr>
        <tr>
            <td>Тип</td>
            <td>{{ $obj->present()->target }}</td>
        </tr>
        <tr>
            <td>Тема письма</td>
            <td>{{ $obj->present()->subject }}</td>
        </tr>
        <tr>
            <td>Текст письма</td>
            <td>{{ $obj->present()->body }}</td>
        </tr>
        <tr>
            <td>Добавлен</td>
            <td>{{ $obj->present()->created_at }}</td>
        </tr>
        <tr>
            <td>Обновлен</td>
            <td>{{ $obj->present()->updated_at }}</td>
        </tr>
        </tbody>
    </table>
</div>
@stop
