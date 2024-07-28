

$(document).ready(function(){


    $('.menu_tablets_open_button').on('click', function(){  
        $(".menu_tablets").show('slow');
    })

    $('.close_button').on('click', function(){ 
        $('.menu_tablets').hide('slow');
    })
    

})
 