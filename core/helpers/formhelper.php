<?php

global $DatabaseHandler, $formhelper;

class formhelper
{
    var $category_name_attr;
    
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

    public function category_name_attr( $value = '' ) {
         
        $default =  array(
            'type'         =>  "text",
            'label'        =>  "Category Name",
            'name'         =>  "categoryname",
            'class'        =>  "form-control validate_field",
            'placeholder'  =>  "Category Name",
            'title'        =>  "Enter Category Name",
            'value'        =>  $value,
            
        );
        return $default;
    }
    public function submit_attr( $value ){

        $value = ( $value == '' ) ? 'Add' : $value;
        $default =  array(
            'type'         =>  "submit",
            'class'        =>  "btn btn-info",
            'value'        =>  $value,
            'name'           =>  "category_form_submit",
        );
        return $default;
    } 

    public function category_image_attr( $value = '',  ){

        $default =  array(
            'type'         =>  "file",
            'label'        =>  "Category image",
            'name'         =>  "categoryimage",
            'class'        =>  "validate_field", 
            'accept'       =>  "image/*", // Add setting Dynamic if enable then add  
            'title'        =>  "Select Category Image",
            'value'        =>  $value,
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
}

$formhelper = new formhelper()
?>