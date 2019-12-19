<?php

$DEBUG = 1;
if( $DEBUG ){
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
}

require 'aws.phar';


$output_bucket_name = 'finished-transcriptions';

//Test JSON
$json = '{"version":"0","id":"798d0a3a-e2ed-897c-9637-dbcf090a482b",
  "detail-type":"Transcribe Job State Change","source":"aws.transcribe",
  "account":"384755050258","time":"2019-09-20T07:39:53Z","region":"us-east-2",
  "resources":[],
  "detail":{"TranscriptionJobName":"testjob-262","TranscriptionJobStatus":"COMPLETED"}}';

if( isset($_GET['json']) ){
  $json = $_GET['json'];
}else{

  $out = "No GET['json'].";
  write_log_output($out);
  exit();
}

$out = $json."\n";

//Decode Json
$json = json_decode($json);

//Get the job status
$status = $json->detail->TranscriptionJobStatus;

//Initialize jobName
$jobname = "";


$out .= $status."\n";


//Check to see if the job is complete
if( $status == "COMPLETED" ){
  $jobname = $json->detail->TranscriptionJobName;
}else{
  $out .= "\ninvalid response.";

  write_log_output($out);//, FILE_APPEND );

  exit();
}

//Create the filename and key for the bucket
$filename = $jobname.".json";
$key = $filename;


echo $filename."</br>";
echo $output_bucket_name."</br></br>";
//Set the output directory path - could be a seperate server here.
//This could be based on the input filename
$output_directory = getOutputDirectory($jobname);

//Get file from s3
$transcipt_json = getTrascriptByBucketAndKey( $output_bucket_name, $key);

//Save response to the servers database or create a json file.
file_put_contents($output_directory.$filename, $transcipt_json);


//Processing the response and storing it as sentences in MySQL
/*
include_once "/home/thirdpla/public_html/studentux/php/pathways.php";
include_once root_directory."text_manager/sentence_builder.php";

//Create new sentence_builder
$sentence_builder = new sentence_builder();
//parse and save
$sentence_builder->parse_and_store($JSON);

*/


function getOutputDirectory($jobname){

  return "json/";
}

//S3 getOperation
function getTrascriptByBucketAndKey( $bucket, $key){

  $sharedConfig = [
      'profile' => 'default',
      'region' => 'us-east-2',
      'version' => 'latest'
  ];

  // Create an SDK class used to share configuration across clients.
  $sdk = new Aws\Sdk($sharedConfig);

  // Use an Aws\Sdk class to create the S3Client object.
  $s3Client = $sdk->createS3();

  $result = $s3Client->getObject([
      'Bucket' => $bucket,
      'Key' => $key
  ]);

  // Print the body of the result by indexing into the result object.
  return $result['Body'];


}

function write_log_output($string){
  $log_file = "log/log_job_complete.txt";
  $today = "\n".date("M,d,Y h:i:s A");
  file_put_contents($log_file, $string.$today);//, FILE_APPEND );

}


?>
