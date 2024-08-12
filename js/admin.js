console.log( 'admin.js is entred' );

$(document).ready(function(){

   
    
    $('[class*="remove_category_"]').on('click', function(){
        var id = $(this).attr('id');
        var class_remove = '.remove_category_'+id; 
        var manageCategories_message = '.manageCategories_message'; 
        
        const url = '../core/models/modelcategory.php';
        const callback = function(data){   
            data = JSON.parse( data );
             
            if(data.success == 1 || data.success == true || data.success == '1'){ 
                $( manageCategories_message ).removeClass('message_error'); 
                var message = $( manageCategories_message ).fadeIn().html("Success! The category has been removed.");
                $( this ).parent('tr').fadeOut(1000);
                show_message_popup(message, 3000); 
            }else{
                $( manageCategories_message ).addClass('message_error'); 
                var message = $('.message_error').css('transform','scale(1)').fadeOut().fadeIn().html("Error: "+ data.error); 
            }  
           console.log( 'data: ' + JSON.stringify(data) );
        }; 
        var extra_data = {
            'action': 'remove_category',
            'remove_id' : id
        };

        if( id != null || id != '' ){
            if( oes_remove_confirmation() ){
                $( this ).prop( 'disabled', true );
                $( this ).html( '<i class="fa fa-spinner fa-spin" style="font-size:24px"></i>' );
                oes_loader( this, true );
                ajax_form_submitor( url, callback, null, extra_data );  
            }
        }
        return false; 

    }); 
}); 
 