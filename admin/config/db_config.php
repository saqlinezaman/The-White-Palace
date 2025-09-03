<?php

class Database {
    private $host = "localhost";
    private $db_name = "the_white_palace";
    private $username = "root";
    private $password = "";

    public $conn;
    public function db_connection(){
        $this->conn = null;
       try {
    $this->conn = new PDO(
        "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
        $this->username,
        $this->password
    );
    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    }catch(PDOException $e){
        echo "Connection Error: ".$e->getMessage();
    }
    return $this->conn;
}
}

?>