<?php 
 
global $addcategories, $formcreator;

/* require_once('../../helpers/commonhelper.php'); */

    class addcategories
    { 
        function __construct()
        {
        }

        function formview( $title = "Add Category" ){
            global $addcategories, $formcreator, $category_name_attr, $submit_attr, $category_form_attr, $parent_category_attr, $formhelper;
            
            $content = '<h1 class="title"> '.$title.' </h1>';

            $content .= '<div class="category_form_message message_popup"> Message </div>';

            $categories_name  = $formcreator->field_create( $formhelper->category_name_attr );  
            $parent_categories  = $formcreator->field_create( $formhelper->parent_category_attr() );  
            $submit           = $formcreator->field_create( $formhelper->submit_attr );  
            
            $fields = array( $categories_name, $parent_categories, $submit );
            $category_form = $formcreator->form_create( $fields, $formhelper->category_form_attr );             


            $content .= $category_form;  
               
            return $content;
        }
    }
    $addcategories = new addcategories();
?>