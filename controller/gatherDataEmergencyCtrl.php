<?php

  include_once 'config/database.php';
/**
 *
 */
class GatherDataEmergencyCtrl
{
  public $conn;
  //private $tblName = "tbl_emergency_data";

  public function __construct (){
    $db = new Database();
    $conn = $db->getConnection();
  }

  public function checkdate_db($data){
    $db = new Database();
    $conn = $db->getConnection();

    //just take the first
    //for($i =0; $i < 9 ; $i++){
      //date is data -> 1
    $sql =  "SELECT IDX from `tbl_emergency_data` WHERE SD='".$data[3]."' AND GEN_YMDHMS='".$data[1]."'";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        printf("Error: %s\n", mysqli_error($conn));
        exit();
    }
    $row = mysqli_fetch_array($result, MYSQLI_NUM);

    //var_dump($row);

    if($row > 0){
      return TRUE;
    }else{
      return FALSE;
    }

    //}
  }

  public function getAllEmergencyData(){
    $db = new Database();
    $conn = $db->getConnection();

    $sql =  "SELECT EMERGENCY_CODE, SD, SGG, EMERGENCY_TEXT, GEN_YMDHMS
    FROM `tbl_emergency_data`";
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

  public function insertData($dataArr){
      $db = new Database();
      $conn = $db->getConnection();

      foreach ($dataArr as $key) {
        //validate
        if(!$this->checkdate_db($key)){
          //get station name
          $sql =  "SELECT FK_PROVINCE_ID from `city` WHERE NAME LIKE '%".$key[3]."%'";
          $result = mysqli_query($conn, $sql);
          if (!$result) {
              printf("Error: %s\n", mysqli_error($conn));
              exit();
          }
          $row = mysqli_fetch_array($result, MYSQLI_NUM);
          //var_dump($sql);

          $sql = "INSERT INTO `tbl_emergency_data`(`EMERGENCY_CODE`, `SD`, `SGG`, `EMERGENCY_TEXT`, `GEN_YMDHMS`) VALUES ('001', '".$key[3]."', '???', '".$key[2]."', '".$key[1]."');";
          //var_dump($sql);
          $result = mysqli_query($conn, $sql);

          if (!$result) {
              printf("Error: %s\n", mysqli_error($this->conn));
              exit();
          }
        }
      }

      mysqli_close($conn);
  }
}

 ?>
