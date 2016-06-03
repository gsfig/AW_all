'use strict';
app.controller('AbstractController', ['AbstractService','ChemicalService', 'AuthenticationService', 'apiBaseUrl', '$http','$timeout', '$scope', '$state', 'nsPopover', function(AbstractService,ChemicalService, AuthenticationService, apiBaseUrl,$http,$timeout, $scope ){

    var sel = undefined;
    var range = undefined;

    $scope.title = AbstractService.get_title();
    $scope.abstract = AbstractService.getabstract();
    $scope.document = AbstractService.get_document();

    $scope.inView = AbstractService.getinView();
    $scope.typeChem = {
        chemical : false,
        other : false
    };

    // console.log($scope.title);

    if($scope.inView === null){
        $timeout(function () {
            $scope.$apply(function () {
                $scope.inView = AbstractService.getinView();
                annotateDoc($scope.inView);
            })
        });
    }
    else{
        annotateDoc($scope.inView);
    }
    function annotateDoc(idOrText){
        // $scope.annotateDoc = function(idOrText){
        // $scope.showButton = false;
        isNaN(parseInt(idOrText)) ? annotateText(idOrText) : annotatePaper(idOrText);

    };

    function annotatePaper(id) {
        // getPaper(id);
        // if(angular.isUndefined(id)){
        //     console.error("Paper id is undefined")
        // }
        // else{
        var urlComplete = apiBaseUrl + "/document/" + id + "/annotation";
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
            AbstractService.setibent_annotation(response.data.payload);
            $scope.ibent_annotation = AbstractService.getibent_annotation();
            // console.log($scope.ibent_annotation);
            // $scope.dataChanged();
            // $scope.showAbstract("Free text annotation", text, undefined);
            $timeout(function () {
                $scope.$apply(function () {
                    $scope.showAnnotations()
                })
            });
        }, function errorCallback(response) {
            console.error(response.data.message);
            // called asynchronously if an error occurs
            // or server returns response with an error status.
        });
    // }

    };
    function annotateText (text){
        $http({
            method: "post",
            url: apiBaseUrl + "/ibent_annotate",
            data: {
                text: text
            },
            headers: { 'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function successCallback(response) {
            AbstractService.setibent_annotation(response.data.payload);
            $scope.ibent_annotation = AbstractService.getibent_annotation();

            // console.log($scope.ibent_annotation);
            // $scope.dataChanged();
            // $scope.showAbstract("Free text annotation", text, undefined);
            $timeout(function(){
                $scope.$apply(function(){
                    $scope.showAnnotations()
                })
            });
        }, function errorCallback(response) {
            console.error(response.data.message);
            // called asynchronously if an error occurs
            // or server returns response with an error status.
        });

    };

    $scope.showAnnotations = function(){
        var list = document.getElementById("annotatedText");

        // console.log("showAnnotations")

        var user = AuthenticationService.user();
        console.log($scope.ibent_annotation);


        angular.forEach($scope.ibent_annotation, function(annot){
            var start = parseInt(annot.offset);
            var end = start + parseInt(annot.size);

            // console.log('userlogged: ' + user + ' user annotation: ' +annot.user)

            // highlighter.highlightCharacterRanges('annotatedText', [start, end])



            // console.log("start: "+start + " end: " + end + " text: " + annot.text);

            if(user === 1 || user == annot.user ||  annot.user === null ){
                // console.log("highlight");
                selectAndHighlightRange('annotatedText', start, end);
            }



            // rangy.createHighlighter(document.getElementById("annotatedText"),)
        });

        if(angular.isDefined(sel)){
            sel.removeAllRanges();
        }

        // range.detach();
        // range.deleteContents();
        // console.log(textRange);
    };

    $scope.showClick = function(){



        var click = AbstractService.getClick();


    };
    $scope.addAnnotation = function(chem, oth){
        var click = AbstractService.getClick();
        var type;
        // console.log("addAnnotation: " + click.text + ' ' + click.anchor1 + ' ' + click.anchor2);
        // console.log("typeChem: " + chem);

        if(chem){
            type = 'chemical'
        }
        else if(oth){
            type = 'other'
        }
        else{
            type = null
        }
        $http({
            method: "post",
            url: apiBaseUrl + "/document/annotation",
            data: {
                document : AbstractService.getidNCBI(),
                text: click.text,
                begin : click.anchor1,
                end : click.anchor2,
                type : type,
                username : AuthenticationService.user()
            },
            headers: { 'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function successCallback(response) {
            annotateDoc($scope.inView)

        }, function errorCallback(response) {
            console.error(response.data.message);
            // called asynchronously if an error occurs
            // or server returns response with an error status.
        });


    };





    // SELECTION AND HIGHLIGHT





    // function getSelText(){
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
            // console.log(offsets);
        }
        // console.log("start pop");
        $scope.popOver(offsets.start, offsets.end, selection.toString() );
        // console.log("end pop");


    };


    $scope.popOver = function(anchor1, anchor2, text) {

        // console.log("pop");
        // $scope.annotationsInClick = [];
        $scope.annotation = undefined;
        $scope.span = undefined;
        $scope.text = text;
        var annottopush = [];
        AbstractService.setannotationsInClick();

        AbstractService.setClick(anchor1, anchor2, text);


        // console.log($scope.ibent_annotation);

        angular.forEach($scope.ibent_annotation, function (annot) {

                var start = parseInt(annot.offset);
                var end = start + parseInt(annot.size);

                // console.log("anchor: " + anchor1 +" anchor2: " + anchor2+ " start: " + start +  " end: " + end);
                if (anchor1 <= end && anchor2 >= start) {
                    $scope.annotation = true;
                    // console.log("intercept," + " start: " + start +  " end: " + end);

                    annottopush = {
                        'chem': {
                            'annotated': annot.text,
                            'ChebiID': annot.fkChemicalCompound != 1 ? annot.fkChemicalCompound : 'no chebi compound found',
                            'chebi_score': annot.chebi_score != 0 ? annot.chebi_score : null,
                            'ssm_entity': annot.ssm_entity != 0 ? annot.ssm_entity : null,
                            'ssm_score': annot.ssm_score != 0 ? annot.ssm_score : null,
                            'type': annot.type,
                            'subtype': annot.subtype
                        },
                        'ops' :{
                            'operation1': 'delete',
                            'operation2': 'change span',
                            'operation3': 'add annotation'

                        }

                    };
                    AbstractService.pushannotationsInClick(annottopush);
                }
            }
            // TODO: change span of annotation


        );
        if (!$scope.annotation && Math.abs(anchor1 - anchor2) > 1) {
            $scope.span = true;
            // else if selection bigger than click, add new compound?
            // optopush = {
            //         op1: 'add annotation'
            //     };
            // AbstractService.pushoperationsInClick(optopush);


        };
        $scope.annotationsInClick = AbstractService.getannotationsInClick();
    }

    $scope.showChemicalDetails = function(annotationInClick){

        // console.log("annot in click: " + annotationInClick);

        ChemicalService.setchemDetails(annotationInClick);
        $scope.chemDetails = ChemicalService.getchemDetails();
    };

    $scope.setChemToSearch = function(chemID){
        ChemicalService.setchemToSearch(chemID);
        $scope.chemToSearch = ChemicalService.getchemToSearch();

    };




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
        document.execCommand("styleWithCSS", true, null);
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
    




}]);