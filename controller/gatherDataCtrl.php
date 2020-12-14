<?php

  include_once 'config/database.php';
/**
 *
 */
class GatherDataCtrl
{
  //public $conn;

  public function __construct (){
    $db = new Database();
    $conn = $db->getConnection();
  }

  public function getAllWeatherDust(){
    $db = new Database();
    $conn = $db->getConnection();

    //$sql = "SELECT * FROM tbl_dust_data;";
    //more readable
    $sql ="SELECT tbl_dust_data.IDX_DATETIME, tbl_dust_data.ITEMCODE, province.NAME_HGL, tbl_dust_data.VALUE_1
    FROM `tbl_dust_data` , `province`
    WHERE province.IDX = tbl_dust_data.LOCATION;";

    $result = mysqli_query($conn, $sql);
    if (!$result) {
        printf("Error: %s\n", mysqli_error($this->conn));
        exit();
    }

    $row = $result->fetch_assoc();
    if($row > 0){
      $itt = 0;
      while($row[$itt] = $result->fetch_assoc()){
        $itt++;
      }
    }
    //print_r($row);
    mysqli_close($conn);

    return($row);
  }

  public function getAllWeatherData(){
    $db = new Database();
    $conn = $db->getConnection();

    //$sql = "SELECT * FROM tbl_wet_data;";
    //more readable
    $sql = "SELECT tbl_wet_data.IDX_DATETIME, city.NAME, province.NAME_HGL, tbl_wet_data.VALUE_1, tbl_wet_data.VALUE_2, tbl_wet_data.VALUE_3
    FROM tbl_wet_data, city, province
    WHERE tbl_wet_data.LOCATION = city.CITY_ID AND province.IDX = city.FK_PROVINCE_ID;";

    $result = mysqli_query($conn, $sql);
    if (!$result) {
        printf("Error: %s\n", mysqli_error($this->conn));
        exit();
    }

    $row = $result->fetch_assoc();
    if($row > 0){
      $itt = 0;
      while($row[$itt] = $result->fetch_assoc()){
        $itt++;
      }
    }
    //print_r($row);
    mysqli_close($conn);

    return($row);
  }

  public function getAllGatherData(){
    $db = new Database();
    $conn = $db->getConnection();

    $sql = "SELECT * FROM tbl_gather_data;";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        printf("Error: %s\n", mysqli_error($this->conn));
        exit();
    }

    $row = $result->fetch_assoc();
    if($row > 0){
      $itt = 0;
      while($row[$itt] = $result->fetch_assoc()){
        $itt++;
      }
    }
    //print_r($row);
    mysqli_close($conn);

