<?php

global $DatabaseHandler, $formhelper;

class formhelper
{ 
    public $category_form_attr = array(
        'id'         => 'categoryform',
        'method'     => 'POST'
    );

    public $category_name_attr = array(
        'type'         =>  "text",
        'label'        =>  "Category Name",
        'name'         =>  "categoryname",
        'class'        =>  "form-control validate_field",
        'placeholder'  =>  "Category Name",
        'value'        =>  "",
        'id'           =>  "Test the id"
    );
    public $submit_attr = array(
        'type'         =>  "submit",
        'class'        =>  "btn btn-info",
        'value'        =>  "Add",
        'id'           =>  "category_form_submit",
    );

    public $category_image = array(
        'type'         =>  "file",
        'label'        =>  "Category image",
        'name'         =>  "categoryimage",
        'class'        =>  "form-control validate_field",
        'value'        =>  "",
        'id'           =>  "Test the id"
    );

    public function parent_category_attr()
    { 
        global $DatabaseHandler;
        $query = $DatabaseHandler->select('categories', '*');
        $parent_category_options = array();

        $parent_category_options['please_select'] = '<option name="" value="" > Select Parent category </option>';
        $parent_category_options[0] = '<option name="parentid" value="0" > Parent Category </option>';

        foreach ($query as $keyValue) {

            $id = $keyValue['categoryid'];
            $name = $keyValue['name'];
            $parent_category_options[$id] = "<option name='parentid' value='{$id}' > {$name} </option>";
        }
 
        $parent_category_attr = array(
            'type'         =>   "select",
            'label'         =>   "Parent Category",
            'id'           =>   "parentcategory",
            'class'           =>   "field form-control form-select parentcategory validate_field",
            'name'         =>   "parentcategory",
            'options'   =>   $parent_category_options // I want use function then show error is :
        );

        return $parent_category_attr;    
    }
}

$formhelper = new formhelper()
?>