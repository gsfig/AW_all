angular.module('d3App.controllers')
    .controller('barchartController', ['$scope','AuthenticationService','UserDataService','apiBaseUrl','$http','$timeout', function($scope,AuthenticationService,UserDataService,apiBaseUrl,$http,$timeout) {

        $scope.dataAvailable = false;
        $scope.message = undefined;
            getannotations();
        function getannotations(){
            // var urlcomplete = apiBaseUrl + '/document/' + '?id=' + id;
            var username = AuthenticationService.user();
            if(username === null){
                username = 0
            }
            // var urlcomplete = apiBaseUrl + '/user/' + '?username=' + username + '/annotations';
            var urlcomplete = apiBaseUrl + '/document/annotation/user/'  + '?user=' + username;
            $http({
                url: urlcomplete,
                method: "GET"
            }).then(function successCallback(response) {
                // $timeout(function(){
                //     $scope.$apply(function(){
                // console.log(response.data.payload);
                        setData(response.data.payload);
                    // })
                // });
            }, function errorCallback(response) {
                $scope.message = "No annotations found";
            });

        }
        function setData(payload){

            var data = angular.fromJson(payload);
            // var data = payload;
            var size = 1;
            var d3Data = [];

            // console.log("data: ");
            // console.log(data);

            angular.forEach(data.list, function (paperid) {
                // console.log(paperid );
                // console.log(data[paperid]);


                // d3Data.push(d);
                // console.log("d3: "+d3Data);
                children  = []
                angular.forEach(data[paperid], function (annot) {
                    // console.log("annot:" );
                    // console.log(annot);
                    var string = annot.text;
                    ann = {
                        "name" : string, "size" : size,
                    };
                    children.push(ann);
                });
                // d = {
                //     "name" : paperid,
                //     "children" : children
                // }
                d3Data.push({
                    "name" : paperid,
                    "children" : children
                });

            });
            // console.log("d3DATA");
            // console.log(d3Data);

            var toSave = {name: "flare", children :d3Data };

            
            

            // console.log(toSave);



            UserDataService.setdata();

                UserDataService.pushdata(toSave);

            $scope.dataAvailable = true;

            // console.log(UserDataService.getdata());

        }



    }]);