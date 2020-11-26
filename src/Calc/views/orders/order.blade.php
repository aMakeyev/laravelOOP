<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Расчет № {{ $order->id }}. {{ $order->title }}</title>
    <style>
        body {
            font-size: 14px;
            font-family: Helvetica, Arial, sans-serif;
            /*margin: 0;*/
            margin:auto;
            padding: 0;
            width: 800px;
        }
        h1 {
            margin-top: 0;
        }
        h3 {
            margin: 0;
        }
        table {
            border-collapse: collapse;
            table-layout: fixed;
        }
        .center {
            text-align: center;
        }
        .right {
            text-align: right;
        }
        .left {
            text-align: left;
        }
        .bold {
            font-weight: bold;
        }
        .header {
            text-align: center;
            vertical-align: middle;
            margin-bottom: 20px;
            border-bottom: 2px solid;
            overflow: hidden;
            padding: 10px 0;
        }
        .contacts ul li {
            text-align: right;
        }
        .contacts ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .contacts {
            float: right;
        }
        .data > table td:first-child {
            padding-right: 15px;
            text-align: right;
        }
        .data:after, .costs:after, .total:after, .manager:after {
            clear: both;
            content: "";
            display: block;
        }
        .data td, .costs td {
            padding: 4px;
        }
        .part, .data {
            margin-top: 40px;
        }
        .part > table {
            margin: 20px 0;
            width: 100%;
            border-bottom: 1px solid;
        }
        .part > table th:first-child, .part > table td:first-child {
            width: 7%;
        }
        .part > table td:last-child {
            width: 13%;
        }
        .part > table td:nth-child(2), .part > table th:nth-child(2) {
            width: 210px;
        }
        .part > table > tbody > tr {
            border-bottom: 1px solid #ddd;
        }
        .part > table > tbody > tr:last-child {
            border-bottom: none;
        }
        .part > table td, .part > table th {
            padding: 10px 5px;
        }
        .part > table thead > tr {
            border-bottom: 2px solid;
        }
        .costs {
            margin-bottom: 20px;
        }
        .costs td, .total td, .manager td {
            padding: 7px 5px;
        }
        .total td {
            height: 50px;
            font-size: 18px;
            font-weight: 700
        }
        .manager td:nth-child(1) {
            width: 163px;
        }
        .costs td:nth-child(2), .total td:nth-child(2) {
            width: 100px;
        }
        .manager td:nth-child(2){
            width:149px;
        }
        .costs td:first-child, .total td:first-child {
            *text-align: right;
            *width: 250px;
            padding-left: 70px;
        }
        .manager {
            margin: 20px 0;
        }
        .info {
            width:90%;
            margin: 10px 5% 0;
            max-width: 90%;
            height: 100px;
            min-height: 100px;
            max-height: 200px;
            border: 1px solid #f8f8f8;
            padding: 5px;
        }
        th {
            font-size: 13px;
            text-align: center;
        }
        .price {
            font-size: 12px;
            white-space: nowrap;
        }
        /* Extra Small Devices, Phones */
        @media only screen and (max-width : 415px) {
            body{
                zoom: 44%;
            }
        }
    </style>
    <style media="print">
        .button {
            display: none;
        }
        .info {
            border-color: #f8f8f8;
        }
    </style>
    <script>function getPDF() { alert('OK') }</script>
</head>
<body>
<div class="header">
    <div style="float: left;margin-top: 20px"><img src="/static/img/logo.png" alt="Гид-Групп" /></div>
    <div class="contacts">
        <ul>
            @include('calc::orders.parts.contacts')
        </ul>
    </div>
</div>

<div class="container">
    <h3>Расчет стоимости мебели</h3>
    <h1>{{ $order->title }}</h1>
    <div class="data">
        <table>
            <tr>
                <td>№ расчета</td>
                <td class="bold">{{ $order->id }}</td>
            </tr>
            <tr>
                <td>Заказчик</td>
                @if($order->client->type != 2)
                <td>
                    {{ $order->client->present()->fullName() }}, {{ $order->client->email }}, {{ $order->client->phone }}
                </td>
                @else
                <td>
                    {{ $order->client->legal_name }}, {{ $order->client->legal_email }}, {{ $order->client->legal_phone }}
                </td>
                @endif
            </tr>
            <tr>
                <td>Дата расчета</td>
                <td>{{ $order->created_at }}</td>
            </tr>
        </table>
    </div>
</div>
<div class="part">
    <h2>В комплект входят следующие предметы:</h2>
    <table>
        <thead>
        <tr>
            <th class="center">№</th>
            <th class="left" style="width: 200px">Наименование</th>
            <th style="width:45px">Кол-во</th>
            <th style="width:45px">Фасад</th>
            <th style="width:70px">Корпус</th>
            <th>Фурнитура</th>
            <th style="width: 100px">Проектирование</th>
            <th class="right">Стоимость</th>
            @if ($order->pseudo_discount_percent)
                {{--<th class="right" style="width: 50px">Со скидкой ({{$order->pseudo_discount_percent}}%)</th>--}}
                <th class="right" style="width: 50px">Со скидкой ({{$order->getPseudoDiscountPercentTextAttribute()}})</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach($subjects as $s)
            <tr>
                <td class="center">{{ $s->i }}</td>
                <td class="left">
                    {{ $s->title }}<br/>
                    ({{ $s->x }} &times; {{ $s->y }} &times; {{ $s->z }} мм)
                </td>
                <td class="center">{{ $s->num }}</td>
                <td class="right price">{{ price($s->facade) }}</td>
                <td class="right price">{{ price($s->skeleton) }}</td>
                <td class="right price">{{ price($s->furniture) }}</td>
                <td class="right price">{{ price($s->construct_assembly) }}</td>
                <td class="right price">{{ price($s->total) }}</td>
                @if ($order->pseudo_discount_percent)
                    <td class="right price">{{ price($s->totalDiscount) }}</td>
                @endif
            </tr>
        @endforeach

        @if ($order->pseudo_discount_meter)
            <tr>
                <td class="right bold" colspan="8" style="padding-right: 148px;">Скидка за замер:</td>
                <td class="right bold price" colspan="1">{{ price($costs->pseudo_discount_meter) }}</td>
            </tr>
        @endif

        <tr>
            <td class="right"></td>
            <td class="bold">Всего предметов:</td>
            <td class="center">{{ $costs->num }}</td>
            <td class="right"></td>

            <td class="left bold" colspan="3" style="padding-left: 95px;">Общая стоимость предметов:</td>

            <td class="right bold price">{{ price($costs->total) }}</td>

            @if ($order->pseudo_discount_percent || $order->pseudo_discount_meter)
                <td class="right bold price">{{ price($costs->totalDiscount) }}</td>
            @endif
        </tr>

        </tbody>
    </table>
</div>

<table style="width:100%">
    <tr>
        <td style="vertical-align: top">
            <textarea class="info"></textarea>
        </td>
        <td style="vertical-align: top">
            <div class="costs">
                <table style="width:100%">
                    <tr>
                        <td>Стоимость доставки</td>
                        <td class="right price">{{ price($order->delivery) }}</td>
                    </tr>
                    <tr>
                        <td>Стоимость подъёма</td>
                        <td class="right price">{{ price($order->climb_price) }}</td>
                    </tr>
                    <tr>
                        <td>Стоимость установки</td>
                        <td class="right price">{{ price($order->install) }}</td>
                    </tr>
                </table>
            </div>
            <div class="total">
                <table style="width:100%">
                    <tr>
                        <td>Итоговая стоимость</td>
                        <td class="right price">{{ price($costs->totalWithInstallAndDelivery()) }}</td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>

<hr/>

<div class="manager">
    <table style="float: right">
        <tr>
            <td>Ваш менеджер</td>
            <td>
                {{ $order->manager->present()->fullName() }}<br/>
                {{ $order->manager->email }}<br/>
                {{ $order->manager->phone }}
            </td>
        </tr>
    </table>
</div>

<div style="text-align:center" class="button">
    <button onclick="window.print()">Распечатать</button>
</div>
</body>
</html>
