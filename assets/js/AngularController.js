var aw_module = angular.module('aw.controllers', ['ngSanitize']);

// new controller
aw_module.controller('documentJSController', ['$scope', '$http', function($scope, $http){

    
    // initial function
    // WEBSERVICE to /document
    // document is available in $scope ( in the tags that have 'data-ng-controller="documentJSController')
    $http.get(window.location + 'document').success(function(data){
        $scope.document = data;
    });
    $scope.abstract = null;

    // function
    // shows abstract and title
    $scope.showAbstract = function(title, abstract) {
        $scope.title = title;
        $scope.abstract = abstract;
    };

    // function
    // sets abstract and title to null so that it doesn't show in view if text in searchbox changes
    $scope.dataChanged = function() {
        if($scope.title != null){
            $scope.title = null;
            $scope.abstract = null;
        }
    };





}]); //end documentJSController


