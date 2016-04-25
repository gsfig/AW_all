

'use strict';

var app = angular.module('headerApp',
    ['ngSanitize',

        'awApp'


    ]);
// new controller

app.controller('MenuController', ['$scope', '$http', function($scope, $http){
    // initial function
    $scope.isUserLoggedIn = false;

    // window.alert($scope.document.payload[0].title);




}]); //end documentJSController



