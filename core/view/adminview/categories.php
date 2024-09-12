<?php 
 
global $categories, $formcreator, $databasehandler, $datatable;
if( class_exists( 'datatable' ) ){
    $datatable = new datatable();
}
if( file_exists( '../../classes/class.formcreator.php' ) ){
    require_once '../../classes/class.formcreator.php';
}
if( file_exists( '../../classes/class.formcreator.php' ) ){
    require_once '../../helpers/formhelper.php';
}
if( file_exists( '../../models/databasehandler.php' ) ){
    require_once '../../models/databasehandler.php';
}
$formhelper = ( class_exists( 'formhelper' ) ) ? new formhelper() : false; 
$formcreator = ( class_exists( 'formcreator' ) ) ? new formcreator() : false; 
$categories = ( class_exists( 'categories' ) ) ? new categories() : false;  
        
        $commonhelper       = ( class_exists( 'commonhelper' ) ) ? new commonhelper() : false;
        $databasehandler  = ( class_exists( 'databasehandler' ) ) ? new databasehandler() : false;
        $datatable        = ( class_exists( 'datatable' ) ) ? new datatable() : false;
        $formhelper       = ( class_exists( 'formhelper' ) ) ? new formhelper() : false;
        $formcreator      = ( class_exists( 'formcreator' ) ) ? new formcreator() : false; 
        $categories       = ( class_exists( 'categories' ) ) ? new categories() : false;

class categories
{  
    var $is_editCategoryForm;
    function __construct()
    {
        $this->is_editCategoryForm = false;
    }

    public function oes_test(...$var){
        echo 'You are entred the categories class of the categories.php file.';
    }
     
    public function formview( $title = "Add Category", $field_extraAttr = array() ){
 
        global $formcreator, $formhelper;
        $formid = $categoryImage_value  = $categoryname_value = $parentcategory_value = $submitButtton_value = false;
        $new_create_field = array();
        $content = '<h1 class="title"> '.$title.' </h1>';

        $content .= '<div class="category_form_message message_popup"> Message </div>';

        if( method_exists( 'formcreator', 'field_create' ) && is_object( $formcreator ) ) {
 
            if( count( $field_extraAttr ) > 0) {
                foreach( $field_extraAttr as $attr ){

                    $attr['name'] = isset($attr['name']) ? $attr['name'] : false;
                    $attr['value'] = isset($attr['value']) ? $attr['value'] : false;
                    $attr['action'] = isset($attr['action']) ? $attr['action'] : false; 

                    if( $attr['name'] == 'category_formid' ){
                        $formid = isset( $attr['id'] ) ? $attr['id'] : '';
                        if( $formid == 'edit_categoryform' ){
                            $this->is_editCategoryForm = true;
                        }
                    } 
                    
                    if( $attr['name'] == 'categoryimage' ){
                        $categoryImage_value = $attr['value'];
                    } else if ( $attr['name'] == 'categoryname' ){
                        $categoryname_value = $attr['value'];
                    } else if ( $attr['name'] == 'parentcategory' ){
                        $parentcategory_value = $attr['value'];
                    } else if ( $attr['name'] == 'submitButtton' ){
                        $submitButtton_value = $attr['value'];
                    } else if( $attr['action'] == 'create_field' ){
                        $field_attr = array();
                        foreach( $attr as $field_name => $field_value ){
                            if( $field_name == 'action' ){
                                continue;
                            }
                            $field_attr[$field_name] = $field_value; 
                        }
                        $new_create_field[]  = $formcreator->field_create( $field_attr );        
                    } 
                }
            }
             
            $category_image  = $formcreator->field_create( $formhelper->category_image_attr( $categoryImage_value ) );  
            $categories_name  = $formcreator->field_create( $formhelper->category_name_attr( $categoryname_value ) );  
            $parent_categories  = $formcreator->field_create( $formhelper->parent_category_attr( $parentcategory_value ) );  
            $submit           = $formcreator->field_create( $formhelper->submit_attr( $submitButtton_value ) );  
            
            foreach( $new_create_field as $new_field ){                        
                $fields[] = $new_field; 
            } 
            $fields[] = $category_image;
            $fields[] = $categories_name;
            $fields[] = $parent_categories;
            $fields[] = $submit; 
            $category_form = $formcreator->form_create( $fields, $formhelper->category_form_attr( $formid ) );    
            $content .= $category_form;  
        }
            
        return $content;
    }

    public function managecategories(){
        global $databasehandler, $datatable, $categories;
        $categoryid = 0;
        $table_data = array();
 
        echo ' 
        <div class="editCategory_popup_container">
            <button class="close_editCategory"> X </button>
            <div class="manageCategories_form_popup"> Loading . . . </div>
        </div>';            
        $table_header = array ( 'th' => array(
                'Id', 'Image', 'Category Name', 'Parent Categorty', 'Created at', 'Updated at', 'Action' 
            )
        ); 
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
            $categoryimages = ( isset( $value['images'] ) && $value['images'] != '' ) ? json_decode( $value['images'], true ) : array();
            $categoryimage = ( is_array($categoryimages) && count($categoryimages) > 0 ) ? trim( $categoryimages[0] ) : '';
            $image_path = ( $categoryimage != '' ) ? "../media/categories/".$categoryimage :  '';


            $categoryid = ( isset($value['categoryid']) && !empty($value['categoryid']) ) ? $value['categoryid']: '-'; 
            $images = ( ! empty($image_path) ) ? "<div class='image_parent'><a href='$image_path' target='_blank' ><img src='$image_path' alt='Not Found' width='100'></a></div>": '-'; 
            $name = ( isset($value['name']) && !empty($value['name']) ) ? $value['name']: '-'; 
            $parent = ( isset($parent) && !empty($parent) ) ? $parent : 'Parent (0)'; 
            $createdat  = ( isset($value['createdat']) && !empty($value['createdat']) ) ? $value['createdat']: '-'; 
            $updatedat = ( isset($value['updatedat']) && !empty($value['updatedat']) ) ? $value['updatedat']: '-'; 
            $action .= "<button id='$categoryid' class='edit edit_category_$categoryid'> Edit<!-- <i class='fa-solid fa-pen-to-square'></i> --> </button> &nbsp; ";
            $action .= "<button id='$categoryid' class='remove remove_category_$categoryid'> Remove<!-- <i class='fa-solid fa-trash'></i> --> </button>";

            $table_data[] = array( $categoryid, $images, $name, $parent, $createdat, $updatedat, $action);
        }

        echo ' <div class="manageCategories_message message_popup"> Message </div> ';
        echo " <div><h1 align='center'> Manage Categories </h1><div> ";
        $datatable->dataTableView( $table_header, $table_data, "category_tr_$categoryid" );
    }
} 

?>