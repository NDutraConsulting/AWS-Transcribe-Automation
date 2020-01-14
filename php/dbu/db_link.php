<?php


class db_link{


public static function open_link($database)
{
	//Initialize Variables
	$dbname =  "thirdpla_studentux";
	$user = "thirdpla_ux";
	$pass = "V4b5KcbZ9[J.";
	$host = "localhost";

	$link = mysqli_connect($host, $user, $pass, $dbname) or die("Error " . mysqli_error($link));

	return $link;


}

public static function open_con()
{
	$dbname =  "thirdpla_studentux";
 	$user = "thirdpla_ux";
 	$pass = "V4b5KcbZ9[J.";
 	$host = "localhost";

	$mysqli = new mysqli($host, $user, $pass, $dbname);

	return $mysqli;


}


}

?>
