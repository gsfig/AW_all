'use strict';
app.controller('DocumentJSController', ['apiBaseUrl',  '$timeout','$scope', '$http','$state', 'nsPopover', function(apiBaseUrl,$timeout, $scope, $http, $state ){
    var idPaperInView = undefined;
    var sel = undefined;
    var range = undefined;
    var selectionStart = undefined;
    var selectionEnd = undefined ;

    var ranges = undefined;
    var mainRange = undefined;

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

            console.log($scope.ibent_annotation);
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
        var list = document.getElementById("annotatedText");

        console.log(list.childElementCount);
        // rangy.init();
        // var txt = document.getElementById("annotatedText2").textContent;
        // console.log(txt);

        // element.charAt(3).style.backgroundColor = "yellow";
        // console.log(txt.substr(8,7));
        // txt.replace(txt.substr(8,7), '<span id="highlight">'+txt.substr(8,7)+'</span>');

        // var classApplier = rangy.createClassApplier('annotatedText');
        // var highlighter = rangy.createHighlighter(element, 'TextRange');
        //
        // highlighter.addClassApplier(classApplier);

        angular.forEach($scope.ibent_annotation, function(annot){
            var start = annot.offset;
            var end = start + annot.size;

            // highlighter.highlightCharacterRanges('annotatedText', [start, end])


            // built range

            // highlight range

            // see if its easy to remove range and re-highlight ranges

            // console.log("start: "+start + " end: " + end + " text: " + annot.text);
            selectAndHighlightRange('annotatedText', start, end);


            // rangy.createHighlighter(document.getElementById("annotatedText"),)
        });

        sel.removeAllRanges();
        // range.detach();
        // range.deleteContents();
        // console.log(textRange);
    };

 



    // SELECTION AND HIGHLIGHT




    $scope.getSelText = function(){
        var element = document.getElementById("annotatedText");
        // console.log(element.children);
        // var range = rangy.createRange();

        // range.selectNodeContents(element);
        var selection = rangy.getSelection();
        // console.log("selection before remove:: " + selection.toString());
        // selection.removeAllRanges();
        // selection.addRange(range);

        // console.log("startContainer: " + range.startContainer.textContent);
        // console.log("range: " + range.toString());

        // console.log("selection: " + selection.rangeCount);

        if(selection.rangeCount > 0){
            // console.log("aqui");
            var range = selection.getRangeAt(0);
            var offsets = range.toCharacterRange(element);
            console.log(offsets);
        }

        // TODO: comparar com anotações e ver se bate com alguma. Se sim, apresentar chemical


        // selection.expand("character", {
        //     characterOptions : {
        //         ignoreCharacters: '/u200B'
        //     }
        // });
        // selection.setSingleRange();
        // var selection = rangy.getSelection();
        // selectionStart  = selection.anchorOffset;
        // selectionEnd  = selection.focusOffset;
        // console.log("start: " + selectionStart + " end: " + selectionEnd + " text: " + selection.toString());

        // rangy.highlightSelection();
    };

    // $scope.getSelText = function(){
    //
    //     var list = document.getElementById("annotatedText");
    //
    //     console.log(list.children);
    //
    //     var current_range = window.getSelection().getRangeAt(0);
    //     window.getSelection().removeAllRanges();
    //     window.getSelection().addRange(current_range);
    //     console.log(list.children);
    //
    //     var txt = '';
    //     if (window.getSelection)
    //     {
    //         txt = window.getSelection();
    //
    //     }
    //     else if (document.getSelection)
    //     {
    //         txt = document.getSelection();
    //     }
    //     else if (document.selection)
    //     {
    //         txt = document.selection.createRange().text;
    //     }
    //     else return;
    //     document.aform.selectedtext.value =  txt;
    //     console.log("anchor: " + txt.anchorOffset + ", offset: " + txt.focusOffset + " text: " + txt.toString());
    //     $scope.popOver(txt.anchorOffset, txt.focusOffset,txt.toString() );
    // };

    $scope.popOver = function(anchor1, anchor2, text){
        angular.forEach($scope.ibent_annotation, function(annot){
            var start = annot.offset;
            var end = start + annot.size;


        });


    };

    $scope.items = [{
        name: "Action"
    }, {
        name: "Another action"
    }, {
        name: "Something else here"
    }];



//http://stackoverflow.com/questions/6240139/highlight-text-range-using-javascript/6242538#6242538
//     $scope.getTextNodesIn = function (node) {
//         var textNodes = [];
//         if (node.nodeType == 3) {
//             textNodes.push(node);
//         } else {
//             var children = node.childNodes;
//             for (var i = 0, len = children.length; i < len; ++i) {
//                 textNodes.push.apply(textNodes, $scope.getTextNodesIn(children[i]));
//             }
//         }
//         return textNodes;
//     };
//
//     $scope.setSelectionRange = function (el, start, end) {
//         if (document.createRange && window.getSelection) {
//             range = document.createRange();
//             range.selectNodeContents(el);
//             var textNodes = $scope.getTextNodesIn(el);
//             var foundStart = false;
//             var charCount = 0, endCharCount;
//
//             for (var i = 0, textNode; textNode = textNodes[i++]; ) {
//                 endCharCount = charCount + textNode.length;
//                 if (!foundStart && start >= charCount && (start < endCharCount || (start == endCharCount && i <= textNodes.length))) {
//                     range.setStart(textNode, start - charCount);
//                     foundStart = true;
//                 }
//                 if (foundStart && end <= endCharCount) {
//                     range.setEnd(textNode, end - charCount);
//                     break;
//                 }
//                 charCount = endCharCount;
//             }
//
//             sel = window.getSelection();
//             sel.removeAllRanges();
//             sel.addRange(range);
//         } else if (document.selection && document.body.createTextRange) {
//             textRange = document.body.createTextRange();
//             textRange.moveToElementText(el);
//             textRange.collapse(true);
//             textRange.moveEnd("character", end);
//             textRange.moveStart("character", start);
//             textRange.select();
//         }
//     };
//
//     $scope.makeEditableAndHighlight = function (colour) {
//         sel = window.getSelection();
//         if (sel.rangeCount && sel.getRangeAt) {
//             range = sel.getRangeAt(0);
//         }
//         document.designMode = "on";
//         if (range) {
//             sel.removeAllRanges();
//             sel.addRange(range);
//         }
//         // Use HiliteColor since some browsers apply BackColor to the whole block
//         if (!document.execCommand("HiliteColor", false, colour)) {
//             document.execCommand("BackColor", false, colour);
//         }
//         document.designMode = "off";
//     };
//
//     $scope.highlight =  function (colour) {
//         var range;
//         if (window.getSelection) {
//             // IE9 and non-IE
//             try {
//                 if (!document.execCommand("BackColor", false, colour)) {
//                     $scope.makeEditableAndHighlight(colour);
//                 }
//             } catch (ex) {
//                 $scope.makeEditableAndHighlight(colour)
//             }
//         } else if (document.selection && document.selection.createRange) {
//             // IE <= 8 case
//             range = document.selection.createRange();
//             range.execCommand("BackColor", false, colour);
//         }
//     };
//
//     $scope.selectAndHighlightRange = function selectAndHighlightRange(id, start, end) {
//         $scope.setSelectionRange(document.getElementById(id), start, end);
//         $scope.highlight("yellow");
//     };



    function getTextNodesIn(node) {
        var textNodes = [];
        if (node.nodeType == 3) {
            textNodes.push(node);
        } else {
            var children = node.childNodes;
            for (var i = 0, len = children.length; i < len; ++i) {
                textNodes.push.apply(textNodes, getTextNodesIn(children[i]));
            }
        }
        return textNodes;
    }

    function setSelectionRange(el, start, end) {
        if (document.createRange && window.getSelection) {
            var range = document.createRange();
            range.selectNodeContents(el);
            var textNodes = getTextNodesIn(el);
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

            var sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
        } else if (document.selection && document.body.createTextRange) {
            var textRange = document.body.createTextRange();
            textRange.moveToElementText(el);
            textRange.collapse(true);
            textRange.moveEnd("character", end);
            textRange.moveStart("character", start);
            textRange.select();
        }
    }

    function makeEditableAndHighlight(colour) {
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
    }

    function highlight(colour) {
        var range, sel;
        if (window.getSelection) {
            // IE9 and non-IE
            try {
                if (!document.execCommand("BackColor", false, colour)) {
                    makeEditableAndHighlight(colour);
                }
            } catch (ex) {
                makeEditableAndHighlight(colour)
            }
        } else if (document.selection && document.selection.createRange) {
            // IE <= 8 case
            range = document.selection.createRange();
            range.execCommand("BackColor", false, colour);
        }
    }

    function selectAndHighlightRange(id, start, end) {
        setSelectionRange(document.getElementById(id), start, end);
        highlight("yellow");
    }





















    $scope.changeState = function(){
        $state.transitionTo('compound');


    }


}]); //end documentJSController
