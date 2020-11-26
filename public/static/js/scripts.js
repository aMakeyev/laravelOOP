function priceFormat(value) {
    if (!value) return '—';
    value = Math.round(value * 100) / 100;
    return value.toString().replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ');
}

function priceFormatZero(value) {
	if (!value) return '—';
	value = Math.round(value);
	return value.toString().replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ');
}

function valueFormat(value, wrap) {
    return value ? (typeof wrap == 'string' ? wrap.replace(':text:', value) : value) : '—'
}

function plural(n, forms) {
    return forms[n%10==1 && n%100!=11 ? 0 : n%10>=2 && n%10<=4 && (n%100<10 || n%100>=20) ? 1 : 2];
}

$(document)
    .ajaxStart(function () {
        $(this).trigger('ajaxPromise');
    })
    .ajaxStop(function () {
        $(this).trigger('ajaxDone');
    })
    .ajaxError(function () {
        $(this).trigger('ajaxFail');
    });

$.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

var App = {};

App.values = {};

var Notify = new (function (notify) {

    var notice, error, success, info, confirm;

    notice = function (message, title, type, options) {
        var defaults = {
            title: title || false,
            text: message,
            type: type || 'notice',
            animation: 'none',
            icon: true,
            shadow: false,
            delay: 5000
        };

        if (options && typeof options === 'object') {
            defaults = jQuery.extend(defaults, options);
        }

        return new notify(defaults);
    };

    confirm = function (message, title, buttons) {
        return new notice(message, title, false, {
            hide: false,
            confirm: {
                confirm: true,
                buttons: buttons || [{
                    text: "Ок",
                    addClass: "",
                    promptTrigger: true,
                    click: function (notice, value) {
                        notice.remove();
                        notice.get().trigger("pnotify.confirm", [notice, value]);
                    }
                },{
                    text: "Отмена",
                    addClass: "",
                    click: function (notice) {
                        notice.remove();
                        notice.get().trigger("pnotify.cancel", notice);
                    }
                }]
            }
        });
    };

    error = function (message, title) {
        return notice(message, title, 'error');
    };

    success = function (message, title) {
        return notice(message, title, 'success');
    };

    info = function (message, title) {
        return notice(message, title, 'info');
    };

    return {
        error:   error,
        success: success,
        info:    info,
        notice:  notice,
        confirm: confirm
    }
})(PNotify);

var Services = {};

Services.Http = function (url) {
    var self = this;
    this.url = url;

    return function (url, data, type, callback) {
        var isFile = ($('#file').length);

        if (data && data.localName && data.localName == 'form') {
            if (isFile) {
                data = $(data).serializeObject();
                var formData = new FormData();

                formData.append('target', data.target);
                formData.append('body', data.body);
                formData.append('subject', data.subject);
                formData.append('_wysihtml5_mode', data._wysihtml5_mode);

                var fileData = $('#file').prop('files')[0];
                formData.append('file', fileData);

                if(type == 'put') {
                    type = 'post';
                }

                var contentType = false;
            } else {
                var formData = $(data).serializeObject();
                var contentType = 'application/x-www-form-urlencoded; charset=UTF-8';
            }
        }

/*        console.log(url);
        console.log(data);
        console.log(type);
        console.log(formData);*/

        $.ajax({
            url: self.url + (url ? '/' + url : ''),
            type: type || 'get',
            data: formData || null,
            cache: false,
            contentType: contentType,
            processData: !isFile,
            statusCode: {
                404: function() {
                    Notify.error('Запрашиваемый ресурс недоступен')
                },
                403: function () {
                    Notify.error('Доступ к ресурсу ограничен')
                },
                500: function () {
                    Notify.error('Ошибка сервера. Попробуйте еще раз')
                }
            },
            success: function (response) {
                if (response.error && response.message) {
                    Notify.error(response.message);
                } else if (response.message) {
                    Notify.success(response.message);
                }

                if (response.modal && response.modal_id) {
                    var modal = $(response.modal_id);

                    if (modal.length) modal.remove();

                    modal = $(response.modal);


                    $('body').append(modal);
                    modal.modal('show');
                    $('.datepicker').datetimepicker();
                }

                if (response.form_id) {
                    var form = $(response.form_id);
                    form.find('.error').remove();
                }

                if (response.errors && response.form_id) {
                    $.each(response.errors, function (name, text) {
                        var elem = form.find('[name="'+name+'"], [name$="['+name+']"]').filter(':enabled').first();
                        if (elem.length > 0) {
                            elem.focus();
                            $('<small class="error">' + text[0] + '</small>').insertAfter(elem);
                            return false;
                        }
                    });
                }

                if (typeof callback === 'function') callback(response);
            }
        });
    };
};

