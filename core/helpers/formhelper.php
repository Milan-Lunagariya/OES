<?php 

global $DatabaseHandler; 


$category_form_attr = array( 
    'id'         => 'categoryform',
    'method'     => 'POST'
);
    
$category_name_attr = array(
    'type'         =>  "text",
    'label'        =>  "Category Name",
    'name'         =>  "categoryname",
    'class'        =>  "form-control validate_field",
    'placeholder'  =>  "Category Name",
    'value'        =>  "",
    'id'           =>  "Test the id"
);
$submit_attr = array(
    'type'         =>  "submit", 
    'class'        =>  "btn btn-info",
    'value'        =>  "Add",
    'id'           =>  "category_form_submit",
);

$parent_category_options = array();
/* array(
    'please_select' => '<option name="please_select" value="" > Select Category </option>',
    '0'             => '<option name="" value="0" > Mobile </option>',
    '5'             => '<option name="" value="5" > iPhone </option>',
    '6'             => '<option name="" value="6" > iPhone 14 </option>', 
); */
    
$query = $DatabaseHandler->select( 'SELECT * FROM `categories` ' );
$parent_category_options['please_select'] = '<option name="parentid" value="0" > Select Category </option>';

foreach($query as $keyValue){

    $id = $keyValue['categoryid'];     
    $name = $keyValue['name'];     
    $parent_category_options[$id] = "<option name='parentid' value='{$id}' > {$name} </option>"; 
}

/* print_r($parent_category_options); */

/* $categories = $DatabaseHandler->oes_test( 'SELECT `parentid` FROM `categories` ' );  */
  
$parent_category_attr = array(
    'type'         =>   "select",
    'label'        =>   "Parent Category",
    'id'           =>   "parentcategory",
    'name'         =>   "parentcategory",
    'class'        =>   "field form-control form-select validate_field",
    'options'      =>   $parent_category_options
);


?>