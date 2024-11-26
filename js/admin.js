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
$( document ).on('click', '[class*="remove_product_"]', function(){
    var id = $(this).attr('id');
    var class_remove = '.remove_product_'+id; 
    var manageProduct_message_selector = '.manageproduct_message'; 
    
    const url = '../core/models/modelproducts.php';
    const callback = function(data){   
        data = JSON.parse( data );
         
        if(data.success == 1 || data.success == true || data.success == '1'){ 
            $( manageProduct_message_selector ).removeClass('message_error'); 
            var message = $( manageProduct_message_selector ).fadeIn().html("Success! The category has been removed.");
            $(class_remove).closest('tr').fadeOut('slow');
            show_message_popup(message, 3000, true); 
        }else{
            $( manageCategories_message ).addClass('message_error'); 
            var message = $('.message_error').css('transform','scale(1)').fadeOut().fadeIn().html("Error: "+ data.error); 
        }  
       console.log( 'data: ' + JSON.stringify(data) );
    }; 
    var extra_data = {
        'action': 'remove_product',
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



$( document ).on( 'click', '[class*="edit_product_"]', function(){
    
    $( '.editProduct_popup_container' ).fadeIn();
    $( '.close_editProduct' ).hide();

    var id = $(this).attr('id');  

    var class_edit = '.edit_product_'+id;

    const url = '../core/models/modelproducts.php'; 

    $( '#productimages' ).removeClass( 'validate_field' );
    console.log( $( '#productimages' ) );

    const callback = function(data){     
        oes_loader( '.manageProduct_form_popup', false, '', {'cursor': 'auto'} );
        $( '.close_editProduct' ).fadeIn();
        $( '.manageProduct_form_popup' ).fadeIn('slow').html( data );  
    };  
    var send_dataOnPHP = {
        'action': 'edit_productform',
        'edit_id' : id
    };
    if( id != null || id != '' ){ 
        oes_loader( '.manageProduct_form_popup', true, '', '', '40px');
        ajax_form_submitor( url, callback, null, send_dataOnPHP );   
    }
     
    return false; 
});  

$( document ).on( 'click', '.close_editCategory, .close_editProduct', function(){
    $( '.editCategory_popup_container' ).fadeOut();
    $( '.editProduct_popup_container' ).fadeOut();
}); 

$( document ).on( 'click', '[class*="pageButton_"]', function(){
    
    try{
        var page = $( this ).attr( 'id' ); 
        var searchWords = $( '.searchCategoriesOnMC' ).val();
        var datatable_page = $( "[name='datatable_page']" ).val();
        var category_record_showLimit = $( '.category_record_showLimit' ).attr( 'value' ); 
        category_record_showLimit = ( category_record_showLimit != '' || category_record_showLimit != undefined || category_record_showLimit != null ) ? category_record_showLimit : 5;
        var load_datatable_pages = [ 'manageProduct' ];

        if( load_datatable_pages.includes( datatable_page ) ){
            readymate_product_refresh( page );
        } else {   
            /* For the category */
            refreshCategory_DataTable( page, category_record_showLimit, searchWords, function(){
                $( '.datatable tr:odd' ).css('background-color', 'aliceblue');
            } );
        }
    } catch( c ){
        console.log( "oes Error:" + c );
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
        $( '.datatable tr:odd' ).css('background-color', 'aliceblue');  
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
            $( '.datatable tr:odd' ).css('background-color', 'aliceblue');
        } );
        console.log( 'search: ' + search );
    } catch( c ){
        console.log( "oes function refreshCategory_DataTable is not find:" + c );
    }
} )
$(document).on('click', 'td[class^="datatable_checked_td_"]', function(){  
    var selector = $( this ).attr( 'id' );  
    selector = "." + selector; 

    if( $( selector ).is(':checked') ){
        $( selector ).prop( 'checked', false );
    } else {
        $( selector ).prop( 'checked', true ); 
    }   
});
$( document ).on( 'click', '.datatable_th_0', function( event ){

    event.stopPropagation();
    console.log( 'Inside the th checked..' );
    if( $( '.datatable_checked_all' ).is(':checked') ){
        $( '.datatable_checked_all' ).prop( 'checked', false );
    } else {
        $( '.datatable_checked_all' ).prop( 'checked', true ); 
    }
    
} );

$( document ).on( 'click', '.apply_button', function(){
    try{
        var datatable_page = $( "[name='datatable_page']" ).val(); 
        var msg_selector = '.manageCategories_message'; // default for categories
        var ajax_url = '../core/models/modelcategory.php'; // default for categories
        var delete_action = 'bulk_deleteCategory'; // default for categories
        var callback_by_obj = {}
        var send_dataOnPHP_by_obj = {}

        if( datatable_page == 'manageProduct' ){  
            msg_selector = '.manageproduct_message'; 
            ajax_url = '../core/models/modelproducts.php';
            delete_action = 'bulk_deleteProduct';
        } 

        var selected_val = $( '.oes_bulk_option :selected' ).attr( 'value' );
        var checked_length = $('.datatable_checked_all:checked').length;
        var checked_ids = [];
        $('.datatable_checked_all:checked').each( function(){
            var id = $( this ).attr( 'id' );
            id = ( id == '' || id == undefined ) ? 0 : id;
            checked_ids.push( id );
        } )
                
        send_dataOnPHP_by_obj['delete'] = {
            'action': delete_action,
            'length' : checked_length,
            'checked_ids': checked_ids
        }; 
        
        callback_by_obj['delete']  = function( data ){
            data = JSON.parse( data );
            console.log( data );
            if( data.success == true || data.success == 1 || data.success == '1' ){
                oes_loader( '.oes_loader_center', false, '', { 'display': 'none' } );
                var success_msg = "Success - Checked categories Deleted";
                var add_callback = function(){
                    console.log( 'Congratulation your checked entries has been successfully Deleted.' );
                    var message = $( msg_selector ).fadeIn().html( success_msg );
                    show_message_popup(message, 3000, true); 
                }

                if( datatable_page == 'manageProduct' ){

                    readymate_product_refresh( '', add_callback );
                    
                } else {
                    /* ---------- For the refresh manage categories ---------- */
                    var searchWords = $( '.searchCategoriesOnMC' ).val();
                    var page = $( '.managecategory_currentpage' ).val(); 
                    var category_record_showLimit = $( '.category_record_showLimit' ).attr( 'value' );  
                    try{
                        refreshCategory_DataTable( page, category_record_showLimit, searchWords, function(){
                            var message = $( '.manageCategories_message' ).fadeIn().html( success_msg );
                            show_message_popup(message, 3000, true); 
                            $( '.datatable tr:odd' ).css('background-color', 'aliceblue');
                        } ); 
                    } catch( c ){
                        console.log( "oes function refreshCategory_DataTable is not find:" + c );
                    }
                    $( '.close_editCategory' ).fadeIn();
                }
            } else{
                oes_loader( '.oes_loader_center', false, 'Somthing went wrong!, do page refresh and try again' ); 
            }
        } 

        datatable_applyButton_action( ajax_url, msg_selector, send_dataOnPHP_by_obj, callback_by_obj );
    } catch( error ){
        console.log( 'Error: ' + error );
    }
} );

$( document ).on( 'change', '.product_record_showLimit', function(){ 
    try {
        readymate_product_refresh();
    } catch (error) {
        console.log( "Error: " + error );
    }
} );

$( document ).on( 'click', '.searchProductButton', function(){ 
    try {
        readymate_product_refresh();
    } catch (error) {
        console.log( "Error: " + error );
    }
} );



$( document ).on( 'click', '.oes_closeButton', function(){
    $( '.general_popup_container' ).fadeOut();
});
$( document ).on( 'click', '.popup_showCategory', function(){
    
    $( '.general_popup_container' ).fadeIn(); 
} )

$( document ).on( 'click', '.oes_adminPanel_sidebar .admininner_sidebarmenu_container', function( event ){
    $( this ).next( '.admininner_sidebarmenu' ).fadeToggle(); 
} )

function openCloseAdminSidebar(){
    
    console.log( Math.abs( $( '.oes_adminPanel_sidebar' ).width()) < 1 );
    if(  Math.abs( $( '.oes_adminPanel_sidebar' ).width()) < 1 ){
        $( '.oes_adminPanel_sidebar' ).animate({ width: '20em' }, 500);
        $( '.oes_adminPanel_sidebar nav' ).show()
    } else {
        $( '.oes_adminPanel_sidebar' ).animate({ width: '0px' }, 500);
        $( '.oes_adminPanel_sidebar nav' ).hide()
    }
    console.log( 'call..' );
}

$( document ).on( 'click', '.manageProducts_descriptionReadmoreLink',function(){
    
    var description_container =  $( this ).parent().find( '.manageProducts_descriptionCotainer' );
    
    if( description_container.height() <= 110 ){
        description_container.css( { 'height': '100%' } );
        $( this ).html( 'Less' ); 
    } else {
        description_container.height( '105' );
        $( this ).html( 'Read more...' ); 
    }
    console.log( description_container.height() );
} )

function manageProductImages( productid ){

    productid = ( productid != undefined || productid != '' ) ? productid : 0;
    $( '.editProduct_popup_container' ).fadeIn();
    $( '.close_editProduct_container' ).hide();

    const url = '../core/models/modelproducts.php';
    const callback = function( data ){
        $( '.close_editProduct_container' ).fadeIn();
        oes_loader( '.manageProduct_form_popup', false, '', '', '40px');
        $( '.manageProduct_form_popup' ).fadeIn().html( data )
        console.log( data );
    };  
    const extra_data = {
        'action': 'manageProductImages',
        'productid': productid
    }
    
    oes_loader( '.manageProduct_form_popup', true, '', { 'cursor': 'auto !imporant' }, '40px');
    ajax_form_submitor(url, callback , this, extra_data ) 
}

function deleteProductImage( _this ){
    var productImage = $( _this ).attr( 'data-image-value' );
    var productid = $( _this ).attr( 'data-productid' );
    productid = ( productid != undefined || productid != '' ) ? productid : 0;
    console.log( productImage );

    if( confirm( "Do you want to delete this Product image permanently delete ?" ) ){
        const url = '../core/models/modelproducts.php';
        const callback = function( data ){
            oes_loader( _this, false );
            console.log( data );
            data = JSON.parse( data );

            if( data.success == '1' && data.success == true && data.success == 1 ){
                var message = $( '.manageproduct_message' ).fadeIn().html( "Success: Product Image Deleted" );
                show_message_popup(message, 3000, true); 
                $( _this ).closest( '.productImageCartContainer' ).html( '' ).fadeOut();
                console.log( $( _this ) );
            } else {
                data.error = ( data.error != undefined || data.error != null ) ? data.error: "Somthing Went Wrong!";
                var message = $( '.manageproduct_message' ).html( data.error );
                show_message_popup(message, 3000, false); 
            } 
        };  
        const extra_data = {
            'action': 'deleteProductImage',
            'productid': productid,
            'productImage': productImage
        }
        
        oes_loader( _this, true );
        ajax_form_submitor(url, callback , this, extra_data ) 
    } 
}

$( document ).on( 'submit', '#editProductImages', function(){ 
    var productid = $( this ).attr( 'data-productid' );
    productid = ( productid != undefined || productid != '' ) ? productid : 0;
    console.log( "Product id: " + productid );
   
    const url = '../core/models/modelproducts.php';
    const callback = function( data ){
        oes_loader( ".productSaveButton", false, 'Save' );
        console.log( data );
        data = JSON.parse( data );

        if( data.success == '1' && data.success == true && data.success == 1 ){
            var message = $( '.manageproduct_message' ).fadeIn().html( "Success: Product Images Updated Successfully." );
            show_message_popup(message, 3000, true); 
            manageProductImages( productid );
        } else {
            data.error = ( data.error != undefined || data.error != '' ) ? data.error: "Somthing Went Wrong!";
            data.moreImageError = ( data.moreImageError != undefined || data.moreImageError != '' ) ? data.moreImageError: "";
            data.replaceImageError = ( data.replaceImageError != undefined || data.replaceImageError != '' ) ? data.replaceImageError: "";
            var all_error = data.error + " " + data.moreImageError + " " + data.replaceImageError;
            var message = $( '.manageproduct_message' ).fadeIn().html( all_error );
            show_message_popup(message, 10000, false); 
        } 
    };  
    const extra_data = {
        'action': 'editProductImages',
        'productid': productid
    }

    oes_loader( ".productSaveButton", true );
    ajax_form_submitor(url, callback , this, extra_data )
    
    return false;

})
