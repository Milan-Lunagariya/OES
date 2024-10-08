console.log( 'Hello, admin_forms.js enterd' );
$(document).ready(function(){      
     
});  

$( document ).on('blur', '.validate_field', function(){ 
    var form_id = $( this ).closest( 'form' ).attr( 'id' );
    var type = $( this ).attr('type');

    console.log( "type = " + type + ', form_id: ' + form_id );
    if( type == 'file' && form_id == 'edit_categoryform' ){
    } else {
        field_validataion(this); 
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
        is_categoryimage =  field_validataion('#categoryimage');
        is_categoryname =  field_validataion('#categoryname');
        is_parentcategory =  field_validataion('#parentcategory');
    } else{
        is_categoryimage = true;
        is_categoryname =  field_validataion('#categoryname');
        is_parentcategory =  field_validataion('#parentcategory');
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