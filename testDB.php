<?php

  include_once "config/database.php";

  $database = new Database();
  $conn = $database->getConnection();

  if(!$conn){
    echo "<h1>Not connected</h1>";
  }else{
    echo "<h1>Connected</h1>";
  }

 ?>
