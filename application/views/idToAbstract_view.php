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
	
		<!-- search abstract -->
		<form action="idToAbstract" method="POST">
			<textarea name="text">NCBI id to annotate, ex: 17284678</textarea>
			<input type="submit" value="submit">
		</form>
	
	</body>
</html>