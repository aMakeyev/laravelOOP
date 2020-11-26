Напоминаем про список рекомендаций к замеру!

Дизайнеру проекта нужно не забыть составить список рекомендаций к замеру по заказу {{ $calculation->id }}

@foreach ($calculation->orders as $order)
    {{ $order->designer_name }}
@endforeach

С уважением, администрация сайта <a href="{{ Config::get('app.url') }}">{{ Config::get('app.site_name') }}</a>
