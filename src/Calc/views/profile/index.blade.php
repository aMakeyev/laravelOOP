@section('content')
<h1>@lang('calc::titles.profile')</h1>

<div class="container">
    <div class="row">
        {{ Notification::showAll() }}
        <form role="form" method="post">
            <hr class="colorgraph">
            <h4>Основные данные</h4>
            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group">
                        <input type="text" tabindex="1" placeholder="Имя" class="form-control input-lg" id="first_name" name="first_name" value="{{ Input::old('first_name', $user->first_name) }}">
                        <small class="error">{{ $errors->first('first_name') }}</small>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group">
                        <input type="text" tabindex="2" placeholder="Фамилия" class="form-control input-lg" id="last_name" name="last_name" value="{{ Input::old('last_name', $user->last_name) }}">
                        <small class="error">{{ $errors->first('last_name') }}</small>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <input type="email" tabindex="4" placeholder="Email" class="form-control input-lg" id="email" name="email" value="{{ Input::old('email', $user->email) }}">
                <small class="error">{{ $errors->first('email') }}</small>
            </div>
            <div class="form-group">
                <input type="text" tabindex="5" placeholder="Телефон" class="form-control input-lg" id="phone" name="phone" value="{{ Input::old('phone', $user->phone) }}">
                <small class="error">{{ $errors->first('phone') }}</small>
            </div>
            <hr class="colorgraph">
            <h4>Изменение пароля</h4>
            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group">
                        <input type="password" tabindex="6" placeholder="Пароль" class="form-control input-lg" id="password" name="password">
                        <small class="error">{{ $errors->first('password') }}</small>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group">
                        <input type="password" tabindex="7" placeholder="Повторить пароль" class="form-control input-lg" id="password_confirmation" name="password_confirmation">
                        <small class="error">{{ $errors->first('password_confirmation') }}</small>
                    </div>
                </div>
            </div>
            <hr class="colorgraph">
            <div class="row" style="text-align: center">
                <input type="submit" tabindex="8" class="btn btn-primary btn-lg" value="Сохранить">
            </div>
        </form>
    </div>
</div>
@stop
