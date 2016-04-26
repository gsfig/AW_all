'use strict';

// new controller
app.controller('NavBarController', ['$scope', 'AuthenticationService', function($scope, AuthenticationService){

    $scope.useractive = function() {
        if (AuthenticationService.check()){
            $scope.isUserLoggedIn = true;
            $scope.username = AuthenticationService.user();
            return true;
        }
        else{
            $scope.isUserLoggedIn = false;
            return false;
        }
    };
    $scope.logout = function() {
        AuthenticationService.delete();
    };







    // só links ?





}]); //end NavBarController







