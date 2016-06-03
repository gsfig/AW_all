'use strict';

app.controller('UserController',['$scope','AuthenticationService','$http','apiBaseUrl', function($scope, AuthenticationService, $http,apiBaseUrl){

    $scope.username = AuthenticationService.user();
    
    $scope.deleteDatabase = function(){

        $http({
            url: apiBaseUrl + '/admin/delete',
            method: "POST",
            data: {
                username: $scope.username
            },
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function successCallback(response) {
            alert("database deleted");
        }, function errorCallback(response) {
            console.error("error deleting DB: " + response);
            // called asynchronously if an error occurs
            // or server returns response with an error status.
        });
    }




}]);
 