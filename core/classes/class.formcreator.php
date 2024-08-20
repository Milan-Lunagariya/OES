<?php

global $formcreator;
class formcreator
{
	function __construct()
	{
	}

	function oes_test(...$var){
		echo " You are entered the class.formcreater.php in oes_test() ";
	}

	function form_create($fields = array(), $forms_attr = array())
	{

		if (count($fields) == 0)
			return;

		$content = '';
		/* $action  = (!empty($action)) ? preg_replace( '/[\'"]/', '', $action ) :  '';
		$is_file = ( $is_file == true || $is_file == '1' || $is_file == 1 ) ? 'enctype="multipart/form-data"' : ''; */


		$submit_button = (!empty($submit_button)) ? stripslashes($submit_button) : 'Submit';

		$content .= '<div class="form_container">';
		$content .= '<form ';
		foreach ($forms_attr as $attr => $value) {
			$content .=  ' ' . $attr . '="' . $value . '"  ';
		}
		$content .= ' >';
		foreach ($fields as $field) {
			$content .= $field;
		}
		$content .= '</form>';
		$content .= '</div>';

		return $content;
	}

	function field_create($field_attr = array())
	{
		global $commonhelper;
		$content = '';

		if ( is_array($field_attr) && count($field_attr) < 1 || ! is_array($field_attr) && $field_attr == '' )
			return;


		$content .= '<div class="field_container">';
		switch ($field_attr['type']) {
 
			case 'text': 
			case 'submit':
			case 'file':
			case 'email':
				/* $field_attr = $commonhelper->stripslashes_deep($field_attr); */
				/* Note : Here, your id key not use because id=name, Id should be ones not multiples. so only use id=name  */

				if (isset($field_attr['label'])) {
					$field_attr['label'] =  trim($field_attr['label']);
					$content .= '<label for="' . $field_attr['name'] . '"> ' . $field_attr['label'] . ' </label> ';
				}
				
				$field_attr['name']  = ( ( isset($field_attr['name']) ) && ( gettype($field_attr['name']) == 'string' ) ) ? trim($field_attr['name']) : ''; 
				 
				$content .= ( $field_attr['type'] == 'submit' ) ? "<button id='".$field_attr['name']."'" : "<input id='".$field_attr['name']."'";
				foreach ($field_attr as $key => $value) {

					if (in_array($key, ['id', 'label'])) {
						continue;
					}
					 
					$key = (gettype($key) == 'string') ? trim($key) : $key;
					$value = (gettype($value) == 'string') ? trim($value) : $value;
 
					$content .= '  ' . $key . '= "' . $value . '"   ';
				}

				$content .= ( $field_attr['type'] == 'submit' ) ? '> '. $field_attr['value'] .' </button>' : '/>';
				/* $content .= '/>'; */
				break;

			case 'select':

				if (isset($field_attr['label'])) {
					$field_attr['label'] =  trim($field_attr['label']);
					$content .= '<label for="' . $field_attr['name'] . '"> ' . $field_attr['label'] . ' </label> ';
				}

				$content .= '<select';

				foreach ($field_attr as $key => $value) {

					if (in_array($key, ['type', 'options', 'label'])) {
						continue;
					}

					$key = (gettype($key) == 'string') ? trim($key) : $key;
					$value = (gettype($value) == 'string') ? trim($value) : $value;
					$content .=  '  ' . $key . '= "' . $value . '"';
				}
				$content .= '>';

				$field_attr['options'] = isset($field_attr['options']) ? $field_attr['options'] : array();

				foreach ($field_attr['options'] as  $key => $value) {
					$content .= $value;
				}

				$content .= '</select>';
				break;
			default:
				$content .= 'Field is missing';
				break;
		}
		$content .= '<div class="formerror"></div>';
		$content .= '</div>';
		return $content;
	}

	function selectpicker()
	{
	}
}

$formcreator =  new formcreator();
