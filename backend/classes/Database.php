<?php

namespace Backend\Classes;

use Dotenv\Dotenv;
use mysqli;

/**
 * Class Database
 * Handles the connection to the MySQL/MariaDB database and provides methods
 * to execute various types of database queries (scalar, non-query & query query).
 * 
 * @package Backend\Classes
 * 
 */
class Database {
    /**
     * @var mysqli|null The database connection instance
     */
    private $conn;

    /**
     * Database Constructor
     * 
     * Loads environment variables and establishes a connection to the database
     * during object instantiation
     */
    public function __construct() {
        $this->loadEnvironmentVariables();
        $this->connect();
    }

    /**
     * Database Destructor
     * 
     * Closes the database connection when object is destroyed
     */
    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }

    /**
     * Loads Environment Variables fron .env file
     * 
     * This method loads the environment variables (database connection info)
     * from the .env file using the Dotenv library
     */
    private function loadEnvironmentVariables() {
        require __DIR__ . "/../vendor/autoload.php";
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
    }

    /**
     * Establishes a connection to the MySQL/MariaDB database
     * 
     * Connects to the database using credentials loaded from environment
     * variables and checks for any connection errors
     */
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

    /**
     * Executes a scalar query (returns a single cell/value)
     * 
     * Executes a query that is expected to return a single cell/value
     * Bind parameters are passed as an array
     * 
     * @param string $query The SQL query to execute
     * @param array $params An optional array of parameters to bind to the query
     * @return mixed The single value returned by the query (e.g., a number of string)
     */
    public function executeScalarQuery($query, $params = []) {
        $stmt = $this->conn->prepare($query);
        if ($params) {
            $stmt->bind_param(...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_row();
        return $row ? $row[0] : null;
    }

    /**
     * Executes a non-query SQL query (e.g., INSERT, UPDATE, DELETE)
     * 
     * Executes a query that does not return data but may modify data in the database
     * 
     * @param string $query The SQL query to execute
     * @param array $params An optional array of parameters to bind to the query
     * @return bool True if the query was sucessful, false otherwise
     */
    public function executeNonQuery($query, $params = []) {
        $stmt = $this->conn->prepare($query);
        if ($params) {
            $stmt->bind_param(...$params);
        }
        $sucess = $stmt->execute();
        $stmt->close();
        return $sucess;
    }

    /**
     * Executes a query and returns the result as an associative array
     * 
     * Executes a query that returns a table of data (e.g., SELECT) and returns result set
     * as an associative array
     * 
     * @param string $query The SQL query to execute
     * @param array $params An optional array of parameters to bind to the query
     * @return array An array of associative arrays containing the query results
     */
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