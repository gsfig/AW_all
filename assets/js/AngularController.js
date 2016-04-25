

'use strict';

/*var app = angular.module('awApp',
    ['ngSanitize'


    ]);*/
// new controller
var docControllers = angular.module('docControllers', []);
docControllers.controller('DocumentJSController', ['$scope', '$http', function($scope, $http){
    // initial function
    // WEBSERVICE to /document
    // document is available in $scope ( in the tags that have 'data-ng-controller="documentJSController')

    // TODO: Alterar esta forma de chamar webservice, ver como está na funcao getPaper
    $http.get(window.location + 'document').success(function(data){
        $scope.document = data;
    });
    // window.alert($scope.document.payload[0].title);

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
    $scope.getPaper = function(id){
        // window.alert($scope.document.payload[0].title);
        // if $scope.document has id, just return
        var foundID = false;
        // window.alert($scope.document.payload[0].title);
        for (var i = 0, len = $scope.document.payload.length; i < len; i++) {
            if ($scope.document.payload[i].idNCBI === id) {
                $scope.title = $scope.document.payload[i].title;
                $scope.abstract = $scope.document.payload[i].abstract;
                foundID = true;
                break;
            }
        }
        // window.alert($scope.document.payload[0].title);
        if(!foundID){

            // WEBSERVICE to /document/:id
            // document_requested is available in $scope ( in the tags that have 'data-ng-controller="documentJSController')
            $http({
                url: window.location + 'document/',
                method: "GET",
                params: {id: id}
            }).then(function successCallback(response) {
                $scope.document_requested = response.data;
                $scope.showAbstract($scope.document_requested.payload.title, $scope.document_requested.payload.abstract );
            }, function errorCallback(response) {
                // called asynchronously if an error occurs
                // or server returns response with an error status.
            });
        }
    };



}]); //end documentJSController


