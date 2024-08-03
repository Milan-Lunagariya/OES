<?php

use function PHPSTORM_META\type;

    require_once 'databasehandler.php'; 
    
    if( isset($_REQUEST['action']) ){
        
        global  $DatabaseHandler;

        $name = (isset($_POST['categoryname'])) ?  $_POST['categoryname'] : '';
        $parentid = (isset($_POST['parentcategory'])) ?  ($_POST['parentcategory']) : 0;  

        switch($_REQUEST['action']){
            
            case 'add':

                $message['parentCategoryOption'] = "";
                $message['success'] = false;
                $message['success'] = '';
                $message = array();
                $data = array(
                    'name' => $name,
                    'parentid' => $parentid,
                );
                
                $where_clause = array(
                    'name' => $name
                );
                $search = $DatabaseHandler->select( "categories", '*', $where_clause ); 
                $message['is_exist'] = count($search);
                if( $message['is_exist'] < 1 ){
                    $insert = $DatabaseHandler->insert( 'categories', $data );
                    if( $insert ){
                        $search = $DatabaseHandler->select( "categories", '*', $where_clause ); 
                        $message['success'] = true;  
                        $message['test'] = $search;
                        if( isset($search[0]['categoryid'])  ){
                            $message['parentCategoryOption'] = "<option name='parentid' value='{$search[0]['categoryid']}'> {$search[0]['name']} </option>";
                        }
                    }else{ 
                        $message['error'] = 'Category Can\'t added , Try again ! ';                    
                    }       
                } else {
                    $message['error'] = "Category $name is already exist ";
                }
                print_r( json_encode($message)  );
                break;
        }
    }

?>