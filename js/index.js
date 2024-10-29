

$(document).ready(function(){


    $( document ).on('click', '.menu_tablets_open_button', function(){  
        $(".menu_tablets").fadeIn();
    })

    $( document ).on('click', '.close_button', function(){ 
        $('.menu_tablets').fadeOut();
    })
     
})

document.querySelectorAll('.thumbnail-image').forEach((thumbnail) => {
    thumbnail.addEventListener('click', function() {
        const index = this.getAttribute('data-slide-to');
        $('#productCarousel').carousel(index);
    });
});

 