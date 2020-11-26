@section('content')
<h1 style="text-align: center">@lang('calc::titles.title')</h1>
{{ Notification::showAll() }}
<form class="form-signin" role="form" method="post">
    <h2 class="form-signin-heading">Вход в систему</h2>
    <input type="text" name="login" class="form-control" placeholder="Email или Имя пользователя" required autofocus value="{{ Input::old('login') }}">
    <input type="password" name="password" class="form-control" placeholder="Пароль" required>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Войти</button>
</form>
@stop
