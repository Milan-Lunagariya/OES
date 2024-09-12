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

$action = ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] != '') ? trim( $_REQUEST['action'] ) : false;

if ( ! in_array( $action, [ false, '', 0 ] ) ) {

    $message = array();
    $br = "<br> - ";
    $error = $imageName_withTime = '';
    $message['success'] = false;
    $message['error'] = '';
    $db_result = false;
    $clause_type = $where_clause = array();

    switch ( $action ) {

        case 'add_categoryform':
        case 'edit_category':
            $name = (isset($_POST['categoryname'])) ?  trim($_POST['categoryname']) : '';
            $parentid = (isset($_POST['parentcategory'])) ?  trim($_POST['parentcategory']) : 0;
            $edit_id = ( isset( $_REQUEST['categoryid'] ) && $_REQUEST['categoryid'] != '' ) ? intval( base64_decode( $_REQUEST['categoryid'] ) ) : 0;

            $message['parentCategoryOption'] = "";
            $message = array();   
 
            if( $action == 'edit_category' ){
                
                /*  $where_clause = "name = ? AND categoryid <> ?";
                $params = ['test', 102];
                $search = $DatabaseHandler->selectData('categories', '*', $where_clause, $params); */
                
                /* $where_clauses = array(
                    array( 'column' => 'name', 'operator' => '=', 'value' => $name, 'placeholder' => 'where_name', 'conjunction' => 'AND' ),
                    array( 'column' => 'categoryid', 'operator' => '<>', 'value' => $edit_id, 'placeholder' => 'where_categoryid', 'conjunction' => 'AND' )
                );                    
                $types = array( 'name' => PDO::PARAM_STR, 'id' => PDO::PARAM_INT ); 
                $search = selectData( 'categories', '*', $where_clauses, $types, '', 'name ASC', '' );*/
                
                /* SELECT * FROM categories WHERE name = 'test' AND category id <> 102 order by name Desc limit 20*/   
 

                $where_clause = array(
                    array( 'column' => 'name', 'type' => PDO::PARAM_STR, 'value' => $name, 'operator' => '=', 'conjunction' => 'AND' ),
                    array( 'column' => 'categoryid', 'type' => PDO::PARAM_INT, 'value' => $edit_id, 'operator' => '<>',  ),
                );
                $search = $DatabaseHandler->selectOurnew( 'categories', '*', $where_clause, '', 'name Desc', '5' );
 
            } else {
                $search = $DatabaseHandler->select( "categories", '*', array( 'name' => $name ) );
            }
            $message['is_exist'] = ( is_array( $search ) ) ? count( $search ) : false;
            
            if ($message['is_exist'] < 1) {
                $image_result =  $commonhelper->file_validation('categoryimage', $_SERVER['DOCUMENT_ROOT']."/project/media/categories/");
                $message['is_image_upload'] =  $image_result['is_upload'];
                if ( /* in_array($image_result['success'], [true, 'true', 1]) */ true ) {
                    $image = isset($image_result['image']) ? array( $image_result['image'] ) : array();
                    $image = json_encode( $image );
                    $data = array(
                        'name' => $name,
                        'parentid' => $parentid,
                        'images' => $image,
                    );
                    
                    if( $action == 'edit_category' ){
 
                        $old_categoryImages = ( isset( $_REQUEST['oldCategoryImages'] ) && $_REQUEST['oldCategoryImages'] != '' ) ? base64_decode( $_REQUEST['oldCategoryImages'] ) : json_encode(array());
                        $data['images'] = ( $image_result['is_upload'] ) ? $image : $old_categoryImages; 
                        $db_result = $DatabaseHandler->update( 'categories', $data, array( 'categoryid' => $edit_id ) );

                    } else {
                        $db_result = $DatabaseHandler->insert( 'categories', $data );
                    }
                    $message['db_result'] = $db_result;


                    if ( in_array( $db_result, [ 1, true, '1' ] ) ) {

                        $message['success'] = true; 
                        $search = $DatabaseHandler->select("categories", '*', array( 'name' => $name ) );
                        if (isset($search[0]['categoryid'])) {
                            $message['parentCategoryOption'] = "<option name='parentid' value='{$search[0]['categoryid']}'> {$search[0]['name']} </option>";
                        }

                    } else { $error .= $br . 'Category Can not added , Try again ! '; }
                    
                } else { $error .= $image_result['message']; } 

            } else { $error .= "$br Category $name is already exist "; } 

            $message['error'] = $error;
            print_r( json_encode( $message ) );
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
        
        case 'edit_categoryform' :
            echo $edit_id = (isset($_REQUEST['edit_id'])) ? intval($_REQUEST['edit_id']) : 0; 

            $db_data = $DatabaseHandler->select("categories", '*', array( 'categoryid' => $edit_id ));
            $parentid = $db_data[0]['parentid'];
            $db_data_for_parentid = $DatabaseHandler->select("categories", '*', array( 'categoryid' => $parentid )); 

            $images = isset($db_data[0]['images'] ) ? json_decode($db_data[0]['images'], true) : array();
            $categoryimage = ( is_array($images) && ($images) > 0 ) ? trim( $images[0] ) : '';
            $edit_categoryFieldData = array( 
                array( 'name' => 'category_formid', 'id' => 'edit_categoryform', ),
                array( 'name' => 'categoryimage', 'value' => $categoryimage,),
                array( 'name' => 'categoryname', 'value' => (isset($db_data[0]['name']) ? $db_data[0]['name'] : ''), ),
                array( 'name' => 'parentcategory', 'value' => (isset($db_data_for_parentid[0]['categoryid'])) ? ($db_data_for_parentid[0]['categoryid']) : 0, ),
                array( 'action' => 'create_field', 'name' => 'categoryid','type' => 'hidden','value' => base64_encode($edit_id), ),  
                array( 'action' => 'create_field', 'name' => 'oldCategoryImages','type' => 'hidden','value' => base64_encode(json_encode(array($categoryimage))), ),  
                array( 'name' => 'submitButtton', 'value' => 'Edit')
            );  
            echo $form_view = ( $categories ) ? $categories->formview( "Edit Category", $edit_categoryFieldData ) : "categories file not in: ".__FILE__.' Line no '.__LINE__; 
            break; 
    }
}
