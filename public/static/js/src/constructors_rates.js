$(document).ready(function () {
  var constructors_rates = $('#constructors_rates');

  App.constructors_rates = new Services.Coefficients('constructors-rates', {
    grid: constructors_rates
  }, {
    reject: {
      title: 'Подтверждение сброса',
      message: 'Сбросить изменения?'
    },
    save: {
      title: 'Подтверждение сохранения',
      message: 'Сохранить сделанные изменения?'
    }
  });

  $(window).resize(function () {
    constructors_rates.datagrid('resize');
  });

  var сolumns = [{
    field: 'title',
    title: 'Название',
    width: 17,
    sortable: false,
    align: 'center',
    editor: {
      type: 'validatebox',
      options: {required: true}
    }
  }, {
    field: 'rate',
    title: 'Ставка за 1 элемент, руб.',
    width: 23,
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
      btns += '<button onclick="App.constructors_rates.edit(' + index + ')" type="button" class="btn btn-link btn-xs"><span class="glyphicon glyphicon-pencil"></span></button>';
      btns += '<button onclick="App.constructors_rates.remove(' + index + ')" type="button" class="btn btn-link btn-xs"><span class="glyphicon glyphicon-trash"></span></button>';
      btns += '</div>';
      return btns
    }
  }];

  constructors_rates.edatagrid({
    url: '/api/constructors-rates',
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
        constructors_rates.datagrid('resize')
      }, 200);
    },
    destroyMsg: {
      confirm: {
        title: 'Подтверждение удаления',
        msg: 'Удалить ставку конструктора?'
      }
    }
  });
});
