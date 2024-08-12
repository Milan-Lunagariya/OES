<?php

use function PHPSTORM_META\type;

require_once 'databasehandler.php';
require_once '../helpers/commonhelper.php'; 
global  $DatabaseHandler, $commonhelper, $media_categories_path;

if (isset($_REQUEST['action'])) {

    $message = array();
    $br = "<br> - ";
    $error = $imageName_withTime = '';
    $message['success'] = false;
    $message['error'] = '';

    switch ($_REQUEST['action']) {

        case 'add_category':
            $name = (isset($_POST['categoryname'])) ?  $_POST['categoryname'] : '';
            $parentid = (isset($_POST['parentcategory'])) ?  ($_POST['parentcategory']) : 0;

            $message['parentCategoryOption'] = ""; 
            $message = array();   

            $where_clause = array(
                'name' => $name
            );
            $search = $DatabaseHandler->select("categories", '*', $where_clause);
            $message['is_exist'] = count($search);
            
            if ($message['is_exist'] < 1) {
                $image_result =  $commonhelper->file_validation('categoryimage', $_SERVER['DOCUMENT_ROOT']."/project/media/categories/");
                
                if (in_array($image_result['success'], [true, 'true', 1])) {
                    $image = isset($image_result['message']) ? array(trim($image_result['message'])) : '';
                    $data = array(
                        'name' => $name,
                        'parentid' => $parentid,
                        'images' => json_encode( $image , true),
                    );
                    
                    $insert = $DatabaseHandler->insert('categories', $data);
                    if ($insert) {
                        $search = $DatabaseHandler->select("categories", '*', $where_clause);
                        $message['success'] = true; 
                        
                        if (isset($search[0]['categoryid'])) {
                            $message['parentCategoryOption'] = "<option name='parentid' value='{$search[0]['categoryid']}'> {$search[0]['name']} </option>";
                        }
                    } else { $error .= $br . 'Category Can\'t added , Try again ! '; }
                    
                } else { $error .= $image_result['message']; } 

            } else { $error .= "$br Category $name is already exist "; } 

            $message['error'] = $error;
            print_r(json_encode($message));
            break;
        
        case 'remove_category' :
            $message['success'] = false;
            $message['error'] = '';
            $remove_id = (isset($_REQUEST['remove_id'])) ? $_REQUEST['remove_id'] : 0;
            $message['remove_id'] = $remove_id; 
            if( !in_array($remove_id, [0, '0', null, '']) && is_numeric($remove_id)  ){
                $remove = $DatabaseHandler->delete( 'categories', array( 'categoryid' => $remove_id ) );
                $message['remove_result'] = $remove; 
                if( in_array( $remove, [true, 'true',1, '1'] )  ){
                    $message['success'] = true;
                } else{
                    $message['error'] = 'Record can not removed, try again!';
                }
            } else{
                $message['error'] = 'Record can not removed, try again!';
            }

            print_r( json_encode( $message ) );
            break;
    }
}
