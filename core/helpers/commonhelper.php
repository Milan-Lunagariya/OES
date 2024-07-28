<?php 

    
    function stripslashes_deep($value = array()){
        $value = ( is_array($value) ) ?
                    array_map( 'stripslashes_deep', $value ) :
                    stripslashes($value);
        return $value;
    }

    function india_timezone() {
        date_default_timezone_set( 'Asia/Kolkata' );
    }
?>