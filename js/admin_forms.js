console.log( 'Hello, admin_forms.js enterd' );
$(document).ready(function(){      
     
});  

$( document ).on('blur', '.validate_field', function(){ 
    var type = $(this).attr( 'type' );
    field_validataion(this);  
});

$( document ).on('submit', '#add_categoryform, #edit_categoryform', function(){  
    var is_categoryname, is_parentcategory, is_categoryimage;
    var id = $( this ).attr( 'id' );
    var action = ( id == 'edit_categoryform' ) ? 'edit_categoryform' : 'add_categoryform';

    if( id == 'add_categoryform' ){
        is_categoryimage =  field_validataion('#categoryimage');
        is_categoryname =  field_validataion('#categoryname');
        is_parentcategory =  field_validataion('#parentcategory');
    } else{
        is_categoryimage = is_categoryname = is_parentcategory = true;
    }
     
    const url = '../core/models/modelcategory.php';
    const callback = function(data){ 
       /*  var data = JSON.parse( data );  */

        console.log( '---------- edit_category data ----------: ' + data );
        /* oes_loader( '#category_form_submit', false, 'Add' ); 
        if(data.success == 1 || data.success == true || data.success == '1'){  
            $('.category_form_message').removeClass('message_error'); 
            var message = $('.category_form_message').fadeIn().html("Success! The category has been added.");
            show_message_popup(message, 2000);    
            $( '#' + id )[0].reset();  
            $('#parentcategory').append(data.parentCategoryOption)
        }else{
            $('.category_form_message').addClass('message_error'); 
            var message = $('.message_error').  css('transform','scale(1)').fadeOut().fadeIn().html("Error: "+ data.error); 
        } */
    }; 

    var extra_data = {
        'action': action
    };
    console.log( 'is_categoryname : ' + is_categoryname );  

    if( is_categoryimage == true && is_categoryname == true && is_parentcategory == true ){ 
        oes_loader( '#category_form_submit', true );
        ajax_form_submitor( url, callback, this, extra_data ); 
    } 
    return false; 
});