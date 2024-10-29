
console.log(' Hello,common.js entred ');
window.black = '#000000a8';
window.red = 'rgb(235 68 68)';
window.svg_icon_success = '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"><!-- Uploaded to: SVG Repo, www.svgrepo.com, Transformed by: SVG Repo Mixer Tools --><svg width="50px" height="50px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#9cfe95"><g id="SVGRepo_bgCarrier" stroke-width="0"/><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#168b0e" stroke-width="3.2640000000000002"> <path fill-rule="evenodd" clip-rule="evenodd" d="M19.7071 6.29289C20.0976 6.68342 20.0976 7.31658 19.7071 7.70711L10.4142 17C9.63316 17.7811 8.36683 17.781 7.58579 17L3.29289 12.7071C2.90237 12.3166 2.90237 11.6834 3.29289 11.2929C3.68342 10.9024 4.31658 10.9024 4.70711 11.2929L9 15.5858L18.2929 6.29289C18.6834 5.90237 19.3166 5.90237 19.7071 6.29289Z" fill="#0F1729"/> </g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M19.7071 6.29289C20.0976 6.68342 20.0976 7.31658 19.7071 7.70711L10.4142 17C9.63316 17.7811 8.36683 17.781 7.58579 17L3.29289 12.7071C2.90237 12.3166 2.90237 11.6834 3.29289 11.2929C3.68342 10.9024 4.31658 10.9024 4.70711 11.2929L9 15.5858L18.2929 6.29289C18.6834 5.90237 19.3166 5.90237 19.7071 6.29289Z" fill="#0F1729"/> </g></svg>';

$(document).ready(function(){   
    $( '.datatable tr:odd' ).css('background-color', 'aliceblue');
});

function show_message_popup( message = $('.give_with_selector').fadeIn().html('Data: '), show_delay_time = 2000, success = true ){ 
    if( success == false ){
        $( message ).addClass( 'message_error' );
    }else {
        $( message ).removeClass( 'message_error' );
    }
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

function oes_test(){
    console.log( 'call oes_test from the common.js' );
}

function ajax_form_submitor(url, callback , formdata_param, extra_data ){

    if( formdata_param instanceof HTMLFormElement ){
        var formdata = new FormData(formdata_param);
    } else{
        var formdata = new FormData();
    }

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
    }, 1000)
} 

function field_validation(id, regexp = /^[A-Za-z0-9\s]+$/, message = '', required = true) {

    var error = ''; 
    var required_msg = 'Required this field.';
    var invalid_msg = 'Invalid Value(Special char not allow).'; 
    var value = $(id).val(); 
    var type = $(id).attr('type'); 
    var flag = true; 
    var focus = false;

    if( value == undefined ){
        $(id).next('.formerror').fadeOut().fadeIn().text('Somthing went wrong !');
        return false 
    }
    
    if( regexp != '' ){
        console.log( "Reguler Expression" );
        console.log( regexp.test(value) );
        if( regexp.test(value) === false ){
            message = (typeof message == 'string' && message.trim() == '') ? invalid_msg : message; 
            flag = false;
            focus = true;
        } else{
            message = ( flag == true ) ? '' : message;             
        }
    }
    if( required == true || required == 1 ){
        if(typeof value == 'string' && value.trim() == ''){ 
            message = required_msg;
            flag = false;
            focus = true;
        }else{
            
            if(  type == 'file' ) {
                if (!value) {
                    message = 'Please select a file';
                    flag = false;
                    focus = true;
                }else{
                    flag = true;
                    focus = false;
                    message = null;
                }
                
            } else{
                regexp =  /^[A-Za-z0-9\s]{1,}$/; 

                if( !regexp.test(value) ){
                    message = invalid_msg; 
                    flag = false;
                    focus = true;
                } 
            }
        }
    } 

    error = message;
    if( focus == true ) {
        $(id).focus().css('border','1px solid ' + window.red);
    }else{
        
        $(id).css('border','1px solid '+ window.black);
    }
    $(id).next('.formerror').fadeOut().fadeIn().text(error); 

    console.log( '\n id:' + id + '\n flag: ' + flag );
    return (flag);
} 
function oes_loader( selector = '', show = true, stop_html = window.svg_icon_success, css_value = '', loader_size = '' ){

    stop_html= ( stop_html != '' ) ? stop_html : window.svg_icon_success;
    var selector_css = {};
    loader_size = ( loader_size != '' ) ? loader_size : '25px';
    
    if( show == true ){ 
        selector_css['cursor'] = 'progress';
        $( selector ).prop( 'disabled', true );
        $( selector ).html( '<i class="fa fa-spinner fa-spin" style="font-size:'+loader_size+'"></i>' );
        $( selector ).fadeIn();
    } else {         
        selector_css['cursor'] = 'pointer';
        $( selector ).prop( 'disabled', false ); 
        $( selector ).html( stop_html );
    }

    if( css_value != '' ) {
        selector_css =  css_value;
    }

    $( selector ).css( selector_css );
}

