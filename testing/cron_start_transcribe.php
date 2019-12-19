<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Can cause silent failure on Ubuntu and debian - DUE to: PHP with the Suhosin patch
//modify suhosin.ini
//suhosin.executor.include.whitelist = phar
//SEE: https://docs.aws.amazon.com/sdk-for-php/v3/developer-guide/getting-started_installation.html
require '../aws.phar';

use Aws\S3\S3Client;

use Aws\Exception\AwsException;


$JobName = $_GET['jobName'];

$input_bucket_name = "new-jobs";
$output_bucket_name = 'finished-transcriptions';

if( isset($_GET['bucket'])){
$bucket_name = $_GET['bucket'];
}

$key = 'test-2.mp3';
if(isset($_GET['key']) ){
  $key = $_GET['key'];
}

$filename = 's3://'.$input_bucket_name.'/'.$key;


$transcribe = new Aws\TranscribeService\TranscribeServiceClient([
 'profile' => 'default',
'region'  => 'us-east-2',
'version' => 'latest'
]);



$result = $transcribe->startTranscriptionJob([
    'LanguageCode' => 'en-US', // REQUIRED
    'Media' => [ // REQUIRED
        'MediaFileUri' => $filename
    ],
    'MediaFormat' => 'mp3',
    'MediaSampleRateHertz' => 44100,
    'OutputBucketName' => $output_bucket_name,
    'Settings' => [
        'ChannelIdentification' => true,
        //'MaxSpeakerLabels' => <integer>,
        //'ShowSpeakerLabels' => true || false,
        //'VocabularyName' => '<string>',
    ],
    'TranscriptionJobName' => $JobName, // REQUIRED
]);




?>
