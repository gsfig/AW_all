'use strict';
app.controller('DocumentJSController', ['apiBaseUrl',  '$timeout','$scope', '$http','$state', function(apiBaseUrl,$timeout, $scope, $http, $state ){
    var idPaperInView = undefined;
    var sel = undefined;
    var range = undefined;
    var textRange = undefined;

    // WEBSERVICE to /document
    // document is available in $scope ( in the tags that have 'data-ng-controller="documentJSController')
    $http({
        url: apiBaseUrl + '/document/',
        method: "GET"
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
        idPaperInView = idNCBI;
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
            var urlcomplete = apiBaseUrl + '/document/' + '?id=' + id;
            $http({
                // url: apiBaseUrl + '/document/',
                url : urlcomplete,
                method: "GET"
                // params: {id: id}
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
            var urlComplete = apiBaseUrl + "/document/" + id + "/annotation" ;
            $http({
                method: "GET",
                // url: apiBaseUrl + "/document/annotation",
                url: urlComplete
                // data: {
                //     idNCBI: id
                //     // add user
                // },
                // headers: { 'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function successCallback(response) {
                $scope.ibent_annotation = response.data.payload;
                console.log(ibent_annotation);
            }, function errorCallback(response) {
                // called asynchronously if an error occurs
                // or server returns response with an error status.
            });
                        
        }
    };
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

            // console.log($scope.ibent_annotation);
            $scope.dataChanged();
            $scope.showAbstract("Free text annotation", text, undefined);
            $timeout(function(){
                $scope.$apply(function(){
                    $scope.showAnnotations()
                })
            });


        }, function errorCallback(response) {
            // called asynchronously if an error occurs
            // or server returns response with an error status.
        });

    };

    $scope.showAnnotations = function(){
        angular.forEach($scope.ibent_annotation, function(annot){
            var start = annot.offset;
            var end = start + annot.size;
            // console.log("start: "+start + " end: " + end + " text: " + annot.text);
            $scope.selectAndHighlightRange('annotatedText', start, end);
        });
        sel.removeAllRanges();
    };





    // SELECTION AND HIGHLIGHT

    $scope.getSelText = function(){
        var txt = '';
        if (window.getSelection)
        {
            txt = window.getSelection();
        }
        else if (document.getSelection)
        {
            txt = document.getSelection();
        }
        else if (document.selection)
        {
            txt = document.selection.createRange().text;
        }
        else return;
        document.aform.selectedtext.value =  txt;
        console.log("anchor: " + txt.anchorOffset + ", offset: " + txt.focusOffset + " text: " + txt.toString());
        $scope.popOver(txt.anchorOffset, txt.focusOffset,txt.toString() );
    };

    $scope.popOver = function(anchor1, anchor2, text){

    };






    $scope.getTextNodesIn = function (node) {
        var textNodes = [];
        if (node.nodeType == 3) {
            textNodes.push(node);
        } else {
            var children = node.childNodes;
            for (var i = 0, len = children.length; i < len; ++i) {
                textNodes.push.apply(textNodes, $scope.getTextNodesIn(children[i]));
            }
        }
        return textNodes;
    };

    $scope.setSelectionRange = function (el, start, end) {
        if (document.createRange && window.getSelection) {
            range = document.createRange();
            range.selectNodeContents(el);
            var textNodes = $scope.getTextNodesIn(el);
            var foundStart = false;
            var charCount = 0, endCharCount;

            for (var i = 0, textNode; textNode = textNodes[i++]; ) {
                endCharCount = charCount + textNode.length;
                if (!foundStart && start >= charCount && (start < endCharCount || (start == endCharCount && i <= textNodes.length))) {
                    range.setStart(textNode, start - charCount);
                    foundStart = true;
                }
                if (foundStart && end <= endCharCount) {
                    range.setEnd(textNode, end - charCount);
                    break;
                }
                charCount = endCharCount;
            }

            sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
        } else if (document.selection && document.body.createTextRange) {
            textRange = document.body.createTextRange();
            textRange.moveToElementText(el);
            textRange.collapse(true);
            textRange.moveEnd("character", end);
            textRange.moveStart("character", start);
            textRange.select();
        }
    };

    $scope.makeEditableAndHighlight = function (colour) {
        sel = window.getSelection();
        if (sel.rangeCount && sel.getRangeAt) {
            range = sel.getRangeAt(0);
        }
        document.designMode = "on";
        if (range) {
            sel.removeAllRanges();
            sel.addRange(range);
        }
        // Use HiliteColor since some browsers apply BackColor to the whole block
        if (!document.execCommand("HiliteColor", false, colour)) {
            document.execCommand("BackColor", false, colour);
        }
        document.designMode = "off";
    };

    $scope.highlight =  function (colour) {
        var range;
        if (window.getSelection) {
            // IE9 and non-IE
            try {
                if (!document.execCommand("BackColor", false, colour)) {
                    $scope.makeEditableAndHighlight(colour);
                }
            } catch (ex) {
                $scope.makeEditableAndHighlight(colour)
            }
        } else if (document.selection && document.selection.createRange) {
            // IE <= 8 case
            range = document.selection.createRange();
            range.execCommand("BackColor", false, colour);
        }
    };

    $scope.selectAndHighlightRange = function selectAndHighlightRange(id, start, end) {
        $scope.setSelectionRange(document.getElementById(id), start, end);
        $scope.highlight("yellow");
    };

























    $scope.changeState = function(){
        $state.transitionTo('compound');


    }


}]); //end documentJSController
