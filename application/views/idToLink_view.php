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
	
		<!-- find related articles -->
		<form action="idToLink" method="POST">
			<textarea name="text">NCBI id to get related articles</textarea>
			<input type="submit" value="submit id">
		</form>
	
	</body>
</html>