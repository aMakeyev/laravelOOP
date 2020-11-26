<div ng-hide="true">[[ords = {{json_encode($ords)}}]]</div>

<div ng-repeat="order in calc.model.orders">
    <div class="[[in_array(order.id,ords) ? 'order ' + order.subject_id : 'order' + order.subject_id]]">
        <div ng-hide="[[!in_array(order.id,ords)]]">
            <h3 class="title">
            <span>Предмет: <span ng-bind="::order.subject.title"></span> (<span ng-bind="::order.subject.num"></span> шт.)</span>
            <span class="pull-right price">Общая стоимость предмета(-ов) из Расчета: <b>[[ ::order.subject.cost_total * order.subject.num | roundPrice ]] руб.</b></span>
            </h3>
            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Конструктор</label>
                            <div class="col-sm-8">
                                <input ng-model="order.constructor_name" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Дизайнер</label>
                            <div class="col-sm-8">
                                <input ng-model="order.designer_name" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Установщик</label>
                            <div class="col-sm-8">
                                <input ng-model="order.installer_name" type="text" class="form-control">
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <div class="table-title"><span>Оплаты конструктору</span></div>
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                        <tr>
                            <th>Дата</th> <th>Сумма</th> <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="out in order[calc.CONSTRUCTOR]">
                            <td ng-bind="::out.date"></td>
                            <td ng-bind="::out.value | roundPrice"></td>
                            <td><button type="button" class="btn btn-link btn-xs" ng-click="calc.removeOutlay(order, out, calc.CONSTRUCTOR)"><span class="glyphicon glyphicon-remove"></span></button></td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-sm-6">
                            <input type="text" class="form-control datepicker input-sm" placeholder="Дата" ng-model="calc.outlay[calc.CONSTRUCTOR][order.id].date"/>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control text-center input-sm" placeholder="Сумма" ng-model="calc.outlay[calc.CONSTRUCTOR][order.id].value"/>
                        </div>
                        <div class="col-sm-3">
                            <button ng-click="calc.addOutlay(order, calc.CONSTRUCTOR)" class="btn btn-primary btn-sm">Добавить</button>
                        </div>
                    </div>
                </div>
            </div>
        <hr/>
        </div>
        <div class="row">
            <div class="contractors col-xs-12">
                <button ng-show="[[in_array(order.id,ords)]]" type="button" class="btn btn-success btn-md pull-left" ng-click="calc.addOrder(order)"><span class="glyphicon glyphicon-plus"></span> Добавить подрядчика
                </button>
                <button ng-show="[[!in_array(order.id,ords)]]" type="button" class="btn btn-danger pull-left" ng-click="calc.removeOrder(order)">Удалить подрядчика
                </button>

            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Подрядчик</label>
                        <div class="col-sm-8">
                            <input ng-model="order.contractor" type="text" class="form-control" ui-select2="calc.select2.contractor" placeholder="Введите ФИО или название подрядчика" ng-required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label"></label>
                        <div class="col-sm-8">
                            <input ng-value="order.contractor.last_name + ' ' + order.contractor.first_name" type="text" class="form-control input-sm" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label"></label>
                        <div class="col-sm-8">
                            <input ng-value="order.contractor.email" type="text" class="form-control input-sm" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label"></label>
                        <div class="col-sm-8">
                            <input ng-value="order.contractor.phone" type="text" class="form-control input-sm" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Статус</label>
                        <div class="col-sm-8">
                            {{ Form::select('status', Config::get('calc::order/statuses'), '', ['class' => 'form-control', 'ui-select2' => 'calc.select2.status', 'ng-model' => 'order.status']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Краткое описание</label>
                        <div class="col-sm-8">
                            <textarea rows="4" class="form-control" ng-model="order.description"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Файлы</label>
                        <div class="col-sm-8">
                            <div class="form-control" style="height:auto;min-height:34px;margin-bottom:5px">
                                <ul class="list-unstyled" style="margin-bottom:0">
                                    <li ng-repeat="file in order.files">
                                        <a target="_blank" href="[[ ::file.src ]]">[[ ::file.name ]]</a>
                                        <button type="button" class="btn btn-link btn-xs" ng-click="calc.removeFile(order, file)"><span class="glyphicon glyphicon-remove"></span></button>
                                    </li>
                                </ul>
                            </div>

                            <input type="file" nv-file-select uploader="calc.uploader" options="{order:order}" multiple />
                            <ul class="list-unstyled">
                                <li ng-repeat="item in calc.uploader.queue">[[ ::item.file.name ]]
                                    <button type="button" class="btn btn-link btn-xs" ng-click="item.remove()"><span class="glyphicon glyphicon-remove"></span></button>
                                </li>
                            </ul>
                            <div ng-show="calc.uploader.queue.length">
                                <div class="progress" style="">
                                    <div class="progress-bar" role="progressbar" ng-style="{ 'width': calc.uploader.progress + '%' }"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label class="col-sm-4">Последний звонок</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control datepicker" ng-model="order.called_at">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4">Следующий звонок</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control datepicker" ng-model="order.next_call_at">
                        </div>
                    </div>
                    <hr/>
                    <div class="table-title table-responsive"><span>Оплаты подрядчику</span></div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-condensed">
                            <thead>
                            <tr>
                                <th>Дата</th> <th>Сумма</th> <th>Статус</th> <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="out in order[calc.CONTRACTOR]">
                                <td ng-bind="::out.date"></td>
                                <td ng-bind="::out.value | roundPrice"></td>
                                <td ng-bind="::out.status"></td>
                                <td><button type="button" class="btn btn-link btn-xs" ng-click="calc.removeOutlay(order, out, calc.CONTRACTOR)"><span class="glyphicon glyphicon-remove"></span></button></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <input type="text" class="form-control datepicker input-sm" placeholder="Дата" ng-model="calc.outlay[calc.CONTRACTOR][order.id].date"/>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control text-center input-sm" placeholder="Сумма" ng-model="calc.outlay[calc.CONTRACTOR][order.id].value"/>
                        </div>
                        <div class="col-sm-5">
                            {{ Form::select('status', Config::get('calc::outlay/statuses'), '', ['class' => 'form-control', 'ui-select2' => 'calc.select2.status', 'ng-model' => 'calc.outlay[calc.CONTRACTOR][order.id].status']) }}
                        </div>
                        <div class="col-sm-3">
                            <button ng-click="calc.addOutlay(order, calc.CONTRACTOR)" class="btn btn-primary btn-sm">Добавить</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                <button ng-click="calc.saveOrder(order)" class="btn btn-success btn-lg">Сохранить</button>
            </div>
        </div>
        <hr/>
    </div>
</div>


