<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'justudhari_db2';
    private $username = 'justudhari_db2'; // Change as needed
    private $password = '!gCF95w1/Af9t$vAB@c%R%aE';     // Change as needed
    private $conn;

    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password,
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}
?>
