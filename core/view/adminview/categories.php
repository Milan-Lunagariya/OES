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
if( file_exists( '../../models/datatable.php' ) ){
    require_once '../../models/datatable.php';
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

    public function managecategories( $current_page = 1, $record_limit = 5 ){

        global $databasehandler, $datatable, $categories;
        $categoryid = 0;
        $table_data = array();
        
        $datatable        = ( class_exists( 'datatable' ) ) ? new datatable() : false;

        echo '
        <div class="oes_loader_center"> Loading . . . </div>
        <div class="editCategory_popup_container">
            <button class="close_editCategory"> X </button>
            <div class="manageCategories_form_popup"> Loading . . . </div>
        </div>';

        $table_header = array ( 'th' => array(
                '<input type="checkbox" class="" name="" value="">',
                'Id', 'Image', 'Category Name', 'Parent Categorty', 'Created at', 'Updated at', 'Action' 
            )
        ); 

        
        $current_page = ( is_numeric( $current_page ) && $current_page > 0 ) ? $current_page : 1;
        $limit = ( $record_limit != '' ) ? intval( $record_limit ) : 10;
        $offset = ( $current_page - 1 ) * $limit;

        $data = $databasehandler->select( 'categories', '*', array(), '', 'categoryid ASC', $limit, $offset);
        $total_records = $databasehandler->select( 'categories', 'COUNT(categoryid) AS "total_record"');
        $total_records = isset( $total_records[0]['total_record'] ) ? $total_records[0]['total_record'] : 0;

        foreach( $data as $key => $value ){  
            $action = '';

            if( in_array($value['parentid'],['0', 0]) ) {
                $parent = "Parent (0)";  
            } else{
                $select = $databasehandler->select( 'categories', '*', array( array( 'column' => 'categoryid', 'value' => $value['parentid'], 'type' => PDO::PARAM_STR ) ) );
                $parent = '';
                foreach( $select as $k => $v ){
                    $parent = isset($v['name']) ? $v['name']."(".$v['categoryid'].')' : ''; 
                }
            } 

            $categoryimages = ( isset( $value['images'] ) && $value['images'] != '' ) ? json_decode( $value['images'], true ) : array();
            $categoryimage = ( is_array($categoryimages) && count($categoryimages) > 0 ) ? trim( $categoryimages[0] ) : '';
            $image_path = ( $categoryimage != '' ) ? "../media/categories/".$categoryimage :  '';

            $select_current = '<input type="checkbox" class="" name="" value="">';
            $categoryid = ( isset($value['categoryid']) && !empty($value['categoryid']) ) ? $value['categoryid']: '-'; 
            $images = ( ! empty($image_path) ) ? "<div class='image_parent'><a href='$image_path' target='_blank' ><img src='$image_path' alt='Not Found' width='100'></a></div>": '-'; 
            $name = ( isset($value['name']) && !empty($value['name']) ) ? $value['name']: '-'; 
            $parent = ( isset($parent) && !empty($parent) ) ? $parent : 'Parent (0)'; 
            $createdat  = ( isset($value['createdat']) && !empty($value['createdat']) ) ? $value['createdat']: '-'; 
            $updatedat = ( isset($value['updatedat']) && !empty($value['updatedat']) ) ? $value['updatedat']: '-'; 
            $action .= "<button id='$categoryid' class='edit edit_category_$categoryid'> Edit<!-- <i class='fa-solid fa-pen-to-square'></i> --> </button> &nbsp; ";
            $action .= "<button id='$categoryid' class='remove remove_category_$categoryid'> Remove<!-- <i class='fa-solid fa-trash'></i> --> </button>";

            $table_data[] = array( $select_current, $categoryid, $images, $name, $parent, $createdat, $updatedat, $action );
        }

        echo ' <div class="manageCategories_message message_popup"> Message </div> '; 
        $total_pages = ceil( $total_records / $limit );
        if ( isset($datatable) && is_object($datatable) ){
            $datatable->dataTableView( $table_header, $table_data, "category_tr_$categoryid", $total_pages, $current_page );
        } else{
            echo '$datatable is not object, Please try to get object $datatable... ';
        }
    }
} 


$error = "OES Error: ". __LINE__. __FILE__;
if (isset($_REQUEST['page']) && $_REQUEST['page'] == 'add_categories') { 
    echo (isset($categories) && !empty($categories)) ? $categories->formview("Add Category") : $error;
}
 
if( isset( $_REQUEST['current_page'] ) ){

    $page_no = ( isset( $_REQUEST['current_page'] ) && $_REQUEST['current_page'] > 0 ) ? $_REQUEST['current_page'] : 1;
    $record_limit = ( isset( $_REQUEST['record_limit'] ) && $_REQUEST['record_limit'] > 0 ) ? intval( $_REQUEST['record_limit'] ) : 5;
    $record_limit = ( is_numeric( $record_limit ) && $record_limit > 0 ) ? $record_limit : 5;
    echo "Page no :".$page_no;
    echo (isset($categories) && !empty($categories)) ? $categories->managecategories( $page_no, $record_limit ) : $error;

} elseif ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'manage_categories') { 
    echo (isset($categories) && !empty($categories)) ? $categories->managecategories( 1, 5 ) : $error;
} 



?>