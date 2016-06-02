
app.service('ChemicalService', function(){

    var chemToSearch;
    this.getchemToSearch = function(){
        return chemToSearch;
    };
    this.setchemToSearch = function(tochange){
        chemToSearch = tochange;
    };



    var chemDetails;
    this.getchemDetails = function(){
        return chemDetails;
    };
    this.setchemDetails = function(tochange){
        chemDetails = tochange;
    };

});