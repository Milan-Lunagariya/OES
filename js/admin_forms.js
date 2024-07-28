
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
     

    function ajax_form_submitor(url, callback , formdata_param, extra_data){

        var formdata = new FormData(formdata_param);
        if( extra_data != '' ){
            $.each(extra_data, function(key, value){
                formdata.append(key, value);
            });
        }
        $.ajax({
            type: 'post',
            url: url,
            data: formdata,
            contentType: false,            
            processData : false,
            success: callback
        })     
    }
    
    
    $('.validate_field').on('blur', function(){  
      field_validataion(this);  
    });


    $('#categoryform').on('submit', function(){ 

        var categoryname, parentcategory;
        categoryname = field_validataion('#categoryname'); 
        parentcategory = field_validataion('#parentcategory'); 


        const url = '../core/models/modelcategory.php';
        const callback = function(data){
            if(data == 'success'){ 
                var message = $('.category_form_message').fadeIn().html("Add category successfully");
                $('.category_form_message').removeClass('message_error');
                show_message_popup(message,2000);
                $('#categoryform')[0].reset();  
            }else{
                alert('Somthing Went Wrong !');
                $('.category_form_message').addClass('  message_error');
                $('.message_error').fadeIn().html("data: "+ data);
            }
        }; 
        var extra_data = {
            'action': 'add'
        };
        console.log( ' categoryname : ' + categoryname +  ' parentcategory : ' + parentcategory ); 
        if( categoryname == true && parentcategory == true ){
            ajax_form_submitor( url, callback, this, extra_data ); 
        } 
        return false; 
    });
});