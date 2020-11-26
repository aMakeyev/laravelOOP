@section('content')
<h1>
    <span class="glyphicon glyphicon-cog"></span> @lang('calc::titles.coefficients')
</h1>

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <h2>Ставки конструкторов</h2>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-7"><table id="constructors_rates"></table></div>
                        <div class="col-md-5">
                            <div class="well buttons-group" style="max-width: 400px; margin: 0 auto 10px;">
                                <button type="button" class="btn btn-success btn-block" onclick="App.constructors_rates.save()">Сохранить</button>
                                <button type="button" class="btn btn-primary btn-block" onclick="App.constructors_rates.add()">Добавить</button>
                                <hr/>
                                <button type="button" class="btn btn-danger btn-block" onclick="App.constructors_rates.reject()">Сбросить изменения</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <h2>Цена сборки</h2>

                {{ HTML::variable('base_price') }}

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-7"><table id="coefficients"></table></div>
                        <div class="col-md-5">
                            <div class="well buttons-group" style="max-width: 400px; margin: 0 auto 10px;">
                                <button type="button" class="btn btn-success btn-block" onclick="App.coefficients.save()">Сохранить</button>
                                <button type="button" class="btn btn-primary btn-block" onclick="App.coefficients.add()">Добавить</button>
                                <hr/>
                                <button type="button" class="btn btn-danger btn-block" onclick="App.coefficients.reject()">Сбросить изменения</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <h2>Дополнительные коэффициенты</h2>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-7"><table id="additional_coefficients"></table></div>
                        <div class="col-md-5">
                            <div class="well buttons-group" style="max-width: 400px; margin: 0 auto 10px;">
                                <button type="button" class="btn btn-success btn-block" onclick="App.additional_coefficients.save()">Сохранить</button>
                                <button type="button" class="btn btn-primary btn-block" onclick="App.additional_coefficients.add()">Добавить</button>
                                <hr/>
                                <button type="button" class="btn btn-danger btn-block" onclick="App.additional_coefficients.reject()">Сбросить изменения</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="alert alert-info">
                Для редактирование записи нажмите на карандаш.<br/>
                Для удаления записи нажмите на корзину.<br/>
                После внесения изменений нажмите "Сохранить".<br/>
                Для сброса изменений нажмите "Сбросить изменения"
                <br/><br/>
                <div class="text-center"><b>Ставки конструкторов и дополнительные коэффициент связанные с расчетами удалены не будут!</b></div>
            </div>
            <div class="alert alert-success" role="alert">
                <b>Внимание!</b> После сохранения, сброс работать не будет!
            </div>
        </div>
    </div>
</div>
@stop
@section('scripts')
<script src="/static/vendor/easyui/jquery.edatagrid.js"></script>
<script src="/static/js/src/constructors_rates.js"></script>
<script src="/static/js/src/coefficients.js"></script>
<script src="/static/js/src/additional_coefficients.js"></script>
@stop
