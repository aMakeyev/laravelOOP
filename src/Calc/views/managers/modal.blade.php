<div class="modal" id="user_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="user_form" method="post" action="" onsubmit="App.user.{{ $obj->exists ? 'update' : 'store' }}(this);return false;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ $obj->exists ? 'Редактирование менеджера' : 'Добавление менеджера' }}</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" value="{{ $obj->id }}" />
                <div class="form-group">
                    <label for="username">Имя пользователя</label>
                    <input type="text" tabindex="1" placeholder="Имя пользователя" class="form-control" id="username" name="username" value="{{ $obj->username }}">
                </div>
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <label for="first_name">Имя</label>
                            <input type="text" tabindex="2" placeholder="Имя" class="form-control" id="first_name" name="first_name" value="{{ $obj->first_name }}">
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <label for="last_name">Фамилия</label>
                            <input type="text" tabindex="3" placeholder="Фамилия" class="form-control" id="last_name" name="last_name" value="{{ $obj->last_name }}">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" tabindex="4" placeholder="Email" class="form-control" id="email" name="email" value="{{ $obj->email }}">
                </div>
                <div class="form-group">
                    <label for="phone">Телефон</label>
                    <input type="text" tabindex="5" placeholder="Телефон" class="form-control" id="phone" name="phone" value="{{ $obj->phone }}">
                </div>

                <div class="row">
                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <label for="rate">Ставка</label>
                            <input type="text" tabindex="6" placeholder="Ставка" class="form-control" id="rate" name="rate" value="{{ $obj->rate }}" autocomplete="off" aria-autocomplete="off">
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <label for="rate">Роль</label><br/>
                            {{ Form::managerRoles($obj->role, ['class' => 'form-control', 'tabindex' => '7']) }}
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <label for="rate">Статус</label><br/>
                            {{ Form::managerStatuses($obj->status, ['class' => 'form-control', 'tabindex' => '8']) }}
                        </div>
                    </div>
                </div>
                <hr/>
                @if ( ! $obj->exists)
                <div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    Если не указать пароль он будет сгенерирован автоматически и выслан в письме на Email менеджера
                </div>
                @endif
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <label for="password">Пароль</label>
                            <input type="password" tabindex="9" placeholder="Пароль" class="form-control" id="password" name="password" value="" aria-autocomplete="off">
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <label for="password_confirmation">Подтверждение пароля</label>
                            <input type="password" tabindex="10" placeholder="Подтверждение пароля" class="form-control" id="password_confirmation" name="password_confirmation" value="">
                        </div>
                    </div>
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
