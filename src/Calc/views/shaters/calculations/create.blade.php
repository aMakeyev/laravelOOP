@section('content')
<h1>

  @if ($obj->exists && $obj->parent_id)
    @lang('calc::titles.addagree_edit')
  @elseif($obj->exists && $obj->child_id)
    @lang('calc::titles.calculation_edit')
    <a class="btn btn-primary btn-md pull-right" href="/shaters/calculation/{{$obj->child_id}}/edit">Редактировать Доп.соглашение</a>
  @elseif($obj->exists)
    @lang('calc::titles.calculation_edit')
    <a class="btn btn-primary btn-md pull-right" href="/shaters/calculation/{{$obj->id}}/addagree">Создать Доп.соглашение</a>
  @else
    @lang('calc::titles.calculation_create')
  @endif
</h1>

<div id="calculation" ng-app="Calc">
  <div ng-controller="ShatersCalcCtrl as calc">
    <form action="" autocomplete="off">
      <div style="margin: 40px 0">
        <div class="row">
          <div class="col-lg-6">
            @if ($obj->exists)
              <h2 style="margin:0">Расчет # {{ $obj->id }}</h2>
            @endif
            <div class="form-horizontal" style="margin-top: 20px">
              <div class="form-group">
                <label class="col-sm-4 control-label">Заказчик</label>
                <div class="col-sm-8">
                  <input style="width:340px;float:left" ng-disabled="!calc.editable()" ng-model="calc.model.client" type="text" class="form-control" ui-select2="calc.select2.client" placeholder="Введите ФИО клиента для поиска">
                  <button title="Добавить заказчика" style="padding:0;margin-top:3px" type="button" class="btn btn-link btn-success pull-right" onclick="App.client.create()">
                    <span class="glyphicon glyphicon-plus"></span></button>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label">Название комплекта</label>

                <div class="col-sm-8">
                  <input ng-disabled="!calc.editable()" ng-model="calc.model.title" type="text" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label">Дата расчета</label>

                <div class="col-sm-8">
                  <input ng-model="calc.model.created_at" type="text" class="form-control text-center" disabled>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label">Изменен</label>

                <div class="col-sm-8">
                  <input ng-model="calc.model.updated_at" type="text" class="form-control text-center" disabled>
                </div>
              </div>
              @if((!$obj->parent_id))
              <div class="form-group">
                <label class="col-sm-4 control-label">Дата договора</label>

                <div class="col-sm-8">
                  <input ng-model="calc.model.contract_at" type="text" class="form-control text-center datepicker">
                </div>
              </div>
              @endif
              <hr/>
              <div class="form-group">
                <label class="col-sm-4 control-label">Описание расчета</label>

                <div class="col-sm-8">
                  <textarea style="max-width:100%" rows="4" ng-model="calc.model.description" class="form-control"></textarea>
                </div>
              </div>
              <hr/>
              <div class="form-group" ng-show="calc.model.exists">
                <label class="col-sm-4 control-label">Файлы клиента</label>

                <div class="col-sm-8">
                  <div class="form-control" style="height:auto;min-height:34px;margin-bottom:5px">
                    <ul class="list-unstyled" style="margin-bottom:0">
                      <li ng-repeat="file in calc.model.files">
                        <a target="_blank" ng-href="[[::file.src]]" ng-bind="::file.name"></a>
                        <button ng-disabled="!calc.editable()" type="button" class="btn btn-link btn-xs" ng-click="calc.removeFile(file)">
                          <span class="glyphicon glyphicon-remove"></span></button>
                      </li>
                    </ul>
                  </div>

                  <input ng-disabled="!calc.editable()" type="file" nv-file-select uploader="calc.uploader" multiple/>
                  <ul class="list-unstyled">
                    <li ng-repeat="item in calc.uploader.queue">
                      <span ng-bind="::item.file.name"></span>
                      <button type="button" class="btn btn-link btn-xs" ng-click="item.remove()">
                        <span class="glyphicon glyphicon-remove"></span>
                      </button>
                    </li>
                  </ul>
                  <div ng-show="calc.uploader.queue.length">
                    <div class="progress">
                      <div class="progress-bar" role="progressbar" ng-style="{ 'width': calc.uploader.progress + '%' }"></div>
                    </div>
                  </div>
                </div>
              </div>
              @if( ! $obj->exists)
                <div class="form-group">
                  <label class="col-sm-4 control-label"></label>

                  <div class="col-sm-8">
                    <div class="alert alert-info">
                      Загрузка файлов будет доступна после сохранения расчета
                    </div>
                  </div>
                </div>
              @endif
            </div>
          </div>
          <div class="col-lg-6">
            <div class="form-horizontal" style="margin-top: 20px">
              <div class="form-group">
                <label class="col-sm-9 control-label">Количество предметов</label>

                <div class="col-sm-3">
                  <input ng-value="calc.subjectsNum()" type="text" class="form-control text-center" disabled>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-9 control-label">Стоимость изготовления</label>

                <div class="col-sm-3">
                  <input ng-value="calc.model.cost_manufacturing | roundPrice" type="text" class="form-control text-center" disabled>
                </div>
              </div>

             {{-- <div class="form-group">
                <label class="col-sm-9 control-label">Стоимость конструкторской работы</label>

                <div class="col-sm-3">
                  <input ng-value="calc.model.cost_construct | roundPrice" type="text" class="form-control text-center" disabled>
                </div>
              </div>--}}

              {{--<div class="form-group">
                <label class="col-sm-9 control-label">Стоимость сборки</label>

                <div class="col-sm-3">
                  <input ng-value="calc.model.cost_assembly | roundPrice" type="text" class="form-control text-center" disabled>
                </div>
              </div>--}}

              <div class="form-group">
                <label class="col-sm-9 control-label">Общая стоимость комплекта</label>

                <div class="col-sm-3">
                  <input ng-value="calc.model.cost_total | roundPrice" type="text" class="form-control text-center input-total" disabled>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-7 control-label">Поправочный коэффициент</label>
                <div class="col-sm-5">
                  <input ng-disabled="!calc.editable()" ui-select2="calc.select2.coef" class="form-control" ng-model="calc.model.additional_coefficient"/>
                  {{ Form::additional_coefficient(null, ['class' => 'form-control input-sm d-mobile add-coef-input', 'ng-model' => 'calc.model.additional_coefficient']) }}
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-9 control-label">Общая стоимость с учетом коэф., руб.</label>

                <div class="col-sm-3">
                  <input ng-value="calc.model.cost_total * calc.model.additional_coefficient.value | roundPrice" type="text" class="form-control text-center input-total" disabled>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-9 control-label">Общая сумма наценок, руб.</label>

                <div class="col-sm-3">
                  <input ng-value="calc.model.discounts | roundPrice" type="text" class="form-control text-center input-total" disabled>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-9 control-label">Общая стоимость с учетом коэф. и наценок, руб.</label>

                <div class="col-sm-3">
                  <input ng-value="calc.model.cost_total * calc.model.additional_coefficient.value + calc.model.discounts | roundPrice" type="text" class="form-control text-center input-total" disabled>
                  {{--запись в финальной цены--}}
                  <p class="d-none">[[calc.model.cost_final = calc.model.cost_total * calc.model.additional_coefficient.value + calc.model.discounts | roundPrice]]</p>
                </div>
              </div>

              {{--<div class="form-group">
                <label class="col-sm-9 control-label">Максимально допустимая скидка, руб.</label>

                <div class="col-sm-3">
                  <input ng-value="calc.model.discount | roundPrice" type="text" class="form-control text-center" disabled>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-9 control-label">Затраты</label>

                <div class="col-sm-3">
                  <input ng-value="calc.model.outlay | roundPrice" type="text" class="form-control text-center" disabled>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-9 control-label">Маржа</label>

                <div class="col-sm-3">
                  <input ng-value="calc.model.margin | roundPrice" type="text" class="form-control text-center" disabled>
                </div>
              </div>--}}

              <div class="form-group">
                <label class="col-sm-9 control-label">Доставка</label>

                <div class="col-sm-3">
                  <input ng-disabled="!calc.editable()" ng-model="calc.model.delivery" type="text" class="form-control text-center">
                </div>
              </div>

            <div class="form-group">
                <label class="col-sm-9 control-label">Подъём</label>

                <div class="col-sm-3">
                    <input ng-disabled="!calc.editable()" ng-model="calc.model.climb_price" type="text" class="form-control text-center">
                </div>
            </div>

              <div class="form-group">
                <label class="col-sm-9 control-label">Установка</label>

                <div class="col-sm-3">
                  <input ng-disabled="!calc.editable()" ng-model="calc.model.install" type="text" class="form-control text-center">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-9 control-label">Псевдоскидка</label>

                <div class="col-sm-3 d-no-mobile">
                  {{ Form::select('pseudo_discount_percent', Config::get('calc::calculation/discounts'), '', ['class' => 'form-control', 'ui-select2' => 'calc.select2.default', 'ng-model' => 'calc.model.pseudo_discount_percent']) }}
                </div>
                <div class="col-sm-3 d-mobile">
                  {{ Form::select('pseudo_discount_percent', Config::get('calc::calculation/discounts'), '', ['class' => 'form-control', 'ng-model' => 'calc.model.pseudo_discount_percent']) }}
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-9 control-label">Псевдоскидка за замер</label>

                <div class="col-sm-3">
                  <input ng-disabled="!calc.editable()" ng-model="calc.model.pseudo_discount_meter" type="text" class="form-control text-center">
                </div>
              </div>
            </div>
          </div>
        </div>
        <hr/>
        <div class="row">
          <div class="col-sm-9">
            <button type="button" class="btn btn-primary btn-lg" ng-click="calc.saveModel()">Сохранить</button>
            @if ($obj->exists)
              <button type="button" class="btn btn-default btn-lg" ng-click="calc.cloneModel()">Сделать копию</button>
              <a target="_blank" href="/shaters/calculation/order/{{ $obj->id }}" ng-show="calc.model.reportable" class="btn btn-success btn-lg">Сформировать
                для клиента</a>
            @endif
          </div>

          <div class="col-sm-3 d-no-mobile">
            @if(Auth::user()->id == 8 || Auth::user()->id == 26 || Auth::user()->id == 5)
            {{ Form::select('status', Config::get('calc::calculation/statuses'), '', ['class' => 'form-control', 'ui-select2' => 'calc.select2.default', 'ng-model' => 'calc.model.status']) }}
              @elseif($obj->status <= 2)
              {{ Form::select('status', Config::get('calc::calculation/statuses_manager'), '', ['class' => 'form-control', 'ui-select2' => 'calc.select2.default', 'ng-model' => 'calc.model.status']) }}
              @else{{ Form::select('status', Config::get('calc::calculation/statuses'), '', ['class' => 'form-control', 'ui-select2' => 'calc.select2.default', 'ng-model' => 'calc.model.status', 'ng-disabled' => 'true']) }}
            @endif
          </div>
          <div class="col-sm-3 d-mobile">
            @if(Auth::user()->id == 8 || Auth::user()->id == 26 || Auth::user()->id == 5)
              {{ Form::select('status', Config::get('calc::calculation/statuses'), '', ['class' => 'form-control', 'ng-model' => 'calc.model.status']) }}
            @elseif($obj->status <= 2)
              {{ Form::select('status', Config::get('calc::calculation/statuses_manager'), '', ['class' => 'form-control', 'ng-model' => 'calc.model.status']) }}
            @else{{ Form::select('status', Config::get('calc::calculation/statuses'), '', ['class' => 'form-control', 'ng-model' => 'calc.model.status', 'ng-disabled' => 'true']) }}
            @endif
          </div>
        </div>
        @if($obj->parent_id)
          <div class="row calc-docs-generate">
            <div class="col-sm-9">
              <a target="_blank" href="/shaters/calculation/addagree/{{ $obj->id }}" ng-show="calc.model.reportable" class="btn btn-info btn-lg">Генерация Доп.соглашения</a>
              <a target="_blank" href="/shaters/calculation/check/{{ $obj->id }}" ng-show="calc.model.reportable" class="btn btn-info btn-lg">Генерация счёта</a>
            </div>
          </div>
        @elseif ($obj->exists)
          <div class="row calc-docs-generate">
            <div class="col-sm-9">
                <a target="_blank" href="/shaters/calculation/contract/{{ $obj->id }}" ng-show="calc.model.reportable" class="btn btn-info btn-lg">Генерация договора</a>
                <a target="_blank" href="/shaters/calculation/spec/{{ $obj->id }}" ng-show="calc.model.reportable" class="btn btn-info btn-lg">Генерация спецификации</a>
                <a target="_blank" href="/shaters/calculation/check/{{ $obj->id }}" ng-show="calc.model.reportable" class="btn btn-info btn-lg">Генерация счёта</a>
            </div>
          </div>

          @if ($obj->status == 7)
            <div class="row calc-docs-generate">
              <div class="col-sm-12">
                <a target="_blank" href="/shaters/calculation/certificate/{{ $obj->id }}" class="btn btn-info btn-lg">Генерация сертификата</a>
                <a target="_blank" href="/shaters/calculation/acceptance/{{ $obj->id }}" class="btn btn-info btn-lg">Генерация акта приёма-передачи</a>
                <a target="_blank" href="/shaters/calculation/work/{{ $obj->id }}" class="btn btn-info btn-lg">Генерация акта оказания работ</a>
              </div>
            </div>
          @endif
        @endif
      </div>
      <hr/>
      <div class="subjects">
        <div class="item" ng-repeat="subject in calc.model.subjects">
          <div class="subject-title">
            <div class="row">
              <div class="col-sm-2"><b>Предмет № <span ng-bind="::subject.i"></span></b></div>
              <div class="col-sm-7" style="vertical-align:middle">
                <p class="d-mobile">[[subject.title]]</p>
                <input ng-disabled="!calc.editable()" ng-model="subject.title" type="text" list="subject.title" class="form-control">
                  <datalist id="subject.title">
                      <option value="Шаттерсы (в сборе, в проем 00х00 мм). Материал МДФ ЭМАЛЬ. Ламели поворотные. Створки шт.">
                      <option value="Направляющие складная система  створки (х)">
                      <option value="Обрамление д/окна МДФ ЭМАЛЬ">
                      <option value="Наличники МДФ ЭМАЛЬ">
                      <option value="Петли (комплект)">
                      <option value="Ручки, дерев. для поворота ламелей">
                      <option value="Прочая фурнитура">
                      <option value="Проектирование изделия">
                  </datalist>
              </div>
              <div class="col-sm-3 text-right">
                <button type="button" ng-disabled="!calc.editable()" class="btn btn-primary" ng-click="calc.duplicateSubject(subject)">
                  Дублировать
                </button>
                <button type="button" ng-disabled="!calc.editable()" class="btn btn-danger" ng-click="calc.removeSubject(subject)">
                  Удалить
                </button>
              </div>
            </div>                                                                  
          </div>
          <h4>Элементы</h4>

          <div class="elements table-responsive">
            <table class="table table-hover table-condensed" style="table-layout:fixed">
              <thead>
              <tr>
                <th style="text-align:center;width:20px">№</th>
                <th style="width:300px">Элемент</th>
                <th style="width:180px;text-align:center">Размеры:<br/><span>ширина, высота, толщина, мм</span></th>
                <th style="text-align:center;width:75px">Площадь<br/><span>Ш &times; В,<br>м²</span></th>
                <th style="text-align:center;width:60px">Объем,<br/>м³</th>
                <th style="text-align:center;width:200px">Материал</th>
                <th style="text-align:center;width:20px"></th>
                <th style="text-align:center;width:70px">Цена за ед.</th>
                <th style="text-align:center;width:60px">Кол-во</th>
                <th style="text-align:center;width:90px">Стоимость</th>
                <th style="width:25px"></th>
              </tr>
              </thead>
              <tbody>
              <tr ng-repeat="el in subject.elements">
                <td style="text-align: center"><span ng-bind="::el.i"></span></td>
                <td>
                  {{ Form::shatersCharacter(null, ['class' => 'form-control input-sm', 'ng-model' => 'el.character', 'ng-disabled' => '!calc.editable()']) }}
                </td>
                <td style="text-align: center">
                  <span class="dimension_cell">
                      <input ng-disabled="!calc.editable()" ng-model="el.x" type="text" class="form-control input-sm text-center">
                  </span>
                  <span class="dimension_cell">
                      <input ng-disabled="!calc.editable()" ng-model="el.y" type="text" class="form-control input-sm text-center">
                  </span>
                  <span class="dimension_cell">
                      <input ng-disabled="!calc.editable()" ng-model="el.z" type="text" class="form-control input-sm text-center">
                  </span>
                </td>
                <td style="text-align:center"><b ng-bind="calc.calculateArea(el) | round"></b></td>
                <td style="text-align:center"><b ng-bind="calc.calculateVol(el) | round"></b></td>
                <td style="text-align:center">
                  <input ng-disabled="!calc.editable()" ng-model="el.part" type="text" class="form-control input-sm" ui-select2="calc.select2.part" placeholder="Введите название материала">
                </td>
                <td style="text-align:center" ng-bind="el.part.unit"></td>
                <td style="text-align:center" ng-bind="calc.calculatePrice(el) | roundPrice"></td>
                <td style="text-align:center">
                  <input ng-disabled="!calc.editable()" ng-model="el.total" type="text" class="form-control input-sm text-center">
                </td>
                <td style="text-align:center"><b ng-bind="calc.calculateSum(el) | roundPrice"></b></td>
                <td style="text-align:center;color:#FF5F5F">
                  <button type="button" ng-disabled="!calc.editable()" ng-click="calc.removeElement(subject, el)" class="btn btn-link" style="padding:0">
                    <span class="glyphicon glyphicon-remove"></span></button>
                </td>
              </tr>
              </tbody>
            </table>
            <button type="button" ng-disabled="!calc.editable()" ng-click="calc.addElement(subject)" class="btn btn-success btn-sm">
              <span class="glyphicon glyphicon-plus"></span> Добавить элемент
            </button>
          </div>

          <hr/>

          <div class="subject_properties">
            <div class="row">
              <div class="col-sm-10 part_props"><!-- Левая колонка предмета с основными опциями -->
                {{--<div class="row">
                  <div class="col-sm-9">3D размеры предмета: ширина, высота, глубина, мм</div>
                  <div class="col-sm-3">
                    <span class="dimension_cell">
                        <input ng-disabled="!calc.editable()" ng-model="subject.x" type="text" class="form-control input-sm text-center">
                    </span>
                    <span class="dimension_cell">
                        <input ng-disabled="!calc.editable()" ng-model="subject.y" type="text" class="form-control input-sm text-center">
                    </span>
                    <span class="dimension_cell">
                        <input ng-disabled="!calc.editable()" ng-model="subject.z" type="text" class="form-control input-sm text-center">
                    </span>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-9">Объем предмета, м³</div>
                  <div class="col-sm-3">
                    <input type="text" disabled ng-value="calc.calculateVol(subject) | round" class="form-control input-sm text-center"/>
                  </div>
                </div>--}}

                <div class="row">
                  <div class="col-sm-9">Количество элементов предмета, шт.</div>
                  <div class="col-sm-3">
                    <input type="text" disabled ng-value="calc.calculateElements(subject)" class="form-control input-sm text-center"/>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-9">Стоимость изготовления элементов предмета (сумма по всем элементам)</div>
                  <div class="col-sm-3">
                    <input type="text" disabled ng-value="subject.cost_manufacturing | roundPrice" class="form-control input-sm text-center"/>
                  </div>
                </div>

               {{-- <div class="row">
                  <div class="col-sm-9">Стоимость конструкторской работы (ставка конструктора * кол-во элементов)</div>
                  <div class="col-sm-3">
                    <input type="text" disabled ng-value="subject.cost_construct | roundPrice" class="form-control input-sm text-center"/>
                  </div>
                </div>--}}

                {{--<div class="row">
                  <div class="col-sm-9">Стоимость сборки предмета (объем м³ * цена за м³ * коэф (кол-во элементов))</div>
                  <div class="col-sm-3">
                    <input type="text" disabled ng-value="subject.cost_assembly | roundPrice" class="form-control input-sm text-center"/>
                  </div>
                </div>--}}

                <div class="row">
                  <div class="col-sm-9">Общая стоимость предмета</div>
                  <div class="col-sm-3">
                    <input type="text" disabled ng-value="subject.cost_total | roundPrice" class="form-control input-sm text-center input-total"/>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-9">Общая стоимость с учетом общего коэф.</div>
                  <div class="col-sm-3">
                    <input type="text" disabled ng-value="subject.cost_total * calc.model.additional_coefficient.value | roundPrice" class="form-control input-sm text-center input-total"/>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-9">Количество, шт.</div>
                  <div class="col-sm-3">
                    <input ng-disabled="!calc.editable()" type="text" ng-model="subject.num" class="form-control input-sm text-center"/>
                  </div>
                </div>

                {{--<div class="row">
                  <div class="col-sm-9">Затраты</div>
                  <div class="col-sm-3">
                    <input type="text" disabled ng-value="subject.outlay | roundPrice" class="form-control input-sm text-center"/>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-9">Маржа</div>
                  <div class="col-sm-3">
                    <input type="text" disabled ng-value="subject.margin | roundPrice" class="form-control input-sm text-center"/>
                  </div>
                </div>--}}
              </div><!-- Левая колонка предмета с основными опциями # END -->

              <div class="col-sm-2"><!-- Правая колонка предмета с доп. опциями -->
                {{--<div><!-- Конструкторская наценка -->
                  <input ng-disabled="!calc.editable()" ui-select2="calc.select2.rate" class="form-control input-sm" ng-model="subject.constructor_rate"/>
                </div><!-- Конструкторская наценка # END -->
--}}
                <div><!-- Скидка / наценка -->
                  Скидка / Наценка<br/>
                  <input type="text" ng-model="subject.discount" class="form-control input-sm text-center"/>
                </div><!-- Скидка / наценка # END -->

                <div>
                  Стоимость предмета с учетом общего коэф., скидки и наценки<br/>
                  <input type="text" disabled ng-value="calc.calculateSubjectSum(subject) | roundPrice" class="form-control input-sm text-center input-total"/>
                </div>
              </div><!-- Правая колонка предмета с доп. опциями # END -->
            </div>
          </div>

          <hr/>

        </div>
        <button type="button" ng-disabled="!calc.editable()" ng-click="calc.addSubject()" class="btn btn-success btn-lg">
          <span class="glyphicon glyphicon-plus"></span> Добавить предмет
        </button>
      </div>
    </form><br>
    @if(($obj->exists && !$obj->parent_id))
    {{--<h3>Скопировать все предметы в текущий расчёт из расчёта #</h3>
    <form class="form-horizontal" role="form" method="post" action="/calculation/merge">
        <div class="input-group input-group-md">
          <input type="hidden" name="id" value="{{$obj->id}}">
          <input type="number" class="form-control" name="id_merged" placeholder="Номер расчёта">
        </div><br>
      <button class="btn btn-danger btn-lg">Скопировать предметы</button>
    </form><br>--}}
    @endif
  </div>
