<?php 

    global $frontheader;

    class front_header
    {
        function __construct()
        {
        }
        function nav_logo($logo = false)
        {
            if ($logo == '' || $logo == false) {
                return;
            }
            $content = '<div class="left">
                            <div class="menu_tablets_open_button"> = </div>
                            <div class="logo"> ' . $logo . ' </div> 
                        </div>';
            return $content;
        }
        function nav_searchbar()
        {
            $content = '<div class="center">
                                <div class="searchbar">
                                <!-- <label for="nav_search"></label> -->
                                <input type="search" name="" placeholder="Search for produc, brands and more" class="nav_search">
                                </div>
                            </div>';
            return $content;
        }
        function nav_menu_top($menu_top = array())
        { 
            if (!is_array($menu_top)) {
                return;
            }
            $content = '';
            $content .= ' <div class="right"> ';
            foreach ($menu_top as $key => $value) { 
                $content .= '<div class="icon">	' . $value . '	</div>';
            }
            $content .= 	'</div>';
            return $content;
        }
        function nav_menu_top_tablets(){
            $content = '<div class="">';
            return $content;
        }
        function nav_tablets_view_sigle_box($link="", $display_name="", $container_class="", $link_class="s"){
            $content = '
            <div class=" '.$container_class.'">
				<a href="'.$link.'">'.$display_name.'</a>
			</div>';
            return $content;
        }
    }
    $frontheader = new front_header();
?>