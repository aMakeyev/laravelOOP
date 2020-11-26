@section('content')
<h1>@lang('calc::titles.order')</h1>
<div><a href="{{ URL::previous() }}">&larr; Назад</a></div>
<br/>
<div class="container">
    <div class="row">
        <div><b>№: </b> {{ $obj->id }}</div>
        <div><b>Название: </b> {{ $obj->title }}</div>
    </div>
</div>
@stop
