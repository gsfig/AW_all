'use strict';
app.controller('DocumentJSController', ['apiBaseUrl',  'AbstractService', '$timeout','$scope', '$http','$state', 'nsPopover', function(apiBaseUrl,AbstractService, $timeout, $scope, $http, $state ){
    var inView = undefined;
    var sel = undefined;
    var range = undefined;
    var selectionStart = undefined;
    var selectionEnd = undefined ;

    var ranges = undefined;
    var mainRange = undefined;
    $scope.annotationsInClick = AbstractService.getannotationsInClick;
    $scope.showButton = false;

    // WEBSERVICE to /document
    // document is available in $scope ( in the tags that have 'data-ng-controller="documentJSController')

    getDocuments();

    function getDocuments(){
        $http({
            url: apiBaseUrl + '/document/',
            method: "GET"
        }).then(function successCallback(response) {

            AbstractService.setdocument(response.data);
            $scope.document = AbstractService.get_document();
            // $scope.document = response.data;
        }, function errorCallback(response) {
            // called asynchronously if an error occurs
            // or server returns response with an error status.
        });

    }


    // shows abstract and title
    $scope.showAbstract = function(title, abstract, idNCBI) {
        AbstractService.settitle(title);
        $scope.title = AbstractService.get_title();
        AbstractService.setabstract(abstract);
        $scope.abstract = AbstractService.getabstract();
        AbstractService.setshowAbstDiv(true);
        $scope.showAbstDiv = AbstractService.getshowAbstDiv();
        AbstractService.setshowButton(true);
        $scope.showButton = AbstractService.getshowButton();
        AbstractService.setidNCBI(idNCBI);
        $scope.idNCBI = AbstractService.getidNCBI();
        angular.isUndefined(idNCBI) ?  AbstractService.setinView(abstract) : AbstractService.setinView(idNCBI)
        inView = AbstractService.getinView();




    };
    // sets abstract and title to null so that it doesn't show in view if text in searchbox changes
    $scope.dataChanged = function() {
        if($scope.title != null){
            AbstractService.settitle(null);
            $scope.title = AbstractService.get_title();
            AbstractService.setabstract(null);
            $scope.abstract = AbstractService.getabstract();
            AbstractService.setshowAbstDiv(false);
            $scope.showAbstDiv = AbstractService.getshowAbstDiv();
            AbstractService.setinView(undefined);
            inView = AbstractService.getinView();
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
            var urlcomplete = apiBaseUrl + '/document/' + '?id=' + id;
            $http({
                // url: apiBaseUrl + '/document/',
                url : urlcomplete,
                method: "GET"
                // params: {id: id}
            }).then(function successCallback(response) {
                AbstractService.setdocument_requested(response.data);
                $scope.document_requested = AbstractService.getdocument_requested();
                $scope.showAbstract($scope.document_requested.payload[0].title, $scope.document_requested.payload[0].abstract,$scope.document_requested.payload[0].idNCBI );
                $timeout(function(){
                    $scope.$apply(function(){
                        getDocuments();
                    })
                });
            }, function errorCallback(response) {
                // called asynchronously if an error occurs
                // or server returns response with an error status.
                console.log("error getting paper: " + response);
            });
        }
    };
    
    $scope.freeTextAnnotate = function(toAnnotate){
        $scope.showAbstract("Free Text", toAnnotate, null)
        AbstractService.setinView(toAnnotate)

    };
    
    



}]); //end documentJSController
