<?php 

global $managecategory;

class managecategory
{

    function __construct(){

    }
    function all_parent_categories($parent_category = array(), $image_src = ""){

        $content = '';
        if(count($parent_category) == 0)
            $content = '<div class="text-center"> Category menu is empty </div>  ';
        else{ 
            foreach( $parent_category as $key => $value ) {
                $content .= '
                <div class="separate_container"> 
                    <img src="'.$image_src.'"  alt="'.$value.'" width="100" >
                </div> ';
            }
        }
        return $content;
    }

}

$managecategory = new managecategory();

?>