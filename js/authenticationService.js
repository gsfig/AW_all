
app.service('AuthenticationService', function(){

    var self = this;
    var data = undefined;
    var username = undefined;
    self.add = function (tokenIn, userName){
        data = tokenIn;
        username = userName;
        // window.alert(tokenIn);
    }
    self.user = function () {
        if (username != undefined) {
            return username;
        }
    }
    self.delete =function (){
        data = undefined;
        username = undefined;

    }
    self.check = function (){
        if(data != undefined){
            // window.alert('defined');
            return true;
        }

    }








 /*   var self = this;
    self.checkToken = function(token){
        var data = {token: token};
        $http.post("endpoints/checkToken.php", data).success(function(response){
            if (response === "unauthorized"){
                console.log("Logged out");
                $state.go("login")
            } else {
                console.log("Logged In");
                return response;
            }
        }).error(function(error){
            $state.go("login")
        })

    }*/

});