(function () {
  function config($httpProvider, $interpolateProvider) {
    $httpProvider
      .defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    $interpolateProvider
      .startSymbol('[[').endSymbol(']]');
  }

  config.$inject = ['$httpProvider', '$interpolateProvider'];

  angular
    .module('Calc', ['ui.select2', 'angularFileUpload', 'notifyService'])
    .config(config);
})();

/*
$(window).on('load resize', function() {
	if ($(window).width() < 415) {
		// $('.select2-choice').select2('destroy');
		// $element.data('select2').destroy();
		$('.form-control.datepicker').focus(function() {
			this.blur();
			alert('hi');
		});
	}
});*/
$("#client_modal").on("hidden.bs.modal", function () {
	$ ('.modal-backdrop.in').remove();
});
if ($(window).width() < 415) {
	$('.datepicker').focus(function() {
		this.blur();
	});
	$('.datepick').focus(function() {
		this.blur();
	});
}