(function ($) {
    "use strict";

    var GridResource = function (url, options, messages) {
        if (url === undefined)
            throw new Error('URL is not defined!');

        this.url = url;
        this.options = options || {};
        this.messages = messages || {};
        this.http = new Services.Http(this.url);
        this.grid = options.grid || { datagrid: function () {} };
    };

    GridResource.prototype.remove = function (id) {
        var self = this;
        $.messager.confirm(this.messages.remove.title, this.messages.remove.message, function (r) {
            if (r) {
                self.http(id, null, 'delete', function (response) {
                    if ( ! response.error) {
                        self.grid.datagrid('reload');
                    }
                });
            }
        });
    };

    GridResource.prototype.edit = function (id) {
        this.http(id + '/edit', null, 'get');
    };

    GridResource.prototype.update = function (form) {
        var self = this;
        this.http(form.id.value, form, 'put', function (response) {
            if (!response.error) {
                $('.modal').modal('hide');
                self.grid.datagrid('reload');
            }
        });
    };

    GridResource.prototype.create = function () {
        this.http('create', null, 'get');
    };

    GridResource.prototype.store = function (form) {
        var self = this;
        this.http('', form, 'post', function (response) {
            if (!response.error) {
                $('.modal').modal('hide');
                self.grid.datagrid('reload');
            }
        });
    };

    GridResource.prototype.duplicate = function (id) {
        var self = this;
        $.messager.confirm(this.messages.duplicate.title, this.messages.duplicate.message, function (r) {
            if (r) {
                self.http('duplicate/' + id, null, 'get', function (response) {
                    if (!response.error) {
                        self.grid.datagrid('reload');
                    }
                });
            }
        });
    };

    var Coefficients = function (url, options, messages) {
        if (url === undefined)
            throw new Error('URL is not defined!');

        this.url = '/api/' + url;
        this.options = options || {};
        this.messages = messages || {};
        this.http = new Services.Http(this.url);
        this.grid = options.grid;
    };

    Coefficients.prototype.reject = function () {
        var self = this;
        $.messager.confirm(this.messages.reject.title, this.messages.reject.message, function (r) {
            if (r) {
                self.grid.edatagrid('rejectChanges');
            }
        });
    };

    Coefficients.prototype.remove = function (index) {
        this.grid.edatagrid('destroyRow', index);
    };

    Coefficients.prototype.edit = function (index) {
        this.grid.edatagrid('editRow', index);
    };

    Coefficients.prototype.add = function () {
        this.grid.edatagrid('addRow');
    };

    Coefficients.prototype.save = function () {
        var self = this;
        $.messager.confirm(this.messages.save.title, this.messages.save.message, function (r) {
            if (r) {
                self.grid.edatagrid('saveRow');
                var data = self.grid.edatagrid('getData').rows;
                self.http('', {data: data}, 'post', function (response) {
                    if ( ! response.error) {
                        $('.modal').modal('hide');
                        self.grid.datagrid('reload');
                    }
                });
            }
        });
    };

    var Variable = function () {
        this.http = new Services.Http('/api/variables');
    };

    Variable.prototype.save = function (name) {
        var variable = $('#' + name);

        if (!variable.length) Notify.error('Переменная не найдена');
        if (!variable.val()) Notify.error('Введите значение переменной');

        this.http(name, {value: variable.val()}, 'put', function (response) {
            variable.val(response.value);
            variable.parents('form').find('button').prop('disabled', true)
        });
    };

    Variable.prototype.change = function (e, type) {
        e = e || event;

        var btn = $(e.target).parents('form').find('button');
        var val = e.target.value;

        if (val === '') {
            btn.prop('disabled', true);
            return;
        }

        if (type === '%') {
            val = parseFloat(val);

            if (val < 0 || val > 100) {
                btn.prop('disabled', true);
                return;
            }
        } else if (type === 'price' && !$.isNumeric(val)) {
            btn.prop('disabled', true);
            return;
        }

        btn.prop('disabled', false);
    };

    Services.Coefficients = Coefficients;
    Services.GridResource = GridResource;
    Services.Variable = Variable;

})(window.jQuery);

$(document).ready(function () {
    $.fn.datetimepicker.defaults = {
        language: 'ru',
        format: 'dd.mm.yyyy',
        autoclose: true,
        todayBtn: 'linked',
        minView: 'month',
        weekStart: 1
    };

    App.variable = new Services.Variable();
});
