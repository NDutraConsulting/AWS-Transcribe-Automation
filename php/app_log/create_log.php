<?php


//include_once "/var/www/html/onehabitat.org/public_html/api/pathways.php";
include_once "/home/thirdpla/trashroute.org/food/php/pathways.php";



include_once MySQL_Adapter_GET_query_absolute_path;
include_once MySQL_Adapter_UPDATE_query_absolute_path;

  class create_log{

    public $actions, $wasSuccess;

    public function __construct(){


    }

    public function save($page_name){

      $DEBUG_ON = false;

/*
      if( isset( $_SESSION['municipality_id'] ) ){
        $municipality_id = $_SESSION['municipality_id'];
      }else{
        if( $DEBUG_ON ){
          echo "SESSION NOT SET</br>;";
        }
        return;
      }*/

      $timelog = time();
      $dt = date('Y-m-d H:i:s', $timelog);


      $all_data['page_name'] = $page_name;



      foreach($_SESSION as $key => $value ){
        $all_data[$key] = $value;
      }

      foreach( $_POST as $key => $value ){
        $all_data[$key] = $value;
      }

      foreach( $_GET as $key => $value ){
        $all_data[$key] = $value;
      }

      $actions = json_encode($all_data);

      $log_id = md5( $actions );

      //Add the date after to prevent refresh attacks
      $all_data['dt'] = $dt;
      $actions = json_encode($all_data);

      $sql = "INSERT INTO app_logs( log_id, actions, page_name,  dt )
                values( '$log_id', '$actions', '$page_name', '$dt' );";


      if( $DEBUG_ON ){
        echo $sql."</br>";
      }

      $u_data = new update_data();
      $rv = $u_data->run_query_with_string_and_debug($sql, $DEBUG_ON);

      $this->wasSuccess = $rv;

      $this->actions = $actions;


    }


  }




 ?>
