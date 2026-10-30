
jQuery(document).ready(function ($) {

    // Handle offset scroll on direct hash link (on page load)
    if (window.location.hash) {
        var target = jQuery(window.location.hash.replace(/@/g, ''));

        if (target.length) {
            // Slight delay to ensure layout is ready
            setTimeout(function() {
                jQuery('html, body').scrollTop(target.offset().top - 80);
            }, 100); 
        }
    }

    function fixedMenu() {
        var windowWidth = jQuery(window).innerWidth();
    
        if(!jQuery("header").hasClass('simplified')) {
            if (windowWidth > 111) {
                if (jQuery(document).scrollTop() > 1) {
                    jQuery("header").addClass('scrolled');
                } else {
                    jQuery("header").removeClass('scrolled');
                }
        
        
                jQuery(window).on('load scroll resize orientationchange', function () {
                    if (jQuery(document).scrollTop() > 1) {
                        jQuery("header").addClass('scrolled');
                    } else {
                        jQuery("header").removeClass('scrolled');
                    }
                });
            } else {
                jQuery(window).on('load scroll resize orientationchange', function () {
                    jQuery("header").removeClass('scrolled');
                });
            }
        }
    }

    fixedMenu();
});