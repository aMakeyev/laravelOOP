@section('content')
<h1>
    <span class="glyphicon glyphicon-envelope"></span> @lang('calc::titles.sendmails')
    <button type="button" class="btn btn-success pull-right" onclick="App.sendmail.create()"><span class="glyphicon glyphicon-plus"></span> Добавить рассылку</button>
</h1>
<div id="toolbar" class="toolbar">
    Статус <input type="text" id="status"/>
    Кому <input type="text" id="target"/>
</div>
<table id="sendmails"></table>
@stop
@section('scripts')
{{ HTML::script('/static/vendor/wysihtml5/bootstrap3-wysihtml5.all.js'); }}
{{ HTML::script('/static/vendor/wysihtml5/locales/bootstrap-wysihtml5.ru-RU.js'); }}
{{ HTML::script('/static/js/src/sendmails.js'); }}
@stop
@section('styles')
{{ HTML::style('/static/vendor/wysihtml5/bootstrap3-wysihtml5.min.css'); }}
@stop