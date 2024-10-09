<?php 
 
global $products, $formcreator, $databasehandler, $datatable, $oescommonsvg; 
$formhelper = ( class_exists( 'formhelper' ) ) ? new formhelper() : false; 
$formcreator = ( class_exists( 'formcreator' ) ) ? new formcreator() : false;  
$commonhelper = ( class_exists( 'commonhelper' ) ) ? new commonhelper() : false;
$databasehandler  = ( class_exists( 'databasehandler' ) ) ? new databasehandler() : false;
$datatable = ( class_exists( 'datatable' ) ) ? new datatable() : false;
$products = ( class_exists( 'products' ) ) ? new products() : false;

class products
{  
    var $is_editProductForm;
  
    function __construct()
    {
        $this->is_editProductForm = false; 
    }

    public function oes_test(...$var){
        echo 'You are entred the products class of the products.php file.';
    }
     
    public function formview( $title = "Add Product", $field_extraAttr = array() ){
 
        global $formcreator, $formhelper;
        $formid = false;
        $productImage_value =  false;
        $productname_value = false;
        $submitButtton_value = false;
        $new_create_field = array();
        $content = '<h1 class="title"> '.$title.' </h1>';

        $content .= '<div class="category_form_message message_popup"> Message </div>';

        if( method_exists( 'formcreator', 'field_create' ) && is_object( $formcreator ) ) {
 
            /* if( count( $field_extraAttr ) > 0 ) {
                foreach( $field_extraAttr as $attr ){

                    $attr['name'] = isset($attr['name']) ? $attr['name'] : false;
                    $attr['value'] = isset($attr['value']) ? $attr['value'] : false;
                    $attr['action'] = isset($attr['action']) ? $attr['action'] : false; 

                    if( $attr['name'] == 'category_formid' ){
                        $formid = isset( $attr['id'] ) ? $attr['id'] : '';
                        if( $formid == 'edit_categoryform' ){
                            global $products;
                            $products->is_editCategoryForm = true;
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
            } */
             
            $fields[] = $formcreator->field_create( $formhelper->product_image_attr( $productImage_value ) );  
            $fields[] = $formcreator->field_create( $formhelper->product_name_attr( $productname_value ) );   
            $fields[] = $formcreator->field_create( $formhelper->product_description_attr( $productname_value ) );   
            $fields[] = $formcreator->field_create( $formhelper->product_submit_attr( $submitButtton_value ) );  
            
            foreach( $new_create_field as $new_field ){                        
                $fields[] = $new_field; 
            } 
            
            $category_form = $formcreator->form_create( $fields, $formhelper->category_form_attr( $formid ) );    
            $content .= $category_form;  
        }
            
        return $content;
    }

    public function categoriesTableData( $current_page = 1, $category_record_showLimit = 5, $search = ''){
        
        global $databasehandler, $datatable, $categories, $oescommonsvg;
        $categoryid = 0;
        $table_data = array();
        $printTable = '';
        $classes = array();
        
        if( file_exists('../../models/databasehandler.php') ){ require_once '../../models/databasehandler.php'; }
        if( file_exists('../../models/datatable.php') ){ require_once '../../models/datatable.php'; }

        $databasehandler  = ( class_exists( 'databasehandler' ) ) ? new databasehandler() : false;
        $datatable = ( class_exists( 'datatable' ) ) ? new datatable() : false;
        
        $current_page = ( is_numeric( $current_page ) && $current_page > 0 ) ? $current_page : 1;
        $limit = ( $category_record_showLimit != '' ) ? intval( $category_record_showLimit ) : 5;
        $offset = ( $current_page - 1 ) * $limit;  

        if( ! empty( $search ) ){
            $search = trim( $search );
            $search_condition = array( array( 'column' => 'name', 'operator' => 'LIKE', 'value' => "%{$search}%" ) );
            echo "<p >Search: <i>$search</i></p> "; 
            $data = $databasehandler->select( 'categories', '*, COUNT(categoryid) OVER() AS total_record', $search_condition, '', 'categoryid DESC', $limit, $offset); 
        } else { 
            $data = $databasehandler->select( 'categories', '*, COUNT(categoryid) OVER() AS total_record', array(), '', 'categoryid DESC', $limit, $offset); 
        }  
        $total_records = isset( $data[0]['total_record'] ) ? $data[0]['total_record'] : 100; 
        /* $total_records = isset( $total_records[0]['total_record'] ) ? $total_records[0]['total_record'] : 0; */

        $th_data = array ( 'th' => array(
                '<input type="checkbox" class="datatable_checked_all" name="" value="1">' => '50px',
                'Id' => '50px', 
                'Image' => '200px',
                'Category Name' => '200px',
                'Parent Categorty'=> '200px',
                'Created at' => '200px',
                'Updated at' => '200px',
                'Action' => '150px' 
            )
        ); 

        if( is_array( $data ) && count($data) > 0 ){

        
            foreach( $data as $key => $value ){  
                $action = ''; 
                $categoryid = ( isset($value['categoryid']) && !empty($value['categoryid']) ) ? $value['categoryid']: '-'; 

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

                $select_current = "<input type='checkbox' class='datatable_checked_all datatable_checked_td_{$categoryid}_0' name='' id='{$categoryid}'  value=''>";

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
            
            $current_page = $current_page; 
            
            $printTable .= ( isset( $current_page ) && $current_page > 0) ? "<input type='hidden' class='managecategory_currentpage' value='{$current_page}' >" : '';
            if ( isset($datatable) && method_exists( 'datatable', 'dataTableView' ) ){
                $printTable .= $datatable->dataTableView( $th_data, $td_data, $classes, $current_page, $total_records, $limit );
            } else{
                $printTable .= '$datatable is not object, Please try to get object $datatable... '.__FILE__.' > '. __LINE__;
            }   
        } else{
            $printTable .= '<hr>'; 
            $printTable .= '<div align="center" style="color: red; font-size: 1.5em;"> Records not found! </div>'; 
            $printTable .= '<hr>'; 
        }   
        return $printTable;
    }

    public function managecategories(){

        global $datatable, $oescommonsvg;
        $viewEntireTable = ''; 
        $th_data = array();
        $td_data = array(); 
        $total_pages = array();
        $current_page = 1;
        $bulk_option_arr = array( '' => 'Select Option', 'delete' => 'Delete' );


        $datatable = ( class_exists( 'datatable' ) ) ? new datatable() : false;
        $showrecord_limit_array = array( 5, 10, 25, 50, 100 );
        
        $viewEntireTable .= "<div class='datatable' > ";
            $viewEntireTable .= '<div class="dataTableHeader" >';
                $viewEntireTable .= "<div class='oes_container_bulk_option' >
                    Bulk Option 
                    <select class='oes_bulk_option oes_field'>";
                    
                        foreach( $bulk_option_arr as $value => $display ){
                            $viewEntireTable .= "<option class='' value='{$value}'>{$display}</option>";

                        }
                    
                $viewEntireTable .= "
                    </select>
                    <button class='apply_button oes_field'> Apply </button>
                </div>";
                $viewEntireTable .= '<div class="showRecordsSelectPicker">
                            Show ';
                            $viewEntireTable .= '<select class="datatable_field category_record_showLimit" name="" id="" value="5" >';
                                foreach( $showrecord_limit_array as $limit ){
                                    $viewEntireTable .= "<option class='recordShow_option_$limit' value='{$limit}'>$limit</option>";
                                }
                            $viewEntireTable .= '</select>';

                            $search_icon = isset( $oescommonsvg['search_icon'] ) ? $oescommonsvg['search_icon'] : 'Search';
                            $viewEntireTable .= ' records
                        </div>
                        <div class="searchRecord">
                            <input type="search" name="" class="datatable_field searchCategoriesOnMC" placeholder="Search category name" id=""><button class="datatable_field searchCategoriesButton"> '.$search_icon.' </button>
                        </div>
                        <button class="showHideColumn datatable_field"> Show/Hide Column </button>
                    </div>';
                    
                    $viewEntireTable .= '<div class="categoriesDataTableOnMC">';
                    $viewEntireTable .= (method_exists( $this, 'categoriesTableData' )) ? $this->categoriesTableData(): 'Not find the categories table data...';
                    $viewEntireTable .= '</div>';
                    
                    $viewEntireTable .= '<div class="oes_loader_center"> Loading . . . </div>
                    <div class="editCategory_popup_container">
                        <button class="close_editCategory"> X </button>
                    <div class="manageCategories_form_popup"> Loading . . . </div>';
                        
            $viewEntireTable .= ' </div>';
            $viewEntireTable .= '<div class="manageCategories_message message_popup"> Message </div> ';  
        $viewEntireTable .= '<div>';
        echo $viewEntireTable; 
    }
}


$error = "OES Error: ". __LINE__. __FILE__;


?>