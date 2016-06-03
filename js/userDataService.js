app.service('UserDataService', function(){


    var data;
    this.getdata = function(){
        return data;
    };
    this.pushdata = function(a){
        data = a;
    };
    this.setdata = function(){
        data = undefined;
    };








    
});
