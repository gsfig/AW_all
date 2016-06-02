'use strict';
var app = angular.module('awApp', [
    // 'ngRoute',
    'ngSanitize',
    'ui.router',
    'ui.bootstrap',
    'nsPopover'
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
                'compound@home' : {templateUrl: 'partials/idToSearch.html', controller: 'ComponentController'},
                'user@home' : {templateUrl: 'partials/userToLogin.html', controller: 'NavBarController'},

                // 'relatedPaper@home' : {templateUrl: "partials/NCBIrelatedPapers.html"},
                // 'query@home' : {templateUrl: "partials/queryNCBI.html"}
            }
        })
        .state('annotation', {
            url: '/annotation',
            views: {
                '' : {templateUrl: 'partials/annotation.html'},
                'header@annotation' : {templateUrl: 'partials/headerSec.html', controller: 'NavBarController'},
                'annotated@annotation' : {templateUrl: "partials/AnnotatedText.html", controller: 'AbstractController'}
            }
        })

        .state('compound', {
            url: '/compound',
            views: {
                '' : {templateUrl: 'partials/compound.html'},
                'header@compound' : {templateUrl: 'partials/header.html', controller: 'NavBarController'},
                'compoundChebi@compound' : {templateUrl: "partials/compoundChebi.html", controller: 'ComponentController'},
                // 'compoundMain@compound' : {templateUrl: "partials/compoundMain.html", controller: 'ComponentController'},
                // 'compoundPathway@compound' : {templateUrl: "partials/compoundPathway.html"},
                // 'compoundOntology@compound' : {templateUrl: "partials/compoundOntology.html"}
            }
        })
        .state('user', {
            url: '/user',
            views: {
                '' : {templateUrl: 'partials/user.html'},
                'header@user' : {templateUrl: 'partials/headerSec.html', controller: 'NavBarController'},
                'page@user' : {templateUrl: "partials/userPage.html", controller: 'UserController'},
                'd3@user' : {templateUrl: "partials/d3User.html"},

            }
        })





    ;

});