function refreshCategory_DataTable( page = 1, category_record_showLimit = 5, searchWords = '', callback_addfun = function(){} ){

    page = ( page != '' ) ? page : 1;
    category_record_showLimit = ( category_record_showLimit != '' ) ? category_record_showLimit : 5;

    const url = '../core/models/modelcategory.php'; 
    const callback = function( data ) {
        $( '.datatable tr:odd' ).css('background-color', 'aliceblue');
        $( '.oes_loader_center' ).fadeOut();
        $( '.categoriesDataTableOnMC' ).html( data );
        callback_addfun();
    };  

    var send_dataOnPHP = {
        'page': 'manage_categories',
        'current_page': page,
        'category_record_showLimit': category_record_showLimit, 
        'searchCategoriesOnMC': searchWords,
        'action': 'loadCategoriesOnMC'
    };

    $( '.oes_loader_center' ).fadeIn();
    oes_loader( '.oes_loader_center', true, '', '', '40px' );
    ajax_form_submitor( url, callback, null, send_dataOnPHP );
}

function refreshAnyOne_DataTable_onCurrentPage( ajax_url = '../core/models/modelproducts.php',  send_dataOnPHP = {}, callback_addfun = function( data ){} ){
   /* Note: This function use for refresh datatable any one on a current page. If you use in single page multiple datatable then you can't use this function because this function create for the controll only one datatable in current page. */
  
    const callback = function( data ) {
        callback_addfun( data );
        $( '.oes_loader_center' ).fadeOut();
        $( '.datatable tr:odd' ).css('background-color', 'aliceblue');
    };  

    $( '.oes_loader_center' ).fadeIn();
    oes_loader( '.oes_loader_center', true, '', '', '40px' );
    ajax_form_submitor( ajax_url, callback, null, send_dataOnPHP );
}

function readymate_product_refresh( current_page = $( '[name="datatable_current_page"]' ).val(), add_callback = function( data ){} ){
    var limit = $( '.product_record_showLimit :selected' ).attr( 'value' );
    var ajax_url = '../core/models/modelproducts.php';  
    var datatable_search = $( '.searchProductOnMC' ).val();
    var datatable_action = 'refreshProductsTable';
    
    current_page = ( current_page != '' ) ? current_page : $( '[name="datatable_current_page"]' ).val();
    current_page = ( current_page != undefined || current_page != '' ) ? current_page : 1;
    limit = ( limit != undefined || limit != '' ) ? limit : 5; 

    var send_dataOnPHP = {
        'current_page': current_page, 
        'limit': limit, 
        'datatable_search': datatable_search, 
        'action': datatable_action
    };
 
    refreshAnyOne_DataTable_onCurrentPage( ajax_url, send_dataOnPHP, function( data ){
        console.log( 'Inside the success: refreshAnyOne_DataTable_onCurrentPage callback' );
        $( '.datatable_change_table' ).html( data );
        add_callback( data );
    } ); 
}

function submissionValidateFields( obj_fields  ){
    const errors = [];
    let isValid = true;

    Object.entries(obj_fields).forEach(([key, value]) => { 
        if ( field_validation( `[name="${key}"]` ) === false ) {
            isValid = false;
            errors.push(key);
        }
    });

    return { isValid, errors };
}

function datatable_applyButton_action( ajax_url, msg_selector, send_dataOnPHP_by_obj = {}, callback_by_obj = {} ){
    var selected_val = $( '.oes_bulk_option :selected' ).attr( 'value' );
    var checked_length = $('.datatable_checked_all:checked').length;
    var message = '';
        
    if( selected_val == '' ){
        message = $( msg_selector ).fadeIn().html( 'Please select an bulk option to apply.' );
        show_message_popup(message, 5000, false);   
        return  false;
    }

    if( checked_length < 1 ){
        message = $( msg_selector ).fadeIn().html("Please checked at least 1 row of entries");
        show_message_popup(message, 5000, false); 
        return false;
    } 

    if( selected_val == 'delete' ){
        if( confirm( 'Are you sure, You want to apply Delete action on checked entries ?' ) ){
 
            const url = ajax_url; 
            const callback = function( data ){      
                try{
                    callback_by_obj.delete( data );
                } catch ( error ){
                    console.log( 'Error: Fail the callback by object of delete check it, ' + error );
                }
            };   
            oes_loader( '.oes_loader_center', true, '', '', '40px' );
            ajax_form_submitor( url, callback, null, send_dataOnPHP_by_obj.delete );    
             
        }
    } 
}

$( document ).on( 'submit', '.login_form', function(){
    var validate_password = field_validation( '[name="LoginForm_password"]' );
    var validate_name = field_validation( '[name="LoginForm_name"]' );
    console.log( 'validate_name: ' + validate_name );
    console.log( 'validate_password: ' + validate_password );
    if( validate_name != true || validate_password != true ){
        return false;
    }
});
