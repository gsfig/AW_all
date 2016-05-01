'use strict';
var app = angular.module('awApp', [
    // 'ngRoute',
    'ngSanitize',
    'ui.router',
    'ui.bootstrap'
]);
var baseurl = 'http://' + window.location.hostname + "/AW_all";
app.constant('apiBaseUrl', baseurl);

app.config( function($stateProvider, $urlRouterProvider) {
    // For any unmatched url, redirect to /home
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
                'compoundChebi@compound' : {templateUrl: "partials/compoundChebi.html", controller: 'ComponentController'},
                'compoundMain@compound' : {templateUrl: "partials/compoundMain.html", controller: 'ComponentController'},
                'compoundPathway@compound' : {templateUrl: "partials/compoundPathway.html"},
                'compoundOntology@compound' : {templateUrl: "partials/compoundOntology.html"}
            }
        });

});

