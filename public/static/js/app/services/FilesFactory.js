(function () {
  var options = {
    url: ''
  };

  function FilesFactory(filesOptions, $http, FileUploader, Notify) {
    var self = this;

    this.$http = $http;
    this.notify = Notify;
    this.uploader = new FileUploader({
      url: '/api/files/upload/' + filesOptions.url,
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

    this.removeFile = function (id) {
      if (!confirm('Удалить файл навсегда?')) {
        return false;
      }
    };
  }

  FilesFactory.$inject = ['filesOptions', '$http', 'FileUploader', 'Notify'];

  angular
    .module('calc')
    .factory('FilesFactory', FilesFactory)
    .value('filesOptions', options);
})();
