<?php

global $commonhelper, $media_categories_path;  
class commonhelper
{
    function oes_test(){
        echo " Congratulation now, You can use the commmonhelper class of var, method,etc.  ";
    }
    function file_validation( $postedName = '', $fileDestination =  '../../media/categories/', $maxSize = (5 * 1024 * 1024), $allowExt = array('jpeg', 'png', 'jpg', 'gif'),  ){
        $result = array();
        $result['success'] = false;
        $result['message'] = '';
        $result['image'] = '';
        $result['is_upload'] = false;
        
        $br = '<br> - '; 
        
        if(isset($_FILES[$postedName]['name']) && !empty($_FILES[$postedName]['name']) ) {
            $result['is_upload'] = true;
            $fileName  = $_FILES[$postedName]['name'];
            $fileName_withTime  = time().'_'.$fileName;
            $fileTempName       = $_FILES[$postedName]['tmp_name'];
            $fileSize           = $_FILES[$postedName]['size']; 
            $fileExtension      = trim(pathinfo($_FILES[$postedName]['name'], PATHINFO_EXTENSION));
            
            if( in_array(strtolower($fileExtension), $allowExt) ){ 
                if( $fileSize <= ( $maxSize )  ){ 
                    $fileDestination = $fileDestination.$fileName_withTime;
                    if(move_uploaded_file($fileTempName, $fileDestination)){ 
                        $result['success'] = true;
                        $result['image']    = trim($fileName_withTime);
                    } else{ $result['message'] = "$br Move uploaded filed time error"; }

                } else{ $result['message'] .= "$br Please ensure the file size is 5 MB or less."; }
                
            } else{ $result['message'] .= "$br File is Invalid: $fileExtension type are not allowed"; }
            
        } else{ $result['message'] .= "$br Filename not found !"; }
         
        return ($result);
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

}

$commonhelper = new commonhelper();
 



