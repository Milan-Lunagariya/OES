
console.log(' Hello,common.js entred ');
window.black = '#000000a8';
window.red = 'rgb(235 68 68)';


$(document).ready(function(){   
    $( '.datatable tr:odd' ).css('background-color', 'aliceblue');
});

function show_message_popup( message = $('.give_with_selector').fadeIn().html('Data: '), show_delay_time = 2000 ){ 
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
    }, 2000)
} 

function field_validataion(id, regexp = /^[A-Za-z0-9\s]+$/, message = '', required = true) {

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
        if( !regexp.test(value) ){
            message = (typeof message == 'string' && message.trim() == '') ? invalid_msg : message; 
            flag = false;
            focus = true;
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
    console.log( 'Field validation..' + regexp + " flag is " + flag + "Id " + id +" Value is " + value );

    return (flag);  
} 
function oes_loader( selector = '', show = true, stop_html = 'Success', css_value = '', loader_size = '' ){

    var selector_css = {};
    loader_size = ( loader_size != '' ) ? loader_size : '25px';

    if( show == true ){ 
        selector_css['cursor'] = 'progress';
        $( selector ).prop( 'disabled', true );
        $( selector ).html( '<i class="fa fa-spinner fa-spin" style="font-size:'+loader_size+'"></i>' );
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