(function () {
  function config($httpProvider, $interpolateProvider) {
    $httpProvider
      .defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    $interpolateProvider
      .startSymbol('[[').endSymbol(']]');
  }

  config.$inject = ['$httpProvider', '$interpolateProvider'];

  angular
    .module('Elements', ['ui.tree', 'notifyService'])
    .config(config)
    .directive('ngEnter', function () {
      return function (scope, element, attrs) {
        element.bind('keydown keypress', function (event) {
          if (event.which === 13) {
            scope.$apply(function () {
              scope.$eval(attrs.ngEnter);
            });

            event.preventDefault();
          }
        });
      };
    })
    .directive('ngEsc', function () {
      return function (scope, element, attrs) {
        element.bind('keydown keypress', function (event) {
          if (event.which === 27) {
            scope.$apply(function () {
              scope.$eval(attrs.ngEsc);
            });

            event.preventDefault();
          }
        });
      };
    });
})();
