(function () {
  function roundPrice() {
    return priceFormat
  }

  function round() {
    return function (value) {
      return value ? Math.round(value * 1000) / 1000 : 0;
    }
  }

  function roundPriceZero() {
		return priceFormatZero
  }
  angular
    .module('Calc')
    .filter('roundPrice', roundPrice)
    .filter('round', round)
    .filter('roundPriceZero', roundPriceZero);

})();
