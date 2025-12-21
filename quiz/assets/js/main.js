(function ($) {
  /*  "use strict";

    // Initiate the wowjs
    new WOW().init();


    // Spinner
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner();


    // Sticky Navbar
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.sticky-top').addClass('shadow-sm').css('top', '0px');
        } else {
            $('.sticky-top').removeClass('shadow-sm').css('top', '-100px');
        }
    });
    
    
    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    });


    // Header carousel
    $(".header-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1500,
        items: 1,
        dots: true,
        loop: true,
        nav : true,
        navText : [
            '<i class="bi bi-chevron-left"></i>',
            '<i class="bi bi-chevron-right"></i>'
        ]
    });*/


  /*  var TimesUsers={
        init: function(){
            this.binding();
            this.login();
            this.register();
        },
        binding: function(){
        },
        login: function(){
            $("#loginForm").submit( function(event){
                event.preventDefault();
                var $this=this;
                var formData = $(this).serialize();
               // console.log(formData); 

                $.post(TIMES_OPTION.ajax_url, formData, function(response) {
                        //console.log(response);
                    if(response.status == 'failed'){
                        $($this).find(".alert").html(response.message);
                    }else{
                        //alert('Form submitted successfully!');
                        window.location.href=response.redirect;
                    }
                })

            });
        },
        register: function(){
            $("#registerForm").submit( function(event){
                event.preventDefault();
                var $this=this;

                var formData = $(this).serialize();
                console.log(formData); 

                $.post(TIMES_OPTION.ajax_url, formData, function(response) {
                    //alert('Form submitted successfully!');
                     if(response.status == 'failed'){
                        $($this).find(".alert").html(response.message);
                    }else{
                        //alert('Form submitted successfully!');
                        window.location.href=response.redirect;
                    }
                });

            });
        },
    };

    TimesUsers.init();*/
    
})(jQuery);

