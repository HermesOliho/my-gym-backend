<?php
class Database
{
    private $host = "unix_socket=/run/mysqld/mysqld.sock";
    private $port = "3306";
    private $db_name = "my_gym";
    private $username = "root";
    private $password = "Herom";
    public $conn;

    public function getConnection(): PDO | null
    {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
