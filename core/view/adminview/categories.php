<?php 
 
global $categories, $formcreator, $databasehandler, $datatable, $oescommonsvg;

/* if( defined( 'OESADMIN_CORE_PATH' ) && file_exists( OESADMIN_CORE_PATH.'/classes/class.formcreator.php' ) ){ require_once OESADMIN_CORE_PATH.'/classes/class.formcreator.php';}
if( defined( 'OESADMIN_CORE_PATH' ) && file_exists( OESADMIN_CORE_PATH.'/classes/class.formcreator.php' ) ){ require_once OESADMIN_CORE_PATH.'/classes/class.formcreator.php'; }
if( defined( 'OESADMIN_CORE_PATH' ) && file_exists( OESADMIN_CORE_PATH.'/models/databasehandler.php' ) ){ require_once OESADMIN_CORE_PATH.'/models/databasehandler.php'; }
if( defined( 'OESADMIN_CORE_PATH' ) && file_exists( OESADMIN_CORE_PATH.'/models/datatable.php' ) ){ require_once OESADMIN_CORE_PATH.'/models/datatable.php'; }
if( defined( 'OESADMIN_ASSETS_PATH' ) && file_exists( OESADMIN_ASSETS_PATH.'/svg/commonsvg.php' ) ){ require_once OESADMIN_ASSETS_PATH.'/svg/commonsvg.php'; }
 */
$formhelper = ( class_exists( 'formhelper' ) ) ? new formhelper() : false; 
$formcreator = ( class_exists( 'formcreator' ) ) ? new formcreator() : false; 
$categories = ( class_exists( 'categories' ) ) ? new categories() : false;  
$commonhelper = ( class_exists( 'commonhelper' ) ) ? new commonhelper() : false;
$databasehandler  = ( class_exists( 'databasehandler' ) ) ? new databasehandler() : false;
$datatable = ( class_exists( 'datatable' ) ) ? new datatable() : false;

class categories
{  
    var $is_editCategoryForm;
  
    function __construct()
    {
        $this->is_editCategoryForm = false;
        /* $this->current_page = 1;
        $this->category_record_showLimit = 5; */
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

    public function categoriesTableData( $current_page = 1, $category_record_showLimit = 5){
        
        
        global $databasehandler, $datatable, $categories, $oescommonsvg;
        $categoryid = 0;
        $table_data = array();
        $printTable = '';
        
        if( file_exists('../../models/databasehandler.php') ){ require_once '../../models/databasehandler.php'; }
        if( file_exists('../../models/datatable.php') ){ require_once '../../models/datatable.php'; }

        $databasehandler  = ( class_exists( 'databasehandler' ) ) ? new databasehandler() : false;
        $datatable = ( class_exists( 'datatable' ) ) ? new datatable() : false;
        
        $current_page = ( is_numeric( $current_page ) && $current_page > 0 ) ? $current_page : 1;
        $limit = ( $category_record_showLimit != '' ) ? intval( $category_record_showLimit ) : 5;
        $offset = ( $current_page - 1 ) * $limit;  

        $data = $databasehandler->select( 'categories', '*', array(), '', 'categoryid ASC', $limit, $offset);
        $total_records = $databasehandler->select( 'categories', 'COUNT(categoryid) AS "total_record"');
        $total_records = isset( $total_records[0]['total_record'] ) ? $total_records[0]['total_record'] : 0;

        $th_data = array ( 'th' => array(
                '<input type="checkbox" class="" name="" value="">' => '50px',
                'Id' => '50px', 
                'Image' => '200px',
                'Category Name' => '200px',
                'Parent Categorty'=> '200px',
                'Created at' => '200px',
                'Updated at' => '200px',
                'Action' => '150px' 
            )
        ); 

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
            $edit_icon = isset( $oescommonsvg['edit_icon'] ) ? $oescommonsvg['edit_icon'] : 'Edit';
            $delete_icon = isset( $oescommonsvg['delete_icon'] ) ? $oescommonsvg['delete_icon'] : 'delete';
            $action .= "<button id='$categoryid' class='edit data_modify_button edit_category_$categoryid'>$edit_icon</button>";
            $action .= "<button id='$categoryid' class='remove data_modify_button remove_category_$categoryid' >$delete_icon</button>";

            $table_data[] = array( $select_current, $categoryid, $images, $name, $parent, $createdat, $updatedat, $action );
        } 

        $td_data = $table_data;
        $tr_class = "category_tr_$categoryid";
        $total_pages = ceil( $total_records / $limit );
        $current_page = $current_page; 
 
        $printTable .= ( isset( $current_page ) && $current_page > 0) ? "<input type='hidden' class='managecategory_currentpage' value='{$current_page}' >" : '';
        if ( isset($datatable) && method_exists( 'datatable', 'dataTableView' ) ){
            $printTable .= $datatable->dataTableView( $th_data, $td_data, $tr_class, $total_pages, $current_page );
        } else{
            $printTable .= '$datatable is not object, Please try to get object $datatable... '.__FILE__.' > '. __LINE__;
        }   
        return $printTable;
    }

