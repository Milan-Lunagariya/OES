<?php
    if( isset( $_POST['LoginForm_sbmit'] ) && $_POST['LoginForm_sbmit'] == 'Login' ){
        if( file_exists( '../core/models/databasehandler.php' ) ) require_once '../core/models/databasehandler.php';

        $DatabaseHandler = class_exists( 'DatabaseHandler' ) ? new DatabaseHandler() : ''; 
        $name = isset( $_POST['LoginForm_name'] ) && !empty( $_POST['LoginForm_name'] ) ? $_POST['LoginForm_name'] : '';
        $password = isset( $_POST['LoginForm_password'] ) && !empty( $_POST['LoginForm_password'] ) ? $_POST['LoginForm_password'] : '';
        $remember = isset( $_POST['login_rememberMe'] ) && !empty( $_POST['login_rememberMe'] ) ? $_POST['login_rememberMe'] : '';
        $where = array(
            array( 'column' => 'role', 'value' => 'administrator', 'operator' => '=', 'conjunction' => 'AND' ),
            array( 'column' => 'username', 'value' => $name, 'operator' => '=', 'conjunction' => 'AND'),
            array( 'column' => 'password', 'value' => $password, 'operator' => '='),
        );
        $db_data = $DatabaseHandler->select( 'users', 'user_id, username, password, role', $where, '', '', '' );

        if( is_array( $db_data) && count( $db_data ) > 0 ){
            foreach( $db_data as $key => $value ){
                echo $value['user_id'];
                echo $value['username'];
                if( session_status() == PHP_SESSION_NONE ){
                    session_start();
                }
                $_SESSION['user_id'] = $value['user_id'];
                header( 'Location: index.php' );
            }
        }

        /* echo '<pre>';
            print_r( $db_data );
        echo '</pre>'; */

    }
?>