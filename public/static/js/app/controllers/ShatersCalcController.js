(function () {

  'use strict';

  /**
   * @class ShatersCalcController
   * @ngInject
   */
  function CalcController($scope, $http, FileUploader, Notify) {
    var self = this;

    this.$scope = $scope;
    this.$http = $http;
    this.notify = Notify;
    // Загрузка файлов
    this.uploader = new FileUploader({
      url: '/api/files/upload/shaterscalculation/',
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

    // Расчет
    this.model = {};
    // Ставки конструкторов
    this.rates = helpers.rates;
    // Коэффициенты
    this.coefficients = helpers.coefficients;
    // Дополнительные коэффициенты
    this.additional_coefficients = helpers.additional_coefficients;
    // Переменные
    this.variables = helpers.variables;

    function formatTitle(data) {
      return data.title
    }

    function formatRateTitle(data) {
      return data.title + ' (' + data.rate + ' руб.)'
    }

    function formatCoeffTitle(data) {
      return data.title + ' (' + data.value + ')'
    }

    this.select2 = {
      default: {},
      coef: {
        data: this.additional_coefficients,
        formatSelection: formatCoeffTitle,
        formatResult: formatCoeffTitle
      },
      rate: {
        data: this.rates,
        formatSelection: formatRateTitle,
        formatResult: formatRateTitle
      },
      client: {
        minimumInputLength: 2,
        allowClear: true,
        ajax: {
          url: '/shaters/api/clients-list',
          dataType: 'json',
          cache: true,
          quietMillis: 200,
          data: function (term) {
            return {term: term}
          },
          results: function (data) {
            return {results: data}
          }
        },
        formatResult: formatClientName,
        formatSelection: formatClientName,
        initSelection: function () {
        }
      },
      part: {
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
          url: '/shaters/api/parts-list',
          dataType: 'json',
          cache: true,
          quietMillis: 200,
          data: function (term) {
            return {term: term}
          },
          results: function (data) {
            return {results: data}
          }
        },
        formatResult: formatTitle,
        formatSelection: formatTitle,
        initSelection: function () {
        }
      }
    };

    function formatClientName(client) {
      return client.last_name + ' ' + client.first_name + ' (' + client.email + ')'
    }

    /**
     * Вычисление цены за ед. изм. элемента предмета мебели с учетом надбавки
     *
     * @param el
     * @returns {number}
     */
    this.calculatePrice = function (el) {
      el.price = 0;

      if (!el.part || !el.part.unit_price || !el.part.unit_id) {
        return el.price
      }

      el.price = el.part.unit_price;
      // было с учетом маржи и т.п.
      // el.price = el.part.unit_price * (1 + this.variables.margin.value / 100);

      return el.price
    };

    /**
     * Вычисление стоимости элемента предмета мебели
     *
     * @param el Элемент
     * @returns {number}
     */
    this.calculateSum = function (el) {
      if (!el.part || !el.part.unit_price || !el.part.unit_id) {
        return 0;
      }
      // метры кв.
      if (el.part.unit_id === 1) {
        el.sum = el.x * el.y / Math.pow(1000, 2)
      }
      // шт.
      if (el.part.unit_id === 2) {
        el.sum = 1
      }
      // метры погонные
      if (el.part.unit_id === 3) {
        el.sum = el.x / 1000
      }
      // метры куб.
      if (el.part.unit_id === 4) {
        el.sum = el.x * el.y * el.z / Math.pow(1000, 3)
      }

      el.sum = el.sum * el.price * el.total;

      return el.sum;
    };

    this.fillModel(obj);
  }

  CalcController.prototype.calculate = function () {
  };

  CalcController.prototype.calculateElements = function (subject) {
    var num = 0;
    angular.forEach(subject.elements, function (el) {
      num += parseInt(el.total) || 0;
    });

    return num;
  };

  CalcController.prototype.getModelId = function () {
    return this.model.id
  };

  /**
   * Вычисление площади, кв. м
   *
   * @param state
   * @returns {number}
   */
  CalcController.prototype.calculateArea = function (state) {
    return state.x * state.y / Math.pow(1000, 2);
  };

  /**
   * Вычисление объема, куб. м
   *
   * @param state
   * @returns {number}
   */
  CalcController.prototype.calculateVol = function (state) {
    return state.x * state.y * state.z / Math.pow(1000, 3)
  };

  /**
   * Заполнение модели данными
   *
   * @param data
   */
  CalcController.prototype.fillModel = function (data) {
    var self = this;
    var model = this.model;

    // ID расчета
    model.id = data.id || 0;
    // Порядковый номер предмета мебели [AUTO INCREMENT]
    model.index = 0;
    model.exists = data.id > 0;
    // Возможность редактирования расчета
    model.editable = (data.status && data.status > 0 && data.status < 6) || !data.id;
    // Возможность сформировать отчет для клиента
    model.reportable = data.status && data.status > 1;
    // Название комплекта
    model.title = data.title || 'Новый комплект';
    // Описание
    model.description = data.description || '';

    model.created_at = data.created_at || (new Date()).format('yyyy-mm-dd H:M:s');
    model.updated_at = data.updated_at || '';

    model.contract_at = data.contract_at || data.created_at || (new Date()).format('dd.mm.yyyy');

    // Заказчик
    model.client = data.client || undefined;
    // Статус расчета
    model.status = data.status || 1;
    // Предметы, subjects[index].elements - Элементы
    model.subjects = [];
    // Файлы
    model.files = data.files || [];

    // Стоимость изготовления
    model.cost_manufacturing = data.cost_manufacturing || 0;
    // Стоимость конструкторской работы
    model.cost_construct = data.cost_construct || 0;
    // Стоимость сборки
    model.cost_assembly = data.cost_assembly || 0;
    // Общая стоимость комплекта
    model.cost_total = data.cost_total || 0;

    // Поправочный коэффициент
    model.additional_coefficient = data.additional_coefficient_id || 0;
    // Максимально допустимая скидка
    model.discount = data.discount || 0;
    // Затраты
    model.outlay = data.outlay || 0;
    // Маржа
    model.margin = data.margin || 0;
    // Доставка
    model.delivery = data.delivery || 0;
    // Установка
    model.install = data.install || 0;
    // Псевдоскидки
    model.pseudo_discount_percent = data.pseudo_discount_percent;
    model.pseudo_discount_meter = data.pseudo_discount_meter;

    model.climb_price = data.climb_price || 0;
    //Финальная цена
    model.cost_final = data.cost_final;

    if (data.subjects) {
      angular.forEach(data.subjects, function (subject) {
        self.addSubject(subject);
      });
    } else {
      self.addSubject();
    }
  };

  /**
   * Проверка данных расчета перед отправкой на сервер
   *
   * @returns {boolean}
   */
  CalcController.prototype.validateModel = function () {
    var model = this.model;
    var errors = [];

    if (!model.title) {
      errors.push('Введите название комплекта')
    }

    if (!model.client || !model.client.id) {
      errors.push('Выберите заказчика')
    }

    if (!model.additional_coefficient) {
      errors.push('Выберите поправочный коэффициент')
    }

    if (!model.status) {
      errors.push('Выберите статус расчета')
    }

    if (!model.subjects.length) {
      errors.push('Добавьте хотя бы один предмет мебели')
    }

    if (model.pseudo_discount_percent == null) {
      errors.push('Укажите псевдоскидку')
    }

    if (model.pseudo_discount_meter == null) {
      errors.push('Укажите псевдоскидку за замер')
    }

    angular.forEach(model.subjects, function (subject) {
      /*if (!subject.constructor_rate || !subject.constructor_rate.id) {
        this.push('Выберите ставку конструктора у Предмета № ' + subject.i)
      }*/

     /* if (subject.x < 1 || subject.y < 1 || subject.z < 1) {
        this.push('Укажите размеры Предмета № ' + subject.i)
      }*/

      if (!subject.num || subject.num < 1) {
        this.push('Укажите кол-во у Предмета № ' + subject.i)
      }

      if (!subject.elements.length) {
        errors.push('Добавьте хотя бы один элемент к Предмету № ' + subject.i)
      } else {
        angular.forEach(subject.elements, function (el) {
          if (!el.character) {
            this.push('Выберите элемент № ' + el.i + ' у Предмета № ' + subject.i)
          }

          if (!el.part || !el.part.id) {
            this.push('Выберите материал элемента № ' + el.i + ' у Предмета № ' + subject.i)
          }

          if (el.total < 1) {
            this.push('Укажите кол-во у элемента № ' + el.i + ' у Предмета № ' + subject.i)
          }
          if (el.part.unit_id !== 2) {

              if (el.x < 1) {
                  this.push('Укажите размеры у элемента № ' + el.i + ' у Предмета № ' + subject.i)
              }
          }

        }, this);
      }
    }, errors);

    if (errors.length > 0) {
      this.notify.error(errors.join('\n'), 'Ошибка сохранения расчета');
      errors = [];
      return false;
    }

    return true;
  };

  /**
   * Удаление предмета мебели
   *
   * @param subject
   * @returns {boolean}
   */
  CalcController.prototype.removeSubject = function (subject) {
    var model = this.model;

    if (!confirm('Удалить предмет?')) {
      return false;
    }

    subject.watcher();

    model.subjects.splice(model.subjects.indexOf(subject), 1);

    this.calculateModel();

    return true;
  };

  /**
   * Дублирование предмета мебели
   *
   * @param subject
   * @returns {boolean}
   */
  CalcController.prototype.duplicateSubject = function (subject) {
    if (!confirm('Сделать копию предмета?')) {
      return false;
    }

    var newSubject = angular.copy(subject);

    this.model.subjects.push(newSubject);

    newSubject.id = 0;
    newSubject.i = this.model.subjects.length;
    newSubject.title = 'Дубль: ' + newSubject.title;

    angular.forEach(newSubject.elements, function (element) {
      element.id = 0;
    });

    console.log(newSubject);

    this.calculateModel();
  };

  /**
   * Удаление элемента предмета мебели
   *
   * @param subject Предмет мебели
   * @param element Элемент мебели
   * @returns {boolean}
   */
  CalcController.prototype.removeElement = function (subject, element) {
    if (!confirm('Удалить элемент?')) {
      return false;
    }

    subject.elements.splice(subject.elements.indexOf(element), 1);

    return true;
  };

  /**
   * Добавление нового предмета мебели и заполнение данными
   *
   * @param data
   */
  CalcController.prototype.addSubject = function (data) {
    var self = this;
    var model = this.model;

    data = data || {};

    if (model.subjects.length == 0) model.index = 0;

    var subject = {
      id: data.id || 0,
      i: ++model.index,
      // Название предмета
      title: data.title,
      // title: data.title || 'Новый предмет',
      // Элементы
      elements: [],
      num: data.num || 1,
      // Размеры
      x: data.x || 0, y: data.y || 0, z: data.z || 0,
      // Стоимость изготовления элементов предмета
      cost_manufacturing: data.cost_manufacturing || 0,
      // Стоимость конструкторской работы
      cost_construct: data.cost_construct || 0,
      // Стоимость сборки предмета
      cost_assembly: data.cost_assembly || 0,
      // Общая стоимость предмета
      cost_total: data.cost_total || 0,
      // Ставка конструктора
      constructor_rate: data.constructor_rate || 0,
      // Затраты
      outlay: data.outlay || 0,
      // Маржа
      margin: data.margin || 0,
      // Наценка / Скидка
      discount: data.discount || '',
      // Порядковый номер элемента предмета мебели [AUTO INCREMENT]
      index: 0,
      watcher: this.$scope.$watch(function () {
        return subject
      }, function () {
        self.calculateSubject(subject);
        self.calculateModel(model);
      }, true)
    };

    if (data.elements) {
      angular.forEach(data.elements, function (element) {
        self.addElement(subject, element);
      });
    } else {
      this.addElement(subject);
    }

    model.subjects.push(subject);
  };

  /**
   * Добавление нового элемента предмета мебели и заполение данными
   *
   * @param subject Предмет мебели
   * @param data
   */
  CalcController.prototype.addElement = function (subject, data) {
    data = data || {};
    if (!subject.elements.length) subject.index = 0;

    var element = {
      // ID элемента
      id: data.id || 0,
      // Порядковый номер
      i: ++subject.index,
      // Название элемента
      title: data.title || 'Новый элемент',
      // Признак
      character: data.character || 0,
      // Размеры
      x: data.x || 0, y: data.y || 0, z: data.z || 0,
      // Материал / Комплектующее
      part: data.part || 0,
      // Кол-во
      total: data.total || 0,
      // Цена за ед. изм. с учетом надбавки
      price: data.price || 0,
      // Стоимость = Цена * Кол-во
      sum: data.sum || 0
    };

    subject.elements.push(element);
  };

  CalcController.prototype.calculateSubjectSum = function (subject) {
    return parseFloat(subject.discount || 0) + subject.cost_total * this.model.additional_coefficient.value;
  };

  /**
   * Вычисление всех данных предмета мебели при его изменении или изменении его элементов
   *
   * @param subject
   */
  CalcController.prototype.calculateSubject = function (subject) {
    var self = this;

    subject.cost_manufacturing = 0;
    subject.cost_construct = 0;
    subject.cost_construct = 0;
    subject.cost_assembly = 0;

    // Объем предмета
    var volume = this.calculateVol(subject);
    // Кол-во элементов
    var elements = this.calculateElements(subject);

    // Вычисляем стоимость изготовления
    angular.forEach(subject.elements, function (el) {
      subject.cost_manufacturing += el.sum;
    });

    // Вычисляем стоимость конструкторской работы
    /*if (subject.constructor_rate) {
      subject.cost_construct += subject.constructor_rate.rate * elements;
    }*/

    // вычисляем стоимость сборки
    /*angular.forEach(this.coefficients, function (coef) {
      if (coef.range_start <= elements && coef.range_end >= elements) {
        subject.cost_assembly = volume * self.variables.base_price.value * (1 + coef.value / 100);
        return false;
      }
    });*/

    // Вычисляем общую стоимость
    subject.cost_total = subject.cost_manufacturing + subject.cost_construct + subject.cost_assembly;
    // Вычисляем затраты
    subject.outlay = subject.cost_total - (subject.cost_manufacturing - subject.cost_manufacturing / (1 + self.variables.margin.value / 100));
    // Вычисляем маржу
    subject.margin = subject.cost_total - subject.outlay;
  };

  /**
   * Вычисление всех вычисляемых данных расчета при изменении предметов или их элементов
   */
  CalcController.prototype.calculateModel = function () {
    var self = this;
    var model = this.model;

    // Стоимость изготовления
    model.cost_manufacturing = 0;
    // Стоимость конструкторской работы
    model.cost_construct = 0;
    // Стоимость сборки
    model.cost_assembly = 0;
    // Затраты
    model.outlay = 0;
    // Сумма наценок / скидок по всем предметам
    model.discounts = 0;

    angular.forEach(model.subjects, function (subject) {
      model.cost_manufacturing += subject.cost_manufacturing * subject.num;
      model.cost_construct += subject.cost_construct * subject.num;
      model.cost_assembly += subject.cost_assembly * subject.num;
      model.outlay += subject.outlay * subject.num;
      model.discounts += parseFloat(subject.discount || 0);
    });

    // Общая стоимость изделия
    model.cost_total = model.cost_manufacturing + model.cost_construct + model.cost_assembly;
    // Маржа
    model.margin = model.cost_total - model.outlay;
    // Максимально допустимая скидка
    model.discount = model.margin * self.variables.discount.value / 100;
  };

  /**
   * Вычисление кол-ва предметов
   */
  CalcController.prototype.subjectsNum = function () {
    var num = 0;
    angular.forEach(this.model.subjects, function (subject) {
      num += parseInt(subject.num) || 0;
    });

    return num;
  };

  /**
   * Отправка расчета на сервер
   *
   * @returns {boolean}
   */
  CalcController.prototype.saveModel = function () {
    var self = this;
    var model = this.model;

    if (!this.validateModel()) {
      return false;
    }
    this.$http({
      method: model.exists ? 'PUT' : 'POST',
      data: model,
      url: model.exists ? '/shaters/api/calculations/' + model.id : '/shaters/api/calculations'
    }).success(function (response) {
      self.notify.notice(response.message, false, response.error ? 'error' : 'success');

      if (response.redirect) {
        window.location = response.redirect;
      }
    });
  };

  /**
   * Клонирование расчета со всеми предметами и элементами
   */
  CalcController.prototype.cloneModel = function () {
    var self = this;
    var model = this.model;

    this.notify.confirm('Копирование расчета', 'Расчет будет скопирован без файлов клиента и установлен статус "Черновик"<br/><br/><div style="text-align: center">Продолжить?</div>', function (r) {
      if (!r) return false;

      self.$http({url: '/shaters/api/calculations/duplicate/' + model.id, method: 'GET'})
        .success(function (response) {
          self.notify.notice(response.message, false, response.error ? 'error' : 'success');

          if (response.redirect) {
            window.location = response.redirect;
          }
        });
    });


    return true;
  };

  /**
   * Проверка возможности редактирования расчета
   */
  CalcController.prototype.canEdit = function () {
    return this.model.status > 0 && this.model.status < 6
  };

  /**
   * Проверка возможности редактирования расчета
   */
  CalcController.prototype.editable = function () {
    return this.model.editable
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

  CalcController.prototype.addFile = function (file) {
    this.model.files.push({
      id: file.id,
      src: file.src,
      name: file.name
    });
  };

  CalcController.$inject = ['$scope', '$http', 'FileUploader', 'Notify'];

  angular
    .module('Calc')
    .controller('ShatersCalcCtrl', CalcController);

})();
