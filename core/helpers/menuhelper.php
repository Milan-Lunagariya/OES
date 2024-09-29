<?php
    global $nav_logo_help, $menu_top_help, $menu_header_help, $oescommonsvg;
    
    function menu_top_help(){
        global $oescommonsvg;
        $user_icon = (! empty( $oescommonsvg && isset( $oescommonsvg['login_icon'] ) )) ? $oescommonsvg['login_icon'] : 'Login';
        $shoppingCart_icon = (! empty( $oescommonsvg ) && $oescommonsvg['shoppingCart_icon']) ? $oescommonsvg['shoppingCart_icon'] : 'Cart';
        $menu_top = array(
            'login'  => "<div class='oesfrontShoopingCartButton' title='Add To cart'> $shoppingCart_icon </div>",
            'cart'  => "<div class='oesfrontLoginButton' title='Login User'> $user_icon </div>"
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