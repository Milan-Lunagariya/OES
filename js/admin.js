console.log( 'admin.js is entred' );

$(document).ready(function(){ 
    
});

$( document ).on('click', '[class*="remove_category_"]', function(){
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
        if( confirm( 'Are you sure, You want to remove this record ?' ) ){ 
            oes_loader( class_remove, true );
            ajax_form_submitor( url, callback, null, extra_data );
        }
    }

    return false; 
    
});

$( document ).on( 'click', '[class*="edit_category_"]', function(){
    
    $( '.editCategory_popup_container' ).fadeIn();
    $( '.close_editCategory' ).hide();

    var id = $(this).attr('id');  

    var class_edit = '.edit_category_'+id;
    var manageCategories_message = '.manageCategories_message';

    const url = '../core/models/modelcategory.php'; 

    const callback = function(data){     
        oes_loader( '.manageCategories_form_popup', false, '', {'cursor': 'auto'} );
        $( '.close_editCategory' ).fadeIn();
        $( '.manageCategories_form_popup' ).fadeIn('slow').html( data );  
    };  
    var send_dataOnPHP = {
        'action': 'edit_categoryform',
        'edit_id' : id
    };
    if( id != null || id != '' ){ 
        oes_loader( '.manageCategories_form_popup', true, '', '', '40px');
        ajax_form_submitor( url, callback, null, send_dataOnPHP );   
    }
     
    return false; 
});    

$( document ).on( 'click', '.close_editCategory', function(){
    $( '.editCategory_popup_container' ).fadeOut();
}); 

$( document ).on( 'click', '[class*="pageButton_"]', function(){
    
    var page = $( this ).attr( 'id' ); 
    
    var searchWords = $( '.searchCategoriesOnMC' ).val();
    var category_record_showLimit = $( '.category_record_showLimit' ).attr( 'value' ); 
    category_record_showLimit = ( category_record_showLimit != '' || category_record_showLimit != undefined || category_record_showLimit != null ) ? category_record_showLimit : 5;
    try{
        refreshCategory_DataTable( page, category_record_showLimit, searchWords );
    } catch( c ){
        console.log( "oes function refreshCategory_DataTable is not find:" + c );
    } 
} );

$( document ).on( 'change', '.category_record_showLimit', function(){
    var limit = this.value;
    var searchWords = $( '.searchCategoriesOnMC' ).val();
    var pageno = $( 'managecategory_currentpage' ).val();  
    pageno = ( pageno != null || pageno != '' ) ? pageno : 1;
    limit = ( limit != null || limit != '' ) ? limit : 5;

    refreshCategory_DataTable( pageno, limit, searchWords, function(){ 
        $( '.category_record_showLimit' ).attr( 'value',limit );   
    } );
    console.log( 'Category record show limit is ' + limit );
} );

$( document ).on( 'click', '.searchCategoriesButton', function(){
    var searchWords = $( '.searchCategoriesOnMC' ).val();
    var page = $( 'managecategory_currentpage' ).val(); 
    var category_record_showLimit = $( '.category_record_showLimit' ).attr( 'value' ); 
    category_record_showLimit = ( category_record_showLimit != '' || category_record_showLimit != undefined || category_record_showLimit != null ) ? category_record_showLimit : 5;
    try{
        refreshCategory_DataTable( page, category_record_showLimit, searchWords, function(){
            $( '.searchCategoriesOnMC' ).val( searchWords );
        } );
        console.log( 'search: ' + search );
    } catch( c ){
        console.log( "oes function refreshCategory_DataTable is not find:" + c );
    }
} )
$(document).on('click', 'td[class^="datatable_checked_td_"]', function(){  
    var id = $( this ).attr( 'id' );
    if( $( "." + id ).is(':checked') ){
        $( "." + id ).prop( 'checked', false );
    } else {
        $( "." + id ).prop( 'checked', true ); 
    }   
});
$( document ).on( 'click', '.datatable_th_0', function(){
      
    if( $( '.datatable_checked_all' ).is(':checked') ){
        $( '.datatable_checked_all' ).prop( 'checked', false );
    } else {
        $( '.datatable_checked_all' ).prop( 'checked', true ); 
    }
    
} );

$( document ).on( 'click', '.apply_button', function(){
    var selected_val = $( '.oes_bulk_option :selected' ).attr( 'value' );
    var checked_length = $('.datatable_checked_all:checked').length;

    if( selected_val == '' ){
        var message = $( '.manageCategories_message' ).fadeIn().html('Please select an bulk option to apply.');
        show_message_popup(message, 5000, false);  
        return  false;
    }
    if( checked_length < 1 ){ 
        var message = $( '.manageCategories_message' ).fadeIn().html("Please checked at least 1 row of entries");
        show_message_popup(message, 5000, false); 
        return false;
    } 
    if( selected_val == 'delete' ){
        if( confirm( 'Are you sure, You want to apply Delete action on select entries ?' ) ){
          
            var checked_ids = [];
            $('.datatable_checked_all:checked').each( function(){
                var id = $( this ).attr( 'id' );
                id = ( id == '' || id == undefined ) ? 0 : id;
                checked_ids.push( id );
            } )
            

            const url = '../core/models/modelcategory.php'; 
            const callback = function(data){     
                data = JSON.parse( data );
                console.log( data );
                if( data.success == true || data.success == 1 || data.success == '1' ){
                    oes_loader( '.oes_loader_center', false, '', { 'display': 'none' } );
                    
                    var searchWords = $( '.searchCategoriesOnMC' ).val();
                    var page = $( 'managecategory_currentpage' ).val(); 
                    var category_record_showLimit = $( '.category_record_showLimit' ).attr( 'value' ); 
                   
                    try{
                        refreshCategory_DataTable( page, category_record_showLimit, searchWords, function(){
                        var message = $( '.manageCategories_message' ).fadeIn().html("Success - Checked categories Deleted");
                        show_message_popup(message, 3000, true); 
                        } );
                        
                        console.log( 'search: ' + search );
                    } catch( c ){
                        console.log( "oes function refreshCategory_DataTable is not find:" + c );
                    }

                } else{
                    oes_loader( '.oes_loader_center', false, 'Somthing went wrong!, do page refresh and try again' ); 
                }
                oes_loader( '.manageCategories_form_popup', false, '', {'cursor': 'auto'} );
                $( '.close_editCategory' ).fadeIn(); 
   
            };  
            var send_dataOnPHP = {
                'action': 'bulk_deleteCategory',
                'length' : checked_length,
                'checked_ids': checked_ids
            }; 
            
            
            oes_loader( '.oes_loader_center', true, '', '', '40px' );
            ajax_form_submitor( url, callback, null, send_dataOnPHP );    
             
        }
    } 

} );