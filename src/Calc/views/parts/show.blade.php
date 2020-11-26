@section('content')
<h1>{{ $obj->present()->fullName }}</h1>
<div><a href="/clients">&larr; Назад</a></div>
<br/>
<div class="container">
    <div class="row">
        <div><b>№: </b> {{ $obj->id }}</div>
        <div><b>Полное имя: </b> {{ $obj->present()->fullName }}</div>
        <div><b>Email: </b> {{ $obj->email }}</div>
        <div><b>Телефон: </b> {{ $obj->phone }}</div>
        <div><b>Статус:</b> {{ $obj->present()->status }}</div>
        <div><b>Описние:</b> {{{ $obj->description }}}</div>
    </div>
</div>
@stop
