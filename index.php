<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GadgetGalaxy</title>
    <?php  require_once "library/index.php";  ?>
    <?php  require_once "library/common.php";  ?>
    
</head>

<body>

    <?php  
        global $commonhelper;
        if( ! defined( 'MEDIA_PATH' ) ){
            define( 'MEDIA_PATH', 'media' );
        }
        if( ! defined( 'IMAGES_PATH' ) ){
            define( 'IMAGES_PATH', 'images' );
        }

        require_once 'core/helpers/commonhelper.php';
        $commonhelper->oes_required_file( 'core/models/databasehandler.php' );
        $commonhelper->oes_required_file( "core/helpers/menuhelper.php" );  
        $commonhelper->oes_required_file( "core/classes/class.header.php" );
        $commonhelper->oes_required_file( "core/classes/class.managecategory.php" );
        $commonhelper->oes_required_file( "core/view/header.php" );
        $commonhelper->oes_required_file( "core/view/main.php" );

        
        
    ?>
    
    
    
    
</body>

</html>