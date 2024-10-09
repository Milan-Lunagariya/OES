<?php

    global  $oesfrontload_index_css, $oesfrontload_index_js;
    $oesfrontload_index_css = array(
        'common.css' => false,
        'forms.css' => false,
        'index.css' => false,
        'tablets_view.css' => false,
    );
    $oesfrontload_index_js = array(
        'jquery.js' => false,
        'common.js' => false,
        'index.js' => false, 
    );



  if( ! defined( 'OESFRONT_CORE_PATH') ) { define( 'OESFRONT_CORE_PATH', 'core' ); }
  if( ! defined( 'OESFRONT_LIBRARY_PATH') ) { define( 'OESFRONT_LIBRARY_PATH', 'library' ); }
  if( ! defined( 'OESFRONT_JS_PATH') ) { define( 'OESFRONT_JS_PATH', 'js' ); }
  if( ! defined( 'OESFRONT_CSS_PATH') ) { define( 'OESFRONT_CSS_PATH', 'css' ); }
  if( ! defined( 'OESFRONT_IMAGES_PATH') ) { define( 'OESFRONT_IMAGES_PATH', 'images' ); }
  if( ! defined( 'OESFRONT_MEDIA_PATH') ) { define( 'OESFRONT_MEDIA_PATH', 'media' ); }
  if( ! defined( 'OESFRONT_ASSETS_PATH') ) { define( 'OESFRONT_ASSETS_PATH', 'assets' ); }
  if( ! defined( 'OESFRONT_BOOTSTRAP_PATH') ) { define( 'OESFRONT_BOOTSTRAP_PATH', 'bootstrap' ); }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GadgetGalaxy</title>

    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">  
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <?php 
    if( defined( 'OESFRONT_LIBRARY_PATH' ) && file_exists( OESFRONT_LIBRARY_PATH.'/oesautoload.php' ) ){
        require_once OESFRONT_LIBRARY_PATH.'/oesautoload.php';
    }
    ?>
    
</head>

<body>

    <?php   
        
        if( ! defined( 'MEDIA_PATH' ) ){
            define( 'MEDIA_PATH', 'media' );
        }
        if( ! defined( 'IMAGES_PATH' ) ){
            define( 'IMAGES_PATH', 'images' );
        } 
        
        global $home_slider_array;
        $home_slider_array = array(
            'home_slider_2.jpg',
            'home_slider_3.jpg',
            'home_slider_4.jpg',
        );

        if( defined( 'OESFRONT_CORE_PATH' ) && file_exists( OESFRONT_CORE_PATH.'/helpers/commonhelper.php' ) ){
             
            require_once OESFRONT_CORE_PATH.'/helpers/commonhelper.php';

            if( method_exists( 'commonhelper', 'oes_required_file' ) ){
            
                global $commonhelper;
                $commonhelper->oes_required_file( OESFRONT_ASSETS_PATH."/svg/commonsvg.php" );
                $commonhelper->oes_required_file( OESFRONT_CORE_PATH.'/models/databasehandler.php' );
                $commonhelper->oes_required_file( OESFRONT_CORE_PATH."/helpers/menuhelper.php" );  
                $commonhelper->oes_required_file( OESFRONT_CORE_PATH."/classes/class.header.php" );
                $commonhelper->oes_required_file( OESFRONT_CORE_PATH."/classes/class.managecategory.php" );
                $commonhelper->oes_required_file( OESFRONT_CORE_PATH."/view/header.php" );
                $commonhelper->oes_required_file( OESFRONT_CORE_PATH."/view/main.php" );
            
            } else {
                echo 'Files Not loaded, Try again ! ';
            } 

        } else{
            echo 'Files Not loaded, Try again ! ';
        }

        
        
    ?>
    
    
    
    
</body>

</html>