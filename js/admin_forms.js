console.log( 'Hello, admin_forms.js enterd' );
$(document).ready(function(){      
/*     function ajax_form_submitor(url, callback , formdata_param, extra_data, loader_id ){
 
        var formdata = new FormData(formdata_param);
        if( extra_data != '' ){
            $.each(extra_data, function(key, value){
                formdata.append(key, value);
            });
        }
        setTimeout(function(){
            $.ajax({
                type: 'post',
                url: url,
                data: formdata,
                contentType: false,            
                processData : false,
                success: callback
            })     
        }, 2000)
    }  */
    
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
            $( '#category_form_submit' ).html( 'Add' );
            $( '#category_form_submit' ).prop( 'disabled', false );
             
            if(data.success == 1 || data.success == true || data.success == '1'){ 
                $('.category_form_message').removeClass('message_error'); 
                var message = $('.category_form_message').fadeIn().html("Success! The category has been added.");
                show_message_popup(message, 2000);
                
                $('#categoryform')[0].reset();  
                $('#parentcategory').append(data.parentCategoryOption)
            }else{
                $('.category_form_message').addClass('message_error'); 
                var message = $('.message_error').  css('transform','scale(1)').fadeOut().fadeIn().html("Error: "+ data.error);
                /* show_message_popup(message, 3000); */
                /* alert('Somthing Went Wrong !'); */
            }
        }; 
        var extra_data = {
            'action': 'add_category'
        };
        console.log( ' categoryname : ' + categoryname ); 
        if( filefield == true && categoryname == true && parentcategory == true ){
            $( '#category_form_submit' ).html( '<i class="fa fa-spinner fa-spin" style="font-size:24px"></i>' );
            $( '#category_form_submit' ).prop( 'disabled', true );
            ajax_form_submitor( url, callback, this, extra_data ); 
        } 
        return false; 
    });
  
});

// $('#remove_categories_button').on('click', function(){
//     alert( 'Tets the delete category' );
// })