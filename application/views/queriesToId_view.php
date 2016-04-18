<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
?>
<!DOCTYPE html>
<html>
	<head>
	<title></title>
	</head>
	<body>	
		<h1></h1>
	
		<!-- find articles id -->
		<form action="queriesToId" method="POST">
			<textarea name="text">query to NCBI id</textarea>
			<input type="submit" value="submit query">
		</form>
	
	</body>
</html>