$(document).ready(function () {
  var grid = $('#contractors');
  var queryData = {};

  App.contractor = new Services.GridResource('/api/contractors', {
    grid: grid
  }, {
    remove: {
      title: 'Подтверждение удаления',
      message: 'Удалить подрядчика навсегда?'
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
    field: 'title',
    title: 'Название',
    width: 100,
    sortable: true,
    align: 'center'
  }, {
    field: 'last_name',
    title: 'Фамилия',
    width: 100,
    sortable: true,
    align: 'center'
  }, {
    field: 'first_name',
    title: 'Имя',
    width: 70,
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
    width: 80,
    sortable: false,
    align: 'center'
  }, {
    field: 'action',
    title: '',
    width: 50,
    align: 'center',
    formatter: function (value, row, index) {
      var btns = '<div class="datagrid-actions">';
      btns += '<button onclick="App.contractor.edit(' + row.id + ')" type="button" class="btn btn-link btn-xs"><span class="glyphicon glyphicon-pencil"></span></button>';
      btns += '<button onclick="App.contractor.remove(' + row.id + ')" type="button" class="btn btn-link btn-xs"><span class="glyphicon glyphicon-trash"></span></button>';
      btns += '<a href="/contractors/' + row.id + '" class="btn btn-link btn-xs"><span class="glyphicon glyphicon-eye-open"></span></a>';
      btns += '</div>';
      return btns
    }
  }];

  grid.datagrid({
    url: '/api/contractors',
    method: 'get',
    idField: 'id',
    columns: [сolumns],
    loadMsg: 'Подождите, идёт загрузка подрядчиков...',
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
    rowStyler: function (index, row) {
      // Статус - Новый
      if (row.status == 2) {
        return 'color:grey'
      }
      // Статус - Постоянный
      if (row.status == 1) {
        return 'color:green'
      }
    }
  });

  $('#status').combobox({
    valueField: 'id',
    textField: 'title',
    url: '/api/contractors-statuses/all',
    method: 'get',
    panelHeight: 'auto',
    onSelect: function (row) {
      queryData.status = row.id;
      grid.datagrid('load', queryData);
    }
  });

  $('#search').searchbox({
    prompt: 'Поиск по названию, email, телефону...',
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
});
