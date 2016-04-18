var aw_module = angular.module('aw.controllers', ['ngSanitize']);

// new controller
aw_module.controller('documentJSController', ['$scope', '$http', function($scope, $http){

    
    // initial function
    // WEBSERVICE to /document
    // document is available in $scope ( in the tags that have 'data-ng-controller="documentJSController')
    $http.get(window.location + 'document').success(function(data){
        $scope.document = data;
    });

    // function
    // shows abstract and title
    $scope.showAbstract = function(title, abstract) {
        $scope.title = title;
        $scope.abstract = abstract;
    };

    // function
    // sets abstract and title to null so that it doesn't show in view if text in searchbox changes
    $scope.dataChanged = function() {
        if($scope.title != null){
            $scope.title = null;
            $scope.abstract = null;
        }
    };
    $scope.getPaper = function(id){
        // window.alert($scope.document.payload[0].title);
        // if $scope.document has id, just return
        var foundID = false;
        for (var i = 0, len = $scope.document.payload.length; i < len; i++) {
            if ($scope.document.payload[i].idNCBI === id) {
                $scope.title = $scope.document.payload[i].title;
                $scope.abstract = $scope.document.payload[i].abstract;
                foundID = true;
                break;
            }
        }
        if(!foundID){

            // WEBSERVICE to /document/:id
            // document_requested is available in $scope ( in the tags that have 'data-ng-controller="documentJSController')
            $http.get(window.location + 'document/'+id).success(function(data){
                $scope.document_requested = data;
            });
            $scope.title = $scope.document_requested.payload[i].title;
            $scope.abstract = $scope.document_requested.payload[i].abstract;
            window.alert($scope.title);
            // TODO: for this to work, still have to deal with what backend does when id isn't in Database

        }


/*        angular.forEach($scope.document.payload, function(doc) {
            // window.alert(doc);


        });*/


        // if it doesn't request document





    };





}]); //end documentJSController


