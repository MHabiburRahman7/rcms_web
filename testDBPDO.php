<?php

$dsn = "mysql:host=localhost";
$user = "root";
$passwd = "";
//$dsn = "mysql:host=192.168.0.17:3306";
//$user = "super";
//$passwd = "password";

try {
  $pdo = new PDO($dsn, $user, $passwd);

  $stm = $pdo->query("SELECT VERSION()");

  $version = $stm->fetch();

  echo $version[0] . PHP_EOL;
} catch(Exception $e) {
    echo $e->getMessage();
}
?>
