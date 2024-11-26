<?php

use function PHPSTORM_META\type;


if (file_exists('../helpers/commonhelper.php')) {
    require_once '../helpers/commonhelper.php';
}
$commonhelper  = class_exists("commonhelper") ? new commonhelper() : false;

if ($commonhelper != false) {
    $commonhelper->oes_required_file('../assets/svg/commonsvg.php');
    $commonhelper->oes_required_file('databasehandler.php');
    $commonhelper->oes_required_file('../helpers/commonhelsper.php');
    $commonhelper->oes_required_file('../view/adminview/products.php');
    $commonhelper->oes_required_file('../classes/class.formcreator.php');
    $commonhelper->oes_required_file('../helpers/formhelper.php');
}
global  $DatabaseHandler, $commonhelper, $media_categories_path, $categories, $products;
$formcreator = (class_exists('formcreator')) ? new formcreator() : false;
$categories = (class_exists('categories')) ? new categories() : false;
$products = (class_exists('products')) ? new products() : false;
$DatabaseHandler = (class_exists('DatabaseHandler')) ? new DatabaseHandler() : false;
$formhelper = (class_exists('formhelper')) ? new formhelper() : false;

$action = (isset($_REQUEST['action']) && $_REQUEST['action'] != '') ? trim($_REQUEST['action']) : false;

