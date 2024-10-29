<?php

global $commonhelper, $media_categories_path;  
class commonhelper
{
    function oes_test(){
        echo " Congratulation now, You can use the commmonhelper class of var, method,etc.  ";
    }
    
    function file_validation($postedName = '', $fileDestination = '../../media/categories/', $maxSize = (5 * 1024 * 1024), $allowExt = array('jpeg', 'png', 'jpg', 'gif'), $maximumFile = 10) {

        $br = '<br> - ';  
        $allowExt = count($allowExt) > 0 ? $allowExt : array('jpeg', 'png', 'jpg', 'gif');
        $maxSize =  $maxSize != '' ? $maxSize : (5 * 1024 * 1024);
        $maximumFile =  $maximumFile != '' ? $maximumFile : 10;
        $blank_msg = "$br No Images uploaded!";
        $result = array(
            'success' => false,
            'message' => '',
            'is_upload' =>false,
            'images' => array(),
        ); 
        
        $files = isset($_FILES[$postedName]) && !empty( $_FILES[$postedName] ) ? $_FILES[$postedName] : false;
        
        if ($files) {  
            $fileCount = is_array($files['name']) ? count($files['name']) : 1;
     
            if ($fileCount > $maximumFile) {
                $result['message'] .= "$br You can only upload a maximum of $maximumFile files.";
                return $result;  
            }
    
            $valid = true;   
            for ($i = 0; $i < $fileCount; $i++) {
                $fileName = is_array($files['name']) ? $files['name'][$i] : $files['name'];
                if( $i == 0 && empty( $fileName ) ){
                    $result['message'] .= $blank_msg; 
                    return $result;
                }
                $fileSize = is_array($files['size']) ? $files['size'][$i] : $files['size'];
                $fileExtension = strtolower(trim(pathinfo($fileName, PATHINFO_EXTENSION)));
    
                if (!in_array($fileExtension, $allowExt)) {
                    $result['message'] .= "$br File is Invalid: $fileExtension type is not allowed for: $fileName"; 
                    $valid = false;  
                } elseif ($fileSize > $maxSize) {
                    $result['message'] .= "$br Please ensure the file size is $maxSize KB or less for: $fileName"; 
                    $valid = false;  
                }
            }
     
            if ($valid) {
                for ($i = 0; $i < $fileCount; $i++) {
                    $fileName = is_array($files['name']) ? $files['name'][$i] : $files['name'];
                    $fileTempName = is_array($files['tmp_name']) ? $files['tmp_name'][$i] : $files['tmp_name'];
    
                    $fileNameWithTime = time() . '_' . $fileName;
                    $fileDestinationPath = $fileDestination . $fileNameWithTime;
    
                    if (move_uploaded_file($fileTempName, $fileDestinationPath)) { 
                        $result['success'] = true;
                        $result['is_upload'] = true;
                        if( is_array($files['name']) ){
                            $result['images'][] = ($fileNameWithTime);
                        } else {
                            $result['image'] = ($fileNameWithTime);
                        }
                    } else { 
                        $result['message'] .= "$br Error moving uploaded file: $fileName"; 
                    }
                }
            }
        } else { 
            $result['message'] .= $blank_msg; 
        }
    
        return $result;
    }
    
    
    
    function stripslashes_deep($value = array())
    {
        $value = (is_array($value)) ?
            array_map([$this, 'stripslashes_deep'], $value) :
            stripslashes($value);
        return $value;
    }
    
    function india_timezone()
    {
        return date_default_timezone_set('Asia/Kolkata');
    }

    function oes_get_timestamp(){
        date_default_timezone_set('Asia/Kolkata');
        return ( date( 'd-F-Y , H:i:s' ) );
    }

    function oes_required_file($filename = "")
    {
        if ( !empty($filename) && file_exists($filename) ) { 
            require_once($filename); 
        }
    }

    function oes_is_json( $data ){
        // please before test
        json_decode( $data, true );
        $data =  (json_last_error() == JSON_ERROR_NONE) ? json_decode( $data, true ) : $data;
        return ( $data ); 
    }

    function oes_file_error(){
        
    }

    function set_image( $image_path = '', $anchor_path = '' ){
        $content = '';
        
        $anchor_path = empty( $anchor_path ) ? $image_path : $anchor_path;

        $content .= "<div class='image_parent'>
            <a href='$anchor_path' class='oes_anchor_class' target='_blank' >
                <img src='$image_path' alt='Not Found' width='100'>
            </a>
        </div>";

        return $content;
    }

    function page_404(){
        $content = '';
            $content .= '<div style="max-width: 600px; margin: auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
                <h1 style="font-size: 70px; margin: 0; color: #dc3545;">404</h1>
                <p style="font-size: 24px; margin: 20px 0;">Oops! The page you are looking for does not exist.</p>
                <p>
                    <a href="index.php" style="color: #007bff; text-decoration: none; font-weight: bold; border: 2px solid #007bff; padding: 10px 20px; border-radius: 5px; transition: background-color 0.3s, color 0.3s;">
                        Go back to Homepage
                    </a>
                </p>
            </div> ';
        return $content;
    }

    function front_categories(){ 
        global $databasehandler;
        ?>
        <div class="oesAllCategoriesFront">
        <div class="container">
            <?php $page_name = isset( $_REQUEST['page'] ) ? $_REQUEST['page']: ''; ?>
            <h1> <?php echo ( $page_name == 'categories' ) ? 'Parent Categories' : 'Categories'; ?> </h1>

            <div class="row">
                <?php 
                    global $commonhelper;
                    $where = array(
                        array( 'column' => 'parentid', 'value' => 0, 'type' => PDO::PARAM_INT, 'operator' => '=' )
                    );
                    $parentid = isset( $_REQUEST['parentid'] ) ? $_REQUEST['parentid'] : 0;
                    if( $parentid != 0 ){                        
                        if( $page_name == 'subCategories' ){
                            $where = array(
                                array( 'column' => 'parentid', 'value' => $parentid, 'type' => PDO::PARAM_INT, 'operator' => '=' )
                            );
                        }
                    } 
                    $categories_dbdata = $databasehandler->select('categories', '*', $where, '', 'categoryid DESC');
                    if (count($categories_dbdata) > 0) {
                        foreach ($categories_dbdata as $value_categories) {
                            $images = isset($value_categories['images']) ? json_decode($value_categories['images'], true) : false;
                            $categoryImage = isset($images[0]) ? $images[0] : '';
                            $categoryName = $value_categories['name'];
                            $categoryId = $value_categories['categoryid'];
                            $viewMore_link = ($page_name == 'categories' ) ? "index.php?page=subCategories&parentid=$categoryId" : "index.php?page=products&categoryid=$categoryId";

                            if (defined('OESFRONT_MEDIA_PATH')) {
                                echo "
                                <div class='col-md-4'>
                                    <div class='card'>
                                        <img src='" . OESFRONT_MEDIA_PATH . '/categories/' . $categoryImage . "' class='card-img-top category-image' alt='$categoryName'>
                                        <div class='card-body'>
                                            <h5 class='card-title'>$categoryName</h5> 
                                            <a href='$viewMore_link' class='btn btn-primary'>View More</a>
                                        </div>
                                    </div>
                                </div>";
                            } else {
                                echo '<h4 align="center">Categories could not be loaded...</h4>';
                                break;
                            }
                        } 
                    } else {
                        echo '<h4 align="center">Category not available</h4>';
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php
    }
}

$commonhelper = new commonhelper();
 



