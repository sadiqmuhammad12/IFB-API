<?php
class Database{
  
    //specify your own database credentials
    // private $host = "db";
    // private $db_name = "idfb";
    // private $username = "idfb_user";
    // private $password = "kJfVqML59kzc8nMS";
    private $host = "localhost";
    private $db_name = "fake_beggar";
    private $username = "root";
    private $password = "";

    // this is for testing commit

    public $conn;
  
    // get the database connection
    public function getConnection(){
  
        $this->conn = null;
  
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
  
        return $this->conn;
    }
}
?>