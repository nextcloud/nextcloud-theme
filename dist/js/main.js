function getUrlParameter(name) {
                name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
                var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
                var results = regex.exec(window.location.search);
                return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
}

function romanize(num) {
  var lookup = {M:1000,CM:900,D:500,CD:400,C:100,XC:90,L:50,XL:40,X:10,IX:9,V:5,IV:4,I:1},
      roman = '',
      i;
  for ( i in lookup ) {
    while ( num >= lookup[i] ) {
      roman += i;
      num -= lookup[i];
    }
  }
  return roman;
}


document.addEventListener("DOMContentLoaded", function () {

    //grid counters 
    const counters = document.querySelectorAll('.custom_grid_item.no-image h3');
    const iconboxes_counters = document.querySelectorAll('.iconboxes.counters .title');
    
    // Intersection Observer – detect element entering viewport
    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                animateCounter(el);
                obs.unobserve(el); // run once only
            }
        });
    }, {
        threshold: 0.3
    });

    counters.forEach(el => observer.observe(el));
    iconboxes_counters.forEach(el => observer.observe(el));


    function animateCounter(el) {
        const text = el.textContent.trim();

        // Matches: 123, 123+, 5k, 5k+, 32M, 32M+
        const match = text.match(/^(\d+(?:\.\d+)?)([kKmM])?(\+)?$/);

        if (!match) return;

        const target = parseFloat(match[1]);
        const suffix = match[2] || '';
        const hasPlus = !!match[3];

        el.textContent = '0';

        jQuery({ countNum: 0 }).animate(
            { countNum: target },
            {
                duration: 3500,
                easing: "swing",
                step: function () {
                    const value = Math.floor(this.countNum);
                    el.textContent = value + suffix + (hasPlus ? '+' : '');
                },
                complete: function () {
                    el.textContent = target + suffix + (hasPlus ? '+' : '');
                }
            }
        );
    }
});


//capabilities animation
document.addEventListener('DOMContentLoaded', function () {
        const originalList = document.querySelector(
            'ul.nc-list.white_boxes.alternate_rows_animated'
        );

        if (!originalList) return;

        const items = Array.from(originalList.children);
        const rows = [];
        let currentRow = [];
        let currentTop = null;

        // Detect visual rows from the original flex UL
        items.forEach(item => {
            const top = item.offsetTop;

            if (currentTop === null) {
            currentTop = top;
            }

            if (Math.abs(top - currentTop) > 5) {
            rows.push(currentRow);
            currentRow = [];
            currentTop = top;
            }

            currentRow.push(item);
        });

        if (currentRow.length) {
            rows.push(currentRow);
        }

        // Outer marquee wrapper
        const marquee = document.createElement('div');
        marquee.className = originalList.className;
        marquee.classList.add('capabilities-marquee');

        // Inner container for all rows
        const rowsContainer = document.createElement('div');
        rowsContainer.className = 'capabilities-track';

        rows.forEach((row, index) => {
            const rowEl = document.createElement('div');
            rowEl.className = 'capability-row';

            const track = document.createElement('div');
            track.className = 'capability-track';

            if (index % 2 !== 0) {
            track.classList.add('reverse');
            }

            const group = document.createElement('div');
            group.className = 'capability-group';

            row.forEach(item => {
            group.appendChild(item.cloneNode(true));
            });

            const groupClone = group.cloneNode(true);

            track.appendChild(group);
            track.appendChild(groupClone);

            rowEl.appendChild(track);
            rowsContainer.appendChild(rowEl);
        });

        marquee.appendChild(rowsContainer);

        originalList.after(marquee);
        originalList.style.display = 'none';
        });



jQuery(document).ready(function ($) {

    //add functionality on select - when side track tables are used
    $(document).on('change', '.table_switch', function () {
        var option = $(this).val();
        var $currentTable = $(this).parents('table.nc_event_agenda');

        var $otherTable = $currentTable
            .siblings('.nc_event_agenda')
            .filter('#' + option);

        // Switch tables
        $currentTable.addClass('hidden');
        $otherTable.removeClass('hidden');

        // Synchronize the select in the newly visible table
        $otherTable
            .find('.table_switch option[value="' + option + '"]')
            .prop('selected', true);
    });


    $(document).on('click', '.label_switch_track', function (e) {
        e.preventDefault();

        var option = $(this).attr('href').replace('#', '');
        var $currentTable = $(this).closest('table.nc_event_agenda');

        // Hide current table
        $currentTable.addClass('hidden');

        // Find table matching the target ID
        var $otherTable = $currentTable
            .siblings('.nc_event_agenda')
            .filter('#' + option)
            .removeClass('hidden');

        // Update selects
        $currentTable.find('.table_switch').val(option);
        $otherTable.find('.table_switch').val(option);
    });

    



    //show track sessions in the agenda element 
    $('.inner_tracks_row .tracks_dropdown').on('change', function () {
        var this_id = $(this).val();

        $(this)
            .siblings('.track_session')
            .removeClass('show')
            .addClass('hidden');

        $(this)
            .siblings('.track_session#' + this_id)
            .removeClass('hidden')
            .addClass('show');
    });



    //pricing tab functionality
    $('.btn_switch_pricing').first().addClass("active");
    $('.btn_switch_pricing a').click(function(e){
        e.preventDefault();

        var target = $(this).attr('href'); // "#talk"

        if (target) {
            window.location.hash = target;
        }

        $('.btn_switch_pricing').removeClass('active');
        $(this).closest('.btn_switch_pricing').addClass('active');

        $('.pricing_row').addClass('not_visible');
        $(target).removeClass('not_visible');
    });

    function activateFromHash(hash){
        if (hash && $(hash).length) {

            // Set active button
            $('.btn_switch_pricing').removeClass('active');
            $('.btn_switch_pricing a[href="' + hash + '"]')
                .closest('.btn_switch_pricing')
                .addClass('active');

            // Show correct section
            $('.pricing_row').addClass('not_visible');
            $(hash).removeClass('not_visible');

            // Scroll to it
            $('html, body').animate({
                scrollTop: $(hash).offset().top - 150
            }, 500);
        }
    }

    // Run on page load
    activateFromHash(window.location.hash);




    const element = $('.case-study-cta');
    if (element.length) {

        const parent = element.parent();
        const elementOffset = element.offset().top;
        const navbarHeight = parseFloat(getComputedStyle(document.documentElement).fontSize) * 8;
        
        function handleSticky() {
            // Disable on mobile
            if ($(window).width() < 768) {
            parent.css('padding-top', '0');
            element.removeClass('is-fixed').css({
                'position': 'static',
                'width': 'auto'
            });
            return;
            }
            
            const scrollPos = $(window).scrollTop();
            
            if (scrollPos >= (elementOffset - navbarHeight)) {
            if (!element.hasClass('is-fixed')) {
                const elementHeight = element.outerHeight();
                const elementWidth = parent.width();
                
                parent.css('padding-top', elementHeight + 'px');
                element.addClass('is-fixed').css({
                'position': 'fixed',
                'top': '8rem',
                'width': elementWidth
                });
            }
            } else {
            parent.css('padding-top', '0');
            element.removeClass('is-fixed').css({
                'position': 'static',
                'width': 'auto'
            });
            }
        }
        
        $(window).scroll(handleSticky);
        
        // Reset on resize
        $(window).resize(function() {
            handleSticky();
            if (element.hasClass('is-fixed') && $(window).width() >= 768) {
            element.css('width', parent.width());
            }
        });

    }



    //load more function on Changelog unsupported page (box links)
    let items = $('.iconboxes_releases .nc_iconbox.nc_compliant_box');
    let maxToShow = 8;
    // Initially hide all, show first 8
    items.hide();
    items.slice(0, maxToShow).show();

    $('#loadMore_releases a').on('click', function (e) {
        e.preventDefault();

        let currentlyVisible = items.filter(':visible').length;
        items.slice(currentlyVisible, currentlyVisible + maxToShow).slideDown();

        // Hide button if all items are visible
        if (currentlyVisible + maxToShow >= items.length) {
            $(this).hide();
        }
    }); 


    //ecosystem illustration animation
    //convert row into carousel for mobile
    if ($(window).width() < 800) {

        var nc_product_items = $('.row.nc_products').children().clone();
            // Empty the container
            $('.row.nc_products').empty();
            // Create carousel wrapper
            $('.row.nc_products').append('<div class="owl-carousel"></div>');
            // Add items inside Owl
            $('.row.nc_products .owl-carousel').append(nc_product_items);
            // Init Owl
            $('.row.nc_products .owl-carousel').owlCarousel({
                loop: false,
                margin: 10,
                nav: true,
                dots: false,
                responsive: {
                    0: { items: 1 },
                    600: { items: 2 }
                }
        });


        var bottom_integrations = $('.row.bottom_integrations').children().clone();
            // Empty the container
            $('.row.bottom_integrations').empty();
            // Create carousel wrapper
            $('.row.bottom_integrations').append('<div class="owl-carousel"></div>');
            // Add items inside Owl
            $('.row.bottom_integrations .owl-carousel').append(bottom_integrations);
            // Init Owl
            $('.row.bottom_integrations .owl-carousel').owlCarousel({
                loop: false,
                margin: 10,
                nav: true,
                dots: false,
                responsive: {
                    0: { items: 1 },
                    600: { items: 2 }
                }
        });


    }



    var instant_trial_cta = $('.nc_platform_logo .instant_trial_cta');
    var url = instant_trial_cta.attr('href');
    var products = [];

    $('.ecosystem_illustration .box:not(.nc_platform_logo):not(.appstore):not(.microsoft_integrations):not(.bottom_box)').click(function(e){
        var target = $(e.target);
        var product = $(this).data('product');
        var product_capitalized = product.charAt(0).toUpperCase() + product.slice(1);
        

        if (!target.is('.ecosystem_illustration .box .see_more')) {

            $(this).toggleClass('active');
            //take icon and clone it to the main container
            var icon = $(this).find('.icon').html();
            $('.main.nc_platform_logo').find('.logos_group').addClass('with_extra_icons');
            $('.main.nc_platform_logo').find('.logos_group').append('<div class="item '+product+'">'+icon+'</div>');

            if($(this).hasClass('sidebar')) {
                $(this).parents('.ecosystem_illustration').find('.main.nc_platform_logo').toggleClass('active');
            }

        }

        if(!$(this).hasClass('active')) {
            //removing
            //console.log("Has class active, so remove. "+product);
            $('.main.nc_platform_logo').find('.logos_group').find('div.item.'+product).remove();


            const index_item = products.indexOf(product_capitalized);
            if (index_item > -1) { // only splice array when item is found
                products.splice(index_item, 1); // 2nd parameter means remove one item only
                var products_text = products.join(',');
            }
            
        } else {
            products.push(product_capitalized);
            var products_text = products.join(',');
        }

        var length = $('.main.nc_platform_logo .logos_group').find('div.item:not(.logo)').length;
        
        var products_text = products.join(',');
        //console.log('Length: '+length);
        if( length != 0) {
            instant_trial_cta.removeClass('hidden');
            instant_trial_cta.attr('href', url+products_text);
        } else {
            instant_trial_cta.addClass('hidden');
        }

    });


    document.addEventListener('click', (e) => {
    const el = e.target.closest('.check[role="button"]');
    if (!el) return;
    toggleCheck(el);
    });

    document.addEventListener('keydown', (e) => {
    const el = e.target.closest('.check[role="button"]');
    if (!el) return;

    if (e.key === 'Enter') {
        e.preventDefault();
        el.click(); // simulate a real button press
    }

    if (e.key === ' ' || e.code === 'Space') {
        e.preventDefault(); // prevent scrolling
        // Do nothing here—wait for keyup to mimic native button behavior
    }
    });

    document.addEventListener('keyup', (e) => {
    const el = e.target.closest('.check[role="button"]');
    if (!el) return;

    if (e.key === ' ' || e.code === 'Space') {
        e.preventDefault();
        el.click(); // activate on keyup, like a native button
    }
    });





    //Ninja Forms related code
    $(document).on( 'nfFormReady', function(e, layoutView) {
        //console.log('Form is ready');
        var form_id = layoutView.model.id;

        //show related content after form submit on whitepapers, case studies, data sheets
        nfRadio.channel('forms').on('submit:response', function(form) {
            if( Array.isArray(form.errors) && form.errors.length == 0 ){
                if(form_id == 4) {
                    //show related content 
                    $('.single-case_studies, .single-whitepapers, .single-data_sheets').find('.related-posts-section .container').show();
                }
            }
        });

        //add focus class to hidden checkbox
        jQuery('.checkbox-container .nf-field-element input[type=checkbox]').each(function(){
            //console.log($(this).val());
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


    document.querySelectorAll('.ez-toc-section[id]').forEach(el => {
     el.setAttribute('ez-toc-data-id', '#' + el.id); // keep encoded, don’t decode
    });

        //$('[data-toggle="tooltip"]').tooltip('toggle');

        //hide related content on case studies, whitepapers, data sheets
        $('.single-case_studies, .single-whitepapers, .single-data_sheets').find('.related-posts-section .container').hide();
        

        //scroll inner track rows in the agenda
        $('.scroll-buttons #scroll-left').click(function() {
            $('.inner_tracks_row').animate({
              scrollLeft: '-=50'
            }, 300);
        });
          
        $('.scroll-buttons #scroll-right').click(function() {
            $('.inner_tracks_row').animate({
              scrollLeft: '+=50'
            }, 300);
        });


        //toggle next row on btn click
        $('.row_to_be_revealed').slideUp();
        $('.toggle_next_row a').click(function(e){
            e.preventDefault();
            var curr_html = 'Show agenda <i class="vc_btn3-icon fas fa-angle-down"></i>';
            var new_html = 'Hide agenda <i class="vc_btn3-icon fas fa-angle-up"></i>';
            $(this).toggleHTML(curr_html, new_html);
            $(this).parents('.vc_row').next('.vc_row.row_to_be_revealed').slideToggle();
        });


        $('blockquote.nc-letter-style').each(function(){
            if($(this).hasClass('truncated')) {
                $(this).append('<a href="#" title="Read more" class="letter_more_less_btn">Read more <i class="vc_btn3-icon fas fa-angle-down"></i></a>');
            }
        });

        $('blockquote.nc-letter-style a.letter_more_less_btn').click(function(e){
            e.preventDefault();
            $(this).parents('blockquote.nc-letter-style').toggleClass('truncated');

            if($(this).parents('blockquote.nc-letter-style').hasClass('truncated')) {
                $(this).html('Read more <i class="vc_btn3-icon fas fa-angle-down"></i>');
            } else {
                $(this).html('Read less <i class="vc_btn3-icon fas fa-angle-up"></i>');
            }
            
        });

        $('.hub-release-changelog .changelog-item .changelog-item-features.truncated').each(function(index){
            $(this).find('.release-changelog-see-more-btn').click(function(e){
                e.preventDefault();
                $(this).find('i').toggleClass('fa-angle-up');
                $(this).parents('.changelog-item-features').toggleClass('truncated');
            });
        });


        //open event session description
        $('.nc_event_agenda .track_sessions .session_see_more').click(function(){
            $(this).siblings('.session_description').toggleClass('details_closed');
        });


        $('.featured_label').unwrap();

        //open button dropdown - used on download page 
        $('.btn-dropdown').each(function(){
            $(this).children('.open_dropdown ').click(function(){
                $(this).parents('.btn-dropdown').siblings('.dropdown-menu').toggle();
                $(this).parents('.btn-dropdown').toggleClass("open");
                $(this).parents('.btn-dropdown').find('i').toggleClass("fa-angle-up");

                
                if($(this).parents('.btn-dropdown').hasClass('open')) {
                    $(this).parents('.vc_tta-panel').siblings().css('pointer-events', 'none');
                } else {
                    $(this).parents('.vc_tta-panel').siblings().css('pointer-events', 'all');
                }

            });
        });

        // CLOSE ON OUTER CLICK
        $(document).click(function (e) {
            // If the click is NOT inside .btn-dropdown or .dropdown-menu
            if (!$(e.target).closest('.btn-dropdown, .dropdown-menu').length) {

                // Close all dropdown menus
                $('.dropdown-menu').hide();
                $('.btn-dropdown').removeClass('open');
                $('.btn-dropdown i').removeClass("fa-angle-up");

                // Restore pointer events
                $('.vc_tta-panel').css('pointer-events', 'all');
            }
        });



        //add class automatically on jpgs
        $('.post-single-section').find('img').each(function(index){
            var url = $(this).attr('src');
            if( url.indexOf(".jpg") >= 0 || url.indexOf(".jpeg") >= 0 ){
                //console.log(url);
                if(!$(this).parents('.wp-block-image').hasClass('imageRoundShadow')) {
                    $(this).parents('.wp-block-image').addClass('imageRoundShadow');
                }
            }
            
        });

        //custom table of contents
        var all_h2s = $('.nc_custom_toc').parents('.text-block').find('h2');
        all_h2s.each(function(index){
            //$(this).attr('id', 'h_'+index);
            //$(this).prepend('<div class="" id="h_'+index+'"></div>');
            $( '<div class="" style="padding: 13px;" id="h_'+index+'"></div>' ).insertBefore( $(this) );
            var index2 = index+1;
            var roman_index = romanize(index2);
            $(this).prepend(roman_index + ". ");
        });

        //wrap the list items in links, for smooth scrolling
        $('.nc_custom_toc li').each(function(index){
            var list_item = $(this);
            //console.log("Heading: "+index+" : "+list_item.text());
            list_item.wrapInner('<a href="#h_'+index+'" class=""></a>');
        });







        $('select.users_select').change(function(){
            if($(this).val() == 200) {
                $('.nc_price.100_users').each(function(){
                    $(this).addClass('hidden');
                });
                $('.nc_price.200_users').each(function(){
                    $(this).removeClass('hidden');
                });
                $('.price_request_offer').each(function(){
                    $(this).addClass('hidden');
                });
                $('.price_meta').each(function(){
                    $(this).removeClass('hidden');
                });
                

            } else if ($(this).val() == 100) {
                $('.nc_price.200_users').each(function(){
                    $(this).addClass('hidden');
                });
                $('.nc_price.100_users').each(function(){
                    $(this).removeClass('hidden');
                });
                $('.price_request_offer').each(function(){
                    $(this).addClass('hidden');
                });
                $('.price_meta').each(function(){
                    $(this).removeClass('hidden');
                });
            } else {
                $('.price_request_offer').each(function(){
                    $(this).removeClass('hidden');
                });
                $('.nc_price').each(function(){
                    $(this).addClass('hidden');
                });
                $('.price_meta').each(function(){
                    $(this).addClass('hidden');
                });
            }
        });


        $('.switch_pricing').click(function(e){
            e.preventDefault();
            $(this).toggleClass('monthly yearly');
            var switch_pricing_btn = $(this);

            if($(this).hasClass('yearly')) { // show monthly
                switch_pricing_btn.text('Show yearly');
                $('.nc_price').each(function(){
                    $(this).text($(this).data('monthly'));
                });
            } else { //show yearly
                switch_pricing_btn.text('Show monthly');
                $('.nc_price').each(function(){
                    $(this).text($(this).data('yearly'));
                });
            }
        });


        $('.load_older_events a').each(function(){
            $(this).html(main_js_strings.show_older_years_events);
            if($(this).hasClass('open')) {
                $(this).append('<i class="vc_btn3-icon fas fa-angle-up"></i>');
            } else {
                $(this).append('<i class="vc_btn3-icon fas fa-angle-down"></i>');
            }   
        });


        $('#events_row').find('.vc_tta-panel-title span.vc_tta-title-text').each(function(){
            var events_title = main_js_strings.events;
            var year = $(this).text().match(/\d+/); // get numbers
            $(this).text(events_title+" "+year);
        });

        $('.older_events_accordion').hide();
        $('.load_older_events a').click(function(e){
            e.preventDefault();
            $('.older_events_accordion').toggle();
            $(this).toggleClass('open');
            $(this).toggleText(main_js_strings.hide_older_years_events, main_js_strings.show_older_years_events);
            if($(this).hasClass('open')) {
                $(this).append('<i class="vc_btn3-icon fas fa-angle-up"></i>');
            } else {
                $(this).append('<i class="vc_btn3-icon fas fa-angle-down"></i>');
            }

        });


        //open past events on Events page
        $('.open_past_events').click(function(e){
            e.preventDefault();
            $('tr.past_events').toggle();
            $(this).toggleClass('open');
            $(this).find('span').toggleText(main_js_strings.hide_past_events, main_js_strings.show_past_events+" "+$(this).data('year'));
            $(this).find('i').toggleClass('fa-angle-up').toggleClass('fa-angle-down');
        });


        //update URL of jobs page when toggle is opened
        var orig_baseUrl = window.location.href.split('#')[0];
        //console.log(orig_baseUrl);
        let stateObj = { id: "100" };

        $('.faq_toggles .vc_toggle').click(function(e){
            e.preventDefault();
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
        $('.iconboxes:not(.notTruncated)').each(function(){

            $(this).find('.iconboxes_carousel .nc_iconbox .description').each(function(index){
                var el = $(this);
                var this_height = el.height(); //102 = 4 rows; 25.6 = 1 row
        
                if (this_height > 127) {
                    $(this).addClass('truncate');
                    $(this).parents('.iconbox_container').addClass('truncated');
                    $("<div class='truncate_see_more'>"+main_js_strings.see_more+"</div>").insertAfter($(this));
                }
            });

        });

        //show more of truncated text
        $('.truncate_see_more').on('click', function(){
            $(this).siblings('.description').removeClass('truncate');
            $(this).hide();
        });

        //show more of partners text
        $('.partner-box .see_more').on('click', function(){
            $(this).siblings('.partner-text').removeClass('truncated');
            $(this).hide();
        });
    
    }


    // truncate functionality for timeline carousel
    $(this).find('.truncate .timeline-carousel .event p').each(function(index){
        $(this).addClass('truncate');
        $("<div class='truncate_see_more'>"+main_js_strings.see_more+"</div>").insertAfter($(this));
    });
    $('.truncate .timeline-carousel .truncate_see_more').on('click', function(){
            $(this).siblings('p').removeClass('truncate');
            $(this).hide();
    });

    

    // show extra content of NC ecosystem illustration
    $('.box .see_more').on('click', function(){
        //$(this).siblings('.inner_content').removeClass('hidden');
        $(this).siblings('.description').removeClass('hidden');
        $(this).siblings('.learn_more').removeClass('hidden');
        $(this).siblings('.extension_box').removeClass('hidden');
        $(this).parent('.inner_box').siblings('.outer_extension_box').removeClass('hidden');

        //ms_apps
        $(this).siblings('.ms_apps').removeClass('hidden');
        //appstore_grid
        $(this).siblings('.appstore_grid').removeClass('hidden');
        //row_container
        $(this).siblings('.row_container').removeClass('hidden');
        $(this).hide();
    });


    //show full partner content on See more button click
    $('.partner-box .see_more').on('click', function(){
        $(this).siblings('.description').removeClass('truncated');
        $(this).hide();
    });


    //add bg image dynamically on feature_iconbox
    $('.feature_iconbox.with_bg_image').each(function(){
        var imgSrc = $(this).find('img').attr('src');
        $(this).find('.iconbox_container').css('background-image', 'url(' + imgSrc + ')');
    });
    

    //make row iconboxes fixed on scroll - used as extra top menu for navigation on the Developers page
    if ($(window).width() > 991 && $('.fixed_row_iconboxes').length) {

        var $fixed_row_iconboxes = $('.fixed_row_iconboxes');
        var divTop = $fixed_row_iconboxes.offset().top;
        var topOffset = 95;
        var $container_width = $fixed_row_iconboxes.parent('.container').width();
        var $container_height = $fixed_row_iconboxes.parent('.container').height();

        $(window).on('scroll', function () {
            if ($(window).scrollTop() + topOffset >= divTop) {
                $fixed_row_iconboxes.addClass('fixed');
                $fixed_row_iconboxes.css('width', $container_width);
                $fixed_row_iconboxes.parent('.container').css('height', $container_height);
            } else {
                $fixed_row_iconboxes.removeClass('fixed');
                $fixed_row_iconboxes.css('width', 'auto');
                $fixed_row_iconboxes.css('height', 'auto');
            }
        });

        //add scroll spy
        $(function () {
            var $links = $('.fixed_row_iconboxes .feature_iconbox a');
            var topOffset = 150;

            $(window).on('scroll', function () {
                var scrollPos = $(window).scrollTop() + topOffset + 1;

                $links.each(function () {
                var $link = $(this);
                var target = $($link.attr('href'));

                if (
                    target.length &&
                    target.offset().top <= scrollPos &&
                    target.offset().top + target.outerHeight() > scrollPos
                ) {
                    $links.removeClass('active');
                    $link.addClass('active');
                }
                });
            });
        });


    }


    // Add clickable hashtag to copy a specific section URL
    $('.copy_element_link').each(function () {
        const $container = $(this);
        const $headings = $container.find('h2, h3:not(.subtitle), h4');

        $headings.each(function () {
            const $heading = $(this);

            if ($heading.css('text-align') === 'center') {
                $heading.addClass('widthFitContent marginAuto');
            }

            // Add the trigger only once
            if (!$heading.find('.copy_element_link_trigger').length) {
                $('<span>', {
                    class: 'copy_element_link_trigger',
                    title: main_js_strings.copy_url,
                    text: '#'
                })
                    .hide()
                    .appendTo($heading);
            }
        });

        const $triggers = $headings.find('.copy_element_link_trigger');

        $container.on('mouseenter', function () {
            $triggers
                .stop(true, true)
                .fadeIn(150);
        });

        $container.on('mouseleave', function () {
            $triggers
                .stop(true, true)
                .delay(500)
                .fadeOut(150);
        });
    });



    //copy hash location of custom heading
    $('.copy_element_link').on('click', '.copy_element_link_trigger' , function(){
        var el_location = '';
        var url = window.location.href;
        var my_url = url.split("#"); // remove any hashtags
        el_location = $(this).parents('.copy_element_link').attr('id');
        //console.log('Location copied: '+el_location);
        //console.log($(this).parents('.wpb_column').length);

        if($(this).parents('.wpb_column').hasClass('copy_element_link')) {
            el_location = $(this).parents('.wpb_column').attr('id');
        }
        else if($(this).parents().hasClass('copy_feature')) {
            el_location = $(this).parents('.nc_feature').attr('id');
        }
        else
        if($(this).parents().hasClass('vc_section')) {
            el_location = $(this).parents('section').attr('id');
        }
        
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


    $('.desktop-apps-buttons .vc_do_btn').each(function(){
        if($(this).hasClass(OSName)) {
            $(this).children('a').addClass('highlight');
            $(this).addClass('btn-main');
            $(this).removeClass('btn-outline');
            
        } else {
            $(this).removeClass('btn-main');
            $(this).addClass('btn-outline');
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


    //obfuscate email address
    $('.mail_obf').each(function(){
        var s = $(this).data('email');
        var e = s.split("").reverse().join("");
        //$(this).append('<a href="mailto:'+email+'">'+email+'</a>');
        $(this).html('<a href="mailto:'+e+'">'+e+'</a>');
    });

    $('a[data-email]').each(function () {
        this.href = 'mailto:' + $(this).attr('data-email').replace('[at]', '@').replace(/\[dot]/g, '.');
        this.innerHTML = this.href.replace('mailto:', '');
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
        //var hash = window.location.hash;
        var hash = window.location.hash.replace(/@/g, '');

        if($(hash).hasClass('vc_toggle')){
            $(hash).addClass('vc_toggle_active');
        }
    } else {
        // Fragment doesn't exist
    }


    $('.changelog_list li a').each(function(){
        $(this).attr('target', '_blank');
    });


    $('.nc_version.withdrawn .version-infos a').each(function(){
        $(this).addClass("disabled");
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
    $(".arrow").click(function(e) {
        e.preventDefault();

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


    $("table td").each(function(){
        if ($(this).find(".tooltip").length > 0) {
            $(this).addClass("has_tooltip");

            $(this).append('<span class="show_tooltip">'+main_js_strings.see_more+'</span>')
        }
    });

    //add dynamic css width to the td of the table header
    $(".platform_logos th").each(function(){
        var width = $(this).width();
        //console.log("width: "+width);
        $(this).css("width", width );
    });



    $('.comparison_table_container').each(function(index){
        var comparison_table_container = $(this);
        comparison_table_container.find('button.reset').attr('disabled', true);


        //add class to the tds where the th has been checked to be compared
        comparison_table_container.find(".platform_logos .check").click(function(){
            comparison_table_container.find('button.filter').attr('disabled', false);
            comparison_table_container.find('button.reset').attr('disabled', false);


            $(this).parent('th').toggleClass('td_selected');
            $(this).toggleClass('selected');

            //always select Nextcloud to compare with
            // $('.comparison_table .platform_logos').find('th').eq(1).find('.check').addClass('selected');
            // $('.comparison_table .platform_logos').find('th').eq(1).addClass('td_selected');
            comparison_table_container.find('.platform_logos').find('th').eq(1).find('.check').addClass('selected');
            comparison_table_container.find('.platform_logos').find('th').eq(1).addClass('td_selected');

            var number_td = $(this).parent('th').index();
            console.log(number_td);
            $(this).parents('.comparison_table').children('tbody').find('tr:not(.colspan10)').each(function(item){
                $(this).find('td').eq(number_td).toggleClass("hightlighted td_selected");

                //always select the nextcloud column = column nr. 2
                $(this).find('td').eq(1).addClass("hightlighted td_selected");
            });
        });


        //add class filtered to the table on button click
        comparison_table_container.find("button.filter").click(function(){
            comparison_table_container.find('.comparison_table').addClass('filtered');
        });

        //reset table comparison on button click
        comparison_table_container.find("button.reset").click(function(){
            $('button.filter').attr('disabled', true);
            comparison_table_container.find('.comparison_table').removeClass('filtered');
            comparison_table_container.find('.check').removeClass("selected");
            comparison_table_container.find('.hightlighted').removeClass("hightlighted");
            comparison_table_container.find('.td_selected').removeClass("td_selected");
        });


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


    var $nextcloud_release_overview = $('.nextcloud_release_overview');
    $nextcloud_release_overview.each(function(){
        var $videos = $(this).find('.release_video');
        $videos.removeClass('active not-active')   // reset (optional but safe)
        .first().addClass('active')         // first = active
        .end().not(':first').addClass('not-active'); // rest = not-active
    });



    //tab view functionality on the blog 
    $('.post_single_container .release_video_tab:not(.vc_active) button').click(function(){
        var $this = $(this);
        $this.parents('.release_video_tab').toggleClass('vc_active');
        $this.parents('.release_video_tab').siblings().toggleClass('vc_active');
    });



    $(document).on('click', '.nextcloud_release_overview .release_video.not-active', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation(); // stops magnific handler
        var $this = $(this);

        $this.siblings('.release_video').toggleClass('active not-active');
        $this.toggleClass('active not-active');

        $('.nextcloud-hub-accordion .product_tab').toggleClass('vc_active');

        var index = $('.nextcloud_release_overview .release_video').index(this);
        $('nextcloud-hub-accordion .product_tab').eq(index).css('height', 'auto');

    });


    //.product_tab
    $('.nextcloud-hub-accordion .tab_link_button').click(function(){
        //var id = $(this).attr('id');
        var id = $(this).parents('.vc_tta-panel').attr('id');
        var relative_preview_id = id + "_preview";  
        
        if( $(this).parents('.vc_tta-panel').hasClass('release_video_tab') ) {
            $('.nextcloud_release_overview .nc_video_preview_box').each(function () {
                var $this = $(this);
                $this.toggleClass('active not-active');
            });
            
        } else {
            //default behaviour
            $("#"+relative_preview_id).show();
            $("#"+relative_preview_id).siblings('.vc_row ').hide();

        }

    });


    //livestream tabs view
    $('.livestream_row').each(function(index){
        /*
        if(!$(this).hasClass('opened')) {
            $(this).hide();
        }
        */
        
        if(index != 0) {
            $(this).hide();
        }
        
    });
    $('.nc_vertical_tabs li:first-child').addClass("opened");

    $('.nc_vertical_tabs .vertical_tab_link').click(function(e){
        e.preventDefault();

        var id = $(this).attr('href');
        var relative_preview_id = id;  
        
        var li_parent = $(this).parents('li');
        if(li_parent.hasClass("opened")){
            li_parent.removeClass("opened");
        } else {
            li_parent.siblings().removeClass("opened");
            li_parent.addClass("opened");
        }

        //console.log(relative_preview_id);
        $(relative_preview_id).show();
        $(relative_preview_id).siblings('.vc_row ').hide();
    });



    //.features_carousel product pages
    /*
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
    */


    //related-slider
    $('.related-slider').owlCarousel({
        loop:false,
        autoplay: false,
        margin:30,
        dots: false,
        nav:true,
        navText : ['<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 14.299 22.409"><path fill="#0082c9" class="st0" d="M11.25,21.205L1.25,11.205,11.25,1.205l1.8,1.8L4.85,11.205l8.2,8.2-1.8,1.8h0Z"/></svg>','<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 14.299 22.409"><path fill="#0082c9" class="st0" d="M3.05,21.205l-1.8-1.8,8.2-8.2L1.25,3.005l1.8-1.8,10,10L3.05,21.205h0Z"/></svg>'],
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




    //make the Owl carousel scrollable on trackpad
    let scrollAmount = 0;
    let wheelLocked = false;
    $('.owl-carousel').on('wheel', function(e) {
        const event = e.originalEvent;

        // Let normal vertical scrolling continue
        if (Math.abs(event.deltaY) > Math.abs(event.deltaX)) {
            return;
        }

        e.preventDefault();

        if (wheelLocked) return;

        scrollAmount += event.deltaX;

        // Increase this value to require a longer swipe
        const threshold = 220;

        if (Math.abs(scrollAmount) >= threshold) {

            wheelLocked = true;

            if (scrollAmount > 0) {
                $(this).trigger('next.owl.carousel');
            } else {
                $(this).trigger('prev.owl.carousel');
            }

            scrollAmount = 0;

            setTimeout(() => {
                wheelLocked = false;
            }, 450); // slightly longer than smartSpeed
        }
    });



    //.single_quotes_carousel
    var single_quotes_carousel = $('.single_quotes_carousel').owlCarousel({
        //loop:true,
        autoplay: true,
        autoplayTimeout: 3500,
        onDragged: owl_stop_autoplay,
        autoplayHoverPause:true,
        margin:15,
        dots: false,
        nav:true,
        navText : ['<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 14.299 22.409"><path fill="#0082c9" class="st0" d="M11.25,21.205L1.25,11.205,11.25,1.205l1.8,1.8L4.85,11.205l8.2,8.2-1.8,1.8h0Z"/></svg>','<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 14.299 22.409"><path fill="#0082c9" class="st0" d="M3.05,21.205l-1.8-1.8,8.2-8.2L1.25,3.005l1.8-1.8,10,10L3.05,21.205h0Z"/></svg>'],
        responsive:{
            0:{
                items:1
            },
            800:{
                items:1
            },
            1000:{
                items: 1
            }
        },
        rewind: true
    });

    single_quotes_carousel.on('click', function(e) {
        single_quotes_carousel.trigger('stop.owl.autoplay');
    });

    function owl_stop_autoplay() {
        //console.log('autoplay stopped.');
        single_quotes_carousel.trigger('stop.owl.autoplay');
    }



    //.quotes_carousel product pages
    var items_desktop = 3;
    if($('.owl_quotes_carousel').data('items-desktop')) {
        items_desktop = $('.owl_quotes_carousel').data('items-desktop');
    }
    $('.owl_quotes_carousel').owlCarousel({
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
                items: items_desktop
            }
        }
    });


    //.quotes_carousel product pages
    var items_desktop = 3;
    if($('.simple_images_carousel').data('items-desktop')) {
        items_desktop = $('.simple_images_carousel').data('items-desktop');
    }
    $('.simple_images_carousel').owlCarousel({
        loop:false,
        autoplay: false,
        margin:15,
        dots: false,
        nav:true,
        navText : ['<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 14.299 22.409"><path fill="#0082c9" class="st0" d="M11.25,21.205L1.25,11.205,11.25,1.205l1.8,1.8L4.85,11.205l8.2,8.2-1.8,1.8h0Z"/></svg>','<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 14.299 22.409"><path fill="#0082c9" class="st0" d="M3.05,21.205l-1.8-1.8,8.2-8.2L1.25,3.005l1.8-1.8,10,10L3.05,21.205h0Z"/></svg>'],
        responsive:{
            0:{
                items:1
            },
            800:{
                items:2
            },
            1000:{
                items: items_desktop
            }
        }
    });




    //iconboxes_carousel
    var iconboxes_carousel = $('.iconboxes_carousel');
    iconboxes_carousel.each(function(index){
        var items_desktop = 2;
        //var this_carousel = $(this);

        if($(this).data('items-desktop')) {
            items_desktop = $(this).data('items-desktop');
        }

        var this_carousel = $(this).owlCarousel({
            loop:false,
            autoplay: false,
            margin:30,
            dots: false,
            onDragged: this_carousel_owl_stop_autoplay,
            nav: true,
            navText : ['<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 14.299 22.409"><path fill="#0082c9" class="st0" d="M11.25,21.205L1.25,11.205,11.25,1.205l1.8,1.8L4.85,11.205l8.2,8.2-1.8,1.8h0Z"/></svg>','<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 14.299 22.409"><path fill="#0082c9" class="st0" d="M3.05,21.205l-1.8-1.8,8.2-8.2L1.25,3.005l1.8-1.8,10,10L3.05,21.205h0Z"/></svg>'],
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
                    items: items_desktop
                }
            },
            autoplayHoverPause:true,
        });

        function this_carousel_owl_stop_autoplay() {
            //console.log('autoplay stopped.');
            this_carousel.trigger('stop.owl.autoplay');
        }

        this_carousel.on('click', function(e) {
            this_carousel.trigger('stop.owl.autoplay');
        });

    });

    

    



    //testimonials_carousel
    var testimonials_carousel = $('.testimonials_carousel').owlCarousel({
        loop:false,
        autoplay: true,
        margin: 15,
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
        },
        onDragged: testimonials_carousel_owl_stop_autoplay,
        autoplayHoverPause:true
    });
    testimonials_carousel.on('click', function(e) {
        owl_stop_autoplay();
        //owl_simple_slider.trigger('stop.owl.autoplay');
    });
    function testimonials_carousel_owl_stop_autoplay() {
        //console.log('autoplay stopped.');
        testimonials_carousel.trigger('stop.owl.autoplay');
    }




    $('.simple_slider_slideshow a').magnificPopup({
        gallery: {
          enabled: true
        },
        type: 'image' // this is default type
    });

    //clients carousel
    const $clients_carousel = $('.clients_carousel:not(.carousel_disabled)');
    $clients_carousel.owlCarousel({
            loop: false,
            autoplay: true,
            margin: 30,
            dots: false,
            nav:true,
            navText : ['<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 14.299 22.409"><path fill="#0082c9" class="st0" d="M11.25,21.205L1.25,11.205,11.25,1.205l1.8,1.8L4.85,11.205l8.2,8.2-1.8,1.8h0Z"/></svg>','<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 14.299 22.409"><path fill="#0082c9" class="st0" d="M3.05,21.205l-1.8-1.8,8.2-8.2L1.25,3.005l1.8-1.8,10,10L3.05,21.205h0Z"/></svg>'],
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

    // Assign original indices to real (non-cloned) items
    $clients_carousel.find('.owl-item:not(.cloned)').each(function (i) {
        $(this).attr('data-original-index', i);
    });

    // Focus handling for links, buttons, inputs, etc.
    $clients_carousel.on('focusin', '.owl-item a, .owl-item button, .owl-item input', function () {
        const $focusedItem = $(this).closest('.owl-item');

        if (!$focusedItem.length || $focusedItem.hasClass('cloned')) {
        // Try to find the real item (not a clone)
        const realIndex = $focusedItem.data('original-index');
        if (typeof realIndex !== 'undefined') {
            $clients_carousel.trigger('to.owl.carousel', [realIndex, 300, true]);
        }
        return;
        }

        const index = $focusedItem.data('original-index');
        if (typeof index !== 'undefined') {
        $clients_carousel.trigger('to.owl.carousel', [index, 300, true]);
        }
    });

    //add popup for single image
    $('.popup-screenshot').magnificPopup({
        type: 'inline',
        gallery: {
            enabled:true
        },
        // other options
        callbacks: {
                elementParse: function(item) {
                    // Get the type from data attribute
                    var type = item.el.attr('data-type');
                    
                    if (type === 'video') {
                        item.type = 'iframe'; // For YouTube, Vimeo, etc.
                    } else {
                        item.type = 'image';
                    }
                }
            }
    });


    //click on gallery items will open a popup
    /*
    $('.gallery').each(function() {
        $(this).magnificPopup({
            delegate: 'a', // Selector for gallery item
            type: 'image',
            gallery: {
                enabled: true
            },
            
            callbacks: {
                elementParse: function(item) {
                    // Get the type from data attribute
                    var type = item.el.attr('data-type');
                    
                    if (type === 'video') {
                        item.type = 'iframe'; // For YouTube, Vimeo, etc.
                    } else {
                        item.type = 'image';
                    }
                }
            }
            
        });
    });
    */



    //add popup for gallery
    $('.popup-screenshot-gal').magnificPopup({
        type: 'image',
        gallery: {
            enabled:true
        }
    });


    //add popup for gallery
    $('.popup-screenshot-delegate').magnificPopup({
        type: 'image',
        delegate: 'a', // the selector for gallery item
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
        if ($(this).scrollTop() > 300) { // When 300 pixels of page is scrolle
        $('.scroll_up').fadeIn();
        } else {
        $('.scroll_up').fadeOut();
        }
    });


    //smooth scroll scroll up button 
    document.addEventListener('click', function (e) {
        //console.log('scroll_up handler');
        const link = e.target.closest('a.scroll_up');
        if (!link) return;

        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
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

    //meetup popup
    $('.open-popup-link-a').magnificPopup({
        type:'inline',
        midClick: true // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
    });


    $('.popup-video a, a.popup-video, .popup-video.active a').magnificPopup({

        //disableOn: 700,
        type: 'iframe',
        callbacks: {
            open: function() {
                var iframe_content = $.magnificPopup.instance.content[0].childNodes[1];
                var popup_element = $.magnificPopup.instance.content[0];
                replace_this_iframe(iframe_content, popup_element);
            },
            elementParse: function(item) {
                // Custom logic to trigger your pattern
                if (item.src.indexOf('openstreetmap.org') !== -1) {
                  item.type = 'iframe';
                  item.iframe = {
                    markup: '<div class="mfp-iframe-scaler">' +
                              '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>' +
                            '</div>',
                    srcAction: 'iframe_src',
                    patterns: {
                      openstreetmap: {
                        index: 'openstreetmap.org/',
                        src: item.src // use the full URL directly
                      }
                    }
                    }
                }
            }     
        },
        iframe: {
            markup: '<div class="mfp-iframe-scaler">'+
            '<div class="mfp-close"></div>'+
            '<iframe id="" allow="autoplay; encrypted-media" referrerpolicy="strict-origin-when-cross-origin" class="mfp-iframe" frameborder="0" allowfullscreen></iframe>'+
            '</div>', // HTML markup of popup, `mfp-close` will be replaced by the close button
            
            patterns: {
              youtube: {
                index: 'youtube.com/', // String that detects type of video (in this case YouTube). Simply via url.indexOf(index).
          
                id: 'v=', // String that splits URL in a two parts, second part should be %id%
                src: '//www.youtube.com/embed/%id%?autoplay=1', // URL that will be set as a source for iframe.
              },
              vimeo: {
                index: 'vimeo.com/',
                id: '/',
                src: '//player.vimeo.com/video/%id%?autoplay=1'
              },
              gmaps: {
                index: '//maps.google.',
                src: '%id%&output=embed'
              },
              openstreetmap: {
                index: 'openstreetmap.org/',
                src: '%id%'
              }
            },
            srcAction: 'iframe_src', // Templating object key. First part defines CSS selector, second attribute. "iframe_src" means: find "iframe" and set attribute "src".
        },
        

        mainClass: 'mfp-fade',
        removalDelay: 160,
        preloader: false,
        fixedContentPos: false
    });



    


    $(window).on('resize', function() {
            //console.log("Window resized to: " + $(window).width() + "x" + $(window).height());
            // Your resize code here
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
            } else {
                $('#pricing_plans_head').stickySidebar('destroy');
                $('#request_quote_left_sidebar').stickySidebar('destroy');
                $('#advantages-left-sticky').stickySidebar('destroy');
                $('#thead_advantages').stickySidebar('destroy');
            }

    }).trigger('resize');

});




jQuery(document).ready(function () {
    
    document.addEventListener("scroll", function() {
        const bar = document.getElementById("reading-progress-bar");
        if (!bar) return; // exit if the element doesn't exist
        const scrollTop = window.scrollY;
        const docHeight = document.body.scrollHeight - window.innerHeight;
        const scrollPercent = (scrollTop / docHeight) * 100;
        bar.style.width = scrollPercent + "%";
    });

    document.querySelectorAll('.menu-footer .menu-item-has-children > a').forEach(menuSection => {
        menuSection.setAttribute('role', 'heading');
        menuSection.setAttribute('aria-level', '2');
        //menuSection.removeAttribute('tab-index');
    })

    const menuItems = document.querySelectorAll('.primary-menu .menu-item-has-children > a');
    menuItems.forEach(function (el) {

        el.setAttribute('aria-haspopup', 'true');
        el.setAttribute('aria-expanded', 'false');

        let timer;

        // Mouse over
        el.addEventListener('mouseover', function () {
            document.querySelector('.menu-item-has-children.open')?.classList.remove('open');

            el.parentElement.classList.add('open');
            el.setAttribute('aria-expanded', 'true');

            clearTimeout(timer);
        });

        // Mouse out
        el.addEventListener('mouseout', function () {
            timer = setTimeout(() => {
                el.parentElement.classList.remove('open');
                el.setAttribute('aria-expanded', 'false');
            }, 500);
        });

        // Keyboard focus
        el.addEventListener('focusin', function () {
            document.querySelector('.menu-item-has-children.open')?.classList.remove('open');

            el.parentElement.classList.add('open');
            el.setAttribute('aria-expanded', 'true');

            clearTimeout(timer);
        });

        el.addEventListener('focusout', function () {
            timer = setTimeout(() => {
                const hasFocused = el.parentElement.contains(document.activeElement);

                if (!hasFocused) {
                    el.parentElement.classList.remove('open');
                    el.setAttribute('aria-expanded', 'false');
                }
            }, 250);
        });

    });


    const topLevelMenuItems = document.querySelectorAll(
        '.primary-menu > .menu-item-has-children > a'
    );

    topLevelMenuItems.forEach(function (el) {

        el.addEventListener('click', function (event) {
            event.preventDefault();

            const parent = el.parentElement;
            const isOpen = parent.classList.contains('open');

            document.querySelectorAll('.primary-menu > .menu-item-has-children.open')
                .forEach(item => {
                    item.classList.remove('open');

                    item.querySelector(':scope > a')
                        ?.setAttribute('aria-expanded', 'false');
                });

            if (!isOpen) {
                parent.classList.add('open');
                el.setAttribute('aria-expanded', 'true');
            }
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
                jQuery('html').removeClass('overflowHidden');
            });
        } else {
            jQuery('header').addClass('active');
            jQuery('html').addClass('overflowHidden');
            jQuery('.header-items').slideDown(300, function(){
                //animation complete
            });
        }

    });


    
    // on page load 
    var windowsize = jQuery(window).width();
    if (windowsize < 1400) { 
        //1200 px normally, 1400px with 10th anniversary logo
        
        jQuery('.phone-menu').attr('tabindex', 0);


        
        // jQuery(".primary-menu > .menu-item-has-children").click(function () {
        //     //close the open submenus
        //     jQuery(this).siblings('li').removeClass('mobile-submenu-open');
        //     jQuery(this).toggleClass('mobile-submenu-open');
        //     console.log("primary sub menu changed. ");
        // });
        
        // jQuery(".office-sub-menu").click(function () {
        //      e.stopPropagation();
        //     //jQuery(this).siblings('li').removeClass('mobile-submenu-open');
        //     jQuery(this).toggleClass('mobile-submenu-open');
        //     console.log("office sub menu changed. ");
        // });
        

        //open 2nd level sub-domain
        jQuery(".primary-menu > .menu-item-has-children > a").click(function (e) {
            e.preventDefault();
            console.log("hub menu clicked.");
            var $parent = jQuery(this).parent();
            $parent
                .siblings('li')
                .removeClass('mobile-submenu-open');
            $parent.toggleClass('mobile-submenu-open');
        });

        //open 3rd level sub-menu
        jQuery(".office-sub-menu > a").click(function (e) {
            e.preventDefault();
            e.stopPropagation();
            jQuery(this)
                .parent()
                .toggleClass('mobile-submenu-open');
        });
   
                

    }

    
    jQuery( window ).on( "resize", function() {
        var windowsize = jQuery(window).width();

        if (windowsize > 1400) { //1200 px normally, 1400px with 10th anniversary logo
            jQuery('.phone-menu').attr('tabindex', -1);

            jQuery('.header-items').show();
            if(jQuery('header').hasClass('active')) {
                jQuery('header').removeClass('active');
            }
            if(jQuery('.phone-menu').hasClass('change')) {
                jQuery('.phone-menu').removeClass('change');
            }
        } else {
            jQuery('.phone-menu').attr('tabindex', 0);

            if(jQuery('.phone-menu').hasClass('change')) {
                jQuery('header').addClass('active');
            } else {
                jQuery('.header-items').hide();
            }

        }
    });
    


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


    
//JQuery smooth scroll, with offset 
/*
jQuery('a[href*="#"]')
  .not('[href="#"]')
  .not('[href="#no_scroll"]')
  .not('[href="#trialModal"]')
  .not('[href="#0"]')
  .not('[href="#hidden_header_anchor"]')
  .not('[href="#nextcloud_files_tab"]')
  .not('[href="#nextcloud_talk_tab"]')
  .not('[href="#nextcloud_groupware_tab"]')
  .not('[href="#nextcloud_office_tab"]')
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
  .not('[data-vc-accordion]')
  .not('.scroll_up')
  .on('click', function (event) {
    if (
      location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') &&
      location.hostname === this.hostname
    ) {
      const targetsArray = [
        '#no_scroll',
        '#conf-form-popup',
        '#submit_proposal',
        '#premiere_tab',
        '#conf_day1_tab',
        '#conf_day2_tab',
        '#register_meetup'
      ];

      const hashHref = jQuery(this).attr('href');

      if (jQuery.inArray(hashHref, targetsArray) !== -1) {
        return;
      }

      let target = jQuery(this.hash);
      target = target.length
        ? target
        : jQuery('[name="' + this.hash.slice(1) + '"]');

      if (!target.length) {
        return;
      }

      event.preventDefault();

      let offset = 95;

      if (jQuery(this).parents('.fixed_row_iconboxes').length) {
        offset = 120;
      }

      const isPopupLink =
        jQuery(this).parents('.vc_do_btn, .nc_iconbox').filter('.open-popup-link').length > 0;

      if (isPopupLink) {
        return;
      }

      const scrollTop = Math.max(0, target.offset().top - offset);

      jQuery('html, body')
        .stop(true)
        .animate({ scrollTop: scrollTop }, 1000, function () {
          if (!target.is(':focus')) {
            target.attr('tabindex', '-1').focus();
          }
        });
    }
  });
  */

  

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

    
    /*
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
    */



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


// Function definition with passing two arrays
function findCommonElement(array1, array2) {
    for(let i = 0; i < array1.length; i++) {
        for(let j = 0; j < array2.length; j++) {
            if(array1[i] === array2[j]) {
                // Return if common element found
                return true;
            }
        }
    }
    return false;
}



function iniFilter() {
    var filter1 = jQuery('#services').data('value');
    //console.log("filter1? services: "+filter1);

    var filter2 = jQuery('#certificates').data('value');
    //var filter3 = jQuery('#country').data('value');
    var filter3 = jQuery('#country').val();
    //console.log("filter3? "+filter3);

    var search_countries = false;

    if(filter3 == '' || filter3 == 'all-comp' || filter3 == main_js_strings.all_label ) {
        var filter3 = '';
    } else {
        search_countries = true;
    }
    //console.log("Search countries? "+search_countries);

    var filter3_array = filter3.split(",");
    //trim white spaces
    var filter3_array = filter3.split(',').map(function(item) {
        return item.trim();
    });



    //filtering based on text inside search field 
    var filter4 = jQuery('#filtersearch').val();

    if (jQuery.inArray("all-dev", filter1) !== -1 || filter1.length === 0) {
        var allDev = 1;
    }
    if (filter2 == 'all-cert') {
        filter2 = '';
    }
    //if(filter4 != '') {
        //jQuery('.partner-col:containsIN("' + filter4 + '")').show();
        //jQuery(this).containsIN(filter4);
        //console.log(filter4);
    //}



    jQuery('.partner-col').each(function () {
        var countries_included = false;
        var partner_id = jQuery(this).attr('id');
        var values = jQuery(this).data('type');
        var country = jQuery(this).data('country');
        var countries_of_this_partner_array = country.split(",");

        /*
        if(partner_id == 'partner-329176') {
            console.log(filter3_array);
            console.log(countries_of_this_partner_array);
        }
        */
        

        if(search_countries) {
            //countries are being filtered
            if(findCommonElement(countries_of_this_partner_array, filter3_array)){
                countries_included = true;
                //console.log("Common country found. Show partner with ID: "+partner_id);
            }
        }
        
        


        if (allDev == 1) { 
            // if selected all services
            //console.log("All services are selected.");
            if (
                //values.includes(filter2) &&
                jQuery(this).find('.partner-text').text().toLowerCase().match(filter4.toLowerCase()) 
                /* || country.toLowerCase().match(filter4.toLowerCase()) */
            ) {

                if(search_countries) {
                    if(countries_included) {
                        jQuery(this).show();
                    } else {
                        jQuery(this).hide();
                    }
                } else {
                    jQuery(this).show();
                }

                

            } else {
                jQuery(this).hide();
            }


        } else { // if selected specific service
            //console.log("Specific service is selected.");

            if (
                /*
                filter1.every(item => values.includes(item)) && values.includes(filter2) 
                &&
                */
                jQuery(this).find('.partner-text').text().toLowerCase().match(filter4)
            ) {

                if(search_countries) {
                    if(countries_included) {
                        jQuery(this).show();
                    } else {
                        jQuery(this).hide();
                    }
                } else {
                    jQuery(this).show();
                }


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