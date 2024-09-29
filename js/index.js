

$(document).ready(function(){


    $( document ).on('click', '.menu_tablets_open_button', function(){  
        $(".menu_tablets").fadeIn();
    })

    $( document ).on('click', '.close_button', function(){ 
        $('.menu_tablets').fadeOut();
    })
    

})
 