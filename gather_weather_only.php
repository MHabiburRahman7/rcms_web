<?php

include_once ('config/database.php');
//include_once ('header.php');

class WeatherData
{
  public function getLatLong(){
    $db = new Database();
    $conn = $db->getConnection();

    $sql = "SELECT DISTINCT `Grid_X`, `Grid_Y` FROM `tbl_full_location`";

    $result = mysqli_query($conn, $sql);
    if (!$result) {
        printf("Error: %s\n", mysqli_error($conn));
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

    return $data;
  }

  public function getDateTime(){
    date_default_timezone_set('Asia/Seoul');

    $res['date_input'] = date('Ymd');
    $clock = date('H')-1;
    if($clock < 10){
      $res['time_input'] = "0".$clock."00";
    }else{
      $res['time_input'] = $clock."00";
    }
    $res['dateTimeSql'] = date('Y-m-d')." ".$clock.":00";
    return $res;
  }

  public function getData($lat, $long, $time, $date){
    $api_url = "http://apis.data.go.kr/1360000/VilageFcstInfoService/getUltraSrtNcst?serviceKey=5rngHQO75rpCVULQ4UNMIXpRWSMzzTduA%2F2N47nrYL3Tc4sHHCAJ3F0k61NC05iQLdrR20%2Bur41a9nEPmpVhtQ%3D%3D&&numOfRows=10&pageNo=1&base_date=".$date."&base_time=".$time."&nx=".$lat."&ny=".$long."";

    $xmlstring = simplexml_load_file($api_url);

    //$xml = simplexml_load_string($xmlstring);
    $json = json_encode($xmlstring);
    $array = json_decode($json,TRUE);

    //var_dump($api_url);
    $res['hum'] = $array['body']['items']['item'][1]['obsrValue'];
    $res['temp'] = $array['body']['items']['item'][3]['obsrValue'];

    return $res;
  }

  public function insertData($grid_x, $grid_y, $time, $hum_temp){
    $db = new Database();
    $conn = $db->getConnection();

    $sql = "SELECT DISTINCT `IDX` FROM `tbl_full_location` WHERE Grid_X=".$grid_x." AND Grid_y=".$grid_y.";";
    //var_dump($sql);

    $result = mysqli_query($conn, $sql);
    if (!$result) {
        printf("Error: %s\n", mysqli_error($conn));
        exit();
    }

    $row = $result->fetch_assoc();
    if($row > 0){
      while($row = $result->fetch_assoc())
       {
           $data[] = $row;
       }
    }else{
      mysqli_close($conn);
      return;
    }

    for($i=0; $i<sizeof($data); $i++){
      $sql ="INSERT INTO `tbl_wet_data`(`IDX_DATETIME`, `DATA_GUBUN`, `LOCATION`, `VALUE_1`, `VALUE_2`)
      VALUES ('".$time."', 'T,H' ,'".$data[$i]['IDX']."','".$hum_temp['temp']."','".$hum_temp['hum']."')";
      //var_dump($sql);
      $result = mysqli_query($conn, $sql);
      if (!$result) {
          printf("Error: %s\n", mysqli_error($conn));
          exit();
      }
    }

    mysqli_close($conn);
  }

  public function NeedUpdate($time){
    $db = new Database();
    $conn = $db->getConnection();

    $sql ="SELECT `IDX` FROM `tbl_wet_data` WHERE `IDX_DATETIME` = '".$time['dateTimeSql']."'";
    //var_dump($sql);
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

}

$weather = new WeatherData();
$lat_long = $weather->getLatLong();

$dateTime = $weather->getDateTime();
if($weather->NeedUpdate($dateTime)){

  for($i=0; $i<sizeof($lat_long); $i++){
    $res = $weather->getData($lat_long[$i]["Grid_X"], $lat_long[$i]["Grid_Y"],$dateTime["time_input"], $dateTime["date_input"]);
    //var_dump($dateTime);
    $weather->insertData($lat_long[$i]["Grid_X"], $lat_long[$i]["Grid_Y"], $dateTime["dateTimeSql"], $res);
  }

  echo "Data Updated";
}else{
  echo "No data update";
}
//var_dump($dateTime);

 ?>
