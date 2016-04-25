<?php

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'awdb2';
$charset= 'utf8';

$dsn = "mysql:host=$dbhost;dbname=$dbname;charset=$charset";
// $db = new mysqli($dbhost, $bduser, $dbpass, $dbname);

// $db = null;
// try{
if(!isset($db)){
    $db = new PDO($dsn,$dbuser,$dbpass);
    $db->setAttribute(PDO::ATTR_ERRMODE,true);
}
	
	
// } catch(PDOException $e){
// 	print_r("Error connecting to mysql" . $e -> getMessage()."<br/>");
// 	die();
// }
?>
