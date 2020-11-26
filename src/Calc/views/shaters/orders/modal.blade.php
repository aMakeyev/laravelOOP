<div class="modal" id="order_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="order_form" method="post" action="" onsubmit="App.order.{{ $obj->exists ? 'update' : 'store' }}(this);return false;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ $obj->exists ? 'Редактирование заказ' : 'Добавление заказа' }}</h4>
                </div>
                <div class="modal-body">
                    @if ($obj->exists)
                    <div class="form-group form-inline">
                        <label for="id">№</label>
                        <input type="text" id="id" disabled class="form-control" name="id" value="{{ $obj->id }}"/>
                    </div>
                    @endif

                    <div class="form-group">
                        <label for="title">Название</label>
                        <input type="text" tabindex="1" placeholder="Нзавание" class="form-control" id="title" name="title" value="{{ $obj->title }}">
                    </div>
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" tabindex="4" placeholder="Email" class="form-control" id="email" name="email" value="{{ $obj->email }}">
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="phone">Телефон</label>
                                <input type="text" tabindex="5" placeholder="Телефон" class="form-control" id="phone" name="phone" value="{{ $obj->phone }}">
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="status">Статус</label>
                                {{ Form::select('status', Config::get('calc::order/statuses'), $obj->status, ['class' => 'form-control', 'tabindex' => '6']) }}
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="form-group">
                        <label for="description">Описание</label>
                        <textarea tabindex="7" rows="3" class="form-control" id="description" name="description">{{ $obj->description }}</textarea>
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
