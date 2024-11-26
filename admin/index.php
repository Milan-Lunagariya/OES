<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
/* unset( $_SESSION['user_id'] ); */
if (! isset($_SESSION['user_id'])) {
    header('Location: adminlogin.php');
}

if (! defined('OESADMIN_CORE_PATH')) {
    define('OESADMIN_CORE_PATH', '../core');
}
if (! defined('OESADMIN_LIBRARY_PATH')) {
    define('OESADMIN_LIBRARY_PATH', '../library');
}
if (! defined('OESADMIN_JS_PATH')) {
    define('OESADMIN_JS_PATH', '../js');
}
if (! defined('OESADMIN_CSS_PATH')) {
    define('OESADMIN_CSS_PATH', '../css');
}
if (! defined('OESADMIN_IMAGES_PATH')) {
    define('OESADMIN_IMAGES_PATH', '../images');
}
if (! defined('OESADMIN_MEDIA_PATH')) {
    define('OESADMIN_MEDIA_PATH', '../media');
}
if (! defined('OESADMIN_ASSETS_PATH')) {
    define('OESADMIN_ASSETS_PATH', '../assets');
}

if( ! defined( 'OESSEPARATE_BOOTSTRAP_CSS_PATH' ) ){
    define( 'OESSEPARATE_BOOTSTRAP_CSS_PATH' , '../bootstrap/css' );
}
if( ! defined( 'OESSEPARATE_BOOTSTRAP_JS_PATH' ) ){
    define( 'OESSEPARATE_BOOTSTRAP_JS_PATH' , '../bootstrap/js' );
}

global $oesadmin_load_css, $oesadmin_load_js, $addcategories, $formcreator, $india_timezone, $commonhelper, $media_categories_path, $DatabaseHandler, $categorycontroller, $datatable, $formhelper, $formcreator, $oescommonsvg;

$media_categories_path = OESADMIN_MEDIA_PATH . "/categories";

// require '../core/assets/svg/commonsvg.php';
if (defined('OESADMIN_CORE_PATH') && file_exists(OESADMIN_CORE_PATH . '/helpers/commonhelper.php')) {
    require_once(OESADMIN_CORE_PATH . '/helpers/commonhelper.php');
    
    if (!empty($commonhelper) && method_exists('commonhelper', 'oes_required_file')) {
        $commonhelper->oes_required_file(OESADMIN_CORE_PATH . '/models/databasehandler.php');
        $commonhelper->oes_required_file(OESADMIN_CORE_PATH . '/models/datatable.php');
        $commonhelper->oes_required_file(OESADMIN_CORE_PATH . '/helpers/formhelper.php');
        $commonhelper->oes_required_file(OESADMIN_CORE_PATH . '/classes/class.formcreator.php');
        $commonhelper->oes_required_file(OESADMIN_CORE_PATH . '/view/adminview/categories.php');
        $commonhelper->oes_required_file(OESADMIN_CORE_PATH. '/assets/svg/commonsvg.php');
        $commonhelper->oes_required_file(OESADMIN_CORE_PATH . '/models/modelcategory.php');
        $commonhelper->oes_required_file(OESADMIN_CORE_PATH . '/view/adminview/categories.php');
        $commonhelper->oes_required_file(OESADMIN_CORE_PATH . '/view/adminview/products.php');
        $commonhelper->oes_required_file(OESADMIN_CORE_PATH . '/view/adminview/products.php');
    }
    /* require_once 'adminlogin.php'; */
}

$commonhelper        = (class_exists('commonhelper')) ? new commonhelper() : false;
$databasehandler     = (class_exists('databasehandler')) ? new databasehandler() : false;
$datatable           = (class_exists('datatable')) ? new datatable() : false;
$formhelper          = (class_exists('formhelper')) ? new formhelper() : false;
$formcreator         = (class_exists('formcreator')) ? new formcreator() : false;
$categories          = (class_exists('categories')) ? new categories() : false;
$products          = (class_exists('products')) ? new products() : false;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  -->
    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script> -->


    
    <title>Admin | Electronic Shopping</title>

    <?php
    $oesadmin_load_css = array(
        'common.css' => false,
        'forms.css' => false,
    );
    if (defined('OESADMIN_LIBRARY_PATH') && file_exists(OESADMIN_LIBRARY_PATH . '/oesautoload.php')) {
        require_once OESADMIN_LIBRARY_PATH . '/oesautoload.php';
        custom_load_admin_css($oesadmin_load_css);
    }
    
    load_bootstrap_css( array( 'bootstrap.css', 'custom_bt.css' ) );
    ?>

</head>

