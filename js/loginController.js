'use strict';

app.controller('LoginController',['$scope','$http','$uibModalInstance', 'apiBaseUrl','AuthenticationService', function($scope, $http, $uibModalInstance, apiBaseUrl, AuthenticationService){
    //Variables
    $scope.signUpInfo = {
        username: undefined,
        email: undefined,
        password: undefined
    }
    $scope.loginInfo = {
        username: undefined,
        password: undefined
    }
    $scope.signUserUp = function (){
        $http({
            method: "post",
            url: apiBaseUrl + "/user/signup",
            data: {
                username: $scope.signUpInfo.username,
                email: $scope.signUpInfo.email,
                password: $scope.signUpInfo.password
            },
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function successCallback(response) {
            AuthenticationService.add( JSON.stringify(response.data),$scope.signUpInfo.username );
        }, function errorCallback(response) {
            // called asynchronously if an error occurs
            // or server returns response with an error status.
        });
    };
    $scope.loginUser = function () {
        // window.alert(apiBaseUrl);
        $http({
            method: "post",
            url: apiBaseUrl + "/user/login",
            data: {
                username: $scope.loginInfo.username,
                password: $scope.loginInfo.password
            },
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function successCallback(response) {
            // console.log(response);
            // TODO: response vem em json do servidor com mais info do que necessario, verificar se response.data enviado ao servidor para autenticacao é o que servidor esta a espera na BD, coluna "Token"
            AuthenticationService.add( JSON.stringify(response.data),$scope.loginInfo.username );
            $uibModalInstance.close();
            // $uibModalInstance.close();
        }, function errorCallback(response) {
            // called asynchronously if an error occurs
            // or server returns response with an error status.
            console.error(error);
        });
    }
}]); //end NavBarController