<div class="navbar navbar-default navbar-static-top" role="navigation" style="background-color: bisque">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Включить навигацию</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/shaters">@lang('calc::titles.title_shaters')</a>
            @if ($user->isAdmin() || $user->isHeadManager())
                    <a class="btn btn-lg btn-info mt-1" href="/">Мебель</a>
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
                <li><a href="/shaters/calculation"><span class="glyphicon glyphicon-list-alt"></span> Расчеты</a></li>
                <li><a href="/shaters/orders"><span class="glyphicon glyphicon-briefcase"></span> Заказы / Подряды</a></li>
                {{--<li><a href="/contractors"><span class="glyphicon glyphicon-tower"></span> Подрядчики</a></li>--}}
                <li><a href="/shaters/clients"><span class="glyphicon glyphicon-user"></span> Заказчики</a></li>
                @if ($user->isAdmin() || $user->isHeadManager())
                <li><a href="/shaters/parts"><span class="glyphicon glyphicon-th-large"></span> Материалы и комплектующие</a></li>
                @endif
                @if ($user->isAdmin() || Auth::user()->id == 5)
                {{--<li><a href="/shaters/coefficients"><span class="glyphicon glyphicon-cog"></span> Параметры и коэффициенты</a></li>--}}
                <li><a href="/shaters/elements"><span class="glyphicon glyphicon-list"></span> Элементы</a></li>
                {{--<li><a href="/docs"><span class="glyphicon glyphicon-file"></span> Документы</a></li>--}}
                @endif
            </ul>
        </div>
    </div>
</div>
