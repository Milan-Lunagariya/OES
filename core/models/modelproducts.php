<?php

use function PHPSTORM_META\type;
 

if( file_exists( '../helpers/commonhelper.php' ) ){
    require_once '../helpers/commonhelper.php'; 
}   
$commonhelper  = class_exists( "commonhelper" ) ? new commonhelper() : false;

if( $commonhelper != false ){
    $commonhelper->oes_required_file( '../assets/svg/commonsvg.php' );
    $commonhelper->oes_required_file( 'databasehandler.php' );
    $commonhelper->oes_required_file( '../helpers/commonhelsper.php' ); 
    $commonhelper->oes_required_file( '../view/adminview/products.php' );
    $commonhelper->oes_required_file( '../classes/class.formcreator.php' ); 
    $commonhelper->oes_required_file( '../helpers/formhelper.php' ); 
}    
global  $DatabaseHandler, $commonhelper, $media_categories_path, $categories, $products;
$formcreator = ( class_exists( 'formcreator' ) ) ? new formcreator() : false;
$categories = ( class_exists( 'categories' ) ) ? new categories() : false; 
$products = ( class_exists( 'products' ) ) ? new products() : false;   
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

        case 'add_product':
        case 'edit_product':
            $productimages = (isset($_FILES['productimages'])) ?  ($_FILES['productimages']) : '';
            $productname = (isset($_POST['productname'])) ?  trim($_POST['productname']) : '';
            $productdescription = (isset($_POST['productdescription'])) ?  trim($_POST['productdescription']) : '';
            $productprice = (isset($_POST['productprice'])) ?  trim($_POST['productprice']) : '';
            $productstock = (isset($_POST['productstock'])) ?  trim($_POST['productstock']) : ''; 
            $categoryids = (isset($_POST['productCategoryids'])) ?  ($_POST['productCategoryids']) : 0; 
            $edit_id = ( isset( $_REQUEST['productid'] ) && $_REQUEST['productid'] != '' ) ? intval( base64_decode( $_REQUEST['productid'], true ) ) : 0;
            $categoryids = json_encode( $categoryids ); 
            $message = array();   
             
            if ( $action == 'edit_product' ){
 
                $exit_this_product = $DatabaseHandler->select( 'products', 'name', array(
                        array( 'column' => 'name', 'type' => PDO::PARAM_STR, 'value' => $productname, 'operator' => '=', 'conjunction' => 'AND' ),
                        array( 'column' => 'productid', 'type' => PDO::PARAM_INT, 'value' => $edit_id, 'operator' => '<>',  ),
                    ), '', '', '1'
                ); 
            } else {
                $exit_this_product = $DatabaseHandler->select( "products", 'name',  array( array( 'column' => 'name', 'value' => $productname ) ), '', '', '2' );
            } 
            $message['is_exist'] = ( is_array( $exit_this_product ) ) ? count( $exit_this_product ) : false;
            if ($message['is_exist'] < 1) {
                $image_result =  $commonhelper->file_validation('productimages', $_SERVER['DOCUMENT_ROOT']."/project/media/products/"); 
                if ( in_array($image_result['success'], [true, 'true', 1 ] ) || ($action == 'edit_product' && $image_result['is_upload'] == false) ) {
                    $images = isset($image_result['images']) ? array( $image_result['images'] ) : array();
                    $images = json_encode( $images ); 
                    $data = array(
                        'name' => $productname,
                        'description' => $productdescription,
                        'price' => $productprice,
                        'stock' => $productstock,
                        'categoryids' => $categoryids,                        
                        'images' => $images,
                    );
                    
                    if( $action == 'edit_product' ){
                        
                        $old_categoryImages = ( isset( $_REQUEST['oldProductImages'] ) && $_REQUEST['oldProductImages'] != '' ) ? base64_decode( $_REQUEST['oldProductImages'] ) : json_encode(array());
                        $data['images'] = ( $image_result['is_upload'] ) ? $images : $old_categoryImages; 
                        $db_result = $DatabaseHandler->update( 'products', $data, array( 'productid' => $edit_id ) );

                    } else {
                        $db_result = $DatabaseHandler->insert( 'products', $data );
                    }
                    $message['db_result'] = $db_result;


                    if ( in_array( $db_result, [ 1, true, '1' ] ) ) {

                        $message['success'] = true; 
                            
                    } else { $error .= $br . 'Category Can not added , Try again ! '; }
                    
                } else { $error .= $image_result['message']; } 

            } else { $error .= "$br Product $productname is already exist "; } 

            $message['error'] = $error;
            print_r( json_encode( $message ) );
            break;
        
        case 'remove_product' :
            $message = array(
                'success' => false,
                'error' => '', 
            );  
            $remove_id = (isset($_REQUEST['remove_id'])) ? intval($_REQUEST['remove_id']) : 0;
            $message['remove_id'] = $remove_id; 
            if( !in_array($remove_id, [0, '0', null, '']) && is_numeric($remove_id)  ){
                $remove = $DatabaseHandler->delete( 'products', array( array( 'column' => 'productid', 'value' => $remove_id ) ) );
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
        
        case 'edit_productform' :
            
            echo $edit_id = (isset($_REQUEST['edit_id'])) ? intval($_REQUEST['edit_id']) : 0; 

            $db_data = $DatabaseHandler->select("products", '*', array( array( 'column' => 'productid', 'value' => $edit_id, 'type' => PDO::PARAM_INT ) ) );

            $productimages = isset( $db_data[0]['images'] ) ? $db_data[0]['images'] : json_encode(array());
            
            $edit_productFieldData = array( 
                array( 'name' => 'product_formid', 'id' => 'edit_productform', ),
                array( 'name' => 'productname', 'value' => (isset($db_data[0]['name']) ? $db_data[0]['name'] : ''), ),
                array( 'name' => 'productdescription', 'value' => (isset($db_data[0]['description']) ? $db_data[0]['description'] : ''), ),
                array( 'name' => 'productstock', 'value' => (isset($db_data[0]['stock']) ? $db_data[0]['stock'] : ''), ),
                array( 'name' => 'productprice', 'value' => (isset($db_data[0]['price']) ? $db_data[0]['price'] : ''), ),
                array( 'name' => 'productcategoryids', 'value' => (isset( $db_data[0]['categoryids']) ? json_decode( $db_data[0]['categoryids'], true ) : ''), ),
                
                array( 'action' => 'create_field', 'name' => 'productid','type' => 'hidden','value' => base64_encode($edit_id), ),  
                array( 'action' => 'create_field', 'name' => 'oldProductImages','type' => 'hidden','value' => base64_encode( $productimages ), ),  
                array( 'name' => 'submitButtton', 'value' => 'Edit')
            );  
            echo $form_view = ( $products ) ? $products->formview( "Edit Product", $edit_productFieldData ) : "Product file not in: ".__FILE__.' Line no '.__LINE__; 
            break;
        
        case 'refreshProductsTable':
            require_once '../models/datatable.php';
            
            $current_page = ( isset( $_REQUEST['current_page'] ) && $_REQUEST['current_page'] > 0 ) ? $_REQUEST['current_page'] : 1;
            
            $limit = ( isset( $_REQUEST['limit'] ) && $_REQUEST['limit'] > 0 ) ? intval( $_REQUEST['limit'] ) : 5;
            $limit = ( is_numeric( $limit ) && $limit > 0 ) ? $limit : 5;

            $search = isset( $_REQUEST['datatable_search'] ) ? $_REQUEST['datatable_search'] : '';
            
            if( isset( $products ) && method_exists( 'products', 'productTableData' ) ){
                echo $products->productTableData( $current_page, $limit, $search );
            } else {
                echo "products object not find: ".__FILE__.' >  '.__LINE__; 
            }
            break;

        case 'bulk_deleteProduct':
            $length = isset( $_REQUEST['length'] ) ? $_REQUEST['length'] : 0;
            $ids = isset( $_REQUEST['checked_ids'] ) ? $_REQUEST['checked_ids'] : array(0); 
            $excluded_ids = explode( ',' , $ids);
            $in_ids = $excluded_ids;

            $message['success'] = false;
            $message['error'] = '';

            if( $length > 0 ){ 
            
                $bulk_condition = array( 
                    array( 'column' => 'productid', 'value' => $in_ids, 'operator' => 'IN', 'type' => PDO::PARAM_INT )
                );

                $result = $DatabaseHandler->delete( 'products', $bulk_condition );

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
