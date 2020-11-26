$(document).ready(function () {
  var grid = $('#additional_coefficients');

  App.additional_coefficients = new Services.Coefficients('additional-coefficients', {
    grid: grid
  }, {
    reject: {
      title: 'Подтверждение сброса',
      message: 'Сбросить внесенные изменения?'
    },
    save: {
      title: 'Подтверждение сохранения',
      message: 'Сохранить все изменения?'
    }
  });

  $(window).resize(function () {
    grid.datagrid('resize');
  });

  var сolumns = [{
    field: 'title',
    title: 'Название',
    width: 20,
    sortable: false,
    align: 'center',
    editor: {
      type: 'validatebox',
      options: {required: true}
    }
  }, {
    field: 'value',
    title: 'Коэффициент',
    width: 20,
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
      btns += '<button onclick="App.additional_coefficients.edit(' + index + ')" type="button" class="btn btn-link btn-xs"><span class="glyphicon glyphicon-pencil"></span></button>';
      btns += '<button onclick="App.additional_coefficients.remove(' + index + ')" type="button" class="btn btn-link btn-xs"><span class="glyphicon glyphicon-trash"></span></button>';
      btns += '</div>';
      return btns
    }
  }];

  grid.edatagrid({
    url: '/api/additional-coefficients',
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
        grid.datagrid('resize')
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
