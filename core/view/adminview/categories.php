<?php 
 
global $categories, $formcreator, $databasehandler, $datatable;
if( class_exists( 'datatable' ) ){
    $datatable = new datatable();
}
    class categories
    { 
        function __construct()
        {
        }

        function oes_test(...$var){
            echo 'You are entred the categories class of the categories.php file.';
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

        function managecategories(){
            global $databasehandler, $datatable;
            $categoryid = 0;
            
            $table_header = array ( 'th' => array(
                    'Id', 'Image', 'Category Name', 'Parent Categorty', 'Created at', 'Updated at', 'Action' 
                )
            );
            $table_data = array();
            
            $data = $databasehandler->select( 'categories', '*');
            foreach( $data as $key => $value ){  
                $action = '';

                if( in_array($value['parentid'],['0', 0]) ) {
                    $parent = "Parent (0)";  
                } else{
                    $select = $databasehandler->select( 'categories', '*', array('categoryid' => $value['parentid']) );    
                    $parent = '';
                    foreach( $select as $k => $v ){
                        $parent = isset($v['name']) ? $v['name']."(".$v['categoryid'].')' : ''; 
                    }
                } 
                $image = json_decode( $value['images'], true );


                $categoryid = ( isset($value['categoryid']) && !empty($value['categoryid']) ) ? $value['categoryid']: '-'; 
                $images = ( isset($value['images']) && !empty($value['images']) ) ? "<div class='image_parent'><img src='../media/categories/".$image[0]."' alt='Not Found' width='100'></div>": '-'; 
                $name = ( isset($value['name']) && !empty($value['name']) ) ? $value['name']: '-'; 
                $parent = ( isset($parent) && !empty($parent) ) ? $parent : '-'; 
                $createdat  = ( isset($value['createdat']) && !empty($value['createdat']) ) ? $value['createdat']: '-'; 
                $updatedat = ( isset($value['updatedat']) && !empty($value['updatedat']) ) ? $value['updatedat']: '-'; 
                $action .= "<button id='$categoryid' class='edit edit_category_$categoryid'> Edit<!-- <i class='fa-solid fa-pen-to-square'></i> --> </button> &nbsp; ";
                $action .= "<button id='$categoryid' class='remove remove_category_$categoryid'> Remove<!-- <i class='fa-solid fa-trash'></i> --> </button>";

                $table_data[] = array( $categoryid, $images, $name, $parent, $createdat, $updatedat, $action);
            }

            echo ' <div class="manageCategories_message message_popup"> Message </div> ';
            echo " <div><h1 align='center'> Manage Categories </h1><div> ";
            $datatable->dataTableView( $table_header, $table_data, "tr_$categoryid" );
        }
    }

    $categories = new categories();
?>