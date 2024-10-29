<?php 
if( file_exists( '../library/oesautoload.php' ) ){ require_once '../library/oesautoload.php'; }
if( file_exists( '..' ) ){ require_once '../core/models/loginprocess.php'; }

!defined( 'OESCUSTOM_CSS_PATH' ) ? define( 'OESCUSTOM_CSS_PATH', '../css' ) :  '';
!defined( 'OESCUSTOM_JS_PATH' ) ? define( 'OESCUSTOM_JS_PATH', '../js' ) :  '';

$admiLoginPage_load_css = array(
    'common.css' => false,
    'forms.css' => false,
    'index.css' => false,
    'tablets_view.css' => false,
);

$admiLoginPage_load_js = array(
    'jquery.js' => false,
    'common.js' => false,
    'index.js' => false, 
);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php  custom_load_front_cssJs( $admiLoginPage_load_css, $admiLoginPage_load_js ); ?>
    <title>Login Page</title>
</head>
<body>
    <div class="oes_adminlogin_container login_page_container">
        <div class="login_bg_container">
            <img src="../images/login_bg.jpg" alt="">
        </div>

        <div class="login_page_form_conatiner">
            <h1 class="adminloginform_title">Login Now</h1>
            <?php
                global $formcreater, $formhelper;
                if(file_exists('../core/classes/class.formcreator.php')){
                    require_once '../core/classes/class.formcreator.php';
                    require_once '../core/helpers/formhelper.php';
                }
                if( class_exists( 'formcreator' ) ){ $formcreater = new formcreator();}
                if( class_exists( 'formhelper' ) ){ $formhelper = new formhelper();}

                $name_field = $formcreater->field_create( $formhelper->adminLoginForm_nameField_attr() );
                $password_field = $formcreater->field_create( $formhelper->adminLoginForm_passwordField_attr() );                
                $rememberme_field = $formcreater->field_create( $formhelper->adminLoginForm_rememberMeField_attr() );                
                $submit_field = $formcreater->field_create( $formhelper->adminLoginForm_submitField_attr() );

                $fields = array( $name_field, $password_field, /* $rememberme_field, */ $submit_field );

                $forms_attr = array(
                    'action' => '',
                    'method' => 'POST',
                    'name' => 'LoginForm',
                    'id'   => 'LoginForm',
                    'class' => 'login_form',
                );

                echo $formcreater->form_create( $fields, $forms_attr ); 
            ?>
        </div>
    </div>
</body>
</html>