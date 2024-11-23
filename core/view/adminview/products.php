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
    
    function displayCategories($editProductCategories = array(), $parentId = 0, &$visited = array(), &$content = '') {
        global $databasehandler;
        
        $where = ($parentId === 0) ? array() : array( array( 'column' => 'parentid', 'value' => $parentId, 'operator' => '=', 'type' => PDO::PARAM_INT ) );
        $categories = $databasehandler->select('categories', '*', $where);
    
        if ( is_array( $categories ) && count( $categories ) > 0 ) {
            $content .= '<ul class="productCategory_ul">';
            foreach ($categories as $category) {

                $category['categoryid'] = isset( $category['categoryid'] ) ? $category['categoryid'] : 0;
                $category['name'] = isset( $category['name'] ) ? $category['name'] : 0;
                 
                if (!in_array($category['categoryid'], $visited)) {                     
                    $visited[] = $category['categoryid'];
                    $content .= '<li>';
                    $checked_category = '';
                    if( is_array( $editProductCategories ) && in_array( $category['categoryid'], $editProductCategories ) ){
                        $checked_category = 'checked="checked"';
                    }
                    $content .= '<input name="productCategoryids[]" type="checkbox" '.$checked_category.' class="categoryProduct_checkbox" id="productCategories_checkbox_'.$category['categoryid'].'" value="'.$category['categoryid'].'" >'; 
                    $content .= '<label for="productCategories_checkbox_'.$category['categoryid'].'">' . $category['name'] . '</label>';
                    
                    $this->displayCategories($editProductCategories, $category['categoryid'], $visited, $content);
                    $content .= '</li>';
                }
            }
            $content .= '</ul>';
        }
        return $content;
    }
    
    public function formview( $title = "Add Product", $field_extraAttr = array() ){
 
        global $formcreator, $formhelper, $databasehandler;
        $formid = false;
        $productdescription = '';
        $productstock = '';
        $productprice = '';
        $productImage_value =  false;
        $productname_value = false;
        $submitButtton_value = 'Add Product';
        $new_create_field = array();
        $content = '<h1 class="title"> '.$title.' </h1>';
        $editProductCategories = array();

        $content .= '<div class="product_form_message message_popup"> Message </div>'; 
         
        if( method_exists( 'formcreator', 'field_create' ) && is_object( $formcreator ) ) {
 
            if( count( $field_extraAttr ) > 0 ) {
                foreach( $field_extraAttr as $attr ){

                    $attr['name'] = isset($attr['name']) ? $attr['name'] : false;
                    $attr['value'] = isset($attr['value']) ? $attr['value'] : false;
                    $attr['action'] = isset($attr['action']) ? $attr['action'] : false; 

                    if( $attr['name'] == 'product_formid' ){
                        $formid = isset( $attr['id'] ) ? $attr['id'] : '';
                        if( $formid == 'edit_productform' ){
                            global $products;
                            $products->is_editProductForm = true; 
                            $submitButtton_value = 'Edit Product';
                        }
                    } 
                    
                    if ( $attr['name'] == 'productname' ){
                        $productname_value = $attr['value'];
                    } else if ( $attr['name'] == 'productdescription' ){
                        $productdescription = $attr['value'];
                    } else if ( $attr['name'] == 'productstock' ){
                        $productstock = $attr['value'];
                    } else if ( $attr['name'] == 'productprice' ){
                        $productprice = $attr['value'];
                    } else if ( $attr['name'] == 'productcategoryids' ){
                        $editProductCategories = $attr['value'];
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
             
            $fields[] = $formcreator->field_create( $formhelper->product_image_attr( $productImage_value ) );
            $fields[] = $formcreator->field_create( $formhelper->product_name_attr( $productname_value ) );
            $fields[] = $formcreator->field_create( $formhelper->product_description_attr( $productdescription ) );
            $fields[] = $formcreator->field_create( $formhelper->product_price_attr( $productprice ) );
            $fields[] = $formcreator->field_create( $formhelper->product_stock_attr( $productstock ) );
            $fields[] = $formcreator->field_create( $formhelper->product_categorty_attr(  ) );
            
            $categories_popup = '<div class="oes_loader_center"> Loading . . . </div>
                    <div class="general_popup_container productCategory_popup">
                        <div class="oes_closeButton_container">
                            <button type="button" class="oes_closeButton" > Close </button>
                            <div class="productCategories_container">'.$this->displayCategories( $editProductCategories ).'</div>
                        </div>
                    </div>';
            $fields[] = $categories_popup;  
            $fields[] = $formcreator->field_create( $formhelper->product_submit_attr( $submitButtton_value ) );  
            
            foreach( $new_create_field as $new_field ){                        
                $fields[] = $new_field; 
            }
            
            $product_form = $formcreator->form_create( $fields, $formhelper->product_form_attr( $formid ) );
            $content .= $product_form;  
        }
        return $content;
    }

    public function productTableData( $current_page = 1, $product_record_showLimit = 5, $search = ''){
        
        global $databasehandler, $datatable, $categories, $oescommonsvg;
        $productid = 0;
        $table_data = array();
        $printTable = '';
        $classes = array();
        
        if( file_exists('../../models/databasehandler.php') ){ require_once '../../models/databasehandler.php'; }
        if( file_exists('../../models/datatable.php') ){ require_once '../../models/datatable.php'; }

        $databasehandler  = ( class_exists( 'databasehandler' ) ) ? new databasehandler() : false;
        $datatable = ( class_exists( 'datatable' ) ) ? new datatable() : false;
        
        $current_page = ( is_numeric( $current_page ) && $current_page > 0 ) ? $current_page : 1;
        $limit = ( $product_record_showLimit != '' ) ? intval( $product_record_showLimit ) : 5;
        $offset = ( $current_page - 1 ) * $limit;  

        $search_condition = array();
        if( ! empty( $search ) ){
            $search = trim( $search );
            $search_condition = array( array( 'column' => 'name', 'operator' => 'LIKE', 'value' => "%{$search}%" ) );
            echo "<p >Search: <i>$search</i></p> "; 
            /* $data = $databasehandler->select( 'products', '*, COUNT(productid) OVER() AS total_record', $search_condition, '', 'productid DESC', $limit, $offset);  */
        } 
        $data = $databasehandler->select( 'products', '*, COUNT(productid) OVER() AS total_record', $search_condition, '', 'productid DESC', $limit, $offset); 
        $total_records = isset( $data[0]['total_record'] ) ? $data[0]['total_record'] : 100; 
        /* $total_records = isset( $total_records[0]['total_record'] ) ? $total_records[0]['total_record'] : 0; */

        $th_data = array ( 'th' => array(
                '<input type="checkbox" class="datatable_checked_all" name="" value="1">' => '50px',
                'Id' => '50px',  
                'Name'=> '200px', 
                'Description' => '300px',
                'Price'=> '100px',
                'Stock'=> '100px',
                'Created at' => '150px',
                'Updated at' => '150px',
                'Actions' => '150px' 
            )
        ); 

        if( is_array( $data ) && count($data) > 0 ){

        
            foreach( $data as $key => $value ){  
                $action = ''; 
                $productid = ( isset($value['productid']) && !empty($value['productid']) ) ? $value['productid']: '-'; 

                /* $productimages = ( isset( $value['images'] ) && $value['images'] != '' ) ? json_decode( $value['images'], true ) : array();
                $productimages = ( is_array($productimages) && count($productimages) > 0 ) ? ( $productimages[0] ) : '';
                $image_path = ( $productimages != '' ) ? "../media/categories/".$productimages :  ''; */

                $select_current = "<input type='checkbox' class='datatable_checked_all datatable_checked_td_{$productid}_0' name='' id='{$productid}'  value=''>";

                // $images = ( ! empty($image_path) ) ? "<div class='image_parent'><a href='$image_path' target='_blank' ><img src='$image_path' alt='Not Found' width='100'></a></div>": '-'; 
                // $images = ( isset($value['images']) && !empty($value['images']) ) ? $value['images']: '-';  
                $name = ( isset($value['name']) && !empty($value['name']) ) ? $value['name']: '-';  
                $description = ( isset($value['description']) && !empty($value['description']) ) ? $value['description']: '-'; 
                $description_readmore_link = ( strlen( $description ) > 120 ) ? "<div class='manageProducts_descriptionReadmoreLink'> Read more... </div>" : '';
                $description = "<div class='manageProducts_descriptionCotainer'>{$description} </div> {$description_readmore_link}";
                $price = ( isset($value['price']) && !empty($value['price']) ) ? $value['price']: '-';  
                $stock = ( isset($value['stock']) && !empty($value['stock']) ) ? $value['stock']: '-';  
                $createdat  = ( isset($value['createdat']) && !empty($value['createdat']) ) ? $value['createdat']: '-'; 
                $updatedat = ( isset($value['updatedat']) && !empty($value['updatedat']) ) ? $value['updatedat']: '-';

                $edit_icon = isset( $oescommonsvg['edit_icon'] ) ? $oescommonsvg['edit_icon'] : 'Edit';
                $delete_icon = isset( $oescommonsvg['delete_icon'] ) ? $oescommonsvg['delete_icon'] : 'delete';
                $action .= "<button id='$productid' class='edit data_modify_button edit_product_$productid'>$edit_icon</button>";
                $action .= "<button id='$productid' class='remove data_modify_button remove_product_$productid' >$delete_icon</button>";
                
                $table_data[] = array( $select_current, $productid, $name, $description, $price, $stock, $createdat, $updatedat, $action );
            } 
            $td_data = $table_data; 
            
            $refreshTable_param = array(
                'datatable_page_name' => 'manageProducts',
                'datatable_current_page' => $current_page,
                'datatable_limit' => $limit,
                'datatable_search' => $search,
                'datatable_action' => 'refreshProductsTable'
            );

            $printTable .= ( isset( $current_page ) && $current_page > 0) ? "<input type='hidden' class='manageproduct_currentpage' value='{$current_page}' >" : '';
            if ( isset($datatable) && method_exists( 'datatable', 'dataTableView' ) ){

                $printTable .= $datatable->dataTableView( $th_data, $td_data, $classes, $current_page, $total_records, $limit, $refreshTable_param );
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

    public function manageproducts( $title = '' ){

        global $datatable, $oescommonsvg;
        $viewEntireTable = ''; 
        $th_data = array();
        $td_data = array(); 
        $total_pages = array();
        $current_page = 1;
        $bulk_option_arr = array( '' => 'Select Option', 'delete' => 'Delete' );


        $datatable = ( class_exists( 'datatable' ) ) ? new datatable() : false;
        $showrecord_limit_array = array( 5, 10, 25, 50, 100 );
        
        $viewEntireTable = "<div class=''> <h2 class='title' align='center'> {$title} </h2> </div>";
        $viewEntireTable .= "<div class='datatable' > ";
            $viewEntireTable .= '<div class="dataTableHeader" >';
                $viewEntireTable .= "<div class='oes_container_bulk_option' >
                    Bulk Option 
                    <select class='oes_bulk_option oes_field'>";
                    
                        foreach( $bulk_option_arr as $value => $display ){
                            $viewEntireTable .= "<option class='' value='{$value}'>{$display}</option>";

                        }
                    
                $viewEntireTable .= "<input type='hidden'  name='datatable_page' value='manageProduct' >";
                $viewEntireTable .= "
                    </select>
                    <button class='apply_button oes_field'> Apply </button>
                </div>";
                $viewEntireTable .= '<div class="showRecordsSelectPicker">
                            Show ';
                            $viewEntireTable .= '<select class="datatable_field product_record_showLimit" name="product_record_showLimit" id="" value="5" >';
                                foreach( $showrecord_limit_array as $limit ){
                                    $viewEntireTable .= "<option class='recordShow_option_$limit' value='{$limit}'>$limit</option>";
                                }
                            $viewEntireTable .= '</select>';

                            $search_icon = isset( $oescommonsvg['search_icon'] ) ? $oescommonsvg['search_icon'] : 'Search';
                            $viewEntireTable .= ' records
                        </div>
                        <div class="searchRecord">
                            <input type="search" name="" class="datatable_field searchProductOnMC" placeholder="Search product name" id=""><button class="datatable_field searchProductButton"> '.$search_icon.' </button>
                        </div>
                        <button class="showHideColumn datatable_field"> Show/Hide Column </button>
                    </div>';
                    
                    $viewEntireTable .= '<div class="productDataTableOnMC datatable_change_table">';
                    $viewEntireTable .= (method_exists( $this, 'productTableData' )) ? $this->productTableData(): 'Not find the product table data...';
                    $viewEntireTable .= '</div>';
                    
                    $viewEntireTable .= '<div class="oes_loader_center"> Loading . . . </div>
                    <div class="editProduct_popup_container">
                        <div class="close_editProduct_container">
                            <button class="close_editProduct" > X </button>
                        </div>
                        <div class="manageProduct_form_popup"> Loading . . . </div>';
                        
            $viewEntireTable .= ' </div>';
            $viewEntireTable .= '<div class="manageproduct_message message_popup"> Message </div> ';  
        $viewEntireTable .= '<div>';
        echo $viewEntireTable; 
    }
}


$error = "OES Error: ". __LINE__. __FILE__;


?>