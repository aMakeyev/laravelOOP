$(document).ready(function () {
  var grid = $('#parts');
  var queryData = {};

  App.part = new Services.GridResource('/api/parts', {
    grid: grid
  }, {
    remove: {
      title: 'Подтверждение удаления',
      message: 'Удалить материал / комплектующее навсегда?'
    },
    duplicate: {
      title: 'Подтверждение копирования',
      message: 'Сделать копию выбранного материала / комплектующего?'
    }
  });

  $(window).resize(function () {
    grid.datagrid('resize');
  });

  var сolumns = [{
    field: 'id',
    title: '№',
    width: 10,
    sortable: true,
    align: 'center'
  }, {
    field: 'article',
    title: 'Арт',
    width: 20,
    sortable: true,
    align: 'center'
  }, {
    field: 'title',
    title: 'Наименование',
    width: 140,
    sortable: true,
    align: 'left'
  }, {
    field: 'unit',
    title: 'Ед. изм.',
    width: 20,
    sortable: true,
    align: 'center'
  }, {
    field: 'unit_price',
    title: 'Цена закупки',
    width: 40,
    sortable: true,
    align: 'center',
    formatter: priceFormat
  }, {
    field: 'action',
    title: '',
    width: 20,
    align: 'center',
    formatter: function (value, row, index) {
      var btns = '<div class="datagrid-actions">';
      btns += '<button onclick="App.part.edit(' + row.id + ')" type="button" class="btn btn-link btn-xs"><span class="glyphicon glyphicon-pencil"></span></button>';
      btns += '<button onclick="App.part.remove(' + row.id + ')" type="button" class="btn btn-link btn-xs"><span class="glyphicon glyphicon-trash"></span></button>';
      btns += '<button onclick="App.part.duplicate(' + row.id + ')" type="button" class="btn btn-link btn-xs"><span class="glyphicon glyphicon-floppy-disk"></span></button>';
      btns += '</div>';
      return btns
    }
  }];

  grid.datagrid({
    url: '/api/parts',
    method: 'get',
    idField: 'id',
    columns: [сolumns],
    loadMsg: 'Подождите, идёт загрузка заказов...',
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
      return App.part.edit(field.id)
    }
  });

  $('#group').combobox({
    valueField: 'id',
    textField: 'title',
    url: '/api/groups-parts',
    method: 'get',
    panelHeight: 'auto',
    onSelect: function (row) {
      queryData.group = row.id;
      grid.datagrid('load', queryData);
    }
  });

  $('#search').searchbox({
    prompt: 'Поиск по наименованию...',
    searcher: function (value) {
      if (value.length === 0) {
        delete queryData.term;
      } else if (value.length < 2) {
        return false;
      } else {
        queryData.term = value;
      }
      grid.datagrid('load', queryData);
    }
  });
});
