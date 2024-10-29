<?php

global $DatabaseHandler, $formhelper;

class formhelper
{
    var $category_name_attr; 
    
    public function __construct()
    { 
    }

    public function category_form_attr( $getid = '', $class = '' ) {

        $id = ( $getid == '' ) ? 'add_categoryform' : $getid; 
        
        $default = array(
            'id'         => $id,
            'class'      => $class,
            'method'     => 'POST',
            'enctype'    => 'multipart/form-data',
        );

        return $default;
    } 
    public function product_form_attr( $getid = '', $class = '' ) {

        $id = ( $getid == '' ) ? 'add_productform' : $getid; 
        
        $default = array(
            'id'         => $id,
            'class'      => $class,
            'method'     => 'POST',
            'enctype'    => 'multipart/form-data',
        );

        return $default;
    } 

    public function product_name_attr( $value = '' ) {
         
        $default =  array(
            'type'         =>  "text",
            'label'        =>  "Product Name",
            'name'         =>  "productname",
            'id'         =>  "productname",
            'class'        =>  "form-control validate_field",
            'placeholder'  =>  "Enter Product Name",
            'title'        =>  "Enter Product Name",
            'value'        =>  $value,
            
        );
        return $default;
    }

    public function category_name_attr( $value = '' ) {
         
        $default =  array(
            'type'         =>  "text",
            'label'        =>  "Category Name",
            'name'         =>  "categoryname",
            'id'         =>  "categoryname",
            'class'        =>  "form-control validate_field",
            'placeholder'  =>  "Category Name",
            'title'        =>  "Enter Category Name",
            'value'        =>  $value,
            
        );
        return $default;
    }
    public function category_submit_attr( $value ){

        $value = ( $value == '' ) ? 'Add' : $value;
        $default =  array(
            'type'         =>  "submit",
            'class'        =>  "btn btn-info",
            'value'        =>  $value,
            'name'           =>  "category_form_submit",
            'id'           =>  "category_form_submit",
        );
        return $default;
    } 
  
    public function product_submit_attr( $value ){

        $value = ( $value == '' ) ? 'Add' : $value;
        $default =  array(
            'type'         =>  "submit",
            'class'        =>  "product_submit_button btn btn-info",
            'value'        =>  $value,
            'name'           =>  "product_form_submit",
            'id'           =>  "product_form_submit",
        );
        return $default;
    } 

    public function category_image_attr( $value = '' ){
 
        $default =  array(
            'type'         =>  "file",
            'label'        =>  "Category image",
            'name'         =>  "categoryimage",
            'id'         =>  "categoryimage",
            'class'        =>  "validate_field", 
            'accept'       =>  "image/*", // Add setting Dynamic if enable then add  
            'title'        =>  "Select Category Image",
            'value'        =>  $value,
        );

        return $default;
    }

    public function product_image_attr( $value = '' ){
 
        $default =  array(
            'type'         =>  "file",
            'label'        =>  "Product image",
            'name'         =>  "productimages[]",
            'class'        =>  "validate_field", 
            'id'        =>  "productimages", 
            'accept'       =>  "image/*", // Add setting Dynamic if enable then add  
            'title'        =>  "Select Product Image",
            'multiple'     =>  true
        );

        return $default;
    }

    public function parent_category_attr( $value )
    {
        global $DatabaseHandler;
        $query = $DatabaseHandler->select('categories', '*');
        $parent_category_options = array();

        $selected = ( $value === 0 ) ? ' selected="selected" ' : '';
        $parent_category_options['please_select'] = '<option name=""  value="" > Select Parent category </option>';
        $parent_category_options[0] = '<option name="parentid"  '.$selected.' value="0" > New Parent category </option>';


        foreach ($query as $keyValue) {
             
            $id = isset( $keyValue['categoryid'] ) ? $keyValue['categoryid'] : '';
            $parentid = isset( $keyValue['parentid'] ) ? $keyValue['parentid'] : '';
            $name = isset( $keyValue['name'] ) ? $keyValue['name'] : '';
            
            $selected = ''; 
            if( strtolower((string)$value) == strtolower((string)$id) ){
                $selected = ' selected="selected" ';
            }

            $parent_category_options[$id] = "<option name='parentid' $selected  value='{$id}' > {$name} </option>";
        }

        $parent_category_attr = array(
            'type'         =>   "select",
            'label'         =>   "Parent Category",
            'id'           =>   "parentcategory",
            'title'        =>  "Select Parent Category",
            'class'           =>   "field form-control form-select parentcategory validate_field",
            'name'         =>   "parentcategory",
            'options'   =>   $parent_category_options, // I want use function then show error is :
            'value'        =>  $value,
        );

        return $parent_category_attr;
    }

    function product_description_attr( $value = '' ){
        $description_attr = array(
            'type' => 'textarea',
            'name' => 'productdescription',
            'id' => 'productdescription',
            'label' => 'Prodcut Description',
            'title' => 'Write Product Description',
            'class' => 'form-control',
            'value' => $value, 
        );
        return $description_attr;
    }
   
    function product_price_attr( $value = '' ){
        $product_price_attr = array(
            'type' => 'number',
            'name' => 'productprice',
            'id' => 'productprice',
            'label' => 'Prodcut Price',
            'title' => 'Enter product Price',
            'class' => 'form-control validate_field',
            'min' => '0',
            'placeholder' => 'Enter product price',
            'max' => '99999',
            'value' => $value, 
        );
        return $product_price_attr;
    }

    function product_stock_attr( $value = '' ){
        $product_stock_attr = array(
            'type' => 'number',
            'name' => 'productstock',
            'id' => 'productstock',
            'label' => 'Prodcut Stock',
            'title' => 'Enter product stock',
            'class' => 'form-control validate_field',
            'placeholder' => 'Enter product stock',
            'min' => '0',
            'max' => '99999',
            'value' => $value, 
        );
        return $product_stock_attr;
    }

    function product_categorty_attr(){
 
        $product_categorty_attr = array(
            'type' => 'button',
            'name' => 'categoryid',
            'id' => 'categoryid',
            'label' => 'Product parent category',
            'title' => 'Please click and checked categories for product',
            'class' => 'form-control popup_showCategory',
            'value' => 'Select category for product'
        );
        return $product_categorty_attr;
    }

    function adminLoginForm_nameField_attr(){
        $name = array( 
            'label' => 'Name',
            'type' => 'text',
            'name' => 'LoginForm_name',
            'class' => 'form-control adminloginform_name_field loginpage_field validate_field',
            'id' => 'LoginForm_name',
        );
        return $name;
    }
    function adminLoginForm_passwordField_attr(){
        $name = array( 
            'label' => 'Password',
            'type' => 'password',
            'name' => 'LoginForm_password',
            'class' => 'form-control adminloginform_password_field loginpage_field validate_field',
            'id' => 'LoginForm_password',
        );
        return $name;
    }
    function adminLoginForm_submitField_attr(){
        $name = array( 
            'type' => 'submit',
            'value' => 'Login',
            'name' => 'LoginForm_sbmit',
            'class' => 'form-control adminloginform_submit_field login_button loginpage_field validate_field',
            'id' => 'LoginForm_submit',
        );
        return $name;
    }
    function adminLoginForm_rememberMeField_attr(){
        $name = array( 
            'type' => 'checkbox',
            'label' => 'Remember Me',
            'value' => '1',
            'name' => 'login_rememberMe',
            'class' => 'form-control rememberme_field',
            'id' => 'login_rememberMe',
        );
        return $name;
    }
}

$formhelper = new formhelper()
?>