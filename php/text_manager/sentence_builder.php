<?php

include_once "/home/thirdpla/public_html/studentux/php/pathways.php";

include_once MySQL_Adapter_GET_query_absolute_path;
include_once MySQL_Adapter_UPDATE_query_absolute_path;


  class sentence_builder{

    public $actions, $wasSuccess;

    public $DEBUG_ON = true;


    public function __construct() {
    }

    public function parse_and_store($JSON){
      // STILL NEEDED -> , $date_time, $lecture_id){

      //results":{"transcripts":[{"transcript":"Okay
      $results = $JSON->results;
      $transcripts = $results->transcripts;
      $items = $results->items;

      //Setup the lecture ID
      $lecture_id = md5( $transcripts[0]->transcript );

      //Setup the timestamp
      $timelog = time();
      $timestamp = date('Y-m-d H:i:s', $timelog);

      //Get the date of the lecture
      //? not sure where this will come from yet
      $date = $timestamp;
      $queries = array();

      //The number of words and punctuation elements.
      $count = count($items);

      //Initialize the sentence variable
      $sentence = "";

      //Initialize the incrementor for the loop
      $i = 0;

      $item = null;//initialize the item

      //Initialize the start time offset
      $starttime_offset = $items[0]->start_time;
      $starttime=0;

      //Iterate over the JSON objects "items":[] array and look for .?! to build sentences
      while( $i < $count ){

        if( $item != null ) { $prevItem = $item; } // store previous item
        $item = $items[$i]; // next item

        //concatenate the next item to the string
        $sentence .= $item->alternatives[0]->content." ";

        //Set the time offset if it is a word
        // -because punctuation doesn't have a start and end time
        if( $item->type == "pronunciation" ){
          if($starttime == 0 ){
            $starttime = $item->start_time - $starttime_offset;
          }
        }

        //Check for the end of the sentence
        if( $item->type == "punctuation" && $this->check_punctuation( $item->alternatives[0]->content ) ){

          $endtime = $prevItem->end_time - $starttime_offset;

          //Escape the single quotes with a forward slash
          // -so it can be inserted into the SQL table
          $sentence = str_replace("'", "\'", $sentence);

          //Remove the space between the final punctuation mark
          $sentence = substr_replace($sentence ,"", -3);
          $sentence .= $item->alternatives[0]->content." ";

          //Geneate a sentence id
          $sentence_id = md5($sentence);

          //Create the MySQL String
          $sql = "INSERT INTO sentences (lecture_id, sentence_id, sentence, starttime, endtime,  date, timestamp)
                      values( '$lecture_id', '$sentence_id', '$sentence', $starttime, $endtime, '$date', '$timestamp');";

          //Add string to the query array.
          $queries[] = $sql;

          //reset variables
          $starttime = 0;
          $sentence = "";
        }

        $i++;

      }

      //Save all of the queries created
      $this->save_sentence( $queries );

    }//end parse and store


    private function check_punctuation($content){
      //Check for the end of a sentence
      // - Could be modified to take an array of values instead
      if( $content == "." || $content == "!" || $content == "?" ){
          return true;
      }
      return false;
    } //End check_punctuation()


    private function save_sentence( $queries ){

      //Loop over the array
      $i = 0;
      foreach( $queries as $sql ){

        if( $this->DEBUG_ON ){
          echo $sql."</br>";
        }

        //This could be improved with a multiquery
        $u_data = new update_data();
        $rv = $u_data->run_query_with_string_and_debug($sql, $this->DEBUG_ON);
        $this->wasSuccess[$i] = $rv;
        $i++;

      }//end foreach

    }//End save_sentence()



  }//End Class




 ?>
