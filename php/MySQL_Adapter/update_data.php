<?php

#-------------------------------------------------------------------------------
# Filename: MYSQL_Adapter/update_data.php -
# Description: Basic MySQL update class with two functions.
#               One funciton accepts query strings and the
#               other takes three parameters $tablename, $set_parameters,
#               $where_parameters to build a string and send data
#
#               Note: building a string using the function may make it easier
#               to manage updates needed when database standards change.
#               EX: Inserts should bind data.
#
# Integration into Student UX System : run from any file that needs it
#
# Nikko Dutra Bouck
# Updated: Nov 3rd 2018
#
#
/** Usage Example Code:
 | $update_data = new update_data();
 | $sql = "REPLACE INTO ".tables::current_customer_web_view()." ( customer_id, timestamp, current_view) VALUES( '$customer_id', '".time()."','$current_view' );";
 | $update_data->run_query_with_string($sql);
**/
#-------------------------------------------------------------------------------

include_once "/home/thirdpla/public_html/studentux/php/pathways.php";

require_once database_link_absolute_path;

class update_data{
	
  private $DEBUG_ON = false;

  private $tablename;

  public $data;

  public function __construct( ){
  }


  public function run_query_with_string_and_debug($q, $DEBUG){

	$mysqli= db_link::open_con();


	if (!$mysqli->query($q)) {

		if( $this->DEBUG_ON || $DEBUG){
			// Oh no! The query failed.
			echo "Sorry, the website is experiencing problems.</br>";

			// Again, do not do this on a public site, but we'll show you how
			// to get the error information
			echo "Error: Our query failed to execute and here is why: </br>";
			echo "Query: " . $q . "</br>";
			echo "Errno: " . $mysqli->errno . "</br>";
			echo "Error: " . $mysqli->error . "</br>";
			//exit;
		}

		$mysqli->close();
		return false;
	}

	// The script will automatically free the result and close the MySQL
	// connection when it exits, but let's just do it anyways
	//$result->free();
	$mysqli->close();
	return true;

  }

  public function run_query_with_string($q){

	$mysqli= db_link::open_con();


	if (!$mysqli->query($q)) {

		if( $this->DEBUG_ON ){
			// Oh no! The query failed.
			echo "Sorry, the website is experiencing problems.</br>";

			// Again, do not do this on a public site, but we'll show you how
			// to get the error information
			echo "Error: Our query failed to execute and here is why: </br>";
			echo "Query: " . $q . "</br>";
			echo "Errno: " . $mysqli->errno . "</br>";
			echo "Error: " . $mysqli->error . "</br>";
			//exit;
		}	

	}

	// The script will automatically free the result and close the MySQL
	// connection when it exits, but let's just do it anyways
	//$result->free();
	$mysqli->close();


  }

  public function run_query( $tablename, $set_parameters, $where_parameters ){

	//Start the query string
	$q = "UPDATE ". $tablename." SET ".$set_parameters ;

	//Insert the Where parameters
	if( $where_parameters != null ){
		$q .= " WHERE ".$where_parameters;
	}

	//Close the Query
	$q .= ";";

	//Open Mysqli connection
	$mysqli= db_link::open_con();


	if (!$result = $mysqli->query($q)) {

		if( $this->DEBUG_ON ){
		  // Oh no! The query failed.
		  echo "Sorry, the website is experiencing problems.";

		  // Again, do not do this on a public site, but we'll show you how
		  // to get the error information
		  echo "Error: Our query failed to execute and here is why: \n";
		  echo "Query: " . $sql . "\n";
		  echo "Errno: " . $mysqli->errno . "\n";
		  echo "Error: " . $mysqli->error . "\n";
		  exit;
		}
      		return "NO DATA";
    	}

    	$this->data = Array();

	while ($row = $result->fetch_assoc()) {
		// Get each entry returned
		$this->data[] = $row;
	}


	// The script will automatically free the result and close the MySQL
	// connection when it exits, but let's just do it anyways
	$result->free();
	$mysqli->close();

	return $this->data;


  } #END run_query( $tablename, $set_parameters, $where_parameters )	



}




?>