if ($action != false) {

    $message = array();
    $br = "<br> - ";
    $error = $imageName_withTime = '';
    $message['success'] = false;
    $message['error'] = '';
    $db_result = false;
    $clause_type = $where_clause = array();

    switch ($action) {

        case 'add_product':
        case 'edit_product':
            $productimages = (isset($_FILES['productimages'])) ?  ($_FILES['productimages']) : '';
            $productname = (isset($_POST['productname'])) ?  trim($_POST['productname']) : '';
            $productdescription = (isset($_POST['productdescription'])) ?  trim($_POST['productdescription']) : '';
            $productprice = (isset($_POST['productprice'])) ?  trim($_POST['productprice']) : '';
            $productstock = (isset($_POST['productstock'])) ?  trim($_POST['productstock']) : '';
            $categoryids = (isset($_POST['productCategoryids'])) ?  ($_POST['productCategoryids']) : 0;
            $edit_id = (isset($_REQUEST['productid']) && $_REQUEST['productid'] != '') ? intval(base64_decode($_REQUEST['productid'], true)) : 0;
            $categoryids = json_encode($categoryids);
            $message = array();

            if ($action == 'edit_product') {

                $exit_this_product = $DatabaseHandler->select(
                    'products',
                    'name',
                    array(
                        array('column' => 'name', 'type' => PDO::PARAM_STR, 'value' => $productname, 'operator' => '=', 'conjunction' => 'AND'),
                        array('column' => 'productid', 'type' => PDO::PARAM_INT, 'value' => $edit_id, 'operator' => '<>',),
                    ),
                    '',
                    '',
                    '1'
                );
            } else {
                $exit_this_product = $DatabaseHandler->select("products", 'name',  array(array('column' => 'name', 'value' => $productname)), '', '', '2');
            }
            $message['is_exist'] = (is_array($exit_this_product)) ? count($exit_this_product) : false;
            if ($message['is_exist'] < 1) {
                $image_result =  $commonhelper->file_validation('productimages', $_SERVER['DOCUMENT_ROOT'] . "/project/media/products/");
                if (in_array($image_result['success'], [true, 'true', 1]) || ($action == 'edit_product' && $image_result['is_upload'] == false)) {
                    $images = isset($image_result['images']) ? array($image_result['images']) : array();
                    $images = json_encode($images);
                    $data = array(
                        'name' => $productname,
                        'description' => $productdescription,
                        'price' => $productprice,
                        'stock' => $productstock,
                        'categoryids' => $categoryids,
                        'images' => $images,
                    );

                    if ($action == 'edit_product') {

                        $old_categoryImages = (isset($_REQUEST['oldProductImages']) && $_REQUEST['oldProductImages'] != '') ? base64_decode($_REQUEST['oldProductImages']) : json_encode(array());
                        $data['images'] = ($image_result['is_upload']) ? $images : $old_categoryImages;
                        $db_result = $DatabaseHandler->update('products', $data, array('productid' => $edit_id));
                    } else {
                        $db_result = $DatabaseHandler->insert('products', $data);
                    }
                    $message['db_result'] = $db_result;


                    if (in_array($db_result, [1, true, '1'])) {

                        $message['success'] = true;
                    } else {
                        $error .= $br . 'Category Can not added , Try again ! ';
                    }
                } else {
                    $error .= $image_result['message'];
                }
            } else {
                $error .= "$br Product $productname is already exist ";
            }

            $message['error'] = $error;
            print_r(json_encode($message));
            break;

        case 'remove_product':
            $message = array(
                'success' => false,
                'error' => '',
            );
            $remove_id = (isset($_REQUEST['remove_id'])) ? intval($_REQUEST['remove_id']) : 0;
            $message['remove_id'] = $remove_id;
            if (!in_array($remove_id, [0, '0', null, '']) && is_numeric($remove_id)) {
                $remove = $DatabaseHandler->delete('products', array(array('column' => 'productid', 'value' => $remove_id)));
                $message['remove_result'] = $remove;
                if (in_array($remove, [true, 'true', 1, '1'])) {
                    $message['success'] = true;
                } else {
                    $message['error'] = 'Record can not removed, try again!';
                }
            } else {
                $message['error'] = 'Record can not removed, try again!';
            }

            echo json_encode($message);
            break;

        case 'edit_productform':

            $edit_productFieldData = array();
            echo $edit_id = (isset($_REQUEST['edit_id'])) ? intval($_REQUEST['edit_id']) : 0;

            $db_data = $DatabaseHandler->select("products", '*', array(array('column' => 'productid', 'value' => $edit_id, 'type' => PDO::PARAM_INT)));

            $productimages = isset($db_data[0]['images']) ? $db_data[0]['images'] : json_encode(array());


            /* $productimages = json_decode( $productimages, true );
            foreach( $productimages[0] as $key => $value ){
                $edit_productFieldData[] = array( 'action' => 'create_field', 'name' => 'replaceProductImages[]','type' => 'file','value' => '' );
                $edit_productFieldData[] = array( 'action' => 'create_field', 'name' => 'oldProductImages[]','type' => 'hidden','value' => $value );
            } */

            $edit_productFieldData[] = array('name' => 'product_formid', 'id' => 'edit_productform',);
            $edit_productFieldData[] = array('name' => 'productname', 'value' => (isset($db_data[0]['name']) ? $db_data[0]['name'] : ''),);
            $edit_productFieldData[] = array('name' => 'productdescription', 'value' => (isset($db_data[0]['description']) ? $db_data[0]['description'] : ''),);
            $edit_productFieldData[] = array('name' => 'productstock', 'value' => (isset($db_data[0]['stock']) ? $db_data[0]['stock'] : ''),);
            $edit_productFieldData[] = array('name' => 'productprice', 'value' => (isset($db_data[0]['price']) ? $db_data[0]['price'] : ''),);
            $edit_productFieldData[] = array('name' => 'productcategoryids', 'value' => (isset($db_data[0]['categoryids']) ? json_decode($db_data[0]['categoryids'], true) : ''),);
            $edit_productFieldData[] = array('action' => 'create_field', 'name' => 'productid', 'type' => 'hidden', 'value' => base64_encode($edit_id),);
            $edit_productFieldData[] = array('name' => 'submitButtton', 'value' => 'Edit');
            echo $form_view = ($products) ? $products->formview("Edit Product", $edit_productFieldData) : "Product file not in: " . __FILE__ . ' Line no ' . __LINE__;
            break;

        case 'refreshProductsTable':
            require_once '../models/datatable.php';

            $current_page = (isset($_REQUEST['current_page']) && $_REQUEST['current_page'] > 0) ? $_REQUEST['current_page'] : 1;

            $limit = (isset($_REQUEST['limit']) && $_REQUEST['limit'] > 0) ? intval($_REQUEST['limit']) : 5;
            $limit = (is_numeric($limit) && $limit > 0) ? $limit : 5;

            $search = isset($_REQUEST['datatable_search']) ? $_REQUEST['datatable_search'] : '';

            if (isset($products) && method_exists('products', 'productTableData')) {
                echo $products->productTableData($current_page, $limit, $search);
            } else {
                echo "products object not find: " . __FILE__ . ' >  ' . __LINE__;
            }
            break;

        case 'bulk_deleteProduct':
            $length = isset($_REQUEST['length']) ? $_REQUEST['length'] : 0;
            $ids = isset($_REQUEST['checked_ids']) ? $_REQUEST['checked_ids'] : array(0);
            $excluded_ids = explode(',', $ids);
            $in_ids = $excluded_ids;

            $message['success'] = false;
            $message['error'] = '';

            if ($length > 0) {

                $bulk_condition = array(
                    array('column' => 'productid', 'value' => $in_ids, 'operator' => 'IN', 'type' => PDO::PARAM_INT)
                );

                $result = $DatabaseHandler->delete('products', $bulk_condition);

                if (in_array($result, array(1, '1', true))) {
                    $message['success'] = true;
                } else {
                    $message['error'] .= $result;
                }
            }
            echo json_encode($message);
            break;

        case 'manageProductImages':
            global $DatabaseHandler, $datatable, $formcreator;
            $productid = isset($_REQUEST['productid']) ? $_REQUEST['productid'] : 0;
 
            if (method_exists($DatabaseHandler, 'select')) {
                $productData = $DatabaseHandler->select('products', 'images', array(
                    array('column' => 'productid', 'value' => $productid, 'operator' => '=', 'type' => PDO::PARAM_INT)
                ));
            } else {
                echo 'DatabaseHanler object or select method not fond!';
            }

            $productImages = isset($productData[0]['images']) ? json_decode($productData[0]['images'], true) : array();
            $productImages = isset($productImages[0]) ? $productImages[0] : array(); 
            $maxLimitImage = 10; ?>
            
            <p class='font-weight-bold' >Product id: <i><?php echo $productid; ?></i> </p>
           
            <form action="" method="post" id="editProductImages" data-productid="<?php echo $productid; ?>" >
                <div class="mangeProductImage_container bg-light w-100 p-2">
                    
                    <?php if( is_array( $productImages ) ){
                        foreach ($productImages as $key => $filename) { ?>
                            <div class="productImageCartContainer card m-3">
                                <div class="imageContaier h-75 p-2  d-flex align-items-center justify-content-center">
                                    <img src="../media/products/<?php echo $filename; ?>" class="" style="max-height: 220px; max-width: 100%;" alt="Card Image">
                                </div>
                                <div class="p-2">
                                    <div class="d-flex flex-column justify-content-center">
                                        <button type="button" class="btn m-1 w-50 btn-danger delete-button" data-productid="<?php echo $productid?>" onclick="deleteProductImage( this )" data-image-value="<?php echo $filename; ?>" title="Click to Delete this image.">Delete</button>
                                        <div class="editProductImageConatiner">
                                            <?php echo $formcreator->field_create(array('type' => 'file', 'title' => 'Select image for replace', 'label' => 'Replace Product image', 'name' => 'replaceProductImages[]', 'accept' => 'image/*')); ?>
                                            <?php echo $formcreator->field_create(array('type' => 'hidden', 'name' => 'hiddenProductImages[]', 'value' => $filename)); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } 
                        
                    } else{
                        echo "if of \$productImages not array please check it, in file ".__FILE__." , line:".__LINE__;
                        echo "\n \n -";
                        print_r( $productImages );
                    }

                    if (count($productImages) < $maxLimitImage) {
                        $multiple = count($productImages) != ($maxLimitImage - 1) ? true : false;
                        echo "<div class='p-2'>";
                        echo $formcreator->field_create(array('type' => 'file', 'label' => 'Add more Product Image', 'name' => 'moreProductImages[]', 'accept' => 'image/*', "multiple" => $multiple, "title" => "Select images for add more Product image."  ));
                        echo "</div>";
                    }
                    ?>
                </div>
                <div class="d-flex w-100 bg-light mt-1 align-items-center" style="position: sticky; bottom: 2px; border: 1px solid var(--dark); box-shadow: 0px 0px 5px var(--dark);">
                    <div class="m-1 bg-light float-left w-75" >
                        <p class='p-1' >Uploaded Product images : <i><?php echo is_array( $productImages ) ?count($productImages) : 0; ?></i> </p>
                        <p class="font-weight-bold text-danger p-1"> Note: Maximum allowed <?php echo $maxLimitImage; ?> Product images. </p>
                    </div>
                    <div class="editProductImageConatiner float-right d-flex align-items-center">
                        <div class="close_editProduct_container ml-2">
                            <button type="button" title="Cancle edit to Product images" class="close_editProduct btn btn-danger"> Cancle </button>
                        </div>
                        <?php echo $formcreator->field_create(array('type' => 'submit', 'class' => 'btn btn-primary ml-2 productSaveButton', 'value' => 'Save', "title" => 'Click to Save or Update the Product images.')); ?>
                    </div>
                </div>
            </form>
<?php
            break;

        case 'deleteProductImage' :
        case 'editProductImages' :
            global $DatabaseHandler, $datatable, $formcreator, $commonhelper;
            $data = array(); 
            $data['error'] = '';
            $maxLimitImage = 10;
            $productid = isset( $_REQUEST['productid'] ) ? intval( $_REQUEST['productid'] ) : 0;
            $productImage = isset( $_REQUEST['productImage'] ) ? ( $_REQUEST['productImage'] ) : ''; 
            $hiddenProductImages = isset( $_REQUEST['hiddenProductImages'] ) ? ( $_REQUEST['hiddenProductImages'] ) : array(); 
            $moreProductImages = isset( $_FILES['moreProductImages'] ) ? ( $_FILES['moreProductImages'] ) : array(); 
            $replaceProductImages = isset( $_FILES['replaceProductImages'] ) ? ( $_FILES['replaceProductImages'] ) : array(); 

            if (method_exists($DatabaseHandler, 'select')) {
                $productData = $DatabaseHandler->select('products', 'images', array(
                    array('column' => 'productid', 'value' => $productid, 'operator' => '=', 'type' => PDO::PARAM_INT)
                ));
            } else {
                $data['error'] .= '--DatabaseHanler object or select method not fond!--';
            }

            $new_images = array();
            if( $action == 'deleteProductImage' ){
                $productImages = isset($productData[0]['images']) ? json_decode($productData[0]['images'], true) : array();
                $productImages = isset($productImages[0]) ? $productImages[0] : array();  
                foreach( $productImages as $filename ){ 
                    if( $filename == $productImage ){
                        continue;
                    }
                    $new_images[] = $filename;
                } 
            } else if( $action == 'editProductImages' ){

                $replaceUpload_images = $commonhelper->file_validation('replaceProductImages', $_SERVER['DOCUMENT_ROOT'] . "/project/media/products/"); 
                $replaceUpload_images['images'] = isset( $replaceUpload_images['images'] ) ? $replaceUpload_images['images'] : array();
                $replaceUpload_images['message'] = isset( $replaceUpload_images['message'] ) ? $replaceUpload_images['message'] : array();

                foreach( $replaceProductImages['name'] as $key => $filename ){

                    $hiddenProductImages[$key] = isset( $hiddenProductImages[$key] ) ? $hiddenProductImages[$key] : '';  
                    if( empty( trim( $filename ) ) ) { 
                        $new_images[] =  $hiddenProductImages[$key];  
                    }
                }

                $moreUpload_images = $commonhelper->file_validation('moreProductImages', $_SERVER['DOCUMENT_ROOT'] . "/project/media/products/"); 
                $moreUpload_images['images'] = isset( $moreUpload_images['images'] ) ? $moreUpload_images['images'] : array();
                $moreUpload_images['message'] = isset( $moreUpload_images['message'] ) ? $moreUpload_images['message'] : array();
                $new_images = array_merge( $replaceUpload_images['images'], $moreUpload_images['images'], $new_images );
                
                $replaceImageError = trim( str_replace( '<br> -  Error moving uploaded file:', '', $replaceUpload_images['message'] ) );
                $moreImageError = trim( str_replace( '<br> -  Error moving uploaded file:', '', $moreUpload_images['message'] ) );
                
                 
                $data['moreImageError'] = $moreImageError;
                $data['replaceImageError'] = $replaceImageError;
            }
            
            $data['new_images_length'] = is_array( $new_images ) ? count( $new_images ) : 0;
            if( is_array($new_images) && count( $new_images ) <= 10 && empty( $moreImageError ) && empty( $replaceImageError )  ){  
                $where_clause = array( 'productid' => $productid );
                $update = $DatabaseHandler->update( 'products', array( 'images' => json_encode( array( $new_images ) ) ), $where_clause );
                $data['success'] = ( $update ) ? true : false; 
            } else {
                $data['success'] = false;
                $data['error'] .= "<br> Error: Maximum allowed {$maxLimitImage} Product images.";
            }

            echo json_encode($data);
            break;

        default:
            echo 'Soory, Your action can not match!';
    }
}
