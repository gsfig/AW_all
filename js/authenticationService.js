
app.service('AuthenticationService', function($http, apiBaseUrl){

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
    self.logout =function (){

        // Webservice logout
        $http({
            method: "post",
            url: apiBaseUrl + "user/logout",
            data: {
                username: localStorage.getItem("username"),
                token: localStorage.getItem("token")
            },
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function successCallback(response) {
            localStorage.removeItem("token");
            localStorage.removeItem("username");
        }, function errorCallback(response) {
            // called asynchronously if an error occurs
            // or server returns response with an error status.
            console.error(response);
        });

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