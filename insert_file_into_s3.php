<?PHP

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Can cause silent failure on Ubuntu and debian - DUE to: PHP with the Suhosin patch
//modify suhosin.ini
//suhosin.executor.include.whitelist = phar
//SEE: https://docs.aws.amazon.com/sdk-for-php/v3/developer-guide/getting-started_installation.html
require 'aws.phar';


$sharedConfig = [
    'profile' => 'default',
    'region' => 'us-east-2',
    'version' => 'latest'
];
$key = '';


if(isset($_GET['key']) ){
  $key = $_GET['key'];
}else{
  exit();
}

$bucket = "new-jobs";
//$bucket = $_GET['bucket'];

$file_location = "audio/test.mp3";
// Create an SDK class used to share configuration across clients.
$sdk = new Aws\Sdk($sharedConfig);

// Use an Aws\Sdk class to create the S3Client object.
$s3Client = $sdk->createS3();


$result = $s3Client->putObject([
    'Bucket' => $bucket,
    'Key' => $key,
    'SourceFile' => $file_location
    //'Body' => 'this is the body!'
]);

echo "File: ".$key." - Was uploaded.";

// Print the body of the result by indexing into the result object.
echo $result['Body'];

?>
