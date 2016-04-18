<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
?>
<!DOCTYPE html>
<html data-ng-app="aw.controllers" >
	<head>
	<script src= "https://ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular.min.js" type="text/javascript"></script>
	<script src= "<?php echo base_url('assets/js/AngularController.js');?>" ></script>
    <script src= "https://ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular-sanitize.js" type="text/javascript"></script>

	</head>
	<body>
	
		<div data-ng-controller="documentJSController">
<!--		<form action="idToTitle" method="POST">-->
			<form >
			<textarea name="text" data-ng-model="input" data-ng-change="dataChanged()" placeholder= "NCBI id to get title"></textarea>
			<button data-ng-click="getPaper(input)">submit id</button>
		</form>
            <ul>
                <li data-ng-repeat= "doc in document.payload |filter:input |limitTo:4 " ng-if="input.length > 0">
                    <a data-ng-click="showAbstract(doc.title, doc.abstract)" >{{doc.idNCBI }}</a>
                </li>
            </ul>
            </br>
            <h4 data-ng-bind = "title"></h4>
            <p data-ng-bind="abstract"></p>

        </div><!-- end angular controller -->
	
	</body>
</html>