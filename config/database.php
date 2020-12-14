<?php
class Database{

    // specify your own database credentials
    // private $host = "192.168.0.17:3306";
    // private $db_name = "rcmsdb";
    // private $username = "super";
    // private $password = "password";

    private $host = "localhost";
    private $db_name = "rcms_new";
    private $username = "root";
    private $password = "";

    //public $conn;

    // get the database connection
    public function getConnection(){

        $this->conn = null;
        $this->conn = mysqli_connect("$this->host", "$this->username", "$this->password", "$this->db_name");

        if (!$this->conn) {
            echo "Error: Unable to connect to MySQL." . PHP_EOL;
            echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }

        return $this->conn;
    }
}
?>