</div>
@stop
@section('scripts')
<script src="/static/vendor/angular/angular.min.js"></script>
<script src="/static/vendor/select2/select2.min.js"></script>
<script src="/static/vendor/select2/select2_locale_ru.js"></script>
<script src="/static/vendor/select2/select2-ng.js"></script>
<script src="/static/vendor/angular/angular-file-upload.min.js"></script>
<script type="text/javascript">
  var obj = {{ $obj->exists ? $obj : '{}' }};
  var helpers = {{ $elements }};

  App.client = new Services.GridResource('/shaters/api/clients', {}, {});

  $(document).ready(function() {
	  $('.datepicker').datetimepicker();
  })
</script>
<script src="/static/js/app/app.js"></script>
<script src="/static/js/app/controllers/ShatersCalcController.js"></script>
<script src="/static/js/app/filters/MainFilters.js"></script>
<script src="/static/js/app/services/NotifyService.js"></script>
<style>
  .dimension_cell {
    text-align: center;
    float: left;
    width: 31%;
    margin-right: 3.5%;
  }

  .dimension_cell:last-child {
    margin-right: 0;
  }

  .item {
    margin-bottom: 30px;
  }

  .elements {
    box-shadow: 0 1px 1px #aaa;
    background-color: #f5f5f5;
    padding: 10px;
  }

  .elements td {
    vertical-align: middle !important;
  }

  .part_prop {
    text-align: right;
    line-height: 2em;
  }

  .part_props > .row {
    margin-bottom: 6px;
  }

  .part_props > .row > div {
    line-height: 2em;
  }

  .part_props > .row > div:first-child {
    text-align: right;
  }

  .subject-title {
    padding: 10px;
    background-color: #f4f4f4;
    margin-bottom: 10px;
    box-shadow: 0 1px 1px #bbb;
  }

  .subject-title > .row > div {
    height: 34px;
    line-height: 34px;
  }

  .dimension_cell > input {
    padding: 5px;
  }
</style>
@stop
