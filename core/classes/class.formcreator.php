<?php 
	
	global $formcreator;
class formcreator
{
	function __construct()
	{
		
	}

	function form_create( $fields = array(), $forms_attr = array() ){
		
		if( count($fields) == 0 )
			return;
		
		$content = '';
		/* $action  = (!empty($action)) ? preg_replace( '/[\'"]/', '', $action ) :  '';
		$is_file = ( $is_file == true || $is_file == '1' || $is_file == 1 ) ? 'enctype="multipart/form-data"' : ''; */

		
		$submit_button = ( !empty($submit_button) ) ? stripslashes( $submit_button ) : 'Submit';

		$content .= '<div class="form_container">';
			$content .= '<form ';
				foreach( $forms_attr as $attr => $value ){
					$content .=  ' '.$attr.'="'.$value.'"  ';
				}
				$content .= ' >';         
				foreach( $fields as $field ){
					$content .= $field;
				} 
			$content .= '</form>';
		$content .= '</div>';

		return $content;
	}

	function field_create( $field_attr = array() ){
		$content = '';
		
		if( count($field_attr) == 0 )
			return;

		
		$content .= '<div class="field_container">'; 
		switch( $field_attr['type'] ){            
			
			case 'text':
			case 'submit':
			case 'email':
				$field_attr = stripslashes_deep( $field_attr ); 
				$field_attr['name']  = isset( $field_attr['name'] ) ? trim( $field_attr['name'] ) : '';
				
				if(isset($field_attr['label'])){
					$field_attr['label'] =  trim( $field_attr['label'] );
					$content .= '<label for="'.$field_attr['name'].'"> '.$field_attr['label'].' </label> ';
				}
				
				$content .= '<input id="'.$field_attr['name'].'" ';
				foreach( $field_attr as $key => $value ){
					
					if( in_array($key, ['id', 'options', 'label']) ){
						continue;
					}

					$key = (gettype($key) == 'string') ? trim($key) : $key;
					$value = (gettype($value) == 'string') ? trim($value) : $value;
					
					if( $key == 'name' ){
						$content .= ' id="'.$field_attr['name'].'  '.$value.'  "';
					}
					$content .= '  '.$key.'= "'.$value.'"   ';                
				}
				
				$content .= '/>';                 
				break;

			case 'select':

				if(isset($field_attr['label'])) {
					$field_attr['label'] =  trim( $field_attr['label'] );
					$content .= '<label for="'.$field_attr['name'].'"> '.$field_attr['label'].' </label> ';
				}

				$content .= '<select';

					foreach( $field_attr as $key => $value ){

						if( in_array($key, ['type', 'options', 'label']) ){
							continue;
						}

						$key = (gettype($key) == 'string') ? trim($key) : $key;
						$value = (gettype($value) == 'string') ? trim($value) : $value;
						$content .=  '  '.$key.'= "'.$value.'"';     
					}
					$content .= '>';
					
				$field_attr['options'] = isset($field_attr['options']) ? $field_attr['options'] : array();

				foreach( $field_attr['options'] as  $key => $value ){                    
					$content .= $value;                   
				}
					
				$content .= '</select>';
				break;

			default:
				$content .= $field_attr['type'].    ' is not founded';
				break;                
		}
		$content .= '<div class="formerror"></div>'; 
		$content .= '</div>'; 
		return $content;
	}
	
	function selectpicker(){
		
	}
}

$formcreator =  new formcreator();


