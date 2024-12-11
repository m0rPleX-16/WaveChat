<?php
class db
{    
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'sms_app';
    
    protected $connection;
    
    public function __construct(){

        if (!isset($this->connection)) {
            
            $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);
            
            if ($this->connection->connect_error) {
                die('Connect Error (' . $this->connection->connect_errno . ') ' . $this->connection->connect_error);
            }
            
            $this->connection->query("SET time_zone = '+08:00'");
        }    
    }

    public function getConnection() {
        return $this->connection;
    }
}
?>