'use strict';
app.controller('ComponentController', ['apiBaseUrl', '$scope', '$http', function(apiBaseUrl, $scope, $http){


    $scope.getDbpedia = function(chemcompound) {
        $http({
            url: apiBaseUrl + '/compound/cheminfo/',
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
            url: apiBaseUrl + '/compound/',
            method: "GET",
            params: {id: chebiID}
        }).then(function successCallback(response) {
            console.log(response.data.payload);
            $scope.chebi = response.data.payload;


        }, function errorCallback(response) {
            console.log("error Chebi");
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