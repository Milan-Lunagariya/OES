<?php 

function custom_load_admin_js( $oesadmin_load_js ){
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

function custom_load_admin_css( $oesadmin_load_css, ){
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

}
    
function load_front_cssJs(){ 
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

function custom_load_front_cssJs( $load_css, $load_js ){  
    if( ! empty( $load_css ) ){
        foreach( $load_css as $filename => $loaded ){
            if( $loaded == false || $loaded == 'false' ){
                if( defined( 'OESCUSTOM_CSS_PATH' ) ){ 
                    $extention = pathinfo( $filename, PATHINFO_EXTENSION );
                    $id = str_replace( '.'.$extention, "-$extention", $filename );
                    echo "<link rel='stylesheet' href='".OESCUSTOM_CSS_PATH.'/'.$filename."' >";
                    $load_css[ $filename ] = true;
                }
            }   
        }
    } 
    if( ! empty( $load_js ) ){
        foreach( $load_js as $filename => $loaded ){
             if( $loaded == false || $loaded == 'false' ){
                if( defined( 'OESCUSTOM_JS_PATH' ) ){
                    $extention = pathinfo( $filename, PATHINFO_EXTENSION );
                    $id = str_replace( '.'.$extention, "-$extention", $filename );
                    echo "<script src='".OESCUSTOM_JS_PATH.'/'.$filename."' ></script>";
                    $load_js[ $filename ] = true;
                }
            }   
        }
    } 
} 


?>