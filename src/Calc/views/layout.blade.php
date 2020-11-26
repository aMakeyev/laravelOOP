<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($title) ? $title : trans('calc::titles.title') }}</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="/static/css/bootstrap.css">
    @if (Auth::check())
    <link rel="stylesheet" href="/static/vendor/easyui/theme/easyui.css">
    <link rel="stylesheet" href="/static/vendor/easyui/icon.css">
    <link rel="stylesheet" href="/static/vendor/pnotify/pnotify.min.css">
    <link rel="stylesheet" href="/static/vendor/datepicker/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="/static/vendor/select2/select2.css">
    <link rel="stylesheet" href="/static/vendor/select2/select2-bootstrap.css">
    @endif
    <link rel="stylesheet" href="/static/css/styles.css">
    <link rel="stylesheet" href="/static/css/extras.css">
    @yield('styles')

    @if (Auth::check())
    <!--script type="text/javascript" src="/static/js/jquery-1.10.2.min.js"></script-->
        <script
                src="/static/js/jquery-3.2.1.min.js"
                {{--src="https://code.jquery.com/jquery-3.2.1.min.js"--}}
                integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
                crossorigin="anonymous"></script>
    <script src="/static/js/bootstrap.min.js"></script>
    <script src="/static/vendor/easyui/jquery.easyui.min.js"></script>
    <script src="/static/vendor/easyui/easyui-lang-ru.js"></script>
    <script src="/static/vendor/pnotify/pnotify.min.js"></script>
    <script src="/static/vendor/datepicker/bootstrap-datetimepicker.js"></script>
    <script src="/static/vendor/datepicker/bootstrap-datetimepicker.ru.js"></script>
    <script src="/static/js/nporgress.js"></script>
    <script src="/static/js/scripts.js"></script>
    <script src="/static/js/extras.js"></script>
    @endif
    @yield('scripts')
</head>
<body>
@if (Auth::check() && Auth::user()->isShatersManager())
    @include('calc::shaters_menu')
@elseif (Auth::check() && Auth::user()->isNotShatersManager())
    @include('calc::menu')
@endif

<div class="container">
    @if(Session::has('status'))
        <div class="alert alert-info" role="alert">{{ Session::get('status') }}</div>
    @endif
@yield('content')
</div>

</body>
</html>
