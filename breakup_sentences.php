<?php

include_once "/var/www/public_html/studentux/php/pathways.php";


include_once root_directory."text_manager/sentence_builder.php";


//Open the JSON file
$url = 'json/asrOutput.json'; // path to your JSON file
$data = file_get_contents($url); // put the contents of the file into a variable

//Convert to an object
$JSON = json_decode($data);// decode the JSON feed


//parse and save
$sentence_builder = new sentence_builder();

$sentence_builder->parse_and_store($JSON);


 ?>
