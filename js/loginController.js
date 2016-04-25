'use strict';

// new controller
app.controller('LoginController',['$scope','$http','apiBaseUrl','AuthenticationService', function($scope, $http, apiBaseUrl, AuthenticationService){

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
    /*result.test = {
        test: "test",
        test2: "test2",
        test3: "testers"
    }

    result.test = JSON.stringify(result.test);*/

    $scope.signUserUp = function (){
        window.alert(apiBaseUrl);
        $http({
            method: "post",
            url: apiBaseUrl + "/signup",
            data: {
                username: $scope.signUpInfo.username,
                email: $scope.signUpInfo.email,
                password: $scope.signUpInfo.password
            },
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function successCallback(response) {
           // do stuff
        }, function errorCallback(response) {
            // called asynchronously if an error occurs
            // or server returns response with an error status.
        });


    };

    $scope.loginUser = function () {

        // window.alert(apiBaseUrl);
        $http({
            method: "post",
            url: apiBaseUrl + "/login",
            data: {
                username: $scope.loginInfo.username,
                password: $scope.loginInfo.password
            },
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function successCallback(response) {
            // console.log(response);
            // localStorage.setItem("token", JSON.stringify(response));
            AuthenticationService.add( JSON.stringify(response),$scope.loginInfo.username );
        }, function errorCallback(response) {
            // called asynchronously if an error occurs
            // or server returns response with an error status.
            console.error(error);
        });


    }





}]); //end NavBarController