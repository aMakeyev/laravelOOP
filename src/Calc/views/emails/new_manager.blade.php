Для Вас была создана учетная запись на сайте <a href="{{ Config::get('app.url') }}">{{ Config::get('app.site_name') }}</a>

<div>
Ваш логин: {{ $user->username }}<br/>
Ваш пароль: {{ $user->new_password }}
</div>

С уважением, администрация сайта <a href="{{ Config::get('app.url') }}">{{ Config::get('app.site_name') }}</a>
