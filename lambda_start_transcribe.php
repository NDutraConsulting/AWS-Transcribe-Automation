<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Can cause silent failure on Ubuntu and debian - DUE to: PHP with the Suhosin patch
//modify suhosin.ini
//suhosin.executor.include.whitelist = phar
//SEE: https://docs.aws.amazon.com/sdk-for-php/v3/developer-guide/getting-started_installation.html
require 'aws.phar';

use Aws\S3\S3Client;

use Aws\Exception\AwsException;

write_log_output("New Job Started");

if( isset($_POST['json']) ){
$json= $_POST['json'];
}else if(isset($_GET['json'])){
  $json = $_GET['json'];
}else{
  write_log_output("Failed - No POST['json']");

  write_log_output("End Job Started");

  exit();
}
write_log_output( "json:".$json );

$json_obj = json_decode($json);
var_dump ($json_obj);
$key = $json_obj->detail->requestParameters->key;


//write_log_output( "detail:".$json_obj->detail);
write_log_output( "key:".$key );
$input_filename = $key;
$job_details = explode('!', $key);
//sample_rate+lecture_id-section_id-class_id-date_time.mp3

$sample_rate = 44100;
$sample_rate = $job_details[0]; //+ 0 should cast to an int

//$sample_rate = $sample_rate+0;
//echo "</br></br>".$sample_rate;
//echo "</br></br>";

$input_bucket_name = "new-jobs";
$output_bucket_name = 'finished-transcriptions';


$filename = 's3://'.$input_bucket_name.'/'.$input_filename;

$JobName = explode( ".",$job_details[1]);
$JobName = $JobName[0];

$transcribe = new Aws\TranscribeService\TranscribeServiceClient([
 'profile' => 'default',
'region'  => 'us-east-2',
'version' => 'latest'
]);

$output_params = "fileURI: ".$filename." sample-rate:".$sample_rate." - out bucket: ".$output_bucket_name. "- Jobname: ".$JobName;
echo "</br>".$output_params."</br>";
write_log_output($output_params);


$result = $transcribe->startTranscriptionJob([
    'LanguageCode' => 'en-US', // REQUIRED
    'Media' => [ // REQUIRED
        'MediaFileUri' => $filename
    ],
    'MediaFormat' => 'mp3',
    'MediaSampleRateHertz' => intval($sample_rate),
    'OutputBucketName' => $output_bucket_name,
    'Settings' => [
        'ChannelIdentification' => true,
        //'MaxSpeakerLabels' => <integer>,
        //'ShowSpeakerLabels' => true || false,
        //'VocabularyName' => '<string>',
    ],
    'TranscriptionJobName' => $JobName, // REQUIRED
]);

write_log_output($result);
write_log_output("New Job Ended");

function write_log_output($string){
  $log_file = "log/log_lambda_start_transcribe.txt";
  $today = " - ".date("M,d,Y h:i:s A") ."- ";

  //file_put_contents($log_file, "\n[ ".$today.$string." ]", FILE_APPEND );

  $myfile = fopen($log_file, "a") or die("Unable to open file!");
  $txt = "\n[ ".$today.$string." ]";
  fwrite($myfile, $txt);
  fclose($myfile);

}


?>
