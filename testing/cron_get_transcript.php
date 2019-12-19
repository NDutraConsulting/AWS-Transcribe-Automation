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


$sharedConfig = [
    'profile' => 'default',
    'region' => 'us-east-2',
    'version' => 'latest'
];

// Create an SDK class used to share configuration across clients.
//$sdk = new Aws\Sdk($sharedConfig);

// Use an Aws\Sdk class to create the S3Client object.
//$s3Client = $sdk->createS3();

$client = new Aws\S3\S3Client($sharedConfig);

// Register the stream wrapper from an S3Client object
$client->registerStreamWrapper();


//The jobname should end in .json
//For example trinscribe-test.json
$JobName = $_GET['jobName'];


//$bucket_name = $_GET['bucket'];
$bucket_name = 'finished-transcriptions';

//transcribe-test-7782';


$transcribe = new Aws\TranscribeService\TranscribeServiceClient([
 'profile' => 'default',
'region'  => 'us-east-2',
'version' => 'latest'
]);




	$result = $transcribe->getTranscriptionJob([
    	'TranscriptionJobName' => $JobName // REQUIRED
	]);

	echo "Status:".$result['TranscriptionJob']['TranscriptionJobStatus']. "</br></br>";

	echo "Result:".var_dump( $result )."</br>";



	$status = $result['TranscriptionJob']['TranscriptionJobStatus'];


	if( $status == "COMPLETED" ){
		$transcription_uri = $result['TranscriptionJob']['Transcript']['TranscriptFileUri'];

    $str_arr = explode ("/", $transcription_uri);

    $arrLength = count($str_arr);

    $key = $str_arr[$arrLength-1];

    echo $key . "</br>";

    $rv = getTrascriptByBucketAndKey( $bucket_name, $key);

    echo "</br></br>".$rv;

	}else{
		echo "</br>Still Processing...";
	}








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

  /*
  $result = $s3Client->putObject([
      'Bucket' => $bucket,
      'Key' => $key,
      'Body' => 'this is the body!'
  ]);*/

  // Download the contents of the object.
  $result = $s3Client->getObject([
      'Bucket' => $bucket,
      'Key' => $key
  ]);

  // Print the body of the result by indexing into the result object.
  return $result['Body'];

/*
  $trans = $result['results']['transcripts'][0]['transcript'];
  echo "</br></br>".$trans;*/


}







?>
