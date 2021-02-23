<?php
/**
 *  * Created by hannah on 2/23/2021.
 */

namespace App\config;

use PDO;
use PDOException;

class Database
{
    // credentials
    private $host = "localhost";
    private $db_name = "todo_app";
    private $username = "root";
    private $password = "";
    public $conn;

    // database connection
    public function getConnection()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password);
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}