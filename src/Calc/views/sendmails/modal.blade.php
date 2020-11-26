<div class="modal" id="sendmail_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="sendmail_form" method="post" enctype="multipart/form-data" action=""
                  onsubmit="App.sendmail.{{ $obj->exists ? 'update' : 'store' }}(this);return false;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ $obj->exists ? 'Редактирование рассылки' : 'Добавление рассылки' }}</h4>
                </div>
                <div class="modal-body">
                    @if ($obj->exists)
                        <input type="hidden" id="id" disabled class="form-control" name="id" value="{{ $obj->id }}"/>
                    @endif

                    <div class="form-group">
                        <label for="type">Кому</label>
                        {{ Form::select('target', Config::get('calc::client/types'), $obj->target, ['class' => 'form-control', 'tabindex' => '8']) }}
                    </div>
                    <div class="form-group">
                        <label for="first_name">Тема письма</label>
                        <input type="text" tabindex="1" placeholder="Имя" class="form-control" id="subject" name="subject" value="{{ $obj->subject }}">
                    </div>
                    <div class="form-group">
                        <label for="description">Текст письма</label>
                        <textarea tabindex="9" rows="10" class="form-control" id="body" name="body">{{ $obj->body }}</textarea>
                        <script>
                            $('#body').wysihtml5({
                                locale: 'ru-RU',
                                toolbar: {
                                    "font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
                                    "emphasis": true, //Italics, bold, etc. Default true
                                    "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
                                    "html": false, //Button which allows you to edit the generated HTML. Default false
                                    "link": true, //Button to insert a link. Default true
                                    "image": true, //Button to insert an image. Default true,
                                    "color": false, //Button to change color of font
                                    "blockquote": true, //Blockquote
                                    "size": 'xs'
                                }
                            });
                        </script>
                    </div>
                    <hr/>
                    <div class="form-group">
                        <p>Переменные:</p>
                        <ul>
                            <li>%FULLNAME% - Имя и фамилия клиента</li>
                        </ul>
                    </div>
                    <hr/>
                    <div class="form-group">
                        @if ($obj->file)
                            <a href="{{$obj->file}}">{{$obj->file_name}}</a>
                            <button type="button" class="btn btn-link btn-xs" onclick="App.removeFile({{$obj->id}})">
                                <span class="glyphicon glyphicon-remove"></span></button>
                        @else
                            <label for="first_name">Прикрепить файл</label>
                            <input type="file" tabindex="1" placeholder="Прикрепить файл..." class="form-control"
                                   id="file" name="file">
                        @endif
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
