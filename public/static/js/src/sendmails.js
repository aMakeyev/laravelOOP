$(document).ready(function () {
  var grid = $('#sendmails'),
    queryData = {};

  App.sendmail = new Services.GridResource('/api/sendmails', {
    grid: grid
  }, {
    remove: {
      title: 'Подтверждение удаления',
      message: 'Удалить рассылку?'
    }
  });

    App.removeFile = function (id) {
        $.ajax({
            url: '/api/sendmails/file/' + id,
            method: 'delete',
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            },
            success: function (data) {
                $('.modal').modal('hide');
            }
        });
    };

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
    field: 'created_at',
    title: 'Добавлен',
    width: 30,
    sortable: true,
    align: 'center',
    formatter: function (value, row) {
      return row.date
    }
  }, {
      field: 'subject',
      title: 'Тема письма',
      width: 30,
      sortable: true,
      align: 'center',
      formatter: function (value, row) {
          return row.subject
      }
  }, {
      field: 'body',
      title: 'Текст письма',
      width: 30,
      sortable: true,
      align: 'center',
      formatter: function (value, row) {
          return row.body
      }
  }, {
    field: 'target',
    title: 'Кому',
    width: 30,
    sortable: true,
    align: 'center',
    formatter: function (value, row) {
      return row.target_text
    }
  }, {
      field: 'status',
      title: 'Статус',
      width: 30,
      sortable: true,
      align: 'center',
      formatter: function (value, row) {
          return row.status_text
      }
  }, {
    field: 'action',
    title: '',
    width: 30,
    align: 'center',
    formatter: function (value, row, index) {
      var btns = '<div class="datagrid-actions">';
      if(row.status == 1) {
          btns += '<button onclick="App.sendmail.edit(' + row.id + ')" type="button" class="btn btn-link btn-xs"><span class="glyphicon glyphicon-pencil"></span></button>';
          btns += '<button onclick="App.sendmail.remove(' + row.id + ')" type="button" class="btn btn-link btn-xs"><span class="glyphicon glyphicon-trash"></span></button>';
      }
      btns += '<a href="/sendmails/' + row.id + '" class="btn btn-link btn-xs"><span class="glyphicon glyphicon-eye-open"></span></a>';
      btns += '</div>';
      return btns
    }
  }];

  grid.datagrid({
    url: '/api/sendmails',
    method: 'get',
    idField: 'id',
    columns: [сolumns],
    loadMsg: 'Подождите, идёт загрузка рассылок...',
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
    onDblClickRow: function (index, field) {
      return App.sendmail.edit(field.id);
    }
  });

  $('#status').combobox({
    valueField: 'id',
    textField: 'title',
    url: '/api/sendmails-statuses/all',
    method: 'get',
    panelHeight: 'auto',
    onSelect: function (row) {
      queryData.status = row.id;
      grid.datagrid('load', queryData);
    }
  });

  $('#target').combobox({
    valueField: 'id',
    textField: 'title',
    url: '/api/clients-types/all',
    method: 'get',
    panelHeight: 'auto',
    onSelect: function (row) {
      queryData.target = row.id;
      grid.datagrid('load', queryData);
    }
  });
});
