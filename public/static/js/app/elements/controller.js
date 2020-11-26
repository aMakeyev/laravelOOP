(function () {

  'use strict';

  function isArray(array) {
    return Object.prototype.toString.call(array) === '[object Array]';
  }

  function isCategory(node) {
    return node.type && node.elements;
  }

  function isElement(node) {
    return node.category_id && !node.elements;
  }

  /**
   * @class ElementsController
   * @classdesc Elements Controller
   * @ngInject
   */
  function ElementsController($scope, $http, Notify, $tree) {
    var self = this;

    this.$scope = $scope;
    this.$http = $http;
    this.$tree = $tree;
    this.notify = Notify;

    this.tree = [];

    this.treeOptions = {
      accept: function (source, dest, index) {
        var node = source.$modelValue;

        if (!node.id) {
          return false;
        }

        if (isElement(node)) {
          return dest.$modelValue !== self.tree;
        }

        if (isCategory(node)) {
          return dest.$modelValue === self.tree;
        }
      },
      dropped: function (e) {
        if (!e.source.nodeScope.$modelValue.id) {
          Notify.notice('Сохраните изменения перед сортировкой новых категорий / элементов');
          return;
        }

        self.save();
      }
    };

    this.saveNode = function (node) {
      node.editing = false;

      $tree[isCategory(node) ? 'saveCategory' : 'saveElement'](node, function (data) {
        if (!data.success && data.error) {
          setTimeout(function () {
            window.location.reload();
          }, 1000);
        }

        if (node.isNew) {
          self.loadTree();
        }

        Notify.success(data.message);
      });
    };

    this.nodeEdit = function (node) {
      self.tree.map(function (n) {
        self.cancelEditNode(n);

        n.elements.map(function (e) {
          self.cancelEditNode(e)
        });
      });

      node.editing = true;
    };

    this.removeCategoryFromTree = function (category) {
      self.tree.splice(self.tree.indexOf(category), 1);
    };

    this.removeElementFromCategory = function (element, category) {
      category.elements.splice(category.elements.indexOf(category), 1);
    };

    this.cancelEditNode = function (node, parent) {
      if (isCategory(node)) {
        if (node.isNew) {
          self.removeCategoryFromTree(node);

          return;
        }
      }

      if (isElement(node)) {
        if (node.isNew) {
          self.removeElementFromCategory(node, parent);

          return;
        }
      }

      node.editing = false;
    };

    this.deleteCategory = function (category) {
      if (!confirm('Удалить категорию со всеми элементами?')) return;

      if (!category.id && !category.elements.length) {
        self.removeCategoryFromTree(category);
        return;
      }

      self.$tree.deleteCategory(category, function (response) {
        if (response.success && !response.error) {
          Notify.success(response.message);

          self.removeCategoryFromTree(category);
        } else {
          Notify.error(response.message);
        }
      });
    };

    this.addCategory = function () {
      for (var i = 0; i < self.tree.length; i++) {
        if (self.tree[i].editing) {
          Notify.error('Закончите редактирование категории');
          return;
        }

        for (var e = 0; e < self.tree[i].elements.length; e++) {
          if (self.tree[i].elements[e].editing) {
            Notify.error('Закончите редактирование элемента');
            return;
          }
        }
      }

      var category = {
        id: null,
        title: 'Новая категория ' + (self.tree.length + 1),
        type: 1,
        sort: self.tree.length + 1,
        elements: [],
        isNew: true,
        editing: true
      };

      self.tree.map(function (n) {
        n.editing && self.saveNode(n);
      });

      self.tree.push(category);
    };

    this.addElement = function (category) {
      var element = {
        id: null,
        title: 'Новый элемент ' + (category.elements.length + 1),
        category_id: category.id || null,
        sort: category.elements.length + 1,
        isNew: true,
        editing: true
      };

      category.elements.map(function (e) {
        e.editing && self.saveNode(e);
      });

      category.elements.push(element);
    };

    this.deleteElement = function (category, element) {
      if (!confirm('Удалить элемент?')) return false;

      if (!element.id) {
        self.removeElementFromCategory(element, category);

        return false;
      }

      self.$tree.deleteElement(element, function (response) {
        if (response.success && !response.error) {
          Notify.success(response.message);

          self.removeElementFromCategory(element, category);
        } else {
          Notify.error(response.message);
        }
      });
    };

    this.loadTree();
  }

  ElementsController.prototype.loadTree = function () {
    var self = this;

    this.$tree.load(function (data) {
      self.tree = data;
    });
  };

  /**
   * Сохранение
   *
   * @returns {boolean}
   */
  ElementsController.prototype.save = function () {
    var self = this;
    this.$tree.save(this.tree, function (data) {
      if (data.success && !data.error) {
        self.notify.success(data.message);

        self.tree = data.data;
      }
    });
  };

  ElementsController.$inject = ['$scope', '$http', 'Notify', 'tree'];

  angular
    .module('Elements')
    .controller('ElementsCtrl', ElementsController);

})();