    public function managecategories(){

        global $datatable, $oescommonsvg;
        $viewEntireTable = ''; 
        $th_data = array();
        $td_data = array();
        $tr_class =  '';
        $total_pages = array();
        $current_page = 1;

        $datatable = ( class_exists( 'datatable' ) ) ? new datatable() : false;
        $showrecord_limit_array = array( 5, 10, 25, 50, 100 );
        $viewEntireTable .= "<div class='datatable' > ";
            $viewEntireTable .= '<div class="dataTableHeader" >
                <div class="showRecordsSelectPicker">
                    Show ';
                    $viewEntireTable .= '<select class="datatable_field category_record_showLimit" name="" id="" value="5" >';
                        foreach( $showrecord_limit_array as $limit ){
                            $viewEntireTable .= "<option class='recordShow_option_$limit' value='{$limit}'>$limit</option>";
                        }
                    $viewEntireTable .= '</select>';

                    $viewEntireTable .= ' records
                </div>
                <div class="searchRecord">
                    <input type="search" name="" class="datatable_field searchCategoriesOnMC" placeholder="Search Record" id=""><button class="datatable_field searchCategoriesButton"> Search </button>
                </div>
                <button class="showHideColumn datatable_field"> Show/Hide Column </button>
            </div>';
            
            $viewEntireTable .= '<div class="categoriesDataTableOnMC">';
            $viewEntireTable .= (method_exists( $this, 'categoriesTableData' )) ? $this->categoriesTableData(): 'Not find the categories table data...';
            $viewEntireTable .= '</div>';
             
            $viewEntireTable .= '<div class="oes_loader_center"> Loading . . . </div>
                <div class="editCategory_popup_container">
                <button class="close_editCategory"> X </button>
                <div class="manageCategories_form_popup"> Loading . . . </div>
            </div>';
            $viewEntireTable .= '<div class="manageCategories_message message_popup"> Message </div> ';  
        $viewEntireTable .= '<div>';
        echo $viewEntireTable; 
    }
}


$error = "OES Error: ". __LINE__. __FILE__;
if (isset($_REQUEST['page']) && $_REQUEST['page'] == 'add_categories') { 
    echo (isset($categories) && !empty($categories)) ? $categories->formview("Add Category") : $error;
}

$paramDataTable = ''; 
 
if( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'manage_categories' ) {

    $page_no = ( isset( $_REQUEST['current_page'] ) && $_REQUEST['current_page'] > 0 ) ? $_REQUEST['current_page'] : 1;
    if( isset( $_REQUEST['current_page'] ) ) {
        $category_record_showLimit = ( isset( $_REQUEST['category_record_showLimit'] ) && $_REQUEST['category_record_showLimit'] > 0 ) ? intval( $_REQUEST['category_record_showLimit'] ) : 5;
        $category_record_showLimit = ( is_numeric( $category_record_showLimit ) && $category_record_showLimit > 0 ) ? $category_record_showLimit : 5;
        $paramDataTable = (isset($categories) && !empty($categories)) ? $categories->categoriesTableData( $page_no, $category_record_showLimit ) : $error;
    }
    /* echo "Page no :".$page_no;

    echo (isset($categories) && !empty($categories)) ? $categories->managecategories( $paramDataTable ) : $error; */
} 
?>