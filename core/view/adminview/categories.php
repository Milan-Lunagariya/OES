<?php 
 
global $addcategories, $formcreator;
    class addcategories
    { 
        function __construct()
        {
        }

        function formview( $title = "Add Category" ){
            global $formcreator, $formhelper;
            
            $content = '<h1 class="title"> '.$title.' </h1>';

            $content .= '<div class="category_form_message message_popup"> Message </div>';

            $categories_name  = $formcreator->field_create( $formhelper->category_name_attr );  
            $parent_categories  = $formcreator->field_create( $formhelper->parent_category_attr() );  
            $category_image  = $formcreator->field_create( $formhelper->category_image_attr );  
            $submit           = $formcreator->field_create( $formhelper->submit_attr );  
            
            $fields = array( 
                $category_image,
                $categories_name,
                $parent_categories,
                $submit,
            );
            $category_form = $formcreator->form_create( $fields, $formhelper->category_form_attr );             


            $content .= $category_form;  
               
            return $content;
        }
    }
    $addcategories = new addcategories();
?>