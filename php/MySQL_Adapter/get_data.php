<?php

#-------------------------------------------------------------------------------
# Filename: MYSQL_Adapter/get_data.php -
# Description: Basic MySQL get class with two functions.
#               One funciton accepts query strings and the
#               other takes three parameters $tablename, $get_parameters,
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
#-------------------------------------------------------------------------------


include_once "/home/thirdpla/public_html/studentux/php/pathways.php";


//include_once debug_code_absolute_path;
require_once database_link_absolute_path;


class get_data{

  private $DEBUG_ON = true;

	private $tablename;

	public $data;

	public function __construct( )
	{

		//echo $tablename. $get_parameters. $where_parameters;

	}


  public function run_query_with_string_and_debug($q, $DEBUG){


    		$mysqli= db_link::open_con();


    		if (!$result = $mysqli->query($q)) {

    				if( $this->DEBUG_ON || $DEBUG ){
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

  }


  public function run_query_with_string($q){



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



	}

	public function run_query( $tablename, $get_parameters, $where_parameters ){

    //Start the query string
    $q = "SELECT ". $get_parameters ." FROM " . $tablename ;



    //Insert the Where parameters
    if( $where_parameters != "0" ){
			//echo "test";
    	$q .= " WHERE ".$where_parameters;
    }

    //Close the Query
    $q .= ";";

    //echo "$where_parameters<br> $q </br>";


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

        //echo $q;
    $this->data = Array();

    while ($row = $result->fetch_assoc()) {
        // Get each entry returned
        $this->data[] = $row;
    }

		return $this->data;

    // The script will automatically free the result and close the MySQL
    // connection when it exits, but let's just do it anyways
    $result->free();
    $mysqli->close();



    }



}




?>
