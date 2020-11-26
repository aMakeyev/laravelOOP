(function () {
  function CalcFactory() {
    var _this = this;
    this.model = {};

    this.removeSubject = function (subject) {
      if (!confirm('Удалить предмет?')) {
        return false;
      }

      subject.watcher();

      this.model.subjects.splice(this.model.subjects.indexOf(subject), 1);

      this.calculateModel();
    };


    this.duplicateSubject = function (subject) {
      if (!confirm('Сделать копию предмета?')) {
        return false;
      }

      this.model.subjects.push(angular.copy(subject));

      this.calculateModel();
    };

    this.removeElement = function (element, subject) {

    };

    this.cloneCalc = function () {

    };

    this.makeOrder = function () {

    };

    this.saveCalc = function () {

    };

    this.fillCalc = function (data) {
      model.title = data.title || 'Новый комплект'; // Название комплекта
      model.created_at = data.created_at || (new Date()).format('yyyy-mm-dd H:M');
      model.client = data.client || ''; // Заказчик
      model.status = data.status || 0; // Заказчик
      model.subjects = []; // Предметы, subjects[index].elements - Элементы
      model.cost = {
        manufacturing: 0, // Стоимость изготовления
        construct: 0, // Стоимость конструкторской работы
        assembly: 0, // Стоимость сборки
        total: 0 // Общая стоимость комплекта
      };
      model.coefficient = 0; // Поправочный коэффициент
      model.discount = 0; // Максимально допустимая скидка
      model.outlay = 0; // Затраты
      model.margin = 0; // Маржа
      model.index = 0;
    };

    this.validate = function () {
      var errors = [];
      var model = this.model;

      if (!model.title) {
        errors.push('Введите название комплекта')
      }

      if (!model.client || !model.client.id) {
        errors.push('Выберите заказчика')
      }

      if (!model.status) {
        errors.push('Выберите статус расчета')
      }

      if (!model.subjects.length) {
        errors.push('Добавьте хотя бы один предмет мебели')
      }

      angular.forEach(model.subjects, function (subject) {
        if (!subject.constructor_rate || !subject.constructor_rate.id) {
          this.push('Выберите ставку конструктора у Предмета № ' + subject.id)
        }

        if (!subject.x || !subject.y || !subject.z) {
          this.push('Укажите размеры Предмета № ' + subject.id)
        }

        if (!subject.elements.length) {
          errors.push('Добавьте хотя бы один эелемент к Предмету №' + subject.id)
        } else {
          angular.forEach(subject.elements, function (el) {
            if (el.count < 1) {
              this.push('Укажите кол-во у элемента № ' + el.id + ' у Предмета № ' + subject.id)
            }
          }, this);
        }
      }, errors);

      if (errors.length > 0) {
        alert(errors.join('\n'));
        errors = [];
        return false;
      }

      return true;
    };

    this.getModel = function (id) {
      return $http.get('/api/calculation/' + id)
        .success(function (data) {
          _this.calc = data;
        })
        .error(function () {
          //NotificationFactory.showError();
        });
    };
  }

  angular
    .module('calc')
    .factory('CalcFactory', CalcFactory);
})();
