<div class="modal" id="part_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="part_form" method="post" action="" onsubmit="App.part.{{ $obj->exists ? 'update' : 'store' }}(this);return false;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ $obj->exists ? 'Редактирование заказчика' : 'Добавление комплектующего или материала' }}</h4>
                </div>
                <div class="modal-body">
                    @if ($obj->exists)
                    <div class="form-group form-inline">
                        <label for="id">№</label>
                        <input type="text" id="id" disabled class="form-control" name="id" value="{{ $obj->id }}"/>
                    </div>
                    @endif

                    <div class="form-group">
                        <label for="first_name">Наименование</label>
                        <input type="text" tabindex="1" placeholder="Наименование" class="form-control" id="title" name="title" value="{{ $obj->title }}">
                    </div>
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label>Артикул</label>
                                <input type="text" tabindex="2" placeholder="Артикул" class="form-control" id="article" name="article" value="{{ $obj->article }}">
                            </div>
                        </div>
                        <div class="col-xs-5 col-sm-5 col-md-5">
                            <div class="form-group">
                                <label>Группа</label>
                                {{ Form::select('group_id', $groups, $obj->group_id, ['class' => 'form-control', 'tabindex' => '3']) }}
                            </div>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3">
                            <div class="form-group">
                                <label for="status">Ед. измерения</label>
                                {{ Form::select('unit', Config::get('calc::part/units'), $obj->getOriginal('unit'), ['class' => 'form-control', 'tabindex' => '4']) }}
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="form-group">
                        <label for="unit_price">Цена закупки, руб.</label>
                        <input type="text" tabindex="5" placeholder="Цена закупки" class="form-control" id="unit_price" name="unit_price" value="{{ $obj->unit_price }}">
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
