<?php

  include_once 'scraping_emergency.php';

  include_once 'header.php';
  include_once 'controller/gatherDataEmergencyCtrl.php';
  //include_once 'config/database.php';

  $scrape = new ScraperEmergency();
  //$scrape_res = $scrape->getResult();
  //$scrape_res = $scrape->getResultUsingHeader();
  //$scrape_res = $scrape->getResultOpenApi();
  $scrape_res = $scrape->getResultPhantom();

  //var_dump($scrape_res);

  $ctrl = new GatherDataEmergencyCtrl();
?>

<body>
  <div class="container">
    <div class="row">

<?php
  echo "<h1>Gathering New Data</h1>";
  //check current saved data
  if(!$ctrl->checkdate_db($scrape_res[0])){
      //$result = $ctrl->setupDataArr($scrape_res, $api_res);
      $ctrl->insertData($scrape_res);
    echo "<h2>New Data inserted!</h2>";
  }else{
    echo "<h2>No new data inserted</h2>";
  }

  //print_r($result);
  // $database = new Database();
  // $conn = $database->getConnection();
  //
  // $newArr = array();
  // $sql = "SELECT province.NAME_ROM FROM city, province WHERE city.NAME='".$scrape_res[4]['city']."' AND city.FK_PROVINCE_ID = province.IDX" ;
  // //print_r($sql);
  // $result = mysqli_query($conn,$sql);
  // if (!$result) {
  //     printf("Error: %s\n", mysqli_error($conn));
  //     exit();
  // }
  // $row = mysqli_fetch_array($result, MYSQLI_NUM);
  //
  // $scrape_res[4][$row[0]] = $api_res[$row[0]];
  // $scrape_res[4]['dataTime'] = $api_res['dataTime'];
  // //print_r($scrape_res[4]);
  //
  // print_r($scrape_res[4]);
  //merge
  //print_r(array_keys($scrape_res[4])[3]);
  //print_r(array_merge($api_res, $scrape_res[4]));
  //printf ("%s (%s)\n", $row[0], $row[1]);

 ?>

</div>
</div>
</body>
