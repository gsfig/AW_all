'use strict';
app.controller('DocumentJSController', ['apiBaseUrl', '$scope', '$http', function(apiBaseUrl, $scope, $http){
    var idPaperInView = undefined;
    // WEBSERVICE to /document
    // document is available in $scope ( in the tags that have 'data-ng-controller="documentJSController')
    $http({
        url: apiBaseUrl + '/document/',
        method: "GET",
    }).then(function successCallback(response) {
        $scope.document = response.data;
    }, function errorCallback(response) {
        // called asynchronously if an error occurs
        // or server returns response with an error status.
    });
    // shows abstract and title
    $scope.showAbstract = function(title, abstract, idNCBI) {
        $scope.title = title;
        $scope.abstract = abstract;
        $scope.showAbstDiv = true;
        $scope.idNCBI = idNCBI;
        idPaperInView =idNCBI;
    };
    // sets abstract and title to null so that it doesn't show in view if text in searchbox changes
    $scope.dataChanged = function() {
        if($scope.title != null){
            $scope.title = null;
            $scope.abstract = null;
            $scope.showAbstDiv = false;
            idPaperInView = undefined;
        }
    };
    $scope.getPaper = function(id){
        // window.alert($scope.document.payload[0].title);
        // if $scope.document has id, just return
        var foundID = false;
        // window.alert($scope.document.payload[0].title);
        for (var i = 0, len = $scope.document.payload.length; i < len; i++) {
            if ($scope.document.payload[i].idNCBI === id) {
                $scope.showAbstract($scope.document.payload[i].title, $scope.document.payload[i].abstract, $scope.document.payload[i].idNCBI)
                foundID = true;
                break;
            }
        }
        // window.alert($scope.document.payload[0].title);
        if(!foundID){
            // WEBSERVICE to /document/:id
            // document_requested is available in $scope ( in the tags that have 'data-ng-controller="documentJSController')
            $http({
                url: apiBaseUrl + '/document/',
                method: "GET",
                params: {id: id}
            }).then(function successCallback(response) {
                $scope.document_requested = response.data;
                $scope.showAbstract($scope.document_requested.payload[0].title, $scope.document_requested.payload[0].abstract,$scope.document_requested.payload[i].idNCBI );

            }, function errorCallback(response) {
                // called asynchronously if an error occurs
                // or server returns response with an error status.
                console.log("error getpaper(): " + response);
            });
        }
    };
    $scope.annotatePaper = function(id){
        if(angular.isUndefined(id)){
            console.error("Paper id is undefined")
        }
        else{
            $http({
                method: "post",
                url: apiBaseUrl + "/document/annotation",
                data: {
                    idNCBI: id
                },
                headers: { 'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function successCallback(response) {
                $scope.ibent_annotation = response.data.payload;
                console.log($scope.ibent_annotation);
            }, function errorCallback(response) {
                // called asynchronously if an error occurs
                // or server returns response with an error status.
            });
                        
        }
    }
    $scope.annotateText = function(text){

        $http({
            method: "post",
            url: apiBaseUrl + "/ibent_annotate",
            data: {
                text: text
            },
            headers: { 'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function successCallback(response) {
            $scope.ibent_annotation = response.data.payload;
            console.log($scope.ibent_annotation);
        }, function errorCallback(response) {
            // called asynchronously if an error occurs
            // or server returns response with an error status.
        });

    }
}]); //end documentJSController





