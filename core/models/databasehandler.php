<?php

/* require_once('../helpers/commonhelper.php'); */


class DatabaseHandler {
    private $host = "localhost";
    private $db_name = "project";  
    private $username = "root";
    private $password = "";
    public $conn;

    public function __construct(){
        $this->conn = null; 
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
    }

    public function oes_test(...$va){
        echo "Enter oes test fnuctions that means Done Dona Done";
    }
    public function insert($table = "", $data = array()) {
        try {
           
            global $india_timezone;
            if( function_exists('india_timezone') ) india_timezone();
            if (empty($table)) {
                throw new InvalidArgumentException('Table name cannot be empty.');
            }
            if( function_exists( 'stripslashes_deep' ) ) 
                stripslashes_deep($data); 


            $data['createdat'] = date( 'd-F-Y , H:i:s' );

            $columns = implode(',', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));
            $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
            
            
            $stmt = $this->conn->prepare($query);
            
            foreach ($data as $column => &$value) { 
                $value = ( is_string($value) ) ? trim( $value ) : $value;
                $stmt->bindParam(":$column", $value); 
            }

            $stmt->execute();

            
            if($stmt == true){
                return true;
            }else{
                return false;
            }

        } catch(PDOException $exception) {
            echo "Insert error: " . $exception->getMessage();
        }
    }

    public function update($table = "", $data = array(), $condition = "") {
        try {
            india_timezone();
            if (empty($table)) {
                throw new InvalidArgumentException('Table name cannot be empty.');
            }

            if (empty($condition)) {
                throw new InvalidArgumentException('Update condition cannot be empty.');
            }
            $data = stripslashes_deep($data); // Assuming stripslashes_deep is defined

            $data['updatedat'] = date( 'd m y , H:i:s' );

            $setClause = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));

            $query = "UPDATE $table SET $setClause WHERE $condition";
            $stmt = $this->conn->prepare($query);

            foreach ($data as $column => &$value) {
                $value = ( is_string($value) ) ? trim( $value ) : $value;
                $stmt->bindParam(":$column", $value);
            }

            $stmt->execute();
        } catch(PDOException $exception) {
            echo "Update error: " . $exception->getMessage();
        }
    }

    public function select($query) {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $exception) {
            echo "Fetch error: " . $exception->getMessage();
        }
    }

    public function __destruct() {
        $this->conn = null;  // Close connection
    }
}


global $DatabaseHandler;
$DatabaseHandler = new DatabaseHandler();

?>