<body>
 
    <div class="oes_adminPanel_container d-flex">

        <div class="oes_adminPanel_sidebar bg-dark text-white p-3">
            <?php 
                $activeParentMeu = '';
                $showSubMenu = '';
                $active = '';
                $page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '';
            ?>
            <div class="openCloseContainer">
                <button class="openCloseButton" onclick="openCloseAdminSidebar()"> = </button>
            </div>
            <nav>
                <ul>
                    <li class="floatLeft">
                        <div class="oes_adminhomelink_container">
                            <a class="floatLeft nav-link text-white oes_adminPanel_nav-link" href="../index.php">
                                <?php echo isset($oescommonsvg['home_icon']) ? $oescommonsvg['home_icon'] : '-'; ?>
                                <h5 class="floatLeft"> Home </h5>
                            </a>
                        </div>
                    </li>
                    <li class="floatLeft">
                        <?php 
                            $activeParentMeu = $showSubMenu = '';
                            if( $page == 'add_categories' || $page == 'manage_categories' ) {
                                $showSubMenu = 'showSubMenu';
                                $activeParentMeu = 'activeParentMeu';
                            }
                            $addCategories_active = ( $page == 'add_categories' ) ? 'active' : '';
                            $manageCategories_active = ( $page == 'manage_categories' ) ? 'active' : '';
                        ?>
                        <div class="admininner_sidebarmenu_container nav-link text-white oes_adminPanel_nav-link <?php echo $activeParentMeu; ?>">Categories</div>
                        <ul class="pl-3 admininner_sidebarmenu <?php echo $showSubMenu; ?>">
                            <li>
                                <a href="index.php?page=add_categories" class="nav-link text-white oes_adminPanel_nav-link <?php echo $addCategories_active; ?>">Add Category</a>
                                <a href="index.php?page=manage_categories" class="nav-link text-white oes_adminPanel_nav-link <?php echo $manageCategories_active; ?>">Manage Category</a>
                            </li>
                        </ul>
                    </li>
                    <li class="floatLeft" >
                        <?php
                            $activeParentMeu = $showSubMenu = '';
                            if( $page == 'add_products' || $page == 'manage_products' ){
                                $showSubMenu = 'showSubMenu';
                                $activeParentMeu = 'activeParentMeu';
                            }
                            $addProducts_active = ( $page == 'add_products' ) ? 'active' : '';
                            $manageProducts_active = ( $page == 'manage_products' ) ? 'active' : '';
                        ?>
                    <div class="admininner_sidebarmenu_container nav-link text-white oes_adminPanel_nav-link <?php echo $activeParentMeu; ?>">Products</div>
                        <ul class="pl-3 admininner_sidebarmenu <?php echo $showSubMenu; ?>">
                            <li>
                                <a href="index.php?page=add_products" class="nav-link text-white oes_adminPanel_nav-link <?php echo $addProducts_active; ?> ">Add Products</a>
                                <a href="index.php?page=manage_products" class="nav-link text-white oes_adminPanel_nav-link <?php echo $manageProducts_active; ?> ">Manage Products</a>
                            </li>
                        </ul>
                    </li>
                    <li class="floatLeft">
                        <?php $activeSettings = ( $page == 'settings' ) ? 'active' : '';  ?>
                        <a href="index.php?page=settings" class="nav-link text-white oes_adminPanel_nav-link <?php echo $activeSettings; ?>">Settings</a>
                    </li>
                </ul>
            </nav>
        </div>
        
        <main class="oes_adminPanel_main flex-grow-1 pt-5 p-3 pl-5">
            <section id="dashboard" class="oes_adminPanel_content-section">
                <?php
                     if (isset($_REQUEST['page'])) {

                        if ($_REQUEST['page'] == 'dashboard') {
    
                        } elseif (isset($_REQUEST['page']) && $_REQUEST['page'] == 'add_categories') { 
    
                            echo (isset($categories) && !empty($categories)) ? $categories->formview("Add Category") :  "Error: ".__FILE__ . ':' . __LINE__;
    
                        } elseif ($_REQUEST['page'] == 'manage_categories') {
    
                            echo (isset($categories) && !empty($categories)) ? $categories->managecategories( 'Manege Categories' ) : "Error: ". __FILE__ . ':' . __LINE__;
    
                        } elseif( $_REQUEST['page'] == 'add_products' ){
                            
                            echo (isset($products) && !empty($products)) ? $products->formview("Add Products") : "Error: ". __FILE__ . ':' . __LINE__;
    
                        } elseif( $_REQUEST['page'] == 'manage_products' ){
                            
                            echo (isset($products) && !empty($products)) ? $products->manageproducts( "Manage Products" ) : "Error: ". __FILE__ . ':' . __LINE__;
    
                        } else {
                            echo "<h2> 404 Page Not Found! <h2>";
                        }
    
                    } else {
                        echo 'Outside the page ';
                    }
                ?>
            </section>
        </main>

    </div>
    <?php
    $oesadmin_load_js = array(
        'jquery.js' => false,
        'common.js' => false,
        'admin_forms.js' => false,
        'admin.js' => false,
    );

    if (defined('OESADMIN_LIBRARY_PATH') && file_exists(OESADMIN_LIBRARY_PATH . '/oesautoload.php')) {
        require_once OESADMIN_LIBRARY_PATH . '/oesautoload.php';
        custom_load_admin_js($oesadmin_load_js);
    }
    load_bootstrap_js( array( 'bootstrap.bundle.js', 'custom_bt.js' ) );
    ?>
</body>

</html>