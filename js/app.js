'use strict';
var app = angular.module('awApp', [
    // 'ngRoute',
    'ngSanitize',
    'ui.router',
    'ui.bootstrap'
    // 'AuthenticationService'
    //  'LoginController'
    // 'navControllers'
    // 'docControllers'
]);
var baseurl = 'http://' + window.location.hostname + "/AW_all";
app.constant('apiBaseUrl', baseurl);

// app.config(['$routeProvider',  function($routeProvider, $stateProvider, $urlRouterProvider) {
app.config( function($stateProvider, $urlRouterProvider) {
    // For any unmatched url, redirect to /
    $urlRouterProvider.otherwise("/home");

    // Now set up the states
    $stateProvider
        .state('home', {
            url: '/home',
            views: {
                '' : {templateUrl: 'partials/home.html'},
                'header@home' : {templateUrl: 'partials/header.html', controller: 'NavBarController'},
                'title@home' : {templateUrl: 'partials/idToAnnotate.html', controller: 'DocumentJSController'},
                'freeText@home' : {templateUrl: "partials/freeTextToAnnotate.html"},
                'relatedPaper@home' : {templateUrl: "partials/NCBIrelatedPapers.html"},
                'query@home' : {templateUrl: "partials/queryNCBI.html"}
            }
        })
        .state('compound', {
            url: '/compound',
            views: {
                '' : {templateUrl: 'partials/compound.html'},
                'header@compound' : {templateUrl: 'partials/header.html', controller: 'NavBarController'},
                'compoundMain@compound' : {templateUrl: "partials/compoundMain.html"},
                'compoundPathway@compound' : {templateUrl: "partials/compoundPathway.html"},
                'compoundOntology@compound' : {templateUrl: "partials/compoundOntology.html"}
            }
        })
/*        .state('login', {
        url: '/login',
        views: {
            '' : {templateUrl: 'partials/login.html', controller: "LoginController"},
            'header@login' : {templateUrl: 'partials/header.html', controller: 'NavBarController'}
        }
    })*/
    
    ;
/*    app.factory('AuthenticationService', function() {

        var token = undefined;

        token.addtoken = function (tokenIn) {
            token = tokenIn;
            window.alert(token);
        }

        token.deletetoken = function () {
            token = undefined;

        }
        token.check = function () {
            // TODO: talk to DB
        }
        return token;
    });*/









});