    return($row);
  }

  public function insertWeatherData($arrData){
    $db = new Database();
    $conn = $db->getConnection();

    //var_dump($arrData[0][0]);

    $newDate = substr_replace($arrData[0]['time'], " ", strlen($arrData[0]['time'])- 6, 1);
    $newDate = str_replace (".", "-", $newDate);

    $itt = 0;
    foreach ($arrData as $key) {

      //first one is just time
      if($itt > 1){
        $sql = "INSERT INTO `tbl_wet_data`(`IDX_DATETIME`, `DATA_GUBUN`, `LOCATION`, `GATHER_INFO`, `VALUE_1`, `VALUE_2`)
        VALUES ('".$newDate."', 'T,H,D', (SELECT CITY_ID FROM city WHERE NAME = '".$key['city']."' LIMIT 1) , '', '".$key['temp']."', '".$key['hum']."')";
        //var_dump($sql);
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            printf("Error: %s\n", mysqli_error($this->conn));
            exit();
        }
        //var_dump($key);
      }
      //var_dump($key);
      $itt++;
    }

    mysqli_close($conn);
  }

  public function insertDustData($arrData){
    $db = new Database();
    $conn = $db->getConnection();

    //due to xml format, fetch the name or array keys
    $itt = 0; //itt 0 --> time, itt 1 --> itemCode, itt 2 --> unimportant
    foreach (array_keys($arrData) as $key) {
        //print_r(array_keys($key));
        //print_r($key[array_keys($key)[3]]);
        //var_dump($arrData[$key]);
        if($itt > 2){
          $sql = "INSERT INTO `tbl_dust_data`(`IDX_DATETIME`, `ITEMCODE` , `LOCATION`, `VALUE_1`)
          VALUES ('".$arrData['dataTime']."', '".$arrData['itemCode']."', (SELECT IDX FROM province WHERE NAME_ROM='".$key."'), '".$arrData[$key]."')";
          //print_r($sql);

          $result = mysqli_query($conn, $sql);
          if (!$result) {
              printf("Error: %s\n", mysqli_error($this->conn));
              exit();
          }
        }
        $itt++;
      }

    mysqli_close($conn);
  }

  private function getFKScrape($scrape, $dust, $curr_time){

    $db = new Database();
    $conn = $db->getConnection();

    //print_r($scrape);
    $sql = "SELECT p.NAME_ROM FROM city c, province p WHERE c.NAME='".$scrape['city']."' AND c.FK_PROVINCE_ID = p.IDX;";

    //print_r($sql);
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        printf("Error: %s\n", mysqli_error($this->conn));
        exit();
    }
    $row = mysqli_fetch_array($result, MYSQLI_NUM);

    $scrape['dust'] = $dust[$row[0]];
    //$scrape['dataTime'] = $dust['dataTime'];
    $scrape['dataTime'] = $curr_time;
    //print_r($scrape);
    mysqli_close($conn);
    return $scrape;
  }

  public function setupDataArr($scrape, $dust){

    //$final_arr[] = array();
    $newDate = "";
    $itt = 0;
    foreach ($scrape as &$key) {
      if($itt > 0 ){
        //print_r($key);
        //$final_arr[][] = array();
        //$scrape[$key] = $this->getFKScrape($key, $dust);
        $key = $this->getFKScrape($key, $dust, $newDate);
        //print_r($key);
        //print_r($value);
      }
      else{
        $newDate = substr_replace($key['time'], " ", strlen($key['time'])- 6, 1);
        $newDate = str_replace (".", "-", $newDate);
        //print_r($key);
      }
      //$final_arr[$key] = $key;
      $itt++;
    }

    return $scrape;
  }

  public function checkdate_db($array){
    $db = new Database();
    $conn = $db->getConnection();

    //print_r($array);
    $newDate = substr_replace($array[0]['time'], " ", strlen($array[0]['time'])- 6, 1);
    $newDate = str_replace (".", "-", $newDate);
    //print_r(substr_replace($array[0]['time'], " ", strlen($array[0]['time'])- 6, 1));
    //formalize the format
    //print_r($newDate);
    $sql = "SELECT DATETIMES from `tbl_data_time` WHERE DATETIMES='".$newDate."'";
    //print_r($sql);
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        printf("Error: %s\n", mysqli_error($conn));
        exit();
    }
    $row = mysqli_fetch_array($result, MYSQLI_NUM);

    if($row > 0){
      $res = TRUE;
    }else{
      //insertData
      $sql = "INSERT INTO `tbl_data_time`(`DATETIMES`) VALUES ('".$array[0]['time']."')";
      //print_r($sql);
      $result = mysqli_query($conn, $sql);
      if (!$result) {
          printf("Error: %s\n", mysqli_error($conn));
          exit();
      }
      $res = FALSE;
    }

    mysqli_close($conn);

    return ($res);
  }

  public function insertData($array)
  {
    $db = new Database();
    $conn = $db->getConnection();

    $itt = 0;
    foreach ($array as $key) {
      if($itt > 0){
        //print_r(array_keys($key)[3]);
        //print_r($key[array_keys($key)[3]]);
        $sql = "INSERT INTO `tbl_gather_data`(`IDX_DATETIME`, `DATA_GUBUN`, `LOCATION`, `GATHER_INFO`, `VALUE_1`, `VALUE_2`, `VALUE_3`, `GATHER_TYPE`)
        VALUES ('".$key['dataTime']."', 'T,H,D', '".$key['city']."', '', '".$key['temp']."', '".$key['hum']."', '".$key['dust']."', '1,3')";

        $result = mysqli_query($conn, $sql);
        if (!$result) {
            printf("Error: %s\n", mysqli_error($this->conn));
            exit();
        }
      }
      $itt++;
    }

    mysqli_close($conn);
  }
}


 ?>
