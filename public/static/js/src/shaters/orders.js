$(document).ready(function () {
  var grid = $('#shaters_orders'),
    queryData = {};

  App.order = new Services.GridResource('/shaters/api/orders', {
    grid: grid
  }, {
    remove: {
      title: 'Подтверждение удаления',
      message: 'Удалить заказ / подряд навсегда?'
    }
  });

  function formatPayments(items) {
    return items.map(function (value) {
      return value.date + ':<br>= ' + priceFormat(value.value) + ' р.'
    });
  }

  $(window).resize(function () {
    grid.datagrid('resize');
  });

  var columns = [{
    field: 'calculation_id',
    title: '№',
    width: 10,
    sortable: true,
    align: 'center',
    formatter: function (value, row) {
      return row.calculation.id
    },
  }, {
    field: 'calculation',
    title: 'Название',
    width: 30,
    sortable: false,
    align: 'left',
    formatter: function (value, row, index) {
      var out = '<a target="_blank" href="/shaters/orders/edit/' + row.calculation.id + '">"' + value.title + '"</a>';
      out += '<br><br>';
      out += '<b>Заказчик:</b><br>';
      out += row.calculation.client.last_name + ' ' + row.calculation.client.first_name;
      out += '<br>';
      out += row.calculation.client.email;
      out += '<br>';
      out += row.calculation.client.phone;
      out += '<br><br>';
      out += '<b>Менеджер:</b><br/>';
      out += '<a href="/managers/' + row.calculation.manager.id + '">' + row.calculation.manager.last_name + ' ' + row.calculation.manager.first_name + '</a>';
      return out;
    }
  }, {
    field: 'address',
    title: 'Адрес доставки, установки',
    width: 20,
    sortable: true,
    align: 'left',
    formatter: function (value, row) {
      return row.calculation.client.delivery_address + '<br><br>' +
        row.calculation.install_address
    }
  }, {
	  field: 'delivery_at',
	  title: 'Дата доставки',
	  width: 20,
	  sortable: true,
	  align: 'center',
	  formatter: function (value, row) {
		  return row.calculation.delivery_at
	  }
  },{
	  field: 'install_at',
	  title: 'Дата установки',
	  width: 20,
	  sortable: true,
	  align: 'center',
	  formatter: function (value, row) {
		  return row.calculation.install_at
	  }
  }, {
	  field: 'cost_final',
	  title: 'Стоимость',
	  width: 20,
	  sortable: true,
	  align: 'center',
	  formatter: function (value, row) {
		  return priceFormatZero(row.calculation.cost_final) + ' р.'
	  }
  },
      {
    field: 'client_payment',
    title: 'Оплата заказчика',
    width: 20,
    sortable: true,
    align: 'center',
    formatter: function (value, row) {
      return formatPayments(row.calculation.incomes).join('<br><br>')
    }
  }, /*{
    field: 'subject',
    title: 'Изделие',
    width: 40,
    sortable: true,
    align: 'center',
    formatter: function (value, row) {
      return '#' + value.id + '<br>' +
        '"' + value.title + '"' + '<br>' +
        value.x + ' &times; ' + value.y + ' &times; ' + value.z + '<br>' +
        value.num + ' шт.' +
        (row.calculation.install_at ? '<br><br>' + row.calculation.install_at : '')
    }
  }, {
    field: 'contractor_id',
    title: 'Подрядчик',
    width: 20,
    sortable: true,
    align: 'center',
    formatter: function (value, row, index) {
      return row.contractor
        ? '<a href="/contractors/' + value + '">' + row.contractor.title + '</a>'
        : '&mdash;';
    }
  }, {
    field: 'contractor_outlay',
    title: 'Оплата подрядчику',
    width: 20,
    sortable: true,
    align: 'center',
    formatter: function (value, row) {
      return formatPayments(value).join('<br><br>')
    }
  }, {
    field: 'constructor_outlay',
    title: 'Оплата конструктору',
    width: 20,
    sortable: true,
    align: 'center',
    formatter: function (value, row) {
      return formatPayments(value).join('<br><br>')
    }
  }, {
    field: 'cost',
    title: 'Стоимость',
    width: 20,
    sortable: true,
    align: 'center',
    formatter: priceFormat
  }, {
    field: 'called_at',
    title: 'Последний звонк',
    width: 20,
    sortable: true,
    align: 'center'
  }, {
    field: 'next_call_at',
    title: 'Следующий звонок',
    width: 20,
    sortable: true,
    align: 'center'
  },*/ {
    field: 'actions',
    title: '',
    width: 10,
    align: 'center',
    formatter: function (value, row, index) {
      var btns = '<div class="datagrid-actions">';
      btns += '<a href="/shaters/orders/edit/' + row.calculation.id + '" class="btn btn-link btn-xs"><span class="glyphicon glyphicon-pencil"></span></a>';
      // btns += '<button onclick="App.order.remove(' + row.id + ')" type="button" class="btn btn-link btn-xs"><span class="glyphicon glyphicon-trash"></span></button>';
      btns += '</div>';
      return btns
    }
  }
  ];

  grid.datagrid({
    url: '/shaters/api/orders',
    method: 'get',
    idField: 'id',
    columns: [columns],
    loadMsg: 'Подождите, идёт загрузка заказов / подрядов...',
    singleSelect: true,
    checkOnSelect: false,
    striped: true,
    editing: false,
    toolbar: '#toolbar',
    fitColumns: true,
    rownumbers: false,
    scrollbarSize: 0,
    pagination: true,
    pageSize: 20,
    pageList: [5, 10, 20, 50, 100],
    onLoadSuccess: function (data) {

      var panel = $(this).datagrid("getPanel");
      var myheaderCol = panel.find("div.datagrid-body td");
      myheaderCol.css("border-bottom","2px solid black");

      /*for (var i = 0; i < data.indexes.length; i++) {
        if (data.indexes[i].length > 1) {
          grid
            .datagrid('mergeCells', {
              index: data.indexes[i][0],
              field: 'calculation_id',
              rowspan: data.indexes[i].length
            })
            .datagrid('mergeCells', {
              index: data.indexes[i][0],
              field: 'address',
              rowspan: data.indexes[i].length
            })
            .datagrid('mergeCells', {
              index: data.indexes[i][0],
              field: 'client_payment',
              rowspan: data.indexes[i].length
            })
            .datagrid('mergeCells', {
              index: data.indexes[i][0],
              field: 'calculation',
              rowspan: data.indexes[i].length
            })*/
          /*.datagrid('mergeCells', {
           index: data.indexes[i][0],
           field: 'actions',
           rowspan: data.indexes[i].length
           })*/
        // }
      // }

      setTimeout(function () {
        grid.datagrid('resize')
      }, 300);
    }
  });

  $('#status').combobox({
    valueField: 'id',
    textField: 'title',
    url: '/api/orders-statuses/all',
    method: 'get',
    panelHeight: 'auto',
    onSelect: function (row) {
      queryData.status = row.id;
      grid.datagrid('load', queryData);
    }
  });

});

