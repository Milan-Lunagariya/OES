console.log( 'Hello, admin_forms.js enterd' );
$(document).ready(function(){      
     
});  

$( document ).on('blur', '.validate_field', function(){ 
    var form_id = $( this ).closest( 'form' ).attr( 'id' );
    var type = $( this ).attr('type');

    console.log( "type = " + type + ', form_id: ' + form_id );
    var exclude_image_validateForm = [ 'edit_categoryform', 'edit_productform' ];
    if( type == 'file' && exclude_image_validateForm.includes( form_id ) ){
        console.log( 'In this edit form image field can not required.' );
    } else {
        field_validation(this); 
    }
});

$( document ).on( 'submit', '#add_productform, #edit_productform', function(){
     
    
    var product_field_val = {}; 
    var formid_attr = $( this ).attr( 'id' );
    var is_editproduct = ( formid_attr == 'edit_productform' ) ? true : false;
    var action = ( is_editproduct ) ? 'edit_product' : 'add_product';
    var for_edit_productid = 0;
    var oldProductImages = $( '[name="oldProductImages"]' ).val();
    var is_valid_form = {
        isValid: false,
        error: 'Declare'
    };

    product_field_val['productname'] = $( '[name="productname"]' ).val();
    if( ! is_editproduct ){
        product_field_val['productimages[]'] = $( '#productimages' ).val();
    }
    is_valid_form = submissionValidateFields( product_field_val );
    console.log( is_valid_form );
    saparateValidate_price = field_validation( '#productprice', /^(0|[1-9]\d*)$/, 'Please enter price only numeric' );
    saparateValidate_stock = field_validation( '#productstock', /^(0|[1-9]\d*)$/, 'Please enter stock only numeric' );
    console.log( is_valid_form.errors );
    saparateValidate_price

    if ( is_valid_form.isValid === false || saparateValidate_stock === false || saparateValidate_price === false ) {
        console.log('Form validation failed: ', is_valid_form.errors);
        return false;
    }
    
    const url = '../core/models/modelproducts.php';
    const callback = function(data){
        console.log( 'Inside the success' );
        console.log( 'Product forms submission data:  ' + data );  
        data = JSON.parse( data );
        var created_message, success_message = '', success_selector = ''; 
        var button_value = ( is_editproduct ) ? 'Edit' : 'Add';
        oes_loader( '#product_form_submit', false, button_value );  

        success_selector = (is_editproduct) ? ".manageproduct_message" : ".product_form_message";            
        if( ( data.success != undefined && data.success == 1 || data.success == true || data.success == '1' ) ){  
            $(success_selector).removeClass('message_error'); 
            success_message = (is_editproduct) ? "Success! The product has been updated." : "Success! The product has been added.";
            created_message = $( success_selector ).fadeIn().html( success_message );
            show_message_popup(created_message, 3000);

            if( is_editproduct ){
                try{
                    readymate_product_refresh();
                } catch( e ){
                    console.log( 'Error: ' + e ); 
                }
            }
 
            $( '.editProduct_popup_container' ).fadeOut();
            $( '#' + formid_attr )[0].reset(); 
        }else{ 
            $( success_selector ).addClass('message_error'); 
            var error = ( data.error != undefined ) ? data.error : 'Somthing Went Wrong! Try again.';
            console.log( error )
            console.log( $( success_selector ) )
            var message =  $( success_selector ).css('transform','scale(1)').fadeOut().fadeIn().html(error);
            show_message_popup( message, 3000, false );
        }
    }; 
     
    /* return false; */
    var extra_data = { 
        'action': action,
    }; 
    oes_loader( '#product_form_submit', true );
    ajax_form_submitor( url, callback, this, extra_data );  

    return false;
} )
