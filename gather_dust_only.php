<?php
include_once ('config/database.php');
//include_once ('header.php');

class DustClass{

  public function getDustName(){
    //fetch the table names
    $db = new Database();
    $conn = $db->getConnection();

    //$sql = "SELECT * FROM tbl_dust_data;";
    //more readable
    $sql ="SELECT `NAME_HGL`, `NAME_DUST` FROM `province`";

    $result = mysqli_query($conn, $sql);
    if (!$result) {
        printf("Error: %s\n", mysqli_error($this->conn));
        exit();
    }

    $row = $result->fetch_assoc();
    if($row > 0){
      while($row = $result->fetch_assoc())
       {
           $data[] = $row;
       }
    }
    mysqli_close($conn);

    return($data);
  }

  public function NeedUpdate($first_data){

    $enc = urlencode($first_data['NAME_DUST']);
    $api_url = "http://openapi.airkorea.or.kr/openapi/services/rest/ArpltnInforInqireSvc/getCtprvnRltmMesureDnsty?sidoName=".$enc."&pageNo=1&numOfRows=200&ServiceKey=5rngHQO75rpCVULQ4UNMIXpRWSMzzTduA%2F2N47nrYL3Tc4sHHCAJ3F0k61NC05iQLdrR20%2Bur41a9nEPmpVhtQ%3D%3D&&ver=1.3";

    $xmlstring = simplexml_load_file($api_url);

    $json = json_encode($xmlstring);
    $array = json_decode($json,TRUE);

    $res['dataTime'] = $array['body']['items']['item'][0]['dataTime'];

    $db = new Database();
    $conn = $db->getConnection();

    $sql ="SELECT `IDX` FROM `tbl_dust_data` WHERE `IDX_DATETIME` = '".$res['dataTime']."'";

    $result = mysqli_query($conn, $sql);
    if (!$result) {
        printf("Error: %s\n", mysqli_error($this->conn));
        exit();
    }

    mysqli_close($conn);

    $row = $result->fetch_assoc();
    if($row > 0)
      return FALSE;
    else
      return TRUE;
  }

  public function insertDustDataNew($theData, $prov_name){
    $db = new Database();
    $conn = $db->getConnection();
    //var_dump($theData);

    for($i=0; $i<sizeof($theData)-1; $i++){
      //find the key
      $sql = "SELECT `IDX` FROM `tbl_full_location` WHERE `Phase_3` LIKE '%".$theData[$i]['city']."%'";

      $result = mysqli_query($conn, $sql);
      if (!$result) {
          printf("Error: %s\n", mysqli_error($this->conn));
          exit();
      }

      $row = $result->fetch_assoc();
      if(!$row){
        continue;
      }
      //var_dump($row);

      $sql ="INSERT INTO `tbl_dust_data`(`IDX_DATETIME`, `ITEMCODE`, `LOCATION`, `VALUE_1`)
      VALUES ('".$theData['dataTime']."','PM10','".$row['IDX']."','".$theData[$i]['val']."')";
      //var_dump($sql);

      $result = mysqli_query($conn, $sql);
      if (!$result) {
          printf("Error: %s\n", mysqli_error(conn));
          exit();
      }
      //var_dump($sql);
    }

    mysqli_close($conn);
  }

  public function getSingleProvince($single_province_name){
    //print_r($single_province_name['NAME_DUST']);
    $enc = urlencode($single_province_name['NAME_DUST']);
    //$api_url = "http://openapi.airkorea.or.kr/openapi/services/rest/ArpltnInforInqireSvc/getCtprvnRltmMesureDnsty?sidoName=%EB%B6%80%EC%82%B0&pageNo=1&numOfRows=10&ServiceKey=5rngHQO75rpCVULQ4UNMIXpRWSMzzTduA%2F2N47nrYL3Tc4sHHCAJ3F0k61NC05iQLdrR20%2Bur41a9nEPmpVhtQ%3D%3D&&ver=1.3";
    //$api_url = "http://openapi.airkorea.or.kr/openapi/services/rest/ArpltnInforInqireSvc/getCtprvnRltmMesureDnsty?sidoName=서울&pageNo=1&numOfRows=10&ServiceKey=5rngHQO75rpCVULQ4UNMIXpRWSMzzTduA%2F2N47nrYL3Tc4sHHCAJ3F0k61NC05iQLdrR20%2Bur41a9nEPmpVhtQ%3D%3D&&ver=1.3";
    $api_url = "http://openapi.airkorea.or.kr/openapi/services/rest/ArpltnInforInqireSvc/getCtprvnRltmMesureDnsty?sidoName=".$enc."&pageNo=1&numOfRows=200&ServiceKey=5rngHQO75rpCVULQ4UNMIXpRWSMzzTduA%2F2N47nrYL3Tc4sHHCAJ3F0k61NC05iQLdrR20%2Bur41a9nEPmpVhtQ%3D%3D&&ver=1.3";

    $xmlstring = simplexml_load_file($api_url);

    $json = json_encode($xmlstring);
    $array = json_decode($json,TRUE);

    //print_r($enc);
    //print_r($api_url);
    //print_r(sizeof($array['body']['items']['item']));

    //single time
    //if($first == TRUE){
    $res['dataTime'] = $array['body']['items']['item'][0]['dataTime'];
    //}
    for($i=0 ; $i < sizeof($array['body']['items']['item']); $i++){
      $res[$i]['city'] = $array['body']['items']['item'][$i]['stationName'];
      $res[$i]['val'] = $array['body']['items']['item'][$i]['pm10Value'];
    }

    return ($res);
  }
}

$api = new DustClass();
$province_names = $api->getDustName();

//print_r($province_names);

//$i=0;
//$res[$province_names[$i]['NAME_HGL']] = $api->getSingleProvince($province_names[$i]);
//$api->insertDustDataNew($res[$province_names[$i]['NAME_HGL']], $province_names[$i]['NAME_HGL']);
if($api->NeedUpdate($province_names[0])){
  //fetch data
  for($i=0; $i<sizeof($province_names)-1; $i++){
    //$temp = $province_names[$i];
    //fetch
    $res[$province_names[$i]['NAME_HGL']] = $api->getSingleProvince($province_names[$i]);
    $api->insertDustDataNew($res[$province_names[$i]['NAME_HGL']], $province_names[$i]['NAME_HGL']);
    //sleep(1.2);
  }
  print_r("Data updated");
}else{
  print_r("Latest data already exist");
}

//print_r($res);

 ?>
