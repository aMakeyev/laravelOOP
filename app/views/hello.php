<!DOCTYPE html>
<html ng-app="myApp">
<head lang="en">
    <meta charset="utf-8">
    <title>Custom Plunker</title>
    <link rel="stylesheet" type="text/css" href="http://angular-ui.github.com/ng-grid/css/ng-grid.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.19/angular.min.js"></script>
    <script type="text/javascript" src="http://angular-ui.github.com/ng-grid/lib/ng-grid.debug.js"></script>
    <style>
    .gridStyle {
        border: 1px solid rgb(212,212,212);
        width: 600px;
        height: 300px;
    }
    </style>
</head>
<body ng-controller="MyCtrl">
<div class="gridStyle" ng-grid="gridOptions"></div>
<script>
var app = angular.module('myApp', ['ngGrid']);
app.controller('MyCtrl', function ($scope, $http) {
    $scope.filterOptions = {
        filterText: "",
        useExternalFilter: true
    };
    $scope.totalServerItems = 0;
    $scope.pagingOptions = {
        pageSizes: [1, 5, 10, 20],
        pageSize: 5,
        currentPage: 1
    };
    $scope.setPagingData = function (data, total, page, pageSize) {
        var pagedData = data.slice((page - 1) * pageSize, page * pageSize);
        $scope.myData = pagedData;
        $scope.totalServerItems = total;
        if (!$scope.$$phase) {
            $scope.$apply();
        }
    };
    $scope.getPagedDataAsync = function (pageSize, page, searchText) {
        setTimeout(function () {
            var data;
            /*if (searchText) {
                var ft = searchText.toLowerCase();
                $http.get('/api/managers').success(function (response) {
                    data = response.filter(function(item) {
                        return JSON.stringify(item).toLowerCase().indexOf(ft) != -1;
                    });
                    $scope.setPagingData(data,page,pageSize);
                });
            } else {*/
                $http.get('/api/managers', {page: page}).success(function (response) {
                    $scope.setPagingData(response.rows, response.total, page, pageSize);
                });
            // }
        }, 100);
    };

    $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);

    $scope.$watch('pagingOptions', function (newVal, oldVal) {
        if (newVal !== oldVal && newVal.currentPage !== oldVal.currentPage) {
          $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
        }
    }, true);
    $scope.$watch('filterOptions', function (newVal, oldVal) {
        if (newVal !== oldVal) {
          $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
        }
    }, true);

    $scope.gridOptions = {
        data: 'myData',
        enablePaging: true,
        showFooter: true,
        totalServerItems:'totalServerItems',
        pagingOptions: $scope.pagingOptions,
        filterOptions: $scope.filterOptions
    };
});
</script>
</body>
</html>