
app.service('AuthenticationService', function(){

    var self = this;
    self.add = function (tokenIn, userName){
        localStorage.removeItem("token");
        localStorage.removeItem("username");
        localStorage.setItem("token", tokenIn);
        localStorage.setItem("username", userName);
        // window.alert(tokenIn);
        // console.log( "authServ add user " + localStorage.getItem("username"));
        // console.log( "authServ add token " + localStorage.getItem("token"));
    }
    self.user = function () {
        // console.log( "authServ return user " + localStorage.getItem("username"));
        return localStorage.getItem("username");

    }
    self.delete =function (){

        localStorage.removeItem("token");
        localStorage.removeItem("username");
        // console.log( "authServ remove " + localStorage.getItem("username"));

    }
    self.check = function (){
        if(localStorage.getItem("token") === null){
            // console.log( "authServ check is Undefined " + localStorage.getItem("username"));
            return false;
        }
        else{
            // console.log( "authServ check is defined " + localStorage.getItem("username"));
            return true;
        }
    }
    

});