jQuery(document).ready(function ($) {

    $(document).on( 'nfFormReady', function() {
        //console.log('Form is ready');

        //add focus class to hidden checkbox
        jQuery('.checkbox-container .nf-field-element input[type=checkbox]').each(function(){
            console.log($(this).val());

            $(this).focus(function(){
                //console.log('focus!');
                $(this).parent('.nf-field-element').prev('.nf-field-label').addClass('focus');
            });

            $(this).focusout(function(){
                $(this).parent('.nf-field-element').prev('.nf-field-label').removeClass('focus');
            });
            
        });


        //toggle form on button click
        jQuery('.open_ed_form').each(function(){
            //$(this).next('.nf-form-cont').hide();
            //$(this).next('.nf-form-cont').hide();
            var link =  $(this).find('a');
            var href = link.attr('href');
            

            $(this).click(function(e){
                e.preventDefault();

                console.log(href);
                var section = $(href);
                section.removeClass('hidden');
                section.slideDown("fast");
                

                $('html, body').animate({
                    scrollTop: $(form).offset().top
                }, 2000);

            });
        });
    });



    jQuery(document).ready(function ($) {

        //open past events on Events page
        $('.open_past_events').click(function(e){
            e.preventDefault();
            $('tr.past_events').toggle();
            $(this).toggleClass('open');
            $(this).find('span').toggleText(main_js_strings.hide_past_events, main_js_strings.show_past_events);
            $(this).find('i').toggleClass('fa-angle-up').toggleClass('fa-angle-down');
        });


        //update URL of jobs page when toggle is opened
        var orig_baseUrl = window.location.href.split('#')[0];
        //console.log(orig_baseUrl);
        let stateObj = { id: "100" };

        $('#openpositions .faq_toggles .vc_toggle').click(function(e){
            e.preventDefault();
            //window.location.hash = "!";
            var hashtag = $(this).attr('id');

            if( $(this).hasClass('vc_toggle_active') ){ 
                //closing, remove hashtag
                window.history.pushState(stateObj, "Jobs", orig_baseUrl);
            } else {
                //reset
                window.history.pushState(stateObj, "Jobs", orig_baseUrl);
                window.location = window.location + '#' + hashtag;
            }

        });


    });


    


    jQuery('.iframe_noScrolling').each(function(){
        var iframe = $(this).find('iframe');
        iframe.css('pointer-events', 'none');
        $(this).click(function(){
            iframe.css('pointer-events', '');
        });
    });

    if (jQuery(window).width() > 767) {
        $('.iconboxes_carousel .nc_iconbox .description').each(function(index){
            var el = $(this);
            var this_height = el.height(); //51.2
    
            if (this_height > 51.2) {
                $(this).addClass('truncate');
                $("<div class='truncate_see_more'>"+main_js_strings.see_more+"</div>").insertAfter($(this));
            }
        });
    
        $('.truncate_see_more').on('click', function(){
            $(this).siblings('.description').removeClass('truncate');
            $(this).hide();
        });
    
    }

    


    //add clickable hashtag to elements to copy specific location
    $('.copy_element_link').hover(
        function(){
        $(this).append('<span class="copy_element_link_trigger" title="Copy URL">#</span>'); 
    }, function(){
        setTimeout(function() {
            $(this).find('.copy_element_link_trigger').remove(); 
        }, 500);
    }
    );

    $('.copy_element_link').on('click', '.copy_element_link_trigger' , function(){
        var el_location = '';
        var url = window.location.href;
        var my_url = url.split("#"); // remove any hashtags
        el_location = $(this).parent('.copy_element_link').attr('id');
        //console.log(el_location);
        
        navigator.clipboard.writeText(my_url[0]+'#'+el_location);
        $(this).append("<div class='copied'>"+main_js_strings.copied+"</div>");

        setTimeout(function() {
            $(this).find('.copied').remove();
        }, 1500);
    });


    //toggle table icons for the Pricing table
    $.fn.toggleHTML = function(t1, t2){
        if (this.html() == t1) this.html(t2);
        else                   this.html(t1);
        return this;
    };

    $('a.toggle_items').parents('tr').closest('tr').nextUntil('.category').hide();
    $('a.toggle_items').click(function(e){
        e.preventDefault();
        $(this).parents('tr').closest('tr').nextUntil('.category').toggle();
        $(this).toggleHTML(main_js_strings.see_more+' <i class="fa fa-angle-down"></i>', main_js_strings.see_less+' <i class="fa fa-angle-up"></i>');
    });


    var isUriImage = function(uri) {
        var uri, uri_splitted;
        if(uri) {
            //make sure we remove any nasty GET params
            var uri_splitted = uri.split('?')[0];

            //moving on now
            var parts = uri_splitted.split('.');
            var extension = parts[parts.length-1];
            var imageTypes = ['jpg','jpeg','tiff','png','gif','bmp']
            if(imageTypes.indexOf(extension) !== -1) {
                return true;   
            }
        }
    }


    var OSName="Unknown";
    if (navigator.appVersion.indexOf("Win")!=-1) OSName="winOS";
    if (navigator.appVersion.indexOf("Mac")!=-1) OSName="macOS";
    if (navigator.appVersion.indexOf("X11")!=-1) OSName="unixOS";
    if (navigator.appVersion.indexOf("Linux")!=-1) OSName="linuxOS";
    //console.log("OS name: "+OSName);

    $('#card-clients-btns .a-btn').each(function(){
        if($(this).hasClass(OSName)) {
            $(this).addClass('highlight');
        } else {
            $(this).addClass('outline');
        }
    });


    //restrict list to max 12 items
    $('ul.list_load_more li').hide().slice(0, 12).show();

    //add button load more
    $('ul.list_load_more').each(function(item){
        var listItems = $(this).children();
        if(listItems.length > 12) {
            $(this).parent().find('.list_load_more_btn').show();
        }
    });

    $('.mail_obf').each(function(){
        var s = $(this).data('email');
        var e = s.split("").reverse().join("");
        //$(this).append('<a href="mailto:'+email+'">'+email+'</a>');
        $(this).html('<a href="mailto:'+e+'">'+e+'</a>');
    });

    $('.list_load_more_btn').on('click',function(e){
        e.preventDefault();
        //console.log('test click');
        $(this).parents('.wpb_wrapper').find('ul.list_load_more li').each(function(index){
            $(this).css("display", "inline-block");
        });

        $(this).hide();
    });


    if(window.location.hash) {
        // Fragment exists
        var hash = window.location.hash;
        if($(hash).hasClass('vc_toggle')){
            $(hash).addClass('vc_toggle_active');
        }
    } else {
        // Fragment doesn't exist
    }


    $('.changelog_list li a').each(function(){
        $(this).attr('target', '_blank');
    });


    $('.nc_version .version_name span.copy_id').click(function(){
        var version_id = '';
        var url = window.location.href;
        var my_url = url.split("#"); // remove any hashtags
        version_id = $(this).parent('.version_name').attr('id');
        navigator.clipboard.writeText(my_url[0]+'#'+version_id);

        $(this).append("<div class='copied'>"+main_js_strings.copied+"</div>");

        setTimeout(function() {
            $('.copied').hide();
        }, 1500);

    });

    //add functionality to select continents and countries
    $('.region_select_list .continent.parent').each(function(){
        $(this).append('<div class="opener fa fa-angle-down"></div>');
    });

    $('.region_select_list li.parent .opener').click(function(){
        //$(this).toggleClass('fa-angle-up');
        $(this).siblings('ul').slideToggle();
        $(this).parents('li').toggleClass('opened');
    });

    $('.region_select_list li.parent > input[type="checkbox"]').change(function(){
        if(this.checked) {
            $(this).siblings('.children_countries').find('input[type="checkbox"]').prop( "checked", true );
        } else {
            $(this).siblings('.children_countries').find('input[type="checkbox"]').prop( "checked", false );
        }
    });


    //force download of images
    $('.image_download a').each(function(){
        $(this).attr('download','');
    });

    $('.wpb_video_wrapper').each(function(){
        if($(this).children('.video-wall').length > 0){
            $(this).addClass('with-iframe-blocker');
        }
    });


    $('.copy_color').click(function(){
        // Select the text field
        $(this).select();
        navigator.clipboard.writeText( $(this).text() );
        $(this).append("<div class='copied'>Copied!</div>");
        setTimeout(function() {
            $('.copied').remove();
        }, 2000);
    });


    //show buttons left right for moving the table when is not fully visible
    if( $('.comparison_table').width() > $('.comparison_table_container_inner').width() ) {
        $('.comparison_table_container_inner').addClass('innerShadow');

        $('.table_move_arrows').show();
    }


    //left right move animation of the table
    var box = $(".comparison_table_container_inner"), x;
    $(".arrow").click(function() {
        if ($(this).hasClass("arrow-right")) {
            x = ((box.width() / 2)) + box.scrollLeft();
            box.animate({
            scrollLeft: x,
            })
        } else {
            x = ((box.width() / 2)) - box.scrollLeft();
            box.animate({
            scrollLeft: -x,
            })
        }
    });



    //add dynamic css width to the td of the table header
    $(".platform_logos th").each(function(){
        //var padding = $(this).css('padding');
        //console.log("padding: "+parseFloat(padding));
        var width = $(this).width();
        //width += parseFloat(padding);
        console.log("width: "+width);
        $(this).css("width", width );
    });


    //add class to the tds where the th has been checked to be compared
    $(".platform_logos .check").click(function(){
        $('button.filter').attr('disabled', false);

        $(this).parent('th').toggleClass('td_selected');
        $(this).toggleClass('selected');
        //always select Nextcloud to compare with
        $('.comparison_table .platform_logos').find('th').eq(1).find('.check').addClass('selected');
        $('.comparison_table .platform_logos').find('th').eq(1).addClass('td_selected');
        

        var number_td = $(this).parent('th').index();
        console.log(number_td);
        $(this).parents('.comparison_table').children('tbody').find('tr:not(.colspan10)').each(function(item){
            $(this).find('td').eq(number_td).toggleClass("hightlighted td_selected");

            //always select the nextcloud column = column nr. 2
            $(this).find('td').eq(1).addClass("hightlighted td_selected");

        });
    });


    //add class filtered to the table on button click
    $("button.filter").click(function(){
        $('.comparison_table').addClass('filtered');
    });

    //reset table comparison on button click
    $("button.reset").click(function(){
        $('button.filter').attr('disabled', true);
        $('.comparison_table').removeClass('filtered');
        $('.check').removeClass("selected");
        $('.hightlighted').removeClass("hightlighted");
        $('.td_selected').removeClass("td_selected");
    });

    var animate_iconboxes = function(){

        $('.animated_iconboxes').each(function(list_index){
            
            var elementTop = $(this).offset().top;
            var elementBottom = elementTop + $(this).outerHeight();
            var viewportTop = $(window).scrollTop();
            var viewportBottom = viewportTop + $(window).height();

            if(elementBottom > viewportTop && elementTop < viewportBottom) {
                //$(this).children('li').addClass('show');

                $(this).find('.nc_iconbox').each(function(index){
                    $(this).addClass('show');
                    $(this).css({
                        'transition-delay' : (list_index + index * 0.2) + 's'
                    });
                });

            }
        });

    };
    animate_iconboxes();


    //animate list on scroll and when element is visible in the viewport
    $(window).scroll(function(){
        $('.animated_list').each(function(list_index){
            var elementTop = $(this).offset().top;
            var elementBottom = elementTop + $(this).outerHeight();
            var viewportTop = $(window).scrollTop();
            var viewportBottom = viewportTop + $(window).height();

            if(elementBottom > viewportTop && elementTop < viewportBottom) {
                //$(this).children('li').addClass('show');

                $(this).children('li').each(function(index){
                    $(this).addClass('show');
                    $(this).css({
                        'transition-delay' : (list_index + index * 0.2) + 's'
                    });
                });

            }
        });


        //animated_iconboxes > nc_iconbox animated when visible in the viewport
        animate_iconboxes();

    });


    //set language switcher short lang as text
    /*
    var curr_lang_a = $('.wpml-ls-current-language > a');
    var curr_lang = curr_lang_a.find('.wpml-ls-native').attr('lang');
    console.log(curr_lang);
    curr_lang_a.find('.wpml-ls-native').html(curr_lang);
    */



    $('.nextcloud-hub-accordion .product_tab').click(function(){
        var id = $(this).attr('id');
        var relative_preview_id = id + "_preview";  
        //console.log(relative_preview_id);
        $("#"+relative_preview_id).show();
        $("#"+relative_preview_id).siblings('.vc_row ').hide();
        
    });


    //.features_carousel product pages
    $('.features_carousel').owlCarousel({
        loop:false,
        autoplay: false,
        margin:30,
        dots: false,
        nav:true,
        //autoWidth:true,
        //stagePadding: 150,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:2
            },
            1000:{
                items:3
            }
        }
    });


    //.quotes_carousel product pages
    $('.quotes_carousel').owlCarousel({
        loop:false,
        autoplay: false,
        margin:15,
        dots: false,
        nav:true,
        responsive:{
            0:{
                items:1
            },
            800:{
                items:2
            },
            1000:{
                items:3
            }
        }
    });

    //iconboxes_carousel
    var iconboxes_carousel = $('.iconboxes_carousel');

    /*
    function owl_setHeights(){
        iconboxes_carousel.find('.description').each(function(index){
            console.log("item height test - "+index+" : "+$(this).height()); 
        });
    };
    owl_setHeights();
    */
    
    iconboxes_carousel.owlCarousel({
        loop:false,
        autoplay: true,
        margin:30,
        dots: false,
        nav: true,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:1
            },
            800:{
                items:2
            },
            1000:{
                items:2
            }
        },
        //onDragged: owl_stop_autoplay,
        autoplayHoverPause:true,
        //onInitialize: owl_setHeights
    });

    



    //testimonials_carousel
    $('.testimonials_carousel').owlCarousel({
        loop:false,
        autoplay: true,
        margin:30,
        dots: false,
        nav:false,
        //autoHeight: true,
        //autoWidth:true,
        //stagePadding: 150,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:1
            },
            800:{
                items:2
            },
            1000:{
                items:3
            }
        }
    });


    $('.simple_slider_slideshow a').magnificPopup({
        gallery: {
          enabled: true
        },
        type: 'image' // this is default type
    });

    //clients carousel
    $('.clients_carousel').owlCarousel({
            loop:true,
            autoplay: true,
            margin:30,
            dots: false,
            nav:true,
            //autoWidth:true,
            lazyLoad:true,
            stagePadding: 15,
            responsive:{
                0:{
                    items:1
                },
                300:{
                    items:2
                },
                600:{
                    items:3
                },
                800:{
                    items:5
                },
                1000:{
                    items:6
                }
            }
    });

    //add popup for single image
    $('.popup-screenshot').magnificPopup({
        type: 'image'
        // other options
    });

    //add popup for gallery
    $('.popup-screenshot-gal').magnificPopup({
        type: 'image',
        gallery: {
            enabled:true
        }
    });

    //click on gallery items will open a popup
    $('.wp-block-gallery:not(.no_popup)').each(function() { // the containers for all your galleries
        $(this).magnificPopup({
            delegate: 'a', // the selector for gallery item
            type: 'image',
            gallery: {
              enabled:true
            }
        });
    });

    //click on gallery items will open a popup
    $('.wp-block-image').each(function() { // the containers for all your galleries
        if(  isUriImage($(this).find('a').attr('href')) ) {
            $(this).magnificPopup({
                delegate: 'a', // the selector for gallery item
                type: 'image',
                gallery: {
                  enabled:true
                }
            });
        } else {
            //console.log('not an image');
        }
        
    });


    //click on gallery items will open a popup
    $('.wp-block-media-text .wp-block-media-text__media').each(function() { // the containers for all your galleries 
        //console.log($(this).find('a').attr('href'));
        
        if(  isUriImage($(this).find('a').attr('href')) ) {
            $(this).magnificPopup({
                delegate: 'a', // the selector for gallery item
                type: 'image',
                gallery: {
                  enabled:true
                }
            });
        } else {
            //console.log('not an image');
        }
        
        
    });


    $('.scroll_up').hide();
    //.scroll_up show
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) { // Wenn 100 Pixel gescrolled wurde
        $('.scroll_up').fadeIn();
        } else {
        $('.scroll_up').fadeOut();
        }
    });



    //smooth scroll
  	$(document).on('click', 'a.scroll_up', function(e) {
	    // target element id
	    //var id = $(this).attr('href');
        var id = "#hidden_header_anchor";
	    // target element
	    var $id = $(id);
	    if ($id.length === 0) {
	        return;
	    }
	    // prevent standard hash navigation (avoid blinking in IE)
	    e.preventDefault();
	    // top position relative to the document
	    var pos = $id.offset().top;
	    // animated top scrolling
	    $('body, html').animate({scrollTop: pos}, 800);
	});
    


    $('.links_carousel').owlCarousel({
                loop:true,
                margin:10,
                nav:true,
                autoWidth:true,
                stagePadding: 50,
                responsive:{
                    0:{
                        items:1
                    },
                    600:{
                        items:3
                    },
                    1000:{
                        items:5
                    }
                }
    });


    $('.open-popup-link').magnificPopup({
        delegate: 'a', // child items selector, by clicking on it popup will open
        type:'inline',
        midClick: true // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
    });


    $('.popup-video a, a.popup-video').magnificPopup({
        //disableOn: 700,
        type: 'iframe',
        callbacks: {
            open: function() {
                var iframe_content = $.magnificPopup.instance.content[0].childNodes[1];
                var popup_element = $.magnificPopup.instance.content[0];
                //console.log('Popup is opened');

                //if(!Cookies.get('nc_cookie_banner')){
                    replace_this_iframe(iframe_content, popup_element);
                //}
            },
        },
        iframe: {
            markup: '<div class="mfp-iframe-scaler">'+
            '<div class="mfp-close"></div>'+
            '<iframe id="" class="mfp-iframe" frameborder="0" allowfullscreen></iframe>'+
            '</div>', // HTML markup of popup, `mfp-close` will be replaced by the close button
            
            patterns: {
              youtube: {
                index: 'youtube.com/', // String that detects type of video (in this case YouTube). Simply via url.indexOf(index).
          
                id: 'v=', // String that splits URL in a two parts, second part should be %id%
                src: '//www.youtube.com/embed/%id%?autoplay=1' // URL that will be set as a source for iframe.
              },
              vimeo: {
                index: 'vimeo.com/',
                id: '/',
                src: '//player.vimeo.com/video/%id%?autoplay=1'
              },
              gmaps: {
                index: '//maps.google.',
                src: '%id%&output=embed'
              }
              // you may add here more sources
            },
            srcAction: 'iframe_src', // Templating object key. First part defines CSS selector, second attribute. "iframe_src" means: find "iframe" and set attribute "src".
        },
        

        mainClass: 'mfp-fade',
        removalDelay: 160,
        preloader: false,
        fixedContentPos: false
    });



    //sticky sidebar
    if ($(window).width() > 990) {

        $('#pricing_plans_head').stickySidebar({
            topSpacing: 94,
            bottomSpacing: 60
        });

        $('#request_quote_left_sidebar').stickySidebar({
            topSpacing: 115,
            bottomSpacing: 60
        });

        $('#advantages-left-sticky').stickySidebar({
            topSpacing: 60,
            bottomSpacing: 60
        });

        $('#thead_advantages').stickySidebar({
            topSpacing: 90,
            bottomSpacing: 60,
            innerWrapperSelector: "tr"
        });
     }

});



