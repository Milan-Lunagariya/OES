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

    public function update($table = "", $data = array(), $where_clause = array(), $operator = '', $groupby = '', $orderby = '', $limit = '') {
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
                /* echo " :set_$set_column , $set_value "; */
            }

            foreach ($where_clause as $where_column => $where_value) { 
                /* echo '<Br> Where cluse: '. */$stmt->bindValue(":where_$where_column", $where_value); 
                /* echo ":where_$where_column , $where_value"; */
            }  
            
            return ($stmt->execute()) ? true : false;
    
        } catch (PDOException $exception) { 
            error_log("Update error: " . $exception->getMessage()); // Log the error
            return false;  
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
                $stmt->bindParam( ":$column", $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR );
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
            $where =    ( is_array( $where_clause ) && count( $where_clause ) > 0 ) ? "WHERE"   : ''; 

            $condition = '';
            foreach( $where_clause as $column => $value ){

                if( $operator == '<>' ){ 
                    $condition .= "$column <> :$column";
                } else {
                    $condition .= ' '.$column .' = '.' :'. $column . ' '.$operator.' ';
                }
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
    /* function selectData($table_name, $data = '*', $where_clause = '', $params = [], $groupby = '', $orderby = '', $limit = '') {
        try {
    
            // Build base query
            $query = "SELECT $data FROM $table_name";
    
            // Add where clause if provided
            if (!empty($where_clause)) {
                $query .= " WHERE $where_clause";
            }
    
            // Add group by clause if provided
            if (!empty($groupby)) {
                $query .= " GROUP BY $groupby";
            }
    
            // Add order by clause if provided
            if (!empty($orderby)) {
                $query .= " ORDER BY $      ";
            }
    
            // Add limit if provided
            if (!empty($limit)) {
                $query .= " LIMIT $limit";
            }
    
            // Prepare and execute the query
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
    
            // Fetch data
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return $result;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } */
    
    public function selectData( $table_name, $data = '*', $where_clauses = array(), $types = array(), $groupby = '', $orderby = '', $limit = '') {
        try { 
            $query = "SELECT $data FROM $table_name";
    
            if (!empty($where_clauses)) {
                $conditions = array();
                foreach ($where_clauses as $condition) {
                    $conditions[] = "{$condition['column']} {$condition['operator']} :{$condition['placeholder']}";
                } 
                $query .= " WHERE " . implode(" ", array_map(function($condition, $i) use ($where_clauses) {
                    return ($i > 0 ? $where_clauses[$i]['conjunction'] . ' ' : '') . $condition;
                }, $conditions, array_keys($conditions)));
            }
     
            if (!empty($groupby)) {
                $query .= " GROUP BY $groupby";
            }
            if (!empty($orderby)) {
                $query .= " ORDER BY $orderby";
            }
            if (!empty($limit)) {
                $query .= " LIMIT $limit";
            }
            $stmt = $this->conn->prepare($query);
    
            foreach ($where_clauses as $condition) {
                $type = isset ( $types[ $condition['placeholder'] ]) ? $types[ $condition['placeholder'] ] : PDO::PARAM_STR;
                $stmt->bindValue(":{$condition['placeholder']}", $condition['value'], $type);
            }
     
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return !empty( $result ) ? $result : false;
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function selectOurnew($table = '', $select_column = '', $where_clause = array(),  $groupby = '', $orderby = '', $limit = '' ) {
        try {

            if (empty($table)) {
                throw new InvalidArgumentException('In select :Table name cannot be empty.');
            } 
 
            $WhereClause_data = array();
            $condition = '';
            $query = '';
             
            foreach( $where_clause as $data ){

                $correctdata['column'] = ( isset( $data['column'] ) ) ? $data['column'] : '';
                $correctdata['operator'] = ( isset( $data['operator'] ) ) ? $data['operator'] : '';
                $correctdata['conjunction'] = ( isset( $data['conjunction'] ) ) ? $data['conjunction'] : '';

                $condition .= " {$correctdata['column']} {$correctdata['operator']} :{$correctdata['column']} {$correctdata['conjunction']} ";
            }
 
             $query .= "SELECT $select_column FROM $table";
            
            if( count( $where_clause ) > 0){
                $query .= " WHERE $condition";
            }
            if( ! empty( $groupby ) ){
                $query .= " GROUP BY $groupby";
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
 
           /*  echo  $query; */
            $stmt = $this->conn->prepare($query);
            
            foreach(  $where_clause as $setData ){
                
                if( !isset( $setData['column'] ) || !isset( $setData['value'] ) ){
                    break;    
                }
                $setData['type'] = ( isset( $setData['type'] ) && !empty( $setData['type'] ) ) ? $setData['type'] : PDO::PARAM_STR;
                $stmt->bindParam( ":{$setData['column']}", $setData['value'], $setData['type'] );
                /* echo ":{$setData['column']} , {$setData['value']},  {$setData['type']}"; */
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
