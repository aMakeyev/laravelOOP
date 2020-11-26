<div class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Включить навигацию</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">@lang('calc::titles.title')</a>
            @if ($user->isAdmin() || $user->isHeadManager())
                <a class="btn btn-lg btn-info mt-1" href="/shaters">@lang('calc::titles.shaters_title')</a>
            @endif
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="/profile">{{ $user->present()->fullName }} (<small>{{ $user->present()->role }}</small>)</a></li>
                <li><a href="/logout">Выход</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
{{--</div>--}}

    <div class="container">
        <div class="navbar-collapse collapse">
            <ul class="nav nav-pills navbar-left top-menu">
                @if ($user->isAdmin() || $user->isHeadManager())
                <li><a href="/managers"><span class="glyphicon glyphicon-user"></span> Менеджеры</a></li>
                @endif
                <li><a href="/calculation"><span class="glyphicon glyphicon-list-alt"></span> Расчеты</a></li>
                <li><a href="/orders"><span class="glyphicon glyphicon-briefcase"></span> Заказы / Подряды</a></li>
                <li><a href="/contractors"><span class="glyphicon glyphicon-tower"></span> Подрядчики</a></li>
                <li><a href="/clients"><span class="glyphicon glyphicon-user"></span> Заказчики</a></li>
                @if ($user->isAdmin() || $user->isHeadManager())
                <li><a href="/parts"><span class="glyphicon glyphicon-th-large"></span> Материалы и комплектующие</a></li>
                @endif
                @if ($user->isAdmin())
                <li><a href="/coefficients"><span class="glyphicon glyphicon-cog"></span> Параметры и коэффициенты</a></li>
                <li><a href="/elements"><span class="glyphicon glyphicon-list"></span> Элементы</a></li>
                <li class="d-no-mobile"><a href="/sendmails"><span class="glyphicon glyphicon-envelope"></span> Рассылки</a></li>
                <li class="d-no-mobile"><a href="/docs"><span class="glyphicon glyphicon-file"></span> Документы</a></li>
                @endif
            </ul>
        </div>
    </div>
</div>
