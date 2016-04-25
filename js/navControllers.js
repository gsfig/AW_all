'use strict';

// new controller
app.controller('NavBarController', ['$scope', 'AuthenticationService', function($scope, AuthenticationService){

    if (AuthenticationService.check()){
        $scope.isUserLoggedIn = true;
        $scope.username = AuthenticationService.user();
    }
    else{
        $scope.isUserLoggedIn = false;
    }




    // só links ?





}]); //end NavBarController







