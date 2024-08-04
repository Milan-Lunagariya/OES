
$(document).ready(function(){  
      /* function show_message_popup( message = $('.give_with_selector').fadeIn().html('Data: '), show_delay_time = 2000 ){ 
        message.css('transform', 'scale(0.5)');
        setTimeout(function(){
            message.css({'transform':'scale(1)', 'transition': '1s'});
            setTimeout(function(){
                message.fadeOut(1000, function(){
                    message.css({'transform':'scale(0.5)', 'transition': '1s'});
                });  
            }, show_delay_time);
        }, 500);
    }    
     */
     
    
    function ajax_form_submitor(url, callback , formdata_param, extra_data, loader_id ){
 
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
    } 
    
    $('.validate_field').on('blur', function(){  
      field_validataion(this);  
    });


    $('#categoryform').on('submit', function(){ 

        var categoryname, parentcategory;
        categoryname =  field_validataion('#categoryname');  
        parentcategory =  field_validataion('#parentcategory');  


        const url = '../core/models/modelcategory.php';
        const callback = function(data){ 
            var data = JSON.parse( data );
            $( '#category_form_submit' ).html( 'Add' );
            $( '#category_form_submit' ).prop( 'disabled', false );
             
            if(data.success == 1 || data.success == true || data.success == '1'){ 
                $('.category_form_message').removeClass('message_error'); 
                var message = $('.category_form_message').fadeIn().html("Add category successfully");
                show_message_popup(message, 2000);
                
                $('#categoryform')[0].reset();  
                $('#parentcategory').append(data.parentCategoryOption)
            }else{
                $('.category_form_message').addClass('message_error'); 
                var message = $('.message_error').fadeOut().fadeIn().css('transform','scale(1)').html("Error: "+ data.error);
                /* show_message_popup(message, 3000); */
                /* alert('Somthing Went Wrong !'); */
            }
        }; 
        var extra_data = {
            'action': 'add'
        };
        console.log( ' categoryname : ' + categoryname ); 
        if( categoryname == true && parentcategory == true){
            $( '#category_form_submit' ).html( '<i class="fa fa-spinner fa-spin" style="font-size:24px"></i>' );
            $( '#category_form_submit' ).prop( 'disabled', true );
            ajax_form_submitor( url, callback, this, extra_data ); 
        } 
        return false; 
    });
});