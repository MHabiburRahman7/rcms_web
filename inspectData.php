<?php
 include_once "header.php";
 include_once "controller/gatherDataCtrl.php";
 include_once "controller/gatherDataEmergencyCtrl.php";
 ?>

<div class = "row">
  <div class="container">
 <h1>Show All data</h1>

 <h2>Weather Data</h2>

 <?php
  $gather = new GatherDataCtrl();
  //$res = $gather->getAllGatherData();
  $res = $gather->getAllWeatherData();

  if($res > 0){
    echo "<table id='dtBasicExample' class='table-striped table-bordered table-sm' cellspacing='0' width='100%'><thead><tr>
    <th scope='col'>Date Time</th>
    <th scope='col'>Location City / Province</th>
    <th scope='col'>temp</th>
    <th scope='col'>humidity</th>
    <th scope='col'>VALUE 3</th>
    </tr></thead>";

    echo "<tbody>";
    //var_dump($res);
    //print_r(sizeof($res));
    //print_r(gettype($res));
    foreach ($res as $key) {
      if(is_array($key)){
        echo "<tr>";
        //echo "<td>".$key['IDX']."</td>";
        echo "<th scope='row'>".$key['IDX_DATETIME']."</td>";
        echo "<td>".$key['NAME']." -- ".$key['NAME_HGL']."</td>";
        echo "<td>".$key['VALUE_1']."</td>";
        echo "<td>".$key['VALUE_2']."</td>";
        echo "<td>".$key['VALUE_3']."</td>";
        echo "</tr>";
      }
      //print_r("this is dump: ");
      //var_dump($value);
    //for($i=0; $i<sizeof($res); $i++){
      //echo "<tr>";
      //echo "<td>".$res[$i]['IDX']."</td>";
      // echo "<td>".$key[$i]['IDX_DATETIME']."</td>";
      // echo "<td>".$key[$i]['LOCATION']."</td>";
      // echo "<td>".$key[$i]['VALUE_1']."</td>";
      // echo "<td>".$key[$i]['VALUE_2']."</td>";
      // echo "<td>".$key[$i]['VALUE_3']."</td>";
      //echo "</tr>";

      //print_r($key);
      //echo "<tr>";
      //echo "<td>".$key['IDX']."</td>";
      //echo "<td>".$key['IDX_DATETIME']."</td>";
      //echo "<td>".$key['LOCATION']."</td>";
      //echo "<td>".$key['VALUE_1']."</td>";
      //echo "<td>".$key['VALUE_2']."</td>";
      //echo "<td>".$key['VALUE_3']."</td>";
      //echo "</tr>";
    }

    echo "</tbody></table>";
  }else{
    echo "<h1>There are no data stored</h1>";
  }
  ?>

  <h2>Dust Data</h2>
  <?php
   $gather = new GatherDataCtrl();
   //$res = $gather->getAllGatherData();
   $res = $gather->getAllWeatherDust();

   if($res > 0){
     echo "<table id='dtBasicExample1' class='table-striped table-bordered table-sm' cellspacing='0' width='100%'><thead><tr>
     <th scope='col'>Date Time</th>
     <th scope='col'>Location Province</th>
     <th scope='col'>Item Code</th>
     <th scope='col'>Value 1</th>
     </tr></thead>";

     echo "<tbody>";
     //var_dump($res);
     //print_r(sizeof($res));
     //print_r(gettype($res));
     foreach ($res as $key) {
       if(is_array($key)){
         echo "<tr>";
         //echo "<td>".$key['IDX']."</td>";
         echo "<th scope='row'>".$key['IDX_DATETIME']."</td>";
         echo "<td>".$key['NAME_HGL']."</td>";
         echo "<td>".$key['ITEMCODE']."</td>";
         echo "<td>".$key['VALUE_1']."</td>";
         echo "</tr>";
       }
     }

     echo "</tbody></table>";
   }else{
     echo "<h1>There are no data stored</h1>";
   }
   ?>

   <h2>Emergency Data</h2>
   <?php
    $gather = new GatherDataEmergencyCtrl();
    //$res = $gather->getAllGatherData();
    $res = $gather->getAllEmergencyData();

    if($res > 0){
      echo "<table id='dtBasicExample2' class='table-striped table-bordered table-sm' cellspacing='0' width='100%'><thead><tr>
      <th scope='col'>Date Time</th>
      <th scope='col'>Emergency Code</th>
      <th scope='col'>Issued Location</th>
      <th scope='col'>Metropolitan City</th>
      <th scope='col'>Emergency Text</th>
      </tr></thead>";

      echo "<tbody>";
      //var_dump($res);
      //print_r(sizeof($res));
      //print_r(gettype($res));
      foreach ($res as $key) {
        if(is_array($key)){
          echo "<tr>";
          //echo "<td>".$key['IDX']."</td>";
          echo "<th scope='row'>".$key['GEN_YMDHMS']."</td>";
          echo "<td>".$key['EMERGENCY_CODE']."</td>";
          echo "<td>".$key['SD']."</td>";
          echo "<td>".$key['SGG']."</td>";
          echo "<td>".$key['EMERGENCY_TEXT']."</td>";
          echo "</tr>";
        }
      }

      echo "</tbody></table>";
    }else{
      echo "<h1>There are no data stored</h1>";
    }
    ?>

</div>
</div>

<script type="text/javascript">
$(document).ready(function () {
  $('#dtBasicExample').DataTable();
  $('#dtBasicExample1').DataTable();
  $('#dtBasicExample2').DataTable();
  $('.dataTables_length').addClass('bs-select');
});
</script>
