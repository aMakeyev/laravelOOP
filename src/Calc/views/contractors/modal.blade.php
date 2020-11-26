<div class="modal" id="contractor_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="contractor_form" method="post" action="" onsubmit="App.contractor.{{ $obj->exists ? 'update' : 'store' }}(this);return false;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ $obj->exists ? 'Редактирование подрядчика' : 'Добавление подрядчика' }}</h4>
                </div>
                <div class="modal-body">
                    @if ($obj->exists)
                    <input type="hidden" id="id" disabled class="form-control" name="id" value="{{ $obj->id }}"/>
                    @endif
                    <div class="form-group">
                        <label for="title">Название (Организации / Компании)</label>
                        <input type="text" tabindex="1" placeholder="Название" class="form-control" id="title" name="title" value="{{ $obj->title }}">
                    </div>
                    <hr/>
                    <div class="form-group">
                        <label>Контактное лицо</label>
                    </div>
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="first_name">Имя</label>
                                <input type="text" tabindex="2" placeholder="Имя" class="form-control" id="first_name" name="first_name" value="{{ $obj->first_name }}">
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="last_name">Фамилия</label>
                                <input type="text" tabindex="3" placeholder="Фамилия" class="form-control" id="last_name" name="last_name" value="{{ $obj->last_name }}">
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="function">Должность</label>
                                <input type="text" tabindex="4" placeholder="Должность" class="form-control" id="function" name="function" value="{{ $obj->function }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" tabindex="5" placeholder="Email" class="form-control" id="email" name="email" value="{{ $obj->email }}">
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="phone">Телефон</label>
                                <input type="text" tabindex="6" placeholder="Телефон" class="form-control" id="phone" name="phone" value="{{ $obj->phone }}">
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="status">Статус</label>
                                {{ Form::select('status', Config::get('calc::contractor/statuses'), $obj->status, ['class' => 'form-control', 'tabindex' => '7']) }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address">Адрес</label>
                        <textarea rows="2" tabindex="8" class="form-control" id="address" name="address">{{ $obj->address }}</textarea>
                    </div>
                    <hr/>
                    <div class="form-group">
                        <label for="description">Описание (вид деятельности)</label>
                        <textarea rows="2" tabindex="9" class="form-control" id="description" name="description">{{ $obj->description }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>
