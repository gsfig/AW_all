'use strict';

app.controller('UserController',['$scope','AuthenticationService', function($scope, AuthenticationService){

    $scope.username = AuthenticationService.user();




}]);
 