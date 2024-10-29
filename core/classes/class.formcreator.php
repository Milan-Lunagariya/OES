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
			
			if( is_array( $fields ) && count( $fields ) > 0 ){
				foreach ($fields as $field) {
					if( isset($field) && ! in_array( trim( $field ), [ '' , false] ) ){
						
						$content .= $field;
					}
				}
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
		switch ( $field_attr['type'] ) {
			
			case 'text':
			case 'hidden':
			case 'password':
			case 'text': 
			case 'submit':
			case 'file': 
			case 'email':
			case 'checkbox';
				/* $field_attr = $commonhelper->stripslashes_deep($field_attr); */
				/* Note : Here, your id key not use because id=name, Id should be ones not multiples. so only use id=name  */
				$multiple = '';
				if(isset($field_attr['type']) && strtolower($field_attr['type']) == 'file'){

					$multiple =  (isset( $field_attr['multiple'] ) && in_array( $field_attr['multiple'], array( 1, '1', true ) ) )? ' multiple ' :  '';

					if( isset($field_attr['value']) && $field_attr['value'] != '' ){
						$image_path = '../media/categories/'.$field_attr['value'];
						echo "<div class='image_parent'>
							<a href='$image_path' target='_blank' ><img src='".$image_path."' alt='Image Not Found' width='100'> </a>
						</div>";
					}
				}
				
				if (isset($field_attr['label']) && $field_attr['type'] != 'checkbox' ) {
					$field_attr['label'] =  trim($field_attr['label']);
					$content .= '<label for="' . $field_attr['name'] . '"> ' . $field_attr['label'] . ' </label> ';
				}

				
				$field_attr['name']  = ( ( isset($field_attr['name']) ) && ( gettype($field_attr['name']) == 'string' ) ) ? trim($field_attr['name']) : ''; 
				 
				$content .= ( $field_attr['type'] == 'submit' ) ? "<button id='".$field_attr['name']."'" : "<input $multiple ";
				foreach ($field_attr as $key => $value) {

					if (in_array($key, array( 'label', 'multiple' ) )) {
						continue;
					}
					 
					$key = (gettype($key) == 'string') ? trim($key) : $key;
					$value = (gettype($value) == 'string') ? trim($value) : $value;
 
					$content .= '  ' . $key . '= "' . $value . '"   ';
				}

				$field_attr['value'] = isset( $field_attr['value'] ) ? $field_attr['value'] : 'Submit';
				$content .= ( $field_attr['type'] == 'submit' ) ? '> '. $field_attr['value'] .' </button>' : '/>'; 

				if (isset($field_attr['label']) && $field_attr['type'] == 'checkbox' ) {
					$field_attr['label'] =  trim($field_attr['label']);
					$content .= '&nbsp; <label style="cursor: pointer;"  for="' . $field_attr['name'] . '"> ' . $field_attr['label'] . ' </label> ';
				}

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

			case 'textarea' :
 
				if (isset($field_attr['label'])) {
					$field_attr['label'] =  trim($field_attr['label']);
					$content .= '<label for="' . $field_attr['name'] . '"> ' . $field_attr['label'] . ' </label> ';
				}
				
				$content .= '<textarea';
				foreach ($field_attr as $key => $value) {

					if ( in_array( $key, array( 'type', 'label', 'value' ) ) ) {
						continue;
					}

					$key = (gettype($key) == 'string') ? trim($key) : $key;
					$value = (gettype($value) == 'string') ? trim($value) : $value;
					$content .=  '  ' . $key . '= "' . $value . '"';
				}
				$content .= '>';


				$content .= ( isset( $field_attr['value'] ) ) ? $field_attr['value'] : '';
				$content .= '</textarea>'; 
				break;

			case 'number' :
 
				if (isset($field_attr['label'])) {
					$field_attr['label'] =  trim($field_attr['label']);
					$content .= '<label for="' . $field_attr['name'] . '"> ' . $field_attr['label'] . ' </label> ';
				}
				
				$content .= '<input';
				foreach ($field_attr as $key => $value) {

					if ( in_array( $key, array( 'label' ) ) ) {
						continue;
					}

					$key = (gettype($key) == 'string') ? trim($key) : $key;
					$value = (gettype($value) == 'string') ? trim($value) : $value;
					$content .=  '  ' . $key . '= "' . $value . '"';
				}
				$content .= '>';
 
				break;
			case 'button' :
 
				if (isset($field_attr['label'])) {
					$field_attr['label'] =  trim($field_attr['label']);
					$content .= '<label for="' . $field_attr['name'] . '"> ' . $field_attr['label'] . ' </label> ';
				}
				
				$content .= '<button type="button"';
				foreach ($field_attr as $key => $value) {

					if ( in_array( $key, array( 'label', 'value' ) ) ) {
						continue;
					}

					$key = (gettype($key) == 'string') ? trim($key) : $key;
					$value = (gettype($value) == 'string') ? trim($value) : $value;
					$content .=  '  ' . $key . '= "' . $value . '"';
				}
				$content .= '>'. $value;
				$content .= '</button>';
 
				break;

			default:
				$content .= 'Field is missing';
				break;
		}
		$content .= '<div class="formerror"></div>';
		$content .= '</div>';
		return $content;
	}
 
}

$formcreator =  new formcreator();
