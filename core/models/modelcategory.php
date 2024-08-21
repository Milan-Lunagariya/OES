<?php

use function PHPSTORM_META\type;

/* require_once 'databasehandler.php';
require_once '../helpers/commonhelper.php';  */

if( file_exists( '../helpers/commonhelper.php' ) ){
    require_once '../helpers/commonhelper.php'; 
}  
$commonhelper  = class_exists( "commonhelper" ) ? new commonhelper() : false;

if( $commonhelper != false ){
    $commonhelper->oes_required_file( 'databasehandler.php' );
    $commonhelper->oes_required_file( '../helpers/commonhelsper.php' );
    $commonhelper->oes_required_file( '../helpers/commonhelsper.php' );
    $commonhelper->oes_required_file( '../view/adminview/categories.php' );
    $commonhelper->oes_required_file( '../classes/class.formcreator.php' ); 
    $commonhelper->oes_required_file( '../helpers/formhelper.php' ); 
} 
/* require_once '../helpers/formhelper.php'; */

global  $DatabaseHandler, $commonhelper, $media_categories_path, $categories;
$formcreator = ( class_exists( 'formcreator' ) ) ? new formcreator() : false;
$categories = ( class_exists( 'categories' ) ) ? new categories() : false; 
$DatabaseHandler = ( class_exists( 'DatabaseHandler' ) ) ? new DatabaseHandler() : false; 
$formhelper = ( class_exists( 'formhelper' ) ) ? new formhelper() : false; 

if (isset($_REQUEST['action'])) {

    $message = array();
    $br = "<br> - ";
    $error = $imageName_withTime = '';
    $message['success'] = false;
    $message['error'] = '';

    switch ($_REQUEST['action']) {

        case 'add_categoryform':
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
            $message = array(
                'success' => false,
                'editform_popup' => '',
                'error' => '', 
            );  
            $remove_id = (isset($_REQUEST['remove_id'])) ? intval($_REQUEST['remove_id']) : 0;
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

            echo json_encode( $message );
            break;
        
        case 'edit_category' :
            echo $edit_id = (isset($_REQUEST['edit_id'])) ? intval($_REQUEST['edit_id']) : 0; 

            $db_data = $DatabaseHandler->select("categories", '*', array( 'categoryid' => $edit_id ));
            $parentid = $db_data[0]['parentid'];
            $db_data_for_parentid = $DatabaseHandler->select("categories", '*', array( 'categoryid' => $parentid )); 
            $edit_categoryFieldData = array( 
                array(
                    'name' => 'category_formid',
                    'id' => 'edit_categoryform',
                ),
                
                array(
                    'name' => 'categoryimage' , 
                    'value' => 'demo.png' ,
                ),
                
                array(
                    'name' => 'categoryname' , 
                    'value' => (isset($db_data[0]['name']) ? $db_data[0]['name'] : ''), 
                ),
    
                array(
                    'name' => 'parentcategory' , 
                    'value' => (isset($db_data_for_parentid[0]['name'])) ? ($db_data_for_parentid[0]['name']) : '', 
                ),
    
                array(
                    'name' => 'submitButtton',
                    'value' => 'Edit'
                )
            );

            echo $form_view = ( $categories ) ? $categories->formview( "Edit Category", $edit_categoryFieldData ) : "categories file not in: ".__FILE__.' Line no '.__LINE__; 
            break;
    }
}
