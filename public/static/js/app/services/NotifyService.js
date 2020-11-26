(function (messanger) {

  function NotifyService() {
    this.messanger = messanger;

    this.getMessanger = function () {
      return this.messanger;
    }
  }

  NotifyService.prototype.confirm = function (title, message, callback) {
    this.messanger.confirm(title, message, callback);
  };

  NotifyService.prototype.notice = Notify.notice;
  NotifyService.prototype.error = Notify.error;
  NotifyService.prototype.success = Notify.success;

  angular
    .module('notifyService', [])
    .service('Notify', NotifyService);

})($.messager);
