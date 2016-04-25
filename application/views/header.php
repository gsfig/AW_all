<?php

echo "THIS IS THE HEADER";
?>

<!DOCTYPE html>
<html data-ng-app="headerApp" >
<head>
    <script src= "https://ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular.min.js" type="text/javascript"></script>
    <script src= "<?php echo base_url('assets/js/app.js');?>" ></script>
    <script src= "https://ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular-sanitize.js" type="text/javascript"></script>

    <script src= "<?php echo base_url('assets/js/AngularController.js');?>" ></script>

</head>
<body >
<div >
    <div class = "navbar" data-ng-controller="MenuController">
        <a href="">Home</a>
        <a href="">other menu</a>
        <a href="" ng-show="isUserLoggedIn">{{username}}</a>
        <a href="" ng-show="!isUserLoggedIn">Login / SignUp</a>
        <a href="" ng-show="isUserLoggedIn">Logout</a>
    </div>
</body>
    </div>

</html>