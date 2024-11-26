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

function load_bootstrap_js( $jsCustomFileName =array() ){
    if( defined( 'OESSEPARATE_BOOTSTRAP_JS_PATH' ) ){

        if( is_array( $jsCustomFileName ) && count( $jsCustomFileName ) > 0 ){
            $temp = array();
            foreach( $jsCustomFileName as $filename ){
                $temp[] = OESSEPARATE_BOOTSTRAP_JS_PATH.'/'.$filename;
            }
            $js_files = $temp;
        } else {
            $js_files = glob( OESSEPARATE_BOOTSTRAP_JS_PATH.'/*.*' );
        }


        if( is_array( $js_files ) ){ 
            foreach( $js_files as $filename ){
                if( $filename != '.' && $filename != '..' ){
                    $extention = pathinfo( $filename, PATHINFO_EXTENSION );
                    $id = str_replace( '.'.$extention, "-$extention", $filename );
                    echo "<script src='$filename' id='oesseparate-$id'></script> \n";
                }
            }
        }
    } 
}
function load_bootstrap_css( $cssCustomFileName = array() ){

    if( defined( 'OESSEPARATE_BOOTSTRAP_CSS_PATH' ) ){
        
        if( is_array( $cssCustomFileName ) && count( $cssCustomFileName ) > 0 ){
            $temp = array();
            foreach( $cssCustomFileName as $filename ){
                $temp[] = OESSEPARATE_BOOTSTRAP_CSS_PATH.'/'.$filename;
            }
            $css_files = $temp;
        } else {
            $css_files = glob( OESSEPARATE_BOOTSTRAP_CSS_PATH.'/*.*' );
        }
 
        if( is_array( $css_files ) ){
            foreach( $css_files as $filename ){ 
                if( $filename != '.' && $filename != '..' ){
                    $extention = pathinfo( $filename, PATHINFO_EXTENSION );
                    $id = str_replace( '.'.$extention, "-$extention", $filename );
                    echo "<link rel='stylesheet' href='$filename' > \n";
                }
            }
        }
    }      
}

?>