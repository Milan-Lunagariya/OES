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
            global $commonhelper;

            if (empty($table)) {
                throw new InvalidArgumentException('Table name cannot be empty.');
            }


            if( isset($commonhelper) && method_exists( $commonhelper, 'india_timezone' ) ) {
                $commonhelper->india_timezone(); 
            }

            if( isset($commonhelper) && method_exists( $commonhelper, 'stripslashes_deep' ) ) {
                $commonhelper->stripslashes_deep($data); 
            } 

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
            global $commonhelper;
            if (empty($table)) {
                throw new InvalidArgumentException('Table name cannot be empty.');
            }

            if (empty($condition)) {
                throw new InvalidArgumentException('Update condition cannot be empty.');
            } 

            if( isset($commonhelper) && method_exists( $commonhelper, 'india_timezone' ) ) {
                $commonhelper->india_timezone(); 
            }

            if( isset($commonhelper) && method_exists( $commonhelper, 'stripslashes_deep' ) ) {
                $commonhelper->stripslashes_deep($data); 
            }

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

    public function delete( $table_name, $where_clause = array(), $operator = '' ){
        try {
            global $commonhelper;

            $column = implode(',', array_keys($where_clause));
 
            $where =    ( count( $where_clause ) > 0 ) ? "WHERE"   : '';
            $operator = ( count( $where_clause ) < 2 ) ? $operator : '';

            $condition = '';
            foreach( $where_clause as $column => $value ){
                $condition .= ' '.$column .' = '.' :'. $column . ' '.$operator.' ';
            }
            
            $query = "DELETE FROM $table_name $where $condition ";
            $stmt = $this->conn->prepare( $query );
            
            foreach( $where_clause as $column => $value ){
                $stmt->bindParam( ":$column", $value );
            }

            $res_bool = $stmt->execute();
            if( $res_bool ) {
                return true;
            } else{
                return false;
            }



        } catch(Exception $exception){
            echo "Delete error: " . $exception->getMessage();
        }
    }

    public function select($table = '', $select_column = '', $where_clause = array(), $operator = '',  $groupby = '', $orderby = '', $limit = '' ) {
        try {

            if(!is_array($where_clause)){
                throw new Exception( "By OES: Where clause must be array!" );
            }
            
            $column = implode(',', array_keys($where_clause));
 
            $where =    ( count( $where_clause ) > 0 ) ? "WHERE"   : '';
            $operator = ( count( $where_clause ) < 2 ) ? $operator : '';

            $condition = '';
            foreach( $where_clause as $column => $value ){
                $condition .= ' '.$column .' = '.' :'. $column . ' '.$operator.' ';
            }

            $query = "SELECT $select_column FROM $table $where $condition $groupby $orderby $limit";
            $stmt = $this->conn->prepare($query);

            foreach( $where_clause as $column => $value ){
                $stmt->bindParam( ":$column", $value );
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $exception) {
            echo "Fetch error: " . $exception->getMessage(); 
        }
    }


    public function __destruct() {
        $this->conn = null;  
    }
}


global $DatabaseHandler;
$DatabaseHandler = new DatabaseHandler();

?>
