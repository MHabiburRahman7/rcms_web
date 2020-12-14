<?php

  // include_once "config/database.php";
  //
  // $database = new Database();
  // $db = $database->getConnection();

  include_once 'config/simple_html_dom.php';

/**
 *
 */
class ScraperEmergency{ //FOR EMERGENCY

  private $answer = array();

  public function getResultUsingHeader(){
    // Create a stream
    $opts = array(
      'http'=>array(
        'method'=>"GET",
        'header'=>"Accept-language: n-US,en;q=0.9,ko-KR;q=0.8,ko;q=0.7,en-GB;q=0.6\r\n" .
                  "Cache-Control: no-cache\r\n" .
                  "User-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36\r\n" .
                  "referer: https://www.safekorea.go.kr/idsiSFK/neo/sfk/cs/sfc/dis/disasterMsgList.jsp?emgPage=Y^&menuSeq=679\r\n" .
                  "Accept: application/json\r\n" .
                  "Origin: https://www.safekorea.go.kr\r\n" .
                  "X-Requested-With: XMLHttpRequest\r\n"
      )
    );

    $context = stream_context_create($opts);

    // Open the file using the HTTP headers set above
    $file = file_get_contents('https://www.safekorea.go.kr/idsiSFK/neo/sfk/cs/sfc/dis/disasterMsgList.jsp?emgPage=Y&menuSeq=679', false, $context);

    print($file);
  }

  public function getResultOpenApi(){
    $ch = curl_init();
    $url ='http://apis.data.go.kr/1741000/DisasterMsg2/getDisasterMsgList'; /*URL*/
    $queryParams ='?' . urlencode('ServiceKey'). '=5rngHQO75rpCVULQ4UNMIXpRWSMzzTduA%2F2N47nrYL3Tc4sHHCAJ3F0k61NC05iQLdrR20%2Bur41a9nEPmpVhtQ%3D%3D'; /*Service Key*/
    $queryParams .='&'. urlencode('pageNo'). '='. urlencode('1'); /**/
    $queryParams .='&'. urlencode('numOfRows'). '='. urlencode('10'); /**/
    $queryParams .='&'. urlencode('type'). '='. urlencode('xml'); /**/
    $queryParams .='&'. urlencode('flag'). '='. urlencode('Y'); /**/

    curl_setopt($ch, CURLOPT_URL, $url. $queryParams);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'GET');
    $response = curl_exec($ch);
    curl_close($ch);

    var_dump($response);
  }

  public function getResultPhantom(){
    $response = exec('phantomjs.exe emergency_scrape.js');
    //array result
    $pieces = explode(",", $response);
    $big_arr = [];
    $itt = 0;
    for($i = 0; $i<9; $i++){
      $mini_arr=[];
      for($j = 0; $j<4; $j++){
        $mini_arr[$j] = $pieces[$itt];
        $itt++;
      }
      $big_arr[$i] = $mini_arr;
    }

    return $big_arr;
  }

  public function checkWithDB($data){
    
  }
}

 ?>
