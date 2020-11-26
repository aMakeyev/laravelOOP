<form class="navbar-form navbar-right" onsubmit="App.variable.save('{{ $var->name }}');return false">
    <div class="form-group">
        <label for="{{ $var->name }}">{{ $var->title }}</label>&nbsp;
        <input onkeyup="App.variable.change(event, '{{ $var->name }}')" type="text" id="{{ $var->name }}" class="form-control text-center" placeholder="Значение" value="{{ $var->value }}" style="width:100px"> {{ $var->unit }}
    </div>
    <button disabled type="submit" class="btn btn-primary">Сохранить</button>
</form>
