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

$( document ).on('submit', '#add_categoryform, #edit_categoryform', function(){  
    var is_categoryname, is_parentcategory, is_categoryimage;
    var form_id = $( this ).attr( 'id' );
    var is_editCategory = ( form_id == 'edit_categoryform' ) ? true : false;
    var action = ( is_editCategory ) ? 'edit_category' : 'add_categoryform';
    var button_value = ( is_editCategory ) ? 'Edit' : 'Add';
    var is_pageRefresh = false;
    var current_page = $( '.managecategory_currentpage' ).val();
    current_page = ( current_page != '' || current_page != undefined || current_page != null ) ? current_page : 1;
    var category_record_showLimit = $( '.category_record_showLimit' ).val(); 
    category_record_showLimit = ( category_record_showLimit != '' || category_record_showLimit != undefined || category_record_showLimit != null ) ? category_record_showLimit : 5;

    if( form_id == 'add_categoryform' ){
        is_categoryimage =  field_validation('#categoryimage');
        
        is_categoryname =  field_validation('#categoryname');
        is_parentcategory =  field_validation('#parentcategory');
    } else{
        is_categoryimage = true;
        is_categoryname =  field_validation('#categoryname');
        is_parentcategory =  field_validation('#parentcategory');
    }
     
    const url = '../core/models/modelcategory.php';
    const callback = function(data){ 
        var data = JSON.parse( data );
        var created_message, success_message = '', success_selector = '';

        console.log( '---------- action ----------: ' + action );
        console.log( '---------- edit_category data ----------: ' + JSON.stringify(data) );
        console.log( '---------- data.is_image_upload ----------: ' + data.is_image_upload );
        oes_loader( '#category_form_submit', false, button_value );  

        if( ( data.success == 1 || data.success == true || data.success == '1' ) ){  
            $('.category_form_message').removeClass('message_error'); 
            success_message = (is_editCategory) ? "Success! The category has been updated." : "Success! The category has been added.";
            success_selector = (is_editCategory) ? ".manageCategories_message" : ".category_form_message";            
            created_message = $( success_selector ).fadeIn().html( success_message );
            show_message_popup(created_message, 2000);
            $( '#' + form_id )[0].reset();  
            $('#parentcategory').append(data.parentCategoryOption);
            if( is_editCategory ){
                $( '.editCategory_popup_container' ).fadeOut();
                console.log( 'After edit category check the crrent page: ' + current_page );
                refreshCategory_DataTable( current_page, category_record_showLimit );// Please check after edited message delay time, I think issue
            }
        }else{
            $('.category_form_message').addClass('message_error'); 
            var message = $('.message_error').  css('transform','scale(1)').fadeOut().fadeIn().html("Error: "+ data.error); 
        }
    }; 

    var extra_data = { 
        'action': action, 
    }; 

    if( is_categoryimage == true && is_categoryname == true && is_parentcategory == true ){ 
        oes_loader( '#category_form_submit', true );
        ajax_form_submitor( url, callback, this, extra_data ); 
    } 
    return is_pageRefresh; 
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
