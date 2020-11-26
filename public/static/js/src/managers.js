$(document).ready(function () {
  var grid = $('#managers'),
    queryData = {};

  App.user = new Services.GridResource('/api/managers', {
    grid: grid
  }, {
    remove: {
      title: 'Подтверждение удаления',
      message: 'Удалить пользователя навсегда?'
    }
  });

  $(window).resize(function () {
    grid.datagrid('resize');
  });

  var сolumns = [{
    field: 'id',
    title: '№',
    width: 20,
    sortable: true,
    align: 'center'
  }, {
    field: 'last_name',
    title: 'Фамилия',
    width: 65,
    sortable: true,
    align: 'center'
  }, {
    field: 'first_name',
    title: 'Имя',
    width: 50,
    sortable: true,
    align: 'center'
  }, {
    field: 'email',
    title: 'Email',
    width: 70,
    sortable: true,
    align: 'center'
  }, {
    field: 'phone',
    title: 'Телефон',
    width: 40,
    sortable: false,
    align: 'center',
    formatter: valueFormat
  }, {
    field: 'rate',
    title: 'Ставка',
    width: 20,
    align: 'center',
    sortable: true,
    formatter: priceFormat
  }, {
    field: 'created_at',
    title: 'Добавлен',
    width: 40,
    align: 'center',
    sortable: true,
    formatter: function (value, row) {
      return row.date
    }
  }, {
    field: 'last_activity',
    title: 'Активность',
    width: 50,
    align: 'center',
    sortable: true,
    formatter: valueFormat
  }, {
    field: 'calculations_count',
    title: 'Кол-во расчетов',
    width: 25,
    sortable: false,
    align: 'center',
    formatter: function (value) {
      return valueFormat(value, '<b>:text:</b>')
    }
  }, {
    field: 'action',
    title: '',
    width: 30,
    align: 'center',
    formatter: function (value, row) {
      var btns = '<div class="datagrid-actions">';
      btns += '<button onclick="App.user.edit(' + row.id + ')" type="button" class="btn btn-link btn-xs"><span class="glyphicon glyphicon-pencil"></span></button>';
      btns += '<button onclick="App.user.remove(' + row.id + ')" type="button" class="btn btn-link btn-xs"><span class="glyphicon glyphicon-trash"></span></button>';
      btns += '<a href="/managers/' + row.id + '" class="btn btn-link btn-xs"><span class="glyphicon glyphicon-eye-open"></span></a>';
      btns += '</div>';
      return btns
    }
  }];

  grid.datagrid({
    url: '/api/managers',
    method: 'get',
    idField: 'id',
    columns: [сolumns],
    loadMsg: 'Подождите, идёт загрузка менеджеров...',
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
    onLoadSuccess: function () {
      setTimeout(function () {
        grid.datagrid('resize')
      }, 200);
    },
    onDblClickRow: function (index, field) {
      return App.user.edit(field.id)
    }
  });

  $('#role').combobox({
    valueField: 'id',
    textField: 'title',
    url: '/api/managers-roles/all',
    method: 'get',
    panelHeight: 'auto',
    onSelect: function (row) {
      queryData.role = row.id;
      grid.datagrid('load', queryData);
    }
  });

  $('#status').combobox({
    valueField: 'id',
    textField: 'title',
    url: '/api/managers-statuses/all',
    method: 'get',
    panelHeight: 'auto',
    onSelect: function (row) {
      queryData.status = row.id;
      grid.datagrid('load', queryData);
    }
  });

  // $('.panel.datagrid').removeClass('panel');

});
