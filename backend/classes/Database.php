<?php

namespace Backend\Classes;

use Dotenv\Dotenv;
use mysqli;

class Database {
    private $conn;

    public function __construct() {
        $this->loadEnvironmentVariables();
        $this->connect();
    }

    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }

    private function loadEnvironmentVariables() {
        require __DIR__ . "/../vendor/autoload.php";
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
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

    public function executeScalarQuery($query, $params = []) {
        $stmt = $this->conn->prepare($query);
        if ($params) {
            $stmt->bind_param(...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_row();
        return $row[0];
    }

    public function executeNonQuery($query, $params = []) {
        $stmt = $this->conn->prepare($query);
        if ($params) {
            $stmt->bind_param(...$params);
        }
        $sucess = $stmt->execute();
        $stmt->close();
        return $sucess;
    }

    public function executeQuery($query, $params = []) {
        $stmt = $this->conn->prepare($query);
        if ($params) {
            $stmt->bind_param(...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

?>