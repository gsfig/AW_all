'use strict';

app.controller('NavBarController', ['$scope','$uibModal', 'AuthenticationService', function($scope, $uibModal, AuthenticationService){
    $scope.open = function () {
        // console.log('opening pop up');
        var modalInstance = $uibModal.open({
            templateUrl: 'partials/login.html',
            controller: 'LoginController'
        });
    }
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
        AuthenticationService.logout();
    };
 }]); //end NavBarController







