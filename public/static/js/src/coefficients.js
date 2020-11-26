$(document).ready(function () {
  var coefficients = $('#coefficients');

  App.coefficients = new Services.Coefficients('coefficients', {
    grid: coefficients
  }, {
    reject: {
      title: 'Сброс изменений',
      message: 'Сбросить изменения?'
    },
    save: {
      title: 'Сохранение изменений',
      message: 'Сохранить сделанные изменения?'
    }
  });

  $(window).resize(function () {
    coefficients.datagrid('resize');
  });

  var сolumns = [{
    field: 'range_start',
    title: 'Элементов, от',
    width: 20,
    sortable: false,
    align: 'center',
    editor: {
      type: 'numberbox',
      options: {precision: 0, required: true}
    }
  }, {
    field: 'range_end',
    title: 'Элементов, до',
    width: 20,
    sortable: false,
    align: 'center',
    editor: {
      type: 'numberbox',
      options: {precision: 0, required: true}
    }
  }, {
    field: 'value',
    title: 'Коэффициент, %',
    width: 25,
    sortable: false,
    align: 'center',
    editor: {
      type: 'numberbox',
      options: {precision: 1, required: true}
    }
  }, {
    field: 'action',
    title: '',
    width: 10,
    sortable: false,
    align: 'center',
    formatter: function (value, row, index) {
      var btns = '<div class="datagrid-actions">';
      btns += '<button onclick="App.coefficients.edit(' + index + ')" type="button" class="btn btn-link btn-xs"><span class="glyphicon glyphicon-pencil"></span></button>';
      btns += '<button onclick="App.coefficients.remove(' + index + ')" type="button" class="btn btn-link btn-xs"><span class="glyphicon glyphicon-trash"></span></button>';
      btns += '</div>';
      return btns
    }
  }];

  coefficients.edatagrid({
    url: '/api/coefficients',
    method: 'get',
    idField: 'id',
    columns: [сolumns],
    loadMsg: '',
    singleSelect: true,
    striped: false,
    editing: true,
    fitColumns: true,
    rownumbers: false,
    scrollbarSize: 0,
    pagination: false,
    onLoadSuccess: function () {
      setTimeout(function () {
        coefficients.datagrid('resize')
      }, 200);
    },
    destroyMsg: {
      confirm: {
        title: 'Удаление коэффициента',
        msg: 'Удалить коэффициент?'
      }
    }
  });
});
