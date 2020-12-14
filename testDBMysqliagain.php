<?php
  $host = "192.168.0.17:3306";
  $db_name = "rcmsdb";
  $username = "super";
  $password = "password";

  $con = new mysqli($host, $username, $password, $db_name );
  if ($con->connect_error) {
      die("Connection failed: " . $con->connect_error);
  }
  echo "done";
?>
