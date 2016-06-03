
app.service('AbstractService', function(){

    var annotationsInClick = [];
    this.getannotationsInClick = function(){
        return annotationsInClick;
    };
    this.pushannotationsInClick = function(a){
        annotationsInClick.push(a);
    };
    this.setannotationsInClick = function(){
        annotationsInClick = [];
    };

    // var operationsInClick = [];
    // this.getoperationsInClick = function(){
    //     return operationsInClick;
    // };
    // this.pushoperationsInClick = function(a){
    //     operationsInClick.push(a);
    // };
    // this.setoperationsInClick = function(){
    //     operationsInClick = [];
    // };
    
    var click;
    this.setClick = function(anchor1, anchor2, text){
        click = {
            anchor1 : anchor1,
            anchor2 : anchor2,
            text : text

        };
    };
    this.getClick = function () {
        return click;

    };

    var document;
    this.get_document = function(){
        return document;
    };
    this.setdocument = function(tochange){
        document = tochange;
    };

    var title;
    this.get_title = function(){
        return title;
    };

    this.settitle = function(tochange){
        title = tochange;
    };

    var abstract;
    this.getabstract = function(){
        return abstract;
    };
    this.setabstract = function(tochange){
        abstract = tochange;
    };

    var showAbstDiv;
    this.getshowAbstDiv = function(){
        return showAbstDiv;
    };
    this.setshowAbstDiv = function(tochange){
        showAbstDiv = tochange;
    };

    var showButton;
    this.getshowButton = function(){
        return showButton;
    };
    this.setshowButton = function(tochange){
        showButton = tochange;
    };

    var idNCBI;
    this.getidNCBI = function(){
        return idNCBI;
    };
    this.setidNCBI = function(tochange){
        idNCBI= tochange;
    };

    var inView;
    this.getinView = function(){
        return inView;
    };
    this.setinView = function(tochange){
        inView= tochange;
    };

    var document_requested;
    this.getdocument_requested = function(){
        return document_requested;
    };
    this.setdocument_requested = function(tochange){
        document_requested= tochange;
    };

    var ibent_annotation;
    this.getibent_annotation = function(){
        return ibent_annotation;
    };
    this.setibent_annotation = function(tochange){
        ibent_annotation = tochange;
    };









/*

    var ;
    this.get = function(){
        return ;
    };
    this.set = function(tochange){
         = tochange;
    };

    */


});
