
    <script src="/static/vendor/angular/angular.min.js"></script>
    <script src="/static/vendor/select2/select2.min.js"></script>
    <script src="/static/vendor/select2/select2_locale_ru.js"></script>
    <script src="/static/vendor/select2/select2-ng.js"></script>
    <script src="/static/vendor/angular/angular-file-upload.min.js"></script>
    <script src="/static/js/app/app.js"></script>
    <script src="/static/js/app/filters/MainFilters.js"></script>
    <script src="/static/js/app/services/NotifyService.js"></script>

<div class="modal" id="client_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="client_form" method="post" action="" onsubmit="App.client.{{ $obj->exists ? 'update' : 'store' }}(this);return false;">
                <div class="modal-header">
                    <button type="button" class="button-close close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ $obj->exists ? 'Редактирование заказчика' : 'Добавление заказчика' }}</h4>
                </div>
                <div class="modal-body">
                    @if ($obj->exists)
                        <input type="hidden" id="id" disabled class="form-control" name="id" value="{{ $obj->id }}"/>
                    @endif
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="first_name">Имя</label>
                                <input type="text" tabindex="1" placeholder="Имя" class="form-control" id="first_name" name="first_name" value="{{ $obj->first_name }}">
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="last_name">Фамилия</label>
                                <input type="text" tabindex="2" placeholder="Фамилия" class="form-control" id="last_name" name="last_name" value="{{ $obj->last_name }}">
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="second_name">Отчество</label>
                                <input type="text" tabindex="2" placeholder="Отчество" class="form-control" id="second_name" name="second_name" value="{{ $obj->second_name }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" tabindex="3" placeholder="Email" class="form-control" id="email" name="email" value="{{ $obj->email }}">
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="phone">Телефон</label>
                                <input type="text" tabindex="4" placeholder="Телефон" class="form-control" id="phone" name="phone" value="{{ $obj->phone }}">
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="status">Статус</label>
                                {{ Form::select('status', Config::get('calc::client/statuses'), $obj->status, ['class' => 'form-control', 'tabindex' => '5']) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label for="last_contact_at">Дата последнего звонка</label>
                                <input type="text" tabindex="6" placeholder="Дата последнего звонка" class="form-control datepicker" id="last_contact_at" name="last_contact_at" value="{{ $obj->last_contact_at }}">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label for="next_contact_at">Дата следующего звонка</label>
                                <input type="text" tabindex="7" placeholder="Дата след. звонка" class="form-control datepick" id="next_contact_at" name="next_contact_at" value="{{ $obj->next_contact_at }}">
                            </div>
                            <script>
                                $('#next_contact_at').datetimepicker(
                                    {startDate: "<?php echo date('Y-m-d', time()); ?>"}
                                );
                            </script>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label for="type">Тип</label>
                                {{ Form::select('type', Config::get('calc::client/types'), $obj->type, ['class' => 'form-control', 'tabindex' => '8']) }}
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label for="subscribe">Добавлен в рассылку</label>
                                {{ Form::select('subscribe', Config::get('calc::client/subscribes'), $obj->subscribe, ['class' => 'form-control', 'tabindex' => '10']) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label for="description">Описание</label>
                                <textarea tabindex="9" rows="4" class="form-control" id="description" name="description">{{ $obj->description }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label for="delivery_address">Адрес доставки</label>
                                <textarea tabindex="9" rows="4" class="form-control" id="delivery_address" name="delivery_address">{{ $obj->delivery_address }}</textarea>
                            </div>
                        </div>
                    </div>
                    @if (!$obj->exists)
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="alert alert-info">
                                Ввод данных частного лица или организации, а также загрузка файлов будут доступны после сохранения заказчика.
                            </div>
                        </div>
                    </div>
                    @endif
                    @if ($obj->type == 1 || $obj->type == 3 )
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="passport_series">Серия паспорта</label>
                                <input type="text" tabindex="2" placeholder="Серия паспорта" class="form-control" id="passport_series" name="passport_series" value="{{ $obj->passport_series }}">
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="passport_number">Номер паспорта</label>
                                <input type="text" tabindex="2" placeholder="Номер паспорта" class="form-control" id="passport_number" name="passport_number" value="{{ $obj->passport_number }}">
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="birthday">Дата рождения</label>
                                <input type="text" tabindex="7" placeholder="Дата рождения" class="form-control datepick" id="birthday" name="birthday" value="{{ $obj->birthday }}">
                            </div>
                            <script>
								$('#birthday').datetimepicker();
                            </script>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="passport_issued_by">Кем выдан</label>
                                <input type="text" tabindex="2" placeholder="Кем выдан" class="form-control" id="passport_issued_by" name="passport_issued_by" value="{{ $obj->passport_issued_by }}">
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="passport_issued_code">Код подразделения</label>
                                <input type="text" tabindex="2" placeholder="Код подразделения" class="form-control" id="passport_issued_code" name="passport_issued_code" value="{{ $obj->passport_issued_code }}">
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="passport_issued_date">Дата выдачи</label>
                                <input type="text" tabindex="7" placeholder="Дата выдачи" class="form-control datepick" id="passport_issued_date" name="passport_issued_date" value="{{ $obj->passport_issued_date }}">
                            </div>
                            <script>
                                $('#passport_issued_date').datetimepicker();
                            </script>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label for="passport_address">Адрес регистрации</label>
                                <textarea tabindex="9" rows="4" class="form-control" id="passport_address" name="passport_address">{{ $obj->passport_address }}</textarea>
                            </div>
                        </div>
                    </div>
                    @elseif($obj->type == 2)
                    <h3>Реквизиты организации</h3>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="legal_name">Название организации</label>
                                <input type="text" tabindex="2" placeholder="Название организации" class="form-control" id="legal_name" name="legal_name" value="{{ $obj->legal_name }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="inn">ИНН</label>
                                <input type="text" tabindex="2" placeholder="ИНН" class="form-control" id="inn" name="inn" value="{{ $obj->inn }}">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="kpp">КПП</label>
                                <input type="text" tabindex="2" placeholder="КПП" class="form-control" id="kpp" name="kpp" value="{{ $obj->kpp }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label for="legal_address">Юридический адрес</label>
                                <textarea tabindex="2" rows="2" class="form-control" id="legal_address" name="legal_address">{{ $obj->legal_address }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label for="mail_address">Почтовый адрес</label>
                                <textarea tabindex="2" rows="2" class="form-control" id="mail_address" name="mail_address">{{ $obj->mail_address }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="bank_name">Наименование банка</label>
                                <input type="text" tabindex="2" placeholder="Наименование банка" class="form-control" id="bank_name" name="bank_name" value="{{ $obj->bank_name }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="settlement_accountnn">Расчетный счет</label>
                                <input type="text" tabindex="2" placeholder="Расчетный счет" class="form-control" id="settlement_account" name="settlement_account" value="{{ $obj->settlement_account }}">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="correspondent_account">Корреспондентский счет</label>
                                <input type="text" tabindex="2" placeholder="Корреспондентский счет" class="form-control" id="correspondent_account" name="correspondent_account" value="{{ $obj->correspondent_account }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label for="bik">БИК</label>
                                <input type="text" tabindex="2" placeholder="БИК" class="form-control" id="bik" name="bik" value="{{ $obj->bik }}">
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label for="okpo">ОКПО</label>
                                <input type="text" tabindex="2" placeholder="ОКПО" class="form-control" id="okpo" name="okpo" value="{{ $obj->okpo }}">
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label for="okato">ОКАТО</label>
                                <input type="text" tabindex="2" placeholder="ОКАТО" class="form-control" id="okato" name="okato" value="{{ $obj->okato }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label for="oktmo">ОКТМО</label>
                                <input type="text" tabindex="2" placeholder="ОКТМО" class="form-control" id="oktmo" name="oktmo" value="{{ $obj->oktmo }}">
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label for="okved">ОКВЭД</label>
                                <input type="text" tabindex="2" placeholder="ОКВЭД" class="form-control" id="okved" name="okved" value="{{ $obj->okved }}">
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label for="ogrn">ОГРН</label>
                                <input type="text" tabindex="2" placeholder="ОГРН" class="form-control" id="ogrn" name="ogrn" value="{{ $obj->ogrn }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="legal_phone">Телефон</label>
                                <input type="text" tabindex="2" placeholder="Телефон" class="form-control" id="legal_phone" name="legal_phone" value="{{ $obj->legal_phone }}">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="legal_email">Email</label>
                                <input type="text" tabindex="2" placeholder="Email" class="form-control" id="legal_email" name="legal_email" value="{{ $obj->legal_email }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="director_name">Генеральный директор</label>
                                <input type="text" tabindex="2" placeholder="Генеральный директор" class="form-control" id="director_name" name="director_name" value="{{ $obj->director_name }}">
                            </div>
                        </div>
                    </div>
                    @endif

                    {{--FILES--}}
                    @if ($obj->exists)
                        <div ng-app="Calc">
                            <div ng-controller="ClientCtrl as calc">
                                <div class=" row form-group">
                                    <label class="col-sm-4 control-label">Файлы клиента</label>

                                    <div class="col-sm-8">
                                        <div class="form-control" style="height:auto;min-height:34px;margin-bottom:5px">
                                            <ul class="list-unstyled" style="margin-bottom:0">

                                                <li ng-repeat="file in calc.model.files">
                                                    <a target="_blank" ng-href="[[::file.src]]" ng-bind="::file.name"></a>
                                                    <button  type="button" class="btn btn-link btn-xs" ng-click="calc.removeFile(file)">
                                                        <span class="glyphicon glyphicon-remove"></span></button>
                                                </li>
                                            </ul>
                                        </div>

                                        <input type="file" nv-file-select uploader="calc.uploader" multiple/>
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
                            </div>
                        </div>
                    @endif

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
    <script>
		if ($(window).width() < 415) {
			$('.datepicker').focus(function() {
				this.blur();
			});
			$('.datepick').focus(function() {
				this.blur();
			});
		}
		$("#client_modal").on("hidden.bs.modal", function () {
			$ ('.modal-backdrop.in').remove();
		});

                {{--for FILES --}}

		var obj = {{ $obj->exists ? $obj : '{}' }};

		(function () {

			'use strict';

			/**
			 * @class CalcController
			 * @classdesc Calculation Controller
			 * @ngInject
			 */
			function CalcController($scope, $http, FileUploader, Notify) {
				var self = this;

				this.$scope = $scope;
				this.$http = $http;
				this.notify = Notify;
				// Загрузка файлов
				this.uploader = new FileUploader({
					url: '/api/files/upload/client/',
					removeAfterUpload: true,
					autoUpload: true,
					onBeforeUploadItem: function (item) {
						item.url += self.getModelId();
					},
					onCompleteItem: function (file, response) {
						self.notify.notice(response.message, false, response.error ? 'error' : 'success');

						if (!response.error && response.file) {
							self.addFile(response.file)
						}
					}
				});

				this.model = {};
				this.fillModel(obj);
			}


			CalcController.prototype.fillModel = function (data) {
				var self = this;
				var model = this.model;

				// ID расчета
				model.id = data.id || 0;

				// Файлы
				model.files = data.files || [];

			};
			/**
			 * Удаление файла
			 */
			CalcController.prototype.removeFile = function (file) {
				var self = this;
				var model = this.model;

				if (!confirm('Удалить файл "' + file.name + '" навсегда?')) {
					return false;
				}

				this.$http({url: '/api/files/' + file.id, method: 'DELETE'})
					.success(function (response) {
						if (!response.error) {
							model.files.splice(model.files.indexOf(file), 1);
						}

						self.notify.notice(response.message, false, response.error ? 'error' : 'success');
					});

				return true;
			};

			CalcController.prototype.getModelId = function () {
				return this.model.id
			};

			CalcController.prototype.addFile = function (file) {
				console.log(this.model.files);

				this.model.files.push({
					id: file.id,
					src: file.src,
					name: file.name
				});
			};

			CalcController.$inject = ['$scope', '$http', 'FileUploader', 'Notify'];

			angular
				.module('Calc')
				.controller('ClientCtrl', CalcController);
		})();

    </script>
</div>

