<?php

/**
 *
 */
class DustData
{

  public function getCurrTime(){
    $api_url = "http://openapi.airkorea.or.kr/openapi/services/rest/ArpltnInforInqireSvc/getCtprvnMesureLIst?itemCode=PM10&dataGubun=HOUR&searchCondition=MONTH&pageNo=1&numOfRows=".$req_data."&ServiceKey=5rngHQO75rpCVULQ4UNMIXpRWSMzzTduA%2F2N47nrYL3Tc4sHHCAJ3F0k61NC05iQLdrR20%2Bur41a9nEPmpVhtQ%3D%3D";

    $xmlstring = simplexml_load_file($api_url);

    //$xml = simplexml_load_string($xmlstring);
    $json = json_encode($xmlstring);
    $array = json_decode($json,TRUE);

    //reformat the time
    $array['body']['items']['item']['0']['dataTime'] = str_replace('-', '.', $array['body']['items']['item']['0']['dataTime']);
    $array['body']['items']['item']['0']['dataTime'] = str_replace(' ', '.', $array['body']['items']['item']['0']['dataTime']);
    return($array['body']['items']['item']['0']['dataTime']);
  }

  public function getResult($req_data)
  {
    //print_r($req_data);
    $api_url = "http://openapi.airkorea.or.kr/openapi/services/rest/ArpltnInforInqireSvc/getCtprvnMesureLIst?itemCode=PM10&dataGubun=HOUR&searchCondition=MONTH&pageNo=1&numOfRows=".$req_data."&ServiceKey=5rngHQO75rpCVULQ4UNMIXpRWSMzzTduA%2F2N47nrYL3Tc4sHHCAJ3F0k61NC05iQLdrR20%2Bur41a9nEPmpVhtQ%3D%3D";

    $xmlstring = simplexml_load_file($api_url);

    //$xml = simplexml_load_string($xmlstring);
    $json = json_encode($xmlstring);
    $array = json_decode($json,TRUE);

    //single time
    $res = $array['body']['items']['item']['0'];

    return ($res);
  }
}

 ?>
