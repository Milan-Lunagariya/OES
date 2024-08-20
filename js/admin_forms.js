console.log( 'Hello, admin_forms.js enterd' );
$(document).ready(function(){      
    
    $('.validate_field').on('blur', function(){ 
        var type = $(this).attr( 'type' );
        field_validataion(this);  
    });


    $('#categoryform').on('submit', function(){  
        var categoryname, parentcategory;
        filefield =  field_validataion('#categoryimage');  
        categoryname =  field_validataion('#categoryname');  
        parentcategory =  field_validataion('#parentcategory');   

        const url = '../core/models/modelcategory.php';
        const callback = function(data){ 
            var data = JSON.parse( data ); 
            oes_loader( '#category_form_submit', false, 'Add' );
             
            if(data.success == 1 || data.success == true || data.success == '1'){  
                $('.category_form_message').removeClass('message_error'); 
                var message = $('.category_form_message').fadeIn().html("Success! The category has been added.");
                show_message_popup(message, 2000); 
                $('#categoryform')[0].reset();  
                $('#parentcategory').append(data.parentCategoryOption)
            }else{
                $('.category_form_message').addClass('message_error'); 
                var message = $('.message_error').  css('transform','scale(1)').fadeOut().fadeIn().html("Error: "+ data.error); 
            }
        }; 
        var extra_data = {
            'action': 'add_category'
        };
        console.log( 'categoryname : ' + categoryname ); 
        
        if( filefield == true && categoryname == true && parentcategory == true ){ 
            oes_loader( '#category_form_submit', true );
            ajax_form_submitor( url, callback, this, extra_data ); 
        } 
        return false; 
    });
 
}); 
$( '#categoryname' ).on('click', function(){
    alert('click the ...');
})