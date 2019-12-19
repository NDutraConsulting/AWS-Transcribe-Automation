<?php
$DEBUG_ON = true;
if( $DEBUG_ON ){
error_reporting(true);
ini_set('display_errors', true);
error_reporting(E_ALL);
}

define("root_directory", "/home/thirdpla/public_html/studentux/php/");

//Database access
define("dbu_directory","dbu/");
define("database_manager_absolute_path", root_directory.dbu_directory."db_manager.php");
define("database_link_absolute_path", root_directory.dbu_directory."db_link.php");


//quick dev Mysql adapter
define("MySQL_Adapter_GET_query_absolute_path", root_directory."MySQL_Adapter/get_data.php");
define("MySQL_Adapter_UPDATE_query_absolute_path", root_directory."MySQL_Adapter/update_data.php");
define("MySQL_Adapter_tables_absolute_path", root_directory."MySQL_Adapter/tables.php");

//Logs
define("app_log_dir", "app_log/");
define("app_log_path", root_directory.app_log_dir."create_log.php");


?>
