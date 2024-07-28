<?php

use function PHPSTORM_META\type;

    require_once 'databasehandler.php'; 
    
    if( isset($_REQUEST['action']) ){
        
        $name = (isset($_REQUEST['categoryname'])) ?  $_POST['categoryname'] : '';
        $parentid = (isset($_REQUEST['parentid'])) ?  intval($_POST['parentid']) : 0; 
        global  $DatabaseHandler;
        switch($_REQUEST['action']){
            
            case 'add':
                $data = array(
                    'name' => $name,
                    'parentid' => $parentid,
                );
                $insert = $DatabaseHandler->insert( 'categories', $data );
                
                $message = '';
                if( $insert ){
                    $message = 'success';
                }else{
                    $message = 'Category Can\'t added , Try again ! ';                    
                }
                echo $message;
                break;
        }
    }

?>