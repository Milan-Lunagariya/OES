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
                $(class_remove).closest('tr').fadeOut('slow');
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
                oes_loader( class_remove, true );
                ajax_form_submitor( url, callback, null, extra_data );  
            }
        }

        return false;

    }); 

    $( '[class*="edit_category_"]' ).on( 'click', function(){
        
        $( '.editCategory_popup_container' ).fadeIn();
        $( '.close_editCategory' ).hide();

        var id = $(this).attr('id'); 
        console.log( 'Edit id: ' + id );

        var class_edit = '.edit_category_'+id;
        var manageCategories_message = '.manageCategories_message';

        const url = '../core/models/modelcategory.php'; 

        const callback = function(data){    

            oes_loader( '.manageCategories_form_popup', false, '', {'cursor': 'auto'} );
            $( '.close_editCategory' ).fadeIn();
            $( '.manageCategories_form_popup' ).fadeIn('slow').html( data );  
        };  
        var send_dataOnPHP = {
            'action': 'edit_category',
            'edit_id' : id
        };
        if( id != null || id != '' ){ 
            oes_loader( '.manageCategories_form_popup', true, '', '', '40px');
            ajax_form_submitor( url, callback, null, send_dataOnPHP );   
        }
         
        return false; 
    });  
    
    
    $('.close_editCategory').on( 'click', function(){
        $( '.editCategory_popup_container' ).fadeOut();
    });
    
}); 

$( '.form_container' ).on( 'click', function(){
    alert( 'Done Dona Done on click to the popup' );
})
$( '#categoryname' ).on('click', function(){
    alert('click the ...');
})