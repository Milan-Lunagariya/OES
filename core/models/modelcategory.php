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

if ( $action != false ) {
    
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
 
            if ( $action == 'edit_category' ){
 
                $search = $DatabaseHandler->select( 'categories', 'name', array(
                        array( 'column' => 'name', 'type' => PDO::PARAM_STR, 'value' => $name, 'operator' => '=', 'conjunction' => 'AND' ),
                        array( 'column' => 'categoryid', 'type' => PDO::PARAM_INT, 'value' => $edit_id, 'operator' => '<>',  ),
                    ), '', '', '5'
                );

            } else {
                $search = $DatabaseHandler->select( "categories", 'name',  array( array( 'column' => 'name', 'value' => $name ) ), '', '', '5' );
            } 
            $message['is_exist'] = ( is_array( $search ) ) ? count( $search ) : false;
            
            if ($message['is_exist'] < 1) {
                $image_result =  $commonhelper->file_validation('categoryimage', $_SERVER['DOCUMENT_ROOT']."/project/media/categories/");
                
                if ( in_array($image_result['success'], [true, 'true', 1 ] ) || ($action == 'edit_category' && $image_result['is_upload'] == false) ) {
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
                        $search = $DatabaseHandler->select( "categories", 'categoryid, name', array( array( 'column' => 'name', 'value' => $name ) ) );
                        
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
                'error' => '', 
            );  
            $remove_id = (isset($_REQUEST['remove_id'])) ? intval($_REQUEST['remove_id']) : 0;
            $message['remove_id'] = $remove_id; 
            if( !in_array($remove_id, [0, '0', null, '']) && is_numeric($remove_id)  ){
                $remove = $DatabaseHandler->delete( 'categories', array( array( 'column' => 'categoryid', 'value' => $remove_id ) ) );
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

            $db_data = $DatabaseHandler->select("categories", '*', array( array( 'column' => 'categoryid', 'value' => $edit_id, 'type' => PDO::PARAM_INT ) ) );
            $parentid = $db_data[0]['parentid'];
            $db_data_for_parentid = $DatabaseHandler->select( "categories", 'categoryid', array( array( 'column'=> 'categoryid', 'value' => $parentid, 'type' => PDO::PARAM_INT ) ) ); 

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
        
        case 'loadCategoriesOnMC':
            require_once '../models/datatable.php';
            
            $page_no = ( isset( $_REQUEST['current_page'] ) && $_REQUEST['current_page'] > 0 ) ? $_REQUEST['current_page'] : 1;
            
            $limit = ( isset( $_REQUEST['category_record_showLimit'] ) && $_REQUEST['category_record_showLimit'] > 0 ) ? intval( $_REQUEST['category_record_showLimit'] ) : 5;
            $limit = ( is_numeric( $limit ) && $limit > 0 ) ? $limit : 5;

            $search = isset( $_REQUEST['searchCategoriesOnMC'] ) ? $_REQUEST['searchCategoriesOnMC'] : '';
            if( method_exists( 'commonhelper', 'stripslashes_deep' ) ){
                /* $search = $commonhelper->stripslashes_deep(); */ // Can not work properly...
            }
            
            if( isset( $categories ) && method_exists( 'categories', 'categoriesTableData' ) ){
                echo $categories->categoriesTableData( $page_no, $limit, $search );
            } else {
                echo "categories object not find: ".__FILE__.' >  '.__LINE__; 
            }
            break;

        case 'bulk_deleteCategory':
            $length = isset( $_REQUEST['length'] ) ? $_REQUEST['length'] : 0;
            $ids = isset( $_REQUEST['checked_ids'] ) ? $_REQUEST['checked_ids'] : array(0); 
            $excluded_ids = explode( ',' , $ids);
            $in_ids = $excluded_ids;

            echo '<pre>';
            print_r( $in_ids );
            echo '</pre>';
            $message['success'] = false;
            $message['error'] = '';

            if( $length > 0 ){ 
            
                $bulk_condition = array( 
                    array( 'column' => 'categoryid', 'value' => $in_ids, 'operator' => 'IN', 'type' => PDO::PARAM_INT )
                );

                $result = $DatabaseHandler->delete( 'categories', $bulk_condition );

                if( in_array( $result, array( 1, '1', true ) ) ){
                    $message[ 'success' ] = true;
                } else {
                    $message[ 'error' ] .= $result; 
                }
            }
            echo json_encode( $message );
            break;

        default:
            echo 'Soory, Your action can not match!';
    }
}
