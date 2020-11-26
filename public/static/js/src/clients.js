$(document).ready(function () {
  var grid = $('#clients'),
    queryData = {};

  App.client = new Services.GridResource('/api/clients', {
    grid: grid
  }, {
    remove: {
      title: 'Подтверждение удаления',
      message: 'Удалить заказчика навсегда?'
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
    title: 'Фамилия, Имя',
    width: 50,
    sortable: true,
    align: 'center',
    formatter: function (value, row, index) {
      var out = row.last_name + ' ' + row.first_name;
      out += '<br>';
      out += '<a href="mailto:' + row.email + '">' + row.email + '</a>';
      out += '<br>';
      out += row.phone;
      return out;
    }
  }, {
    field: 'next_contact_at',
    title: 'Звонки',
    width: 35,
    sortable: true,
    align: 'center',
    formatter: function (value, row) {
      var out = row.last_contact_at ? 'Последний звонок:<br>' + row.last_contact_at : '';
      out += '<br>';
      out += value ? 'Следующий звонок:<br>' + value : '';

      return out;
    }
  }, {
    field: 'created_at',
    title: 'Добавлен',
    width: 30,
    sortable: true,
    align: 'center',
    formatter: function (value, row) {
      return row.date
    }
  }, {
    field: 'type',
    title: 'Тип',
    width: 30,
    sortable: true,
    align: 'center',
    formatter: function (value, row) {
      return row.type_text
    }
  }, {
    field: 'description',
    title: 'Описание',
    width: 60,
    sortable: false,
    align: 'left',
    formatter: function (value, row) {
      return row.description ? row.description : '&mdash;';
    }
  }, {
    field: 'users.last_name',
    title: 'Менеджер',
    width: 35,
    sortable: true,
    align: 'center',
    formatter: function (value, row) {
      return row.manager && row.manager.id
        ? '<a href="/managers/' + row.manager.id + '">' + row.manager.last_name + ' ' + row.manager.first_name + '</a>'
        : '&mdash;';
    }
  }, {
    field: 'action',
    title: '',
    width: 30,
    align: 'center',
    formatter: function (value, row, index) {
      var btns = '<div class="datagrid-actions">';
      btns += '<button onclick="App.client.edit(' + row.id + ')" type="button" class="btn btn-link btn-xs"><span class="glyphicon glyphicon-pencil"></span></button>';
      btns += '<button onclick="App.client.remove(' + row.id + ')" type="button" class="btn btn-link btn-xs"><span class="glyphicon glyphicon-trash"></span></button>';
      btns += '<a href="/clients/' + row.id + '" class="btn btn-link btn-xs"><span class="glyphicon glyphicon-eye-open"></span></a>';
      btns += '</div>';
      return btns
    }
  }];

  grid.datagrid({
    url: '/api/clients',
    method: 'get',
    idField: 'id',
    columns: [сolumns],
    loadMsg: 'Подождите, идёт загрузка заказчиков...',
    singleSelect: true,
    striped: false,
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
    rowStyler: function (index, row) {
      // Статус - Новый
      if (row.status == 1) {
        return 'color:green'
      }
      // Статус - Постоянный
      else if (row.status == 1) {
        return 'color:gray'
      }
      // Статус - VIP
      else if (row.status == 3) {
        return 'background-color:bisque'
      }
    },
    onDblClickRow: function (index, field) {
      return App.client.edit(field.id)
    }
  });

  $('#status').combobox({
    valueField: 'id',
    textField: 'title',
    url: '/api/clients-statuses/all',
    method: 'get',
    panelHeight: 'auto',
    onSelect: function (row) {
      queryData.status = row.id;
      grid.datagrid('load', queryData);
    }
  });

  $('#type').combobox({
    valueField: 'id',
    textField: 'title',
    url: '/api/clients-types/all',
    method: 'get',
    panelHeight: 'auto',
    onSelect: function (row) {
      queryData.type = row.id;
      grid.datagrid('load', queryData);
    }
  });

  $('#search').searchbox({
    prompt: 'Поиск по ФИО / email...',
    searcher: function (value) {
      if (value.length === 0) {
        delete queryData.term;
      } else if (value.length < 3) {
        return false;
      } else {
        queryData.term = value;
      }
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
});
