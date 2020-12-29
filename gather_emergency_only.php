<?php

include_once ('config/database.php');
//include_once ('header.php');

class EmergencyMess
{
  public function getData(){
    //$api_url = "http://openapi.airkorea.or.kr/openapi/services/rest/ArpltnInforInqireSvc/getCtprvnMesureLIst?itemCode=PM10&dataGubun=HOUR&searchCondition=MONTH&pageNo=1&numOfRows=".$req_data."&ServiceKey=5rngHQO75rpCVULQ4UNMIXpRWSMzzTduA%2F2N47nrYL3Tc4sHHCAJ3F0k61NC05iQLdrR20%2Bur41a9nEPmpVhtQ%3D%3D";
    $api_url = "http://apis.data.go.kr/1741000/DisasterMsg2/getDisasterMsgList?ServiceKey=5rngHQO75rpCVULQ4UNMIXpRWSMzzTduA%2F2N47nrYL3Tc4sHHCAJ3F0k61NC05iQLdrR20%2Bur41a9nEPmpVhtQ%3D%3D&type=xml&pageNo=1&numOfRows=10&flag=Y";
    $xmlstring = simplexml_load_file($api_url);

    //$xml = simplexml_load_string($xmlstring);
    $json = json_encode($xmlstring);
    $array = json_decode($json,TRUE);

    for($i=0 ; $i < sizeof($array['row']); $i++){
      $res[$i]['create_date'] = $array['row'][$i]['create_date'];
      $temp = $array['row'][$i]['location_name'];
      //contain phase 1 and phase 2
      $pieces = explode(" ", $temp);
      $res[$i]['sd'] = $pieces[0]; //phase 1
      $res[$i]['sgg'] = $pieces[1]; //phase 2

      $res[$i]['dissaster_msg_sn'] = $array['row'][$i]['md101_sn'];

      //the header contain just location
      $res[$i]['emergency_text'] = $array['row'][$i]['msg'];
      //$pieces = explode("]", $res[$i]['emergency_text']);
      //$res[$i]['msg_header'] = $pieces[0];
    }

    return ($res);
  }

  public function insertData($single_data){
    $db = new Database();
    $conn = $db->getConnection();

    $sql = "SELECT * FROM `tbl_emergency_data` WHERE `GEN_YMDHMS` = '".$single_data['create_date']."';";

    $result = mysqli_query($conn, $sql);
    if (!$result) {
        printf("Error: %s\n", mysqli_error($conn));
        exit();
    }

    $row = $result->fetch_assoc();
    //var_dump($sql);
    if($row){
      mysqli_close($conn);
      return 0;
    }

    $sql = "INSERT INTO `tbl_emergency_data`(`EMERGENCY_CODE`, `SD`, `SGG`, `EMERGENCY_TEXT`, `GEN_YMDHMS`)
    VALUES ('001', '".$single_data['sd']."', '".$single_data['sgg']."' , '".$single_data['emergency_text']."', '".$single_data['create_date']."')";

    $result = mysqli_query($conn, $sql);
    //var_dump($sql);
    if (!$result) {
        printf("Error: %s\n", mysqli_error($conn));
        exit();
    }

    mysqli_close($conn);
    return 1;
  }
}

$emergency = new EmergencyMess();
$res = $emergency->getData();

//var_dump($res);
$total;
for($i=0; $i<sizeof($res) ; $i++){
  $total += $emergency->insertData($res[$i]);
}

if($total > 0){
  echo "Updated ".$total." Messages";
}else{
  echo "No data updated";
}

 ?>
