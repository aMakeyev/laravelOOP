


$(document).ready(function () {
  'use strict';

  function fullName(state) {
    return state.last_name + ' ' + state.first_name
  }

  var grid = $('#calculations'),
    queryData = {};

  App.calculation = new Services.GridResource('/api/calculations', {
    grid: grid
  }, {
    remove: {
      title: 'Подтверждение удаления',
      message: 'Удалить расчет навсегда?'
    }
  });

  $(window).resize(function () {
    grid.datagrid('resize');
  });

  var columns = [{
    field: 'id',
    title: '№',
    width: 10,
    sortable: true,
    align: 'center'
  }, {
    field: 'title',
    title: 'Название',
    width: 30,
    sortable: true,
    formatter: function (value, row) {
      return '<a target="_blank" href="/calculation/' + row.id + '/edit">' + value + '</a>';
    }
  }, {
    field: 'status',
    title: 'Статус',
    width: 20,
    sortable: true,
    align: 'center',
    formatter: function (value, row) {
      return row.status_text
    }
  }, {
    field: 'users.last_name',
    title: 'Менеджер',
    width: 30,
    sortable: true,
    align: 'center',
    formatter: function (value, row) {
      return '<a target="_blank" href="/managers/' + row.manager.id + '">' + fullName(row.manager) + '</a>';
    }
  }, {
    field: 'clients.last_name',
    title: 'Заказчик',
    width: 30,
    sortable: true,
    align: 'center',
    formatter: function (value, row) {
      return '<a target="_blank" href="/clients/' + row.client.id + '">' + fullName(row.client) + '</a>';
    }
  }, {
    field: 'cost_final',
    title: 'Сумма, руб.',
    width: 20,
    sortable: true,
    align: 'center',
    formatter: priceFormat
  }, {
    field: 'delivery',
    title: 'Доставка, руб.',
    width: 20,
    sortable: false,
    align: 'center',
    formatter: priceFormat
  }, {
    field: 'install',
    title: 'Установка, руб.',
    width: 20,
    sortable: false,
    align: 'center',
    formatter: priceFormat
  }, {
    field: 'created_at',
    title: 'Создан',
    width: 20,
    sortable: true,
    align: 'center'
  }, {
    field: 'action',
    title: '',
    width: 15,
    align: 'center',
    formatter: function (value, row, index) {
      var btns = '<div class="datagrid-actions">';
      btns += '<a target="_blank" href="/calculation/' + row.id + '/edit" class="btn btn-link btn-xs"><span class="glyphicon glyphicon-pencil"></span></a>';
      btns += '<button onclick="App.calculation.remove(' + row.id + ')" type="button" class="btn btn-link btn-xs"><span class="glyphicon glyphicon-trash"></span></button>';
      btns += '<a target="_blank" href="/calculation/' + row.id + '" class="btn btn-link btn-xs"><span class="glyphicon glyphicon-eye-open"></span></a>';
      btns += '</div>';
      return btns
    }
  }];

  grid.datagrid({
    url: '/api/calculations',
    method: 'get',
    idField: 'id',
    columns: [columns],
    loadMsg: 'Подождите, идёт загрузка расчетов...',
    singleSelect: true,
    striped: true,
    editing: false,
    toolbar: '#toolbar',
    fitColumns: true,
    rownumbers: false,
    scrollbarSize: 0,
    pagination: true,
    pageSize: 10,
    pageList: [5, 10, 20, 50, 100],
    autoRowHeight: true,
    onLoadSuccess: function () {
      setTimeout(function () {
        grid.datagrid('resize')
      }, 200);
    }
  });

  $('#status').combobox({
    valueField: 'id',
    textField: 'title',
    url: '/api/calculations-statuses/all',
    method: 'get',
    panelHeight: 'auto',
    onSelect: function (row) {
      queryData.status = row.id;
      grid.datagrid('load', queryData);
    }
  });

  $('#manager').combobox({
    valueField: 'id',
    textField: 'text',
    url: '/api/managers-list/all',
    method: 'get',
    panelHeight: 'auto',
    onSelect: function (row) {
      queryData.manager = row.id;
      grid.datagrid('load', queryData);
    }
  });

  $('#search').searchbox({
    prompt: 'Поиск по заказчику и № заказа...',
    searcher: function (value) {
      if (value.length === 0) {
        delete queryData.term;
      } else {
        queryData.term = value;
      }
      grid.datagrid('load', queryData);
    }
  });

});
