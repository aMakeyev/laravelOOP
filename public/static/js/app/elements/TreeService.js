(function () {
  angular
    .module('Elements')
    .service('tree', function ($http) {
      return {
        load: function (callback) {
          return $http.get('api/elements').then(function (response) {
            return response.data.success ? callback(response.data.data) : [];
          });
        },
        deleteCategory: function (node, callback) {
          return $http.delete('api/elements/category/' + node.id).then(function (response) {
            return callback ? callback(response.data) : response;
          });
        },
        deleteElement: function (node, callback) {
          return $http.delete('api/elements/element/' + node.id).then(function (response) {
            return callback ? callback(response.data) : response;
          });
        },
        saveCategory: function (category, callback) {
          return $http.post('api/elements/category', category).then(function (response) {
            return callback ? callback(response.data) : response;
          });
        },
        saveElement: function (element, callback) {
          return $http.post('api/elements/element', element).then(function (response) {
            return callback ? callback(response.data) : response;
          });
        },
        save: function (tree, callback) {
          return $http.post('api/elements', tree).then(function (response) {
            return callback ? callback(response.data) : response;
          });
        }
      }
    });
})();
