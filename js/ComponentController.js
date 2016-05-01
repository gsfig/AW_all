'use strict';
app.controller('ComponentController', ['apiBaseUrl', '$scope', '$http', function(apiBaseUrl, $scope, $http){


    $scope.getDbpedia = function(chemcompound) {
        $http({
            url: apiBaseUrl + '/cheminfo/',
            method: "GET",
            params: {name: chemcompound}
        }).then(function successCallback(response) {
            $scope.dbpedia = response.data.payload;
            $scope.dbpedia.vars = response.data.payload.head.vars;
            console.log($scope.dbpedia.vars);
            $scope.dbpedia.bindings = response.data.payload.results.bindings;
            console.log($scope.dbpedia.bindings);
        }, function errorCallback(response) {
            // called asynchronously if an error occurs
            // or server returns response with an error status.
        });
    };
    $scope.getChebi = function(chebiID) {
        $http({
            url: apiBaseUrl + '/compounds/',
            method: "GET",
            params: {id: chebiID}
        }).then(function successCallback(response) {
            $scope.chebi = response.data;

        }, function errorCallback(response) {
            // called asynchronously if an error occurs
            // or server returns response with an error status.
        });
    };

    $scope.testForArray = function(myObj){
        if(angular.isArray(myObj)){
            return true
        }else{
            return false;
        }

    }



}]);