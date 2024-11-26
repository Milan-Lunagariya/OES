<?php
if( class_exists( 'commonhelper', true ) ){
    $commonhelper = new commonhelper();
}


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

            $data['createdat'] = $commonhelper->oes_get_timestamp();

            $columns = implode(',', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));
            $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
            
            
            $stmt = $this->conn->prepare($query);
            
            foreach ($data as $column => &$value) { 
                $value = ( is_string($value) ) ? trim( $value ) : $value;
                $stmt->bindParam(":$column", $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR); 
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

    public function update($table = "", $data = array(), $where_clause = array(), $operator = '=', $groupby = '', $orderby = '', $limit = '') {
        try {
            global $commonhelper;
    
            if (empty($table)) {
                throw new InvalidArgumentException('In updat: Table name cannot be empty.');
            } 

            if (empty($where_clause) || !is_array($where_clause) || count($where_clause) < 1) {
                throw new InvalidArgumentException('In update: Update condition cannot be empty.');
            }

            if (isset($commonhelper)) {
                if (method_exists($commonhelper, 'oes_get_timestamp')) {
                    $data['updatedat'] = $commonhelper->oes_get_timestamp();
                }
                if (method_exists($commonhelper, 'stripslashes_deep')) {
                    $data = $commonhelper->stripslashes_deep($data);
                }
            } 

            $setData = array();
            foreach ($data as $data_column => $data_value) {
                $setData[] = "$data_column = :set_$data_column";           
            }
            $setDataStr = implode(', ', $setData);
     
            $condition = array();
            foreach ($where_clause as $column => $value) {
                $condition[] = "$column = :where_$column";
            }

            $conditionStr = implode(" $operator ", $condition);
     
            /* echo '<Br> Query: '. */$query = "UPDATE $table SET $setDataStr WHERE $conditionStr $groupby $orderby $limit"; 

            $stmt = $this->conn->prepare($query);
     
            foreach ($data as $set_column => $set_value) { 
                /* echo '<Br> Set value with column: '.  */ $stmt->bindValue( ":set_$set_column", $set_value ); 
                // echo "<br> :set_$set_column , $set_value ";
            }

            foreach ($where_clause as $where_column => $where_value) { 
                /* echo '<Br> Where cluse: '. */$stmt->bindValue(":where_$where_column", $where_value); 
                /* echo ":where_$where_column , $where_value"; */
            }  
            
            $result = $stmt->execute(); 
            return ($result) ? true : false;
    
        } catch (PDOException $exception) { 
            error_log("Update error: " . $exception->getMessage()); // Log the error
            return false;  
        }
    }

    public function delete( $table_name, $where_clause = array() ){
        try {
            global $commonhelper;
            $using_in = false;

            $column = implode(',', array_keys($where_clause));
 
            $where_clause = ( is_array( $where_clause ) ) ? $where_clause : array();
            $condition = '';
            $in_key = '';
            foreach( $where_clause as $data ){

                $correctdata['column'] = ( isset( $data['column'] ) ) ? $data['column'] : '';
                $correctdata['operator'] = ( isset( $data['operator'] ) && ! empty( $data['operator'] ) ) ? $data['operator'] : '='; 
                $correctdata['conjunction'] = ( isset( $data['conjunction'] ) ) ? $data['conjunction'] : '';
                if( strtoupper($correctdata['operator']) == 'IN' ){
                    
                    foreach( $data['value'] as $key => $value){
                        $in_key .= !empty( $in_key ) ? ", :key_$key" : ":key_$key";
                    } 
                    $condition .= " {$correctdata['column']} IN ( {$in_key} )  {$correctdata['conjunction']}";
                } else {
                    $condition .= " {$correctdata['column']} {$correctdata['operator']} :{$correctdata['column']} {$correctdata['conjunction']} ";
                }
            }
 
            $query = "DELETE FROM $table_name";

            if( count( $where_clause ) > 0 ) {
                $query .= " WHERE $condition";
            }
            
            /* echo $query; */
            $stmt = $this->conn->prepare( $query ); 
            
            foreach(  $where_clause as $setData ){
                
                if( !isset( $setData['column'] ) || !isset( $setData['value'] ) ){
                    break;
                }
                $setData['type'] = ( isset( $setData['type'] ) && !empty( $setData['type'] ) ) ? $setData['type'] : PDO::PARAM_STR;
               
                if( isset( $setData['operator'] ) && strtoupper( $setData['operator'] ) == 'IN' ){ 
                    
                    foreach( $setData['value'] as $key => $value ){ 
                        $stmt->bindValue( ":key_$key", $value, $setData['type'] ); 
                    } 

                } else {
                    $stmt->bindValue( ":{$setData['column']}", "{$setData['value']}", $setData['type'] ); 
                }
                
               /*  echo "<br>:{$setData['column']} = {$setData['value']} " ; */
            }  
            return $stmt->execute();

        } catch(Exception $exception){
            echo "Delete error: " . $exception->getMessage();
        }
    }

    public function select( $table = '', $select_column = '', $where_clause = array(),  $groupby = '', $orderby = '', $limit = '', $offset = '' ) {
        try {

            if (empty($table)) {
                throw new InvalidArgumentException('In select :Table name cannot be empty.');
            } 
 
            $WhereClause_data = array();
            $condition = '';
            $query = '';
            $in_key = '';
             
            $where_clause = ( is_array( $where_clause ) ) ? $where_clause : array();
            foreach( $where_clause as $data ){

                $correctdata['column'] = ( isset( $data['column'] ) ) ? $data['column'] : '';
                $correctdata['operator'] = ( isset( $data['operator'] ) && ! empty( $data['operator'] ) ) ? $data['operator'] : '=';
                $correctdata['conjunction'] = ( isset( $data['conjunction'] ) ) ? $data['conjunction'] : '';

                if( strtoupper($correctdata['operator']) == 'IN' ){
                    
                    foreach( $data['value'] as $key => $value){
                        $in_key .= !empty( $in_key ) ? ", :key_$key" : ":key_$key";
                    } 
                    $condition .= " {$correctdata['column']} IN ( {$in_key} )  {$correctdata['conjunction']}";
                } else {
                    $condition .= " {$correctdata['column']} {$correctdata['operator']} :{$correctdata['column']} {$correctdata['conjunction']} ";
                }
            }
  
            $query .= "SELECT $select_column FROM $table"; 
             
            if( isset( $where_clause[0]['use'] ) && strtolower( $where_clause[0]['use'] ) == 'join' ){
                $query .= " ON $condition";                
            } elseif( count( $where_clause ) > 0 ) {
                $query .= " WHERE $condition";
            } 

            if( ! empty( $groupby ) ){
                $query .= " GROUP BY $groupby";
            } 
            if( ! empty( $orderby ) ){
                $query .= " ORDER BY $orderby";
            }
            if( ! empty( $limit ) ){
                $query .= " LIMIT $limit";
            }
            if( ! empty( $offset ) ){
                $query .= " OFFSET $offset";
            }
 
            /* echo '<br>-----------<br>';
            echo  $query;
            echo '<br>-----------<br>'; */
            $stmt = $this->conn->prepare($query);
            foreach(  $where_clause as $setData ){
                
                if( !isset( $setData['column'] ) || !isset( $setData['value'] ) ){
                    break;
                }
                $setData['type'] = ( isset( $setData['type'] ) && !empty( $setData['type'] ) ) ? $setData['type'] : PDO::PARAM_STR;
               
                if( isset( $setData['operator'] ) && strtoupper( $setData['operator'] ) == 'IN' ){ 
                    
                    foreach( $setData['value'] as $key => $value ){ 
                        $stmt->bindValue( ":key_$key", $value, $setData['type'] ); 
                        /* echo "<br>:key_{$key} = {$value} " ; */
                    } 

                } else {
                    $stmt->bindValue( ":{$setData['column']}", "{$setData['value']}", $setData['type'] ); 
                    // echo "<br>:{$setData['column']} = {$setData['value']} <br>";
                }
                
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
