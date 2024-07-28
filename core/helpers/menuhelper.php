<?php
    global $nav_logo_help, $menu_top_help, $menu_header_help;
    
    function menu_top_help(){
        $menu_top = array(
            'cart'   => '<i class="fa-solid fa-cart-shopping cart_icon"></i>',
            'login'  => '<i class="fa-regular fa-user login_icon"></i>'
        );
        return $menu_top;
    }
    $menu_top_help = menu_top_help();

    function nav_logo_help(){
        return $logo = '<img src="images/flipcart.png" height="150" class="image" alt="GadgetGalaxy">';
    }
    $nav_logo_help = nav_logo_help();

    function menu_header_help(){
        $menu_header = array(
            'home'          => '<a href="#"> Home </a>',
            'about_us'      => '<a href="#"> About Us </a>',
            'contact_us'    => '<a href="#"> Contact us </a>',
            'settings'      => '<a href="#"> Settings </a>',
        );
        return $menu_header;
    }
    $menu_header_help = menu_header_help();
    
?>