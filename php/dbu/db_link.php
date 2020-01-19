<?php


class db_link{


	public static function open_link($database)
	{
		//Initialize Variables
		$dbname =  $database;			// Used for sharding databases 
		$user = "YOUR USER NAME";		// Change these Values
		$pass = "YOUR PASS"; 			// Change these Values
		$host = "localhost";

		$link = mysqli_connect($host, $user, $pass, $dbname) or die("Error " . mysqli_error($link));

		return $link;


	}

	public static function open_con()
	{
		//Initialize Variables
		$dbname =  "YOUR DATABASE NAME";	// Change these Values
		$user = "YOUR USER NAME";		// Change these Values
		$pass = "YOUR PASS"; 			// Change these Values

		$mysqli = new mysqli($host, $user, $pass, $dbname);

		return $mysqli;

	}


}

?>
