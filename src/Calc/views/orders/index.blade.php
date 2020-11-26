@section('content')
<h1><span class="glyphicon glyphicon-briefcase"></span> @lang('calc::titles.orders')</h1>

<div id="toolbar" class="toolbar">
    <span>
        Статус <input type="text" value="" name="status" id="status"/>
    </span>
</div>

<table id="orders"></table>

@stop
@section('scripts')
<script src="/static/js/src/orders.js"></script>
<script type="text/javascript">
	if( window.innerWidth <= 415 ){
		$(window).scroll(function() {
			if ($(window).scrollTop() >= 230) {
				$('.datagrid-header').addClass('fixed');
			} else {
				$('.datagrid-header').removeClass('fixed');
			}
		});
	} else {
		$(window).scroll(function() {
			if ($(window).scrollTop() >= 165) {
				$('.datagrid-header').addClass('fixed');
			} else {
				$('.datagrid-header').removeClass('fixed');
			}
		});
	}


</script>
<style>
.datagrid-row-over, .datagrid-row-selected {
    background-color: #fff;
    color: #000;
}
.datagrid-header {
    background: #fff3f3;
    z-index: 99;
}
.datagrid-header.fixed {
    position:fixed; top:0;
    background:#fff3f3;
}

/* Extra Small Devices, Phones */
@media only screen and (max-width : 415px) {
    .datagrid-cell-c1-address,
    .datagrid-cell-c1-constructor_outlay,
    .datagrid-cell-c1-called_at,
    .datagrid-cell-c1-actions,
    /*.datagrid-cell-c1-next_call_at,*/
    .datagrid-cell-c1-contractor_id {
        display:none !important;
    }

    .datagrid-cell-c1-calculation_id {width:31px !important;}
    .datagrid-cell-c1-calculation {width:72px !important;}
    .datagrid-cell-c1-client_payment {width:67px !important;}
    .datagrid-cell-c1-contractor_outlay {width:50px !important;}
    .datagrid-cell-c1-subject {width:72px !important;}
    .datagrid-cell-c1-cost {width:51px !important;}
    .datagrid-cell-c1-next_call_at {width:40px !important;}

    .datagrid-cell-c1-cost_final {
        width: 64px!important;
    }
    .datagrid-cell-c1-install_at {
        width: 60px!important;
    }
    .datagrid-cell-c1-delivery_at {
        width: 60px !important;
    }
}

</style>
@stop
