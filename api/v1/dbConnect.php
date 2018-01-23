<?php

 class dbConnect {

    private $conn;

    function __construct() {        
    }
    function connect() {
        include_once '../config.php';
        $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8';
        try {
        $this->conn = new PDO($dsn, DB_USERNAME, DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        } catch (PDOException $e) {
            $response["status"] = "error";
            $response["message"] = 'Connection failed: ' . $e->getMessage();
            $response["data"] = null;
        }
        // Check for database connection error

        // returing connection resource
        return $this->conn;
        
    }

}

?>