jQuery(document).ready(function () {
    fixedMenu();


    document.querySelectorAll('.menu-footer .menu-item-has-children > a').forEach(menuSection => {
        menuSection.setAttribute('role', 'heading');
        menuSection.setAttribute('aria-level', '2');
        //menuSection.removeAttribute('tab-index');
    })

    
    const menuItems = document.querySelectorAll('.primary-menu > .menu-item-has-children > a');
    Array.prototype.forEach.call(menuItems, function (el, i) {
        el.setAttribute('aria-haspopup', 'true');
        let timer;

        el.addEventListener("mouseover", function(){
            document.querySelector(".menu-item-has-children.open")?.classList.remove('open');
            el.parentElement.classList.add('open');
            el.setAttribute('aria-expanded', 'true');
            clearTimeout(timer);
        });
        el.addEventListener("focusin", function(){
            document.querySelector(".menu-item-has-children.open")?.classList.remove('open');
            el.parentElement.classList.add('open');
            el.setAttribute('aria-expanded', 'true');
            clearTimeout(timer);
        });
        el.addEventListener("mouseout", (event) => {
            timer = setTimeout((event) => {
                document.querySelector(".menu-item-has-children.open")?.classList.remove('open');
                el.setAttribute('aria-expanded', 'false');
            }, 250)
        });


        //console.log(el);
        el.addEventListener("focusout", (event) => {
            timer = setTimeout((event) => {

                //error is here.....
                var hasFocused = el.parentElement.contains(document.activeElement);
                //console.log("Active element: "+document.activeElement);

                if(!hasFocused) {
                    //if no focused elements inside the current parent, then close the submenu
                    document.querySelector(".menu-item-has-children.open")?.classList.remove('open');
                    el.setAttribute('aria-expanded', 'false');
                } else {
                    //console.log("There are elements focused inside el.");
                }
            }, 250)
        });

        el.addEventListener("click", function(event) {
            if (this.parentNode.className.includes('menu-item-has-children')) {
                document.querySelector(".menu-item-has-children.open")?.classList.remove('open')
                this.parentNode.classList.add('open')
                el.setAttribute('aria-expanded', 'true')
            } else {
                this.parentNode.classList.remove('open')
                el.setAttribute('aria-expanded', 'false')
            }
            event.preventDefault()
            return false
        });
    });


    jQuery(document).on('focusin', function (event) {
        var $target = jQuery(event.target);
        if (!$target.closest('.primary-menu').length) {
          //console.log('You focused outside of .primary-menu!');
          document.querySelector(".menu-item-has-children.open")?.classList.remove('open');
        }
    });




    jQuery(".phone-menu").click(function () {
        jQuery(this).toggleClass("change");

        if(jQuery('header').hasClass('active')){
            jQuery('.header-items').slideUp(300, function(){
                //animation complete    
                jQuery('header').removeClass('active');
            });
        } else {
            jQuery('header').addClass('active');
            jQuery('.header-items').slideDown(300, function(){
                //animation complete
            });
        }

    });



    
    var windowsize = jQuery(window).width();
    if (windowsize < 1200) {
        jQuery(".menu-item-has-children").click(function () {
                jQuery(this).siblings('li').children('.sub-menu').slideUp();
                jQuery(this).siblings('li').removeClass('mobile-submenu-open');
    
                jQuery(this).children('.sub-menu').slideToggle();
                jQuery(this).toggleClass('mobile-submenu-open');
            });
    }
    


    
    jQuery( window ).on( "resize", function() {
        var windowsize = jQuery(window).width();
        if (windowsize > 1200) {

            jQuery('.header-items').show();

            if(jQuery('header').hasClass('active')) {
                jQuery('header').removeClass('active');
            }

            if(jQuery('.phone-menu').hasClass('change')) {
                jQuery('.phone-menu').removeClass('change');
            }

        } else {
            if(jQuery('.phone-menu').hasClass('change')) {
                jQuery('header').addClass('active');
            } else {
                jQuery('.header-items').hide();
            }

        }

    } );
    


    jQuery('.card-btn').click(function () {
        var box = jQuery(this).parent('.accordion-card');
        box.toggleClass('active');
        box.find('.card-box').slideToggle();
        jQuery('.accordion-card').not(box).removeClass('active');
        jQuery('.accordion-card').not(box).find('.card-box').slideUp();
    });

    jQuery('a').click(function () {
        var href = jQuery(this).attr('href');
        if (href == '#trialModal') {
            jQuery('#trialModal').modal('show');
        } else {

        }
    });

    if (jQuery(window).width() < 991) {
        jQuery('.tab-buttons li button').click(function (){
            var targetPlan = jQuery(this).attr('id');
            jQuery('.tab-buttons li button').removeClass('active');
            jQuery(this).addClass('active');
            jQuery('.plan-holder').hide();
            jQuery(".plan-holder[data-plan='" + targetPlan +"']").show();
        });
    }

    jQuery('#trialModal').on('shown.bs.modal', function (e) {
        jQuery('.modal-slider').slick('setPosition');
        jQuery('.wrap-modal-slider').addClass('open');
    })

    jQuery('.video-modal').each(function () {
        var videoSrc = jQuery(this).find('iframe').attr("src");

        jQuery(this).on('show.bs.modal', function () {
            jQuery(this).find('iframe').attr("src", videoSrc + "&amp;autoplay=1");
        });
        jQuery(this).on('hidden.bs.modal', function (e) {
            jQuery(this).find('iframe').attr("src", null);
        });
    });

    jQuery('.card-head').click(function () {
        jQuery(this).parent('.card-block').toggleClass('active');
        jQuery(this).next('.card-main').slideToggle();
    });

    jQuery('#accordion .card').click(function () {
        jQuery('#accordion .card').not(this).removeClass('active');
        jQuery(this).toggleClass('active');
    });

    jQuery('#accordion2 .card').click(function () {
        jQuery('#accordion2 .card').not(this).removeClass('active');
        jQuery(this).toggleClass('active');
    });


    setTimeout(function() {
        jQuery('.post-holder').each(function() {
            var what = jQuery(this).find('.head').text()
            var file = jQuery(this).attr('data-file')
            jQuery(this).find('nf-field:nth-child(4) input').val(file).trigger('change')
            jQuery(this).find('nf-field:nth-child(5) input').val(what).trigger('change')
        });
    }, 1000);

    jQuery('a[href*="#"]')
        // Remove links that don't actually link to anything
        .not('[href="#"]')
        .not('[href="#no_scroll"]')
        .not('[href="#trialModal"]')
        .not('[href="#0"]')
        .not('[href="#hidden_header_anchor"]')
        .not('[href="#nextcloud_files_tab"]')
        .not('[href="#nextcloud_talk_tab"]')
        .not('[href="#nextcloud_groupware_tab"]')
        .not('[href="#nextcloud_office_tab"]')
        .not('.no_scroll')
        .not('[href="#nextcloud_outlook_integration_tab"]')
        .not('[href="#nextcloud_ad_integration_tab"]')
        .not('[href="#nextcloud_sharepoint_integration_tab"]')
        .not('[href="#nextcloud_teams_integration_tab"]')  
        .not('[href="#nextcloud_mail_tab"]')
        .not('[href="#nextcloud_calendar_tab"]')
        .not('[href="#nextcloud_contacts_tab"]')
        .not('[href="#nextcloud_deck_tab"]')
        .not('[href="#nextcloud_tab1"]')
        .not('[href="#nextcloud_tab2"]')
        .not('[href="#nextcloud_tab3"]')
        .not('[href="#nextcloud_tab4"]')
        .not('[data-vc-accordion]').click(function (event) {
            // On-page links
            //event.preventDefault();

            if (
                location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '')
                &&
                location.hostname == this.hostname
            ) {

                targetsArray = [
                    '#no_scroll',
                    '#conf-form-popup',
                    '#submit_proposal'
                ];

                // Figure out element to scroll to
                var target = jQuery(this.hash);
                target = target.length ? target : jQuery('[name=' + this.hash.slice(1) + ']');
                // Does a scroll target exist?
                if (target.length && jQuery.inArray(target, targetsArray) !== -1 ) {
                    // Only prevent default if animation is actually gonna happen
                    event.preventDefault();
                    jQuery('html, body').animate({
                        scrollTop: target.offset().top - 95
                    }, 1000, function () {
                        // Callback after animation
                        // Must change focus!
                        var $target = jQuery(target);
                        $target.focus();
                        if ($target.is(":focus")) { // Checking if the target was focused
                            return false;
                        } else {
                            //$target.attr('tabindex', '-1'); // Adding tabindex for elements not focusable
                            $target.focus(); // Set focus again
                        }
                        ;
                    });
                }
            }
        });



    if (jQuery('.needs-slider').width() > 1) {
        jQuery('.needs-slider').slick({
            infinite: false,
            slidesToShow: 3,
            slidesToScroll: 3,
            arrows: true,
            dots: false,
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    }
    if (jQuery('.more-slider').width() > 1) {
        jQuery('.more-slider').slick({
            infinite: false,
            slidesToShow: 3,
            slidesToScroll: 3,
            arrows: true,
            dots: false,
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    }
    //    if (jQuery('.modal-slider').width() > 1) {
    jQuery('.modal-slider').slick({
        infinite: false,
        slidesToShow: 2,
        slidesToScroll: 1,
        arrows: true,
        dots: false,
        responsive: [
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 1
                }
            }
        ]
    });
    //    }
    if (jQuery('.capabilities-slider').width() > 1) {
        jQuery('.capabilities-slider').slick({
            infinite: false,
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            dots: false,
            mobileFirst: true,
            responsive: [
                {
                    breakpoint: 992,
                    settings: "unslick"
                }
            ]
        });
    }
    if (jQuery('.enterprise-slider').width() > 1) {
        jQuery('.enterprise-slider').slick({
            infinite: false,
            slidesToShow: 3,
            slidesToScroll: 3,
            arrows: true,
            dots: false,
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    }
    if (jQuery('.benefits-slider').width() > 1) {
        jQuery('.benefits-slider').slick({
            infinite: false,
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            dots: false,
            mobileFirst: true,
            responsive: [
                {
                    breakpoint: 992,
                    settings: "unslick"
                }
            ]
        });
    }
    if (jQuery('.benefits2-slider').width() > 1) {
        jQuery('.benefits2-slider').slick({
            infinite: false,
            slidesToShow: 3,
            slidesToScroll: 3,
            arrows: true,
            dots: false,
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    }
    if (jQuery('.image-slider').width() > 1) {
        jQuery('.image-slider').slick({
            infinite: false,
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            dots: false,
            fade: true
        });
    }
    if (jQuery('.coll-slider').width() > 1) {
        jQuery('.coll-slider').slick({
            infinite: false,
            fade: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            dots: false
        });
        jQuery('.coll-slider').on('beforeChange', function (event, slick, currentSlide, nextSlide) {
            var current = jQuery(slick.$slides[currentSlide]);
            current.html(current.html());
        });
    }
    if (jQuery('.col-slider').width() > 1) {
        jQuery('.col-slider').slick({
            infinite: false,
            fade: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            dots: false
        });
        jQuery('.col-slider').on('beforeChange', function (event, slick, currentSlide, nextSlide) {
            var current = jQuery(slick.$slides[currentSlide]);
            current.html(current.html());
        });
    }

    
    if (jQuery('.related-slider').width() > 1) {
        jQuery('.related-slider').slick({
            infinite: false,
            slidesToShow: 3,
            slidesToScroll: 1,
            arrows: true,
            dots: false,
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    }
    if (jQuery('.testimonial-slider').width() > 1) {
        jQuery('.testimonial-slider').slick({
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 1,
            autoplay: true,
            arrows: true,
            dots: false,
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    }

    if (jQuery('.selection').width() > 1) {
        // Iterate over each select element
        jQuery('select').each(function () {

            // Cache the number of options
            var $this = jQuery(this),
                numberOfOptions = jQuery(this).children('option').length;

            // Hides the select element
            $this.addClass('s-hidden');

            // Wrap the select element in a div
            $this.wrap('<div class="select"></div>');

            // Insert a styled div to sit over the top of the hidden select element
            $this.after('<div class="styledSelect"></div>');

            // Cache the styled div
            var $styledSelect = $this.next('div.styledSelect');

            // Show the first select option in the styled div
            $styledSelect.text($this.children('option').eq(0).text());

            // Insert an unordered list after the styled div and also cache the list
            var $list = jQuery('<ul />', {
                'class': 'options'
            }).insertAfter($styledSelect);

            // Insert a list item into the unordered list for each select option
            for (var i = 0; i < numberOfOptions; i++) {
                jQuery('<li />', {
                    text: $this.children('option').eq(i).text(),
                    rel: $this.children('option').eq(i).val()
                }).appendTo($list);
            }

            // Cache the list items
            var $listItems = $list.children('li');

            // Show the unordered list when the styled div is clicked (also hides it if the div is clicked again)
            $styledSelect.click(function (e) {
                e.stopPropagation();
                jQuery('div.styledSelect.active').not(this).removeClass('active').next('ul.options').hide();
                jQuery(this).toggleClass('active').next('ul.options').toggle();
            });

            // Hides the unordered list when a list item is clicked and updates the styled div to show the selected list item
            // Updates the select element to have the value of the equivalent option
            $listItems.click(function (e) {
                e.stopPropagation();
                $styledSelect.text(jQuery(this).text()).removeClass('active');
                $this.val(jQuery(this).attr('rel'));
                $list.hide();
                if (jQuery('.order-form-section').width() > 1) {
                    doCalculation();
                }
                if (jQuery('.ionos-form-section').width() > 1) {
                    doCalculation2();
                }

                /* alert($this.val()); Uncomment this for demonstration! */
            });

            // Hides the unordered list when clicking outside of it
            jQuery(document).click(function () {
                $styledSelect.removeClass('active');
                $list.hide();
            });

        });
    }



    /*
    function resetFilter() {
    jQuery('#services').val('All services');
    jQuery('#services').data('value', 'all-dev');
    jQuery('#certificates').val('All levels');
    jQuery('#certificates').data('value', 'all-cert');
    jQuery('#country').val('All');
    jQuery('#country').data('value', 'all-comp');
    jQuery('input[type="checkbox"]').each(function () {
        jQuery(this).prop('checked', false);
    });
}


    var filter1 = 'all-dev';
    var filter2 = 'all-cert';
    var filter3 = 'all-comp';

    jQuery('#services').val('All services');
    jQuery('#certificates').val('All levels');
    jQuery('#country').val('All');

    jQuery('input[type="checkbox"]').each(function () {
        jQuery(this).prop('checked', false);
    });

    jQuery('#filtersearch').val('');

    jQuery('.tab-link').click(function () {
        var Panel = jQuery(this).attr('id');
        jQuery('.tab-link').removeClass('active');
        jQuery(this).addClass('active');
        jQuery('.custom-tab-panel').removeClass('active');
        jQuery('[data-panel="' + Panel + '"]').addClass('active');
        resetFilter();
        jQuery('#filtersearch').val('');
        jQuery('.partner-col').show();
        if (jQuery('#technology-tab').hasClass('active')) {
            jQuery('.filters-holder').slideUp();
        } else {
            jQuery('.filters-holder').slideDown();
        }
    });

    jQuery('.selection').click(function () {
        jQuery(this).toggleClass('active');
        jQuery('.selection').not(this).removeClass('active');
        var select = jQuery(this).next('.select-list');
        jQuery('.select-list').not(select).slideUp();
        select.slideToggle();
        select.focus();
    });

    jQuery('input[name="servi"]').change(function () {
        var textArray = '';
        var valArray = [];
        var count = jQuery('input[name="servi"]:checked').length;
        var i = 1;


        var selected_cb = jQuery(this).val();
        if(selected_cb == 'all-dev') {
            textArray = 'All services';
            valArray.push(selected_cb);

            jQuery('input[name="servi"]').prop('checked', false);
            jQuery('input[name="servi"][id="shk00"]').prop('checked', true);
            

        } else {

            //deselect All option when everything else is checked
            jQuery('input[name="servi"][id="shk00"]').prop('checked', false);

            jQuery('input[name="servi"]:checked').each(function () {
                var labeltext = jQuery(this).next('label').text();
                var labeltext2 = jQuery.trim( labeltext );
                var valuefilter = jQuery(this).val();
                
                

                valArray.push(valuefilter);

                if (count == i) {
                    textArray += labeltext2;
                } else {
                    textArray += labeltext2 + ', ';
                }
                i++;

                console.log("textArray: "+textArray);

            });

        }

        jQuery('#services').val(textArray);
        jQuery('#services').data('value', valArray);

        iniFilter();


        //close select menu when changing service input checkboxes
        jQuery(this).closest('.selection').toggleClass('active');
        jQuery(this).closest('.select-list').slideUp();
        //jQuery(this).closest('.select-list').focus();

    });


    //close menu selection when clicked outside
    jQuery('ul.select-list').blur(function() {
        console.log('select list blurred');
        //if(jQuery(this).css('display') == 'block') {
            jQuery(this).slideUp();
        //}
    });


    //close country select div when clicked outside of it
    var country_select = jQuery('#country_select');
    var region_select_list = country_select.find('.region_select_list');
    jQuery(document).click(function (event) {
        if (!country_select.is(event.target) && country_select.has(event.target).length === 0) {      
            //console.log('clicking outside the div');
            jQuery('.selection.active').removeClass('active');
            region_select_list.slideUp();
        }
    });


    //service_select
    var service_select = jQuery('#service_select');
    var service_select_list = service_select.find('.select-list');
    jQuery(document).click(function (event) {
        if (!service_select.is(event.target) && service_select.has(event.target).length === 0) {      
            //console.log('clicking outside the div');
            jQuery('.selection.active').removeClass('active');
            service_select_list.slideUp();
        }
    });
    


    jQuery('.cert-list li').click(function () {
        var filter2 = jQuery(this).data('certificate');
        jQuery(this).parent('.select-list').slideUp();
        jQuery(this).closest('.input-outer').find('.selection').removeClass('active');
        var value1 = jQuery(this).text();
        jQuery('#certificates').val(value1);
        jQuery('#certificates').data('value', filter2);
        iniFilter();
    });

    jQuery('input[name="country"]').change(function () {
        var textArray = '';
        var valArray = '';
        var valArray2 = [];
        var count = jQuery('input[name="country"]:checked').length;
        var i = 1;

        var selected_cb = jQuery(this).val();
        if(selected_cb == 'all-comp') {
            textArray = 'All';
            valArray = selected_cb;
            //valArray2.push(selected_cb);

            jQuery('#country').data('country_value_test', 'all-comp');

            jQuery('input[name="country"]').prop('checked', false);
            jQuery('input[name="country"][id="chk01"]').prop('checked', true);

        } else {

            //deselect All option when everything else is checked
            jQuery('input[name="country"][id="chk01"]').prop('checked', false);

            jQuery('input[name="country"]:checked').each(function () {
                var labeltext = jQuery(this).next('label').text();
                var valuefilter = jQuery(this).val();

                //console.log("valuefilter: "+valuefilter);
    
                    valArray += valuefilter + ',';
                    //valArray.push(valuefilter);

                    if (count == i) {
                            textArray += labeltext;
                    } else {
                            textArray += labeltext + ',';
                    }

                    valArray2.push(valuefilter);
    
                i++;
            });

            jQuery('#country').data('country_value_test', valArray2);

        }


        jQuery('#country').val(textArray);
        jQuery('#country').data('value', valArray);
        

        iniFilter();
    });

    jQuery.extend(jQuery.expr[":"], {
        "containsIN": function (elem, i, match, array) {
            return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
        }
    });

    jQuery('#filtersearch').keyup(function () {
        iniFilter();
    });
    */






    jQuery('#fieldname').change( function (){
        doCalculation2();
    });
    jQuery('#fieldemail').change( function (){
        doCalculation2();
    });
    jQuery('#fieldorg').change( function (){
        doCalculation2();
    });
    jQuery('#fieldphone').change( function (){
        doCalculation2();
    });
    jQuery('#fieldaddress').change( function (){
        doCalculation2();
    });
    jQuery('#fieldoutlook').change( function (){
        doCalculation2();
    });
    jQuery('#fieldterms').change( function (){
        doCalculation2();
    });
    jQuery('#fieldusers').change( function (){
        setUsers();
    });


    jQuery('#field1name').change( function (){
        doCalculation();
    });
    jQuery('#field1email').change( function (){
        doCalculation();
    });
    jQuery('#field1org').change( function (){
        doCalculation();
    });
    jQuery('#field1phone').change( function (){
        doCalculation();
    });
    jQuery('#field1address').change( function (){
        doCalculation();
    });
    jQuery('#edition').change( function (){
        doCalculation();
    });
    jQuery('#field1duration').change( function (){
        doCalculation();
    });
    jQuery('#field1edugov').change( function (){
        doCalculation();
    });
    jQuery('#field1outlook').change( function (){
        doCalculation();
    });
    jQuery('#collaboraCheck').change( function (){
        doCalculation();
    });
    jQuery('#onlyofficeCheck').change( function (){
        doCalculation();
    });
    jQuery('#field1terms').change( function (){
        doCalculation();
    });
    
    
    jQuery('#field1users').change( function (){
        setUsers();
    });

});



jQuery(window).scroll(function () {
    var cutoff = jQuery(window).scrollTop();
    jQuery('section').each(function () {
        if (jQuery(this).offset().top + jQuery(this).height() > cutoff) {
            var targetSection = jQuery(this).attr('id');
            jQuery('.page-nav a').removeClass('current-section');
            jQuery('.page-nav a[href="#' + targetSection + '"]').addClass('current-section');
            return false; // stops the iteration after the first one on screen
        }
    });
});




function fixedMenu() {
    var windowWidth = jQuery(window).innerWidth();
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


// Function definition with passing two arrays
// Function definition with passing two arrays
function findCommonElement(array1, array2) {
    // Loop for array1
    for(let i = 0; i < array1.length; i++) {
        
        // Loop for array2
        for(let j = 0; j < array2.length; j++) {
            
            // Compare the element of each and
            // every element from both of the
            // arrays
            if(array1[i] === array2[j]) {
            
                // Return if common element found
                return true;
            }
        }
    }
    // Return if no common element exist
    return false;
}



function iniFilter() {
    var filter1 = jQuery('#services').data('value');
    var filter2 = jQuery('#certificates').data('value');
    //var filter3 = jQuery('#country').data('value');
    //var filter3_array = filter3.split(',');
    //console.log("Countries searched: "+filter3_array);


    if(jQuery('#country').data('country_value_test')) {
        var filter3 = jQuery('#country').data('country_value_test');
    } else {
        var filter3 = 'all-comp';
    }

    var filter4 = jQuery('#filtersearch').val();

    if (jQuery.inArray("all-dev", filter1) !== -1 || filter1.length === 0) {
        var allDev = 1;
    }
    if (filter2 == 'all-cert') {
        filter2 = '';
    }
    if (filter3.includes('all-comp') || filter3 == '') {
        filter3 = '';
        //countries_included = true;
    }
    if(filter4 != '') {
        //jQuery('.partner-col:containsIN("' + filter4 + '")').show();
        //jQuery(this).containsIN(filter4);
        //console.log(filter4);
    }

    //console.log("test findCommonElement: "+findCommonElement(['south korea', 'Japan', 'China'], ['south korea', 'brazil'] ));

    jQuery('.partner-col').each(function () {
        var countries_included = false;

        var partner_id = jQuery(this).attr('id');
        var values = jQuery(this).data('type');
        var country = jQuery(this).data('country');
        var countries_of_this_partner_array = country.split(",");
        //console.log("countries_of_this_partner: "+countries_of_this_partner);


        if(filter3 != '') {
            if(findCommonElement(countries_of_this_partner_array, filter3)){
                countries_included = true;
           }
        } else {
            countries_included = true;
        }

        


        if (allDev == 1) { // if selected all services
            //console.log("All services are selected.");

            if (
                //values.includes(filter2) && countries_included && 
                jQuery(this).find('.partner-text').text().toLowerCase().match(filter4.toLowerCase()) 
                || 
                country.toLowerCase().match(filter4.toLowerCase())
            ) {
                jQuery(this).show();

            } else {
                jQuery(this).hide();
            }


        } else { // if selected specific serivce
            //console.log("Specific serivce is selected.");

            if (filter1.every(item => values.includes(item)) && values.includes(filter2) 
            && countries_included 
            && jQuery(this).find('.partner-text').text().toLowerCase().match(filter4)
            ) {
                jQuery(this).show();
            } else {
                jQuery(this).hide();
            }
        }
    });



}



// if we need to do something when the user number is changed...
function setUsers() {
    var theForm = document.forms["orderform"];
    // 			var includeCollaboraUsers = theForm.elements["collabora"];
    // 			var usersNumber = theForm.elements["users"];
    // 			var chosenEdition = theForm.elements["edition"];
    doCalculation();
}

function getUsersPrice() {
    var usersPrice = 1900;
    //Get a reference to the form id="orderform"
    var theForm = document.forms["orderform"];
    //Get a reference to the select id="users"
    var usersNumber = theForm.elements["users"];
    var chosenEdition = theForm.elements["edition"];
    var edugovDiscount = theForm.elements["edugov"];
    //set users price based on the number of users chosen and the edition. Yes, we could calculate this but that is complicated and it is easier updated as well this way.
    if (chosenEdition.value == "basic") {
        if (usersNumber.value == 50) {
            usersPrice = 1995;
        }
        if (usersNumber.value == "75") {
            usersPrice = 2782;
        }
        if (usersNumber.value == "100") {
            usersPrice = 3570;
        }
        if (usersNumber.value == "150") {
            usersPrice = 4620;
        }
        if (usersNumber.value == "200") {
            usersPrice = 5670;
        }
        if (usersNumber.value == "250") {
            usersPrice = 6720;
        }
    }
    if (chosenEdition.value == "standard") {
        if (usersNumber.value == "50") {
            usersPrice = 3604;
        }
        if (usersNumber.value == "75") {
            usersPrice = 5034;
        }
        if (usersNumber.value == "100") {
            usersPrice = 6466;
        }
        if (usersNumber.value == "150") {
            usersPrice = 8056;
        }
        if (usersNumber.value == "200") {
            usersPrice = 9646;
        }
        if (usersNumber.value == "250") {
            usersPrice = 11235;
        }
    }
    // apply multi-year discount and edu/gov/charity discount
    usersPrice = multiYearDiscount(edugovcharDiscount(usersPrice));

    //finally we return usersPrice
    return usersPrice;
}


// function to optionally apply educational discount. As the percentage varies, it has to be given to the formula.
function eduDiscount(amount, percentage) {
    //Get a reference to the form id="orderform", to education discount and duration
    var theForm = document.forms["orderform"];
    var discount = theForm.elements["edugov"];
    if (discount.value == "edu") {
        amount = amount * percentage;
    }
    return amount;
}

// function to apply the standard discounts over the three categories.
function edugovcharDiscount(amount) {
    //Get a reference to the form id="orderform", to education discount and duration
    var theForm = document.forms["orderform"];
    var discount = theForm.elements["edugov"];
    var chosenEdition = theForm.elements["edition"];

    if (chosenEdition.value == "standard") {
        if (discount.value != "no") {
            if (discount.value == "edu") {
                return amount *= 0.9;
            }
            if (discount.value == "gov") {
                return amount *= 0.9;
            }
            if (discount.value == "charity") {
                return amount *= 0.9;
            }
        }
    }
    return amount;
}

// function to optionally apply an equal discount percentage over any of the three categories.
function anyDiscount(amount, percentage) {
    //Get a reference to the form id="orderform", to education discount and duration
    var theForm = document.forms["orderform"];
    var discount = theForm.elements["edugov"];
    if (discount.value != "no") {
        amount = amount * percentage;
    }
    return amount;
}

// function to apply multi-year discount
function multiYearDiscount(price) {
    //Get a reference to the form id="orderform", to education discount and duration
    var theForm = document.forms["orderform"];
    var contractLength = theForm.elements["duration"];

    if (contractLength.value == 2) {
        return price *= 1.92;
    }
    else if (contractLength.value == 3) {
        return price *= 2.8;
    }
    else return price;
}

function getOptionsPrice() {
    var optionsPrice = 0;
    var collaboraPrice = 0;
    var outlookPrice = 0;
    var onlyofficePrice = 0;
    //Get a reference to the form id="orderform"
    var theForm = document.forms["orderform"];
    //Get a reference to the select id="users" and the other elements needed
    // 		    var includeCollaboraUsers = theForm.elements["collabora"];
    var includeCollaboraCheck = theForm.elements["collaboraCheck[]"];
    var includeOnlyofficeCheck = theForm.elements["onlyofficeCheck[]"];
    var includeOutlook = theForm.elements["outlook[]"];
    // 			var includeRemoteinstall = theForm.elements["remoteinstall"];
    // var includeBranding = theForm.elements["branding"];
    // var includeSpreed = theForm.elements["spreed"];
    var selectedUsersNumber = theForm.elements["users"];
    var chosenEdition = theForm.elements["edition"];
    var edugovDiscount = theForm.elements["edugov"];
    var contractLength = theForm.elements["duration"];
    //check if they are checked and if so, add the monies

    // collabora, Outlook and remote install only with Standard
    if (includeOutlook.checked == true) {
        outlookPrice = multiYearDiscount(selectedUsersNumber.value * 7.2);
        // apply edu/gov/charity discount
        outlookPrice = edugovcharDiscount(outlookPrice);
        optionsPrice = optionsPrice + outlookPrice;
    }
    if (chosenEdition.value !== "basic") {
        if (includeCollaboraCheck.checked == true) {
            // 					if( includeCollaboraUsers.value <= 100)
            if (selectedUsersNumber.value <= 100) {
                collaboraPrice = multiYearDiscount(selectedUsersNumber.value * 17);
            }
            else if (selectedUsersNumber.value > 99) {
                collaboraPrice = multiYearDiscount(1683 + (selectedUsersNumber.value - 99) * 16);
            }
            // apply edu discount (no gov, charity)
            collaboraPrice = eduDiscount(collaboraPrice, 0.25);
            optionsPrice = optionsPrice + collaboraPrice;
        }
        if (includeOnlyofficeCheck.checked == true) {
            optionsPrice = optionsPrice + (contractLength.value * 935);
        }
    }
    return optionsPrice;
}

function getTotal() {
    //Here we calculate, return and show the total price by calling our function

    // set variables
    var theForm = document.forms["orderform"];
    var contractLength = theForm.elements["duration"];
    var selectedUsersNumber = theForm.elements["users"];
    var inDollars = theForm.elements["dollars[]"];
    // 			var edugovDiscount = theForm.elements["edugov"];
    //Each function returns a number so by calling them we add the values they return together
    var finalPrice = getUsersPrice() + getOptionsPrice();
    var peruserfinalPrice = finalPrice / selectedUsersNumber.value / contractLength.value / 12;

    //display the result (dollars or euro's)
    if (inDollars.checked == false) {
        document.getElementById('totalprice').innerHTML = " € " + Math.round(finalPrice);
        document.getElementById('peruserprice').innerHTML = " € " + peruserfinalPrice.toPrecision(3);
    }
    if (inDollars.checked == true) {
        var finalPrice = finalPrice * 1.1;
        document.getElementById('totalprice').innerHTML = " $ " + Math.round(finalPrice);
        document.getElementById('peruserprice').innerHTML = " $ " + Math.round(peruserfinalPrice);
    }
    return +Math.round(finalPrice);
}
var firstCall = true;
function checkSubscription() {
    //disable optional features when basic subscription is chosen; enable submit button when terms are accepted
    var theForm = document.forms["orderform"];
    // 			var includeCollaboraUsers = theForm.elements["collabora"];
    var includeCollaboraCheck = theForm.elements["collaboraCheck[]"];
    var includeOnlyofficeCheck = theForm.elements["onlyofficeCheck[]"];
    var includeOutlook = theForm.elements["outlook[]"];
    // var includeBranding = theForm.elements["branding"];
    // var includeSpreed = theForm.elements["spreed"];
    var chosenEdition = theForm.elements["edition"];
    var agreedToTerms = theForm.elements["terms[]"];
    var submitButton = theForm.getElementsByClassName("wpcf7-submit");
    var selectedUsersNumber = theForm.elements["users"];
    // 			document.getElementById("collaboraUserNumberChoiceDiv").style.display = "none";
    // 			document.getElementById("getenterprisequote").style.display = "none";
    // disable them by default as they are blocked by the default basic subscription
    // 			includeCollaboraUsers.disabled = false;
    includeCollaboraCheck.disabled = false;
    includeOnlyofficeCheck.disabled = false;
    includeOutlook.disabled = false;
    // includeSpreed.disabled = false;
    // includeBranding.disabled = false;
    submitButton.disabled = true;
    // 			var numberOfCollaboraUsers = includeCollaboraUsers.value;
    var numberOfSelectedUsers = selectedUsersNumber.value;
    // 			document.getElementById("minUsers").style.fontWeight = "normal";
    // 			document.getElementById("maxUsers").style.fontWeight = "normal";

    // you can't have less collabora users than Nextcloud users
    // 			if(includeCollaboraCheck.checked==false)
    // 			{
    // 				includeCollaboraUsers.value = 0;
    // 			}
    // 			if(includeCollaboraCheck.checked==true)
    // 			{
    // 				document.getElementById("collaboraUserNumberChoiceDiv").style.display = "block";
    //
    // 				if(parseInt(numberOfCollaboraUsers) < 20) {
    // 					numberOfCollaboraUsers = numberOfSelectedUsers;
    // 				}
    //
    // 				if(((parseInt(numberOfCollaboraUsers) * 4) + 1) < parseInt(numberOfSelectedUsers))
    // 				{
    // 					numberOfCollaboraUsers  = numberOfSelectedUsers / 4;
    // 					document.getElementById("minUsers").style.fontWeight = "bold";
    // 					// handle the weird numbers
    // 					if (parseInt(numberOfCollaboraUsers) < 50)
    // 					{
    // 						numberOfCollaboraUsers = 50;
    // 					}
    // 					if (numberOfCollaboraUsers == "62.5")
    // 					{
    // 						numberOfCollaboraUsers = 75;
    // 					}
    // 				}
    //
    // 				if(parseInt(numberOfCollaboraUsers) > parseInt(numberOfSelectedUsers))
    // 				{
    // 					document.getElementById("maxUsers").style.fontWeight = "bold";
    // 					numberOfCollaboraUsers = numberOfSelectedUsers;
    //
    // 				}
    //
    // 				includeCollaboraUsers.value = numberOfCollaboraUsers;
    // 			}


    if (chosenEdition.value == "basic") {
        // 				includeCollaboraUsers.disabled = true;
        includeCollaboraCheck.disabled = true;
        includeOnlyofficeCheck.disabled = true;
        // 				includeOutlook.disabled = true;
        // includeSpreed.disabled = true;
        // includeBranding.disabled = true;
    }

    // you can't pick premium so we set it back to standard and show the info about asking sales for a quote.
    // 			if(chosenEdition.value=="premium")
    // 			{
    // 				document.getElementById("getenterprisequote").style.display = "block";
    // 				chosenEdition.value="standard";
    // 				alert("Sorry, you can't order the Premium Subscription through this form. Please ask our sales team for a quote.");
    // figure out how to zero the price
    // 			}

    if (firstCall) {
        firstCall = false;
        // don't show errors on very first call
        return;
    }


    var enableSubmitButton = true;

    // required form elements
    var yourName = theForm.elements["yourname"];
    var email = theForm.elements["email"];
    var organization = theForm.elements["organization"];
    var phone = theForm.elements["phone"];
    var address = theForm.elements["address"];

    if (yourName.value == '') {
        yourName.classList.add('error');
        enableSubmitButton = false;
    } else {
        var expression = /^[A-Za-z .'-]+$/,
            yourNameError = jQuery('#yourname-error');

        if (expression.test(yourName.value)) {
            yourName.classList.remove('error');
            yourNameError.html('');
        } else {
            var message = 'The name you entered does not appear to be valid.';
            yourNameError.html('<br />' + message);
            yourName.classList.add('error');
            enableSubmitButton = false;
        }
    }

    if (email.value == '') {
        email.classList.add('error');
        enableSubmitButton = false;
    } else {
        var expression = /^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,10}$/,
            emailError = jQuery('#email-error');

        if (expression.test(email.value)) {
            email.classList.remove('error');
            emailError.html('');
        } else {
            var message = 'The email address you entered does not appear to be valid.';
            emailError.html('<br />' + message);
            email.classList.add('error');
            enableSubmitButton = false;
        }
    }

    if (organization.value == '') {
        organization.classList.add('error');
        enableSubmitButton = false;
    } else {
        organization.classList.remove('error');
    }

    if (phone.value == '') {
        phone.classList.add('error');
        enableSubmitButton = false;
    } else {
        phone.classList.remove('error');
    }

    if (address.value == '') {
        address.classList.add('error');
        enableSubmitButton = false;
    } else {
        address.classList.remove('error');
    }

    // only when the terms are agreed to can you submit the form
    if (agreedToTerms.checked != true) {
        enableSubmitButton = false;
    }

    if (enableSubmitButton == true) {
        submitButton.disabled = "";
        jQuery('#form-error').addClass('hidden');
    } else {
        submitButton.disabled = "disabled";
        jQuery('#form-error').removeClass('hidden');
    }
}
// this function is called whenever the user changes any of the form values to re-calculate the price and disable or enable options.
function doCalculation() {
    checkSubscription();
    getTotal();
}
// this function listens to the submit event and adds the price to it before sending it out
jQuery('#orderform').submit(function (eventObj) { //listen to submit event
    var theForm = document.forms["orderform"];
    var inDollars = theForm.elements["dollars[]"];
    var includePrice = getTotal();
    jQuery(this).append('<input type="hidden" name="givenPrice" value="' + includePrice + '">');
    return true;
});

// if we need to do something when the user number is changed...
function setUsers2() {
    var theForm = document.forms["orderform"];
    // 			var includeCollaboraUsers = theForm.elements["collabora"];
    // 			var usersNumber = theForm.elements["users"];
    // 			var chosenEdition = theForm.elements["edition"];
    doCalculation2();
}

function getUsersPrice2() {
    var usersPrice = 9.5;
    //Get a reference to the form id="orderform"
    var theForm = document.forms["orderform"];
    //Get a reference to the select id="users"
    var usersNumber = theForm.elements["users"];
    var selectedUsersNumber = theForm.elements["users"];
    var edugovDiscount = theForm.elements["edugov"];
    //set users price based on the number of users chosen and the edition. Yes, we could calculate this but that is complicated and it is easier updated as well this way.
    //             console.log(selectedUsersNumber.value);
    usersPrice = usersNumber.value * 9.5;

    // apply multi-year discount and edu/gov/charity discount
    usersPrice = multiYearDiscount2(edugovcharDiscount2(usersPrice));

    //finally we return usersPrice
    // 		    console.log(usersPrice.value);
    return usersPrice;
}


// function to optionally apply educational discount. As the percentage varies, it has to be given to the formula.
// 		function eduDiscount(amount,percentage)
// 		{
// 			//Get a reference to the form id="orderform", to education discount and duration
// 			var theForm = document.forms["orderform"];
// 		    var discount = theForm.elements["edugov"];
// 		    if(discount.value=="edu")
// 			{
// 				amount = amount * percentage;
// 			}
// 			return amount;
// 		}

// function to apply the standard discounts over the three categories.
function edugovcharDiscount2(amount) {
    //Get a reference to the form id="orderform", to education discount and duration
    var theForm = document.forms["orderform"];
    var discount = theForm.elements["edugov"];
    var discount = "no";

    if (discount.value != "no") {
        if (discount.value == "edu") {
            return amount *= 0.9;
        }
        if (discount.value == "gov") {
            return amount *= 0.9;
        }
        if (discount.value == "charity") {
            return amount *= 0.9;
        }
    }
    return amount;
}

// function to optionally apply an equal discount percentage over any of the three categories.
function anyDiscount2(amount, percentage) {
    //Get a reference to the form id="orderform", to education discount and duration
    var theForm = document.forms["orderform"];
    var discount = theForm.elements["edugov"];
    var discount = "no";
    if (discount.value != "no") {
        amount = amount * percentage;
    }
    return amount;
}

// function to apply multi-year discount
function multiYearDiscount2(price) {
    //Get a reference to the form id="orderform", to education discount and duration
    var contractLength = 1;
    // 			var contractLength = theForm.elements["duration"];
    var theForm = document.forms["orderform"];
    if (contractLength.value == 2) {
        return price *= 1.90;
    }
    else if (contractLength.value == 3) {
        return price *= 2.75;
    }
    else return price;
}

function getOptionsPrice2() {
    var optionsPrice = 0;
    var outlookPrice = 0;
    //Get a reference to the form id="orderform"
    var theForm = document.forms["orderform"];
    //Get a reference to the select id="users" and the other elements needed
    // 		    var includeCollaboraUsers = theForm.elements["collabora"];
    var includeOutlook = theForm.elements["outlook[]"];
    // var includeBranding = theForm.elements["branding"];
    // var includeSpreed = theForm.elements["spreed"];
    var selectedUsersNumber = theForm.elements["users"];
    var chosenEdition = "standard";
    var edugovDiscount = theForm.elements["edugov"];
    //check if they are checked and if so, add the monies

    // collabora, Outlook and remote install only with Standard
    if (includeOutlook.checked == true) {
        outlookPrice = multiYearDiscount2(selectedUsersNumber.value * 0.6);
        // apply edu/gov/charity discount
        outlookPrice = edugovcharDiscount2(outlookPrice);
        optionsPrice = optionsPrice + outlookPrice;
    }
    return optionsPrice;
}

function round_to_precision2(x, precision) {
    var y = +x + (precision === undefined ? 0.5 : precision / 2);
    return y - (y % (precision === undefined ? 1 : +precision));
}
function getTotal2() {
    //Here we calculate, return and show the total price by calling our function

    // set variables
    var theForm = document.forms["orderform"];
    // 			var contractLength = theForm.elements["duration"];
    var inDollars = theForm.elements["dollars[]"];
    // 			var edugovDiscount = theForm.elements["edugov"];
    //Each function returns a number so by calling them we add the values they return together
    var finalPrice = round_to_precision2(getUsersPrice2() + getOptionsPrice2(), 0.5);
    var selectedUsersNumber = theForm.elements["users"];
    //display the result (dollars or euro's)
    // 			if(usersNumber<201) {
    if (inDollars.checked == false) {
        document.getElementById('totalprice').innerHTML = " € " + finalPrice.toFixed(2);
    }
    if (inDollars.checked == true) {
        var finalPrice = finalPrice * 1.1;
        document.getElementById('totalprice').innerHTML = " $ " + finalPrice.toFixed(2);
    }
    //             }
    console.log(selectedUsersNumber.value);
    if (selectedUsersNumber.value == "201") {
        document.getElementById('totalprice').innerHTML = "custom";
    }
    return +finalPrice.toFixed(2);
}
var firstCall = true;
function checkSubscription2() {
    //disable optional features when basic subscription is chosen; enable submit button when terms are accepted
    var theForm = document.forms["orderform"];
    // 			var includeCollaboraUsers = theForm.elements["collabora"];
    // 			var includeCollaboraCheck = theForm.elements["collaboraCheck"];
    var includeCollaboraCheck = false;
    // 			var includeOnlyofficeCheck = theForm.elements["onlyofficeCheck"];
    var includeOnlyofficeCheck = false;
    var includeOutlook = theForm.elements["outlook[]"];
    // var includeBranding = theForm.elements["branding"];
    // var includeSpreed = theForm.elements["spreed"];
    // 			var includeRemoteinstall = theForm.elements["remoteinstall"];
    var includeRemoteinstall = false;
    var chosenEdition = "standard";
    var agreedToTerms = theForm.elements["terms[]"];
    var submitButton = theForm.getElementsByClassName("wpcf7-submit");
    var selectedUsersNumber = theForm.elements["users"];
    // 			document.getElementById("collaboraUserNumberChoiceDiv").style.display = "none";
    // 			document.getElementById("getenterprisequote").style.display = "none";
    // disable them by default as they are blocked by the default basic subscription
    includeRemoteinstall.disabled = false;
    // 			includeCollaboraUsers.disabled = false;
    includeCollaboraCheck.disabled = false;
    includeOnlyofficeCheck.disabled = false;
    includeOutlook.disabled = false;
    // includeSpreed.disabled = false;
    // includeBranding.disabled = false;
    submitButton.disabled = true;
    // 			var numberOfCollaboraUsers = includeCollaboraUsers.value;
    var numberOfSelectedUsers = selectedUsersNumber.value;
    // 			document.getElementById("minUsers").style.fontWeight = "normal";
    // 			document.getElementById("maxUsers").style.fontWeight = "normal";

    // you can't have less collabora users than Nextcloud users
    // 			if(includeCollaboraCheck.checked==false)
    // 			{
    // 				includeCollaboraUsers.value = 0;
    // 			}
    // 			if(includeCollaboraCheck.checked==true)
    // 			{
    // 				document.getElementById("collaboraUserNumberChoiceDiv").style.display = "block";
    //
    // 				if(parseInt(numberOfCollaboraUsers) < 20) {
    // 					numberOfCollaboraUsers = numberOfSelectedUsers;
    // 				}
    //
    // 				if(((parseInt(numberOfCollaboraUsers) * 4) + 1) < parseInt(numberOfSelectedUsers))
    // 				{
    // 					numberOfCollaboraUsers  = numberOfSelectedUsers / 4;
    // 					document.getElementById("minUsers").style.fontWeight = "bold";
    // 					// handle the weird numbers
    // 					if (parseInt(numberOfCollaboraUsers) < 50)
    // 					{
    // 						numberOfCollaboraUsers = 50;
    // 					}
    // 					if (numberOfCollaboraUsers == "62.5")
    // 					{
    // 						numberOfCollaboraUsers = 75;
    // 					}
    // 				}
    //
    // 				if(parseInt(numberOfCollaboraUsers) > parseInt(numberOfSelectedUsers))
    // 				{
    // 					document.getElementById("maxUsers").style.fontWeight = "bold";
    // 					numberOfCollaboraUsers = numberOfSelectedUsers;
    //
    // 				}
    //
    // 				includeCollaboraUsers.value = numberOfCollaboraUsers;
    // 			}


    if (chosenEdition.value == "basic") {
        includeRemoteinstall.disabled = true;
        // 				includeCollaboraUsers.disabled = true;
        includeCollaboraCheck.disabled = true;
        includeOnlyofficeCheck.disabled = true;
        // 				includeOutlook.disabled = true;
        // includeSpreed.disabled = true;
        // includeBranding.disabled = true;
    }

    // you can't pick premium so we set it back to standard and show the info about asking sales for a quote.
    // 			if(chosenEdition.value=="premium")
    // 			{
    // 				document.getElementById("getenterprisequote").style.display = "block";
    // 				chosenEdition.value="standard";
    // 				alert("Sorry, you can't order the Premium Subscription through this form. Please ask our sales team for a quote.");
    // figure out how to zero the price
    // 			}

    if (firstCall) {
        firstCall = false;
        // don't show errors on very first call
        return;
    }


    var enableSubmitButton = true;

    // required form elements
    var yourName = theForm.elements["yourname"];
    var email = theForm.elements["email"];
    var organization = theForm.elements["organization"];
    var phone = theForm.elements["phone"];
    var address = theForm.elements["address"];

    if (yourName.value == '') {
        yourName.classList.add('error');
        enableSubmitButton = false;
    } else {
        var expression = /^[A-Za-z .'-]+$/,
            yourNameError = jQuery('#yourname-error');

        if (expression.test(yourName.value)) {
            yourName.classList.remove('error');
            yourNameError.html('');
        } else {
            var message = 'The name you entered does not appear to be valid. Please only use characters a-z and A-Z.';
            yourNameError.html('<br />' + message);
            yourName.classList.add('error');
            enableSubmitButton = false;
        }
    }

    if (email.value == '') {
        email.classList.add('error');
        enableSubmitButton = false;
    } else {
        var expression = /^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,10}$/,
            emailError = jQuery('#email-error');

        if (expression.test(email.value)) {
            email.classList.remove('error');
            emailError.html('');
        } else {
            var message = 'The email address you entered does not appear to be valid. Please only use characters a-z and A-Z and numbers.';
            emailError.html('<br />' + message);
            email.classList.add('error');
            enableSubmitButton = false;
        }
    }

    if (organization.value == '') {
        organization.classList.add('error');
        enableSubmitButton = false;
    } else {
        organization.classList.remove('error');
    }

    if (phone.value == '') {
        phone.classList.add('error');
        enableSubmitButton = false;
    } else {
        phone.classList.remove('error');
    }

    if (address.value == '') {
        address.classList.add('error');
        enableSubmitButton = false;
    } else {
        address.classList.remove('error');
    }

    // only when the terms are agreed to can you submit the form
    if (agreedToTerms.checked != true) {
        enableSubmitButton = false;
    }

    if (enableSubmitButton == true) {
        submitButton.disabled = "";
        jQuery('#form-error').addClass('hidden');
    } else {
        submitButton.disabled = "disabled";
        jQuery('#form-error').removeClass('hidden');
    }
}
// this function is called whenever the user changes any of the form values to re-calculate the price and disable or enable options.
function doCalculation2() {
    checkSubscription2();
    getTotal2();
}

// this function listens to the submit event and adds the price to it before sending it out
jQuery('.ionos-form-section #orderform').submit(function (eventObj) { //listen to submit event
    var theForm = document.forms["orderform"];
    var inDollars = theForm.elements["dollars[]"];
    var includePrice = getTotal2();
    jQuery(this).append('<input type="hidden" name="givenPrice" value="' + includePrice + '">');
    return true;
});