<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="charset=utf-8" />
    <title>Расчет № {{ $obj->id }}. {{ $obj->title }}</title>
    <style>
    body {
        font-size: 14px;
        font-family: Helvetica, Arial, sans-serif;
        margin: 0;
        padding: 0;
        width: 600px;
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
    .header {
      height: 104px;
      text-align: center;
      vertical-align: middle;
      margin-bottom: 10px;
    }
    .data > table {
      float: right;
    }
    .data > table td:first-child {
      padding-right: 15px;
      text-align: right;
      width: 120px;
    }
    .data > table td:nth-child(2) {
      width: 210px;
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
      text-align: center;
      width: 7%;
    }
    .part > table td:last-child {
        text-align: center;
        width: 13%;
    }
    .part > table td:nth-child(2), .part > table th:nth-child(2) {
        width: 80%;
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
    .costs > table {
      float: right;
    }
    .total > table {
      float: right;
    }
    .manager > table {
      float: right;
    }
    .costs {
        margin-bottom: 20px;
    }
    .costs td:first-child, .total td:first-child {
      text-align: right;
      width: 300px;
    }
    .costs td, .total td, .manager td {
      padding: 7px 5px;
    }
    .total td {
      height: 50px;
      font-size: 18px;
      font-weight: 700
    }
    .costs td:nth-child(2), .total td:nth-child(2) {
        text-align: center;
        width: 160px;
    }
    .manager td:first-child {
      width: 200px;
      text-align: center;
    }
    .manager {
        margin: 20px 0;
    }
    </style>
    <style media="print">
    .button {
        display: none;
    }
    </style>
    <script>
        function getPDF() {
            alert('OK');
        }
    </script>
</head>
<body>
<div class="header">
    <img src="/static/img/logo.png" alt="Гид-Групп" />
</div>

<div class="container">
    <h3>Расчет стоимости мебели</h3>
    <h1>{{ $obj->title }}</h1>
</div>

<div class="data">
    <table>
        <tr>
            <td>№ расчета</td>
            <td>{{ $obj->id }}</td>
        </tr>
        <tr>
            <td>Заказчик</td>
            <td>
                {{ $obj->client->present()->fullName() }}<br/>
                {{ $obj->client->email }}, {{ $obj->client->phone }}
            </td>
        </tr>
        <tr>
            <td>Дата расчета</td>
            <td>{{ $obj->date }}</td>
        </tr>
    </table>
</div>

<div class="part">
    <h2>В комплект входят следующие предметы:</h2>
    <table>
        <thead>
            <tr>
                <th>№</th>
                <th style="text-align: left">Наименование</th>
                <th>Кол-во</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subjects as $s)
            <tr>
                <td>{{ $s->i }}</td>
                <td>{{ $s->title }}</td>
                <td>{{ $s->num }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="costs">
    <table>
        <tr>
            <td>"Общая стоимость<br/>
                Фасадной части комплекта мебели"</td>
            <td>{{ price($costs->facade) }}</td>
        </tr>
        <tr>
            <td>"Общая стоимость<br/>
                Каркасной части и внутренних элементов"</td>
            <td>{{ price($costs->skeleton) }}</td>
        </tr>
        <tr>
            <td>Общая стоимость Фурнитуры</td>
            <td>{{ price($costs->furniture) }}</td>
        </tr>
        <tr>
            <td>"Общая стоимость работ<br/>
                По конструированию и сборке"</td>
            <td>{{ price($costs->construct_assembly) }}</td>
        </tr>
        <tr>
            <td>Стоимость доставки</td>
            <td>{{ price($obj->delivery) }}</td>
        </tr>
        <tr>
            <td>Стоимость установки</td>
            <td>{{ price($obj->install) }}</td>
        </tr>
    </table>
</div>

<div class="total">
    <table>
        <tr>
            <td>Итоговая стоимость</td>
            <td>{{ price($costs->total) }}</td>
        </tr>
    </table>
</div>

<div class="manager">
    <table>
        <tr>
            <td>Ваш менеджер</td>
            <td>
                {{ $obj->manager->present()->fullName() }}<br/>
                {{ $obj->manager->email }}<br/>
                {{ $obj->manager->phone }}
            </td>
        </tr>
    </table>
</div>

<div style="text-align:center" class="button">
    <button onclick="window.print()">Распечатать</button>
</div>
</body>
</html>
