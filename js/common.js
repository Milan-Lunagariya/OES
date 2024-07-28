
console.log(' Hello,common.js entred ');

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

function field_validataion(id, regexp = /^[A-Za-z0-9\s]+$/, message = '', required = true) {

    var error = ''; 
    var required_msg = 'Required this field.';
    var invalid_msg = 'Invalid Value(Special char not allow).';
    /* var id = '#'+id; */
    var value = $(id).val(); 
    var flag = true; 

    if( value == undefined ){
        $(id).next('.formerror').fadeOut().fadeIn().text('Somthing went wrong !');
        return false 
    }
    
    if( regexp != '' ){
        if( !regexp.test(value) ){
            message = (typeof message == 'string' && message.trim() == '') ? invalid_msg : message;
            $(id).focus();
            flag = false;
        }
    }
    if( required == true || required == 1 ){
        if(typeof value == 'string' && value.trim() == ''){
            $(id).focus();
            message = required_msg;
            flag = false;
        }else{
            regexp = /^[A-Za-z0-9\s]{1,}$/;
            if( !regexp.test(value) ){
                message = invalid_msg;
                $(id).focus();
                flag = false;
            } 
        }
    } 

    error = message;
    console.log( 'Field validation..' + regexp + " flag is " + flag + "Id " + id +" Value is " + value );
    $(id).next('.formerror').fadeOut().fadeIn().text(error);
   /*  $(id).next('.formerror').text(error); */

    return (flag);  
}
$(document).ready(function(){   
    
    /* console.log(' Start cheking for validation... : ');
    
    $('.validate_field').on('blur', function(){ 
        console.log( 'on blur' ); 
        field_validataion('categoryname',  /^[A-Za-z\s]{2,}$/, 'Enter category name' ); 
    }); */

});