<?php

namespace Backend;

use Dotenv\Dotenv;
use mysqli;

class Database {
    private $conn;

    public function __construct() {
        $this->loadEnvironmentVariables();
        $this->connect();
    }

    private function loadEnvironmentVariables() {
        require __DIR__ . "/../vendor/autoload.php";
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();
    }

    private function connect() {
        $servername = $_ENV['MYSQL_SERVER_NAME'];
        $dbname = $_ENV['MYSQL_DATABASE_NAME'];
        $username = $_ENV['MYSQL_USERNAME'];
        $password = $_ENV['MYSQL_PASSWORD'];
        $dbport = $_ENV['MYSQL_PORT'];

        $this->conn = new mysqli($servername, $username, $password, $dbname, $dbport);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}

?>