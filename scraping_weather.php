<?php

  // include_once "config/database.php";
  //
  // $database = new Database();
  // $db = $database->getConnection();

  include_once 'config/simple_html_dom.php';

/**
 *
 */
class Scraper{

  private $answer = array();

  public function getResult(){
    $dom = file_get_html('https://www.weather.go.kr/w/wnuri-fct/weather/sfc-city-weather.do?stn=&type=t99&reg=100&tm=&unit=km%2Fh', false);

    if(!empty($dom)) {
      //for time
      $time_temp = $dom->getElementsByTagName('p')->getAttribute('data-tm');
      //print_r($time_temp);
      $answer[0]["time"] = $time_temp;
      $i = 0;

      //table contents
      foreach($dom->find(".table-col") as $mainTable) {
        foreach($mainTable->getElementsByTagName('tr') as $subTable){
          //attrib
          $itt = 0;
          foreach($subTable->getElementsByTagName('td') as $real_content){
            if($itt == 0){
                $answer[$i]['city'] = $real_content->plaintext;
            }
            elseif ($itt == 5) {
                $answer[$i]['temp'] = $real_content->plaintext;
            }elseif ($itt == 10) {
                $answer[$i]['hum'] = $real_content->plaintext;
            }
            $itt++;
          }
          //row
          $i++;
        }
      }
    }
    return ($answer);
  }
}

 ?>
