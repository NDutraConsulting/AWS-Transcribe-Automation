<?php

$DEBUG = 1;
if( $DEBUG ){
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
}

require '../aws.phar';

$output_bucket_name = 'finished-transcriptions';

//Test JSON
$json = "C Test";

/*foreach($_POST as $data){
  $json .= "+".$data;
}*/
$out = "\nFile Loaded:\n";

if( isset($_GET['json']) ){
  $json = $_GET['json'];
}else{
  $out .= "\nNO GET response.";

}
if( isset($_POST['json']) ){
  $json = $_POST['json'];
}else{
  $out .= "\nNO POST response.";
  //exit();
}
//$json = "B Test";


write_log_output($out);//, FILE_APPEND );

$jobname = "test";
//Create the filename and key for the bucket
$filename = $jobname.".json";
$key = $filename;


echo $filename."</br>";
echo $output_bucket_name."</br></br>";

//Set the output directory path - could be a seperate server here.
//This could be based on the input filename
$output_directory = getOutputDirectory($jobname);

$json_obj = json_decode($json);
$json .= "\n".$json_obj->detail->requestParameters->key;

//Save response to the servers database or create a json file.
file_put_contents("test-output", $json);

echo "fileout</br>";

write_log_output('success');

function getOutputDirectory($jobname){

  return "json/";
}



function write_log_output($string){
  $log_file = "log/log_test_reciever.txt";
  $today = "\n".date("M,d,Y h:i:s A");
  file_put_contents($log_file, $string.$today);//, FILE_APPEND );

}

?>
