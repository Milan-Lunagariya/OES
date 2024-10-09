
<?php 

if( isset( $_REQUEST['page'] ) && ! empty( $_REQUEST['page'] ) ) { 
    $page = $_REQUEST['page'];
    if( in_array( $page, array( 'add_categories', 'manage_categories', 'add_products', 'manage_products' ) ) ){
            
        global $oesadmin_load_css, $oesadmin_load_js;
        if( ! empty( $oesadmin_load_css ) ){
           foreach( $oesadmin_load_css as $filename => $loaded ){
                if( $loaded == false || $loaded == 'false' ){
                    if( defined( 'OESADMIN_CSS_PATH' ) ){
                        $extention = pathinfo( $filename, PATHINFO_EXTENSION );
                        $id = str_replace( '.'.$extention, "-$extention", $filename );
                        echo "<link rel='stylesheet' href='".OESADMIN_CSS_PATH.'/'.$filename."' id='oesadmin-$id' >";
                        $oesadmin_load_css[ $filename ] = true;
                    }
                }   
            }
        } 
        if( ! empty( $oesadmin_load_js ) ){
            foreach( $oesadmin_load_js as $filename => $loaded ){
                if( $loaded == false || $loaded == 'false' ){
                    if( defined( 'OESADMIN_JS_PATH' ) ){
                        $extention = pathinfo( $filename, PATHINFO_EXTENSION );
                        $id = str_replace( '.'.$extention, "-$extention", $filename ); 
                        echo "<script src='".OESADMIN_JS_PATH.'/'.$filename."' id='oesadmin-$id'> </script>";
                        $oesadmin_load_js[ $filename ] = true;
                    }
                }   
            }
        }
    }  
} else {
    
    global $oesfrontload_index_css, $oesfrontload_index_js;
    if( ! empty( $oesfrontload_index_css ) ){
        foreach( $oesfrontload_index_css as $filename => $loaded ){
             if( $loaded == false || $loaded == 'false' ){
                 if( defined( 'OESFRONT_CSS_PATH' ) ){
                    $extention = pathinfo( $filename, PATHINFO_EXTENSION );
                    $id = str_replace( '.'.$extention, "-$extention", $filename );
                    echo "<link rel='stylesheet' href='".OESFRONT_CSS_PATH.'/'.$filename."' id='oesfront-$id' >";
                    $oesfrontload_index_css[ $filename ] = true;
                }
            }   
        }
    } 
    if( ! empty( $oesfrontload_index_js ) ){
        foreach( $oesfrontload_index_js as $filename => $loaded ){
             if( $loaded == false || $loaded == 'false' ){
                if( defined( 'OESFRONT_JS_PATH' ) ){
                    $extention = pathinfo( $filename, PATHINFO_EXTENSION );
                    $id = str_replace( '.'.$extention, "-$extention", $filename );
                    echo "<script src='".OESFRONT_JS_PATH.'/'.$filename."' id='oesfront-$id' ></script>";
                    $oesfrontload_index_js[ $filename ] = true;
                }
            }   
        }
    } 

}


?>