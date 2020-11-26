{{ $sendmail->present()->fullBody($client) }}

С уважением, администрация сайта <a href="{{ Config::get('app.url') }}">{{ Config::get('app.site_name') }}</a>
