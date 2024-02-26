jQuery(document).ready(function ($) {

    $.fn.toggleText = function(t1, t2){
    if (this.text() == t1) this.text(t2);
    else                   this.text(t1);
    return this;
    };

    //check if nc_cookie_banner cookie is set, if so set checkboxes as checked
    if(getCookie('nc_cookie_banner')) {
        var nc_cookies_banner_saved = JSON.parse(getCookie('nc_cookie_banner'));

        //convenience
        if(nc_cookies_banner_saved.convenience && getCookie('nc_form_fields')) {
            document.getElementById("accept_convenience_cookies").checked = true;
            document.getElementById("accept_nc_form_fields").checked = true;
        }

        //stats
        if(nc_cookies_banner_saved.statistics.matomo) {
            document.getElementById("accept_statistics").checked = true;
            document.getElementById("accept_matomo").checked = true;
        }

        //external media
        if(nc_cookies_banner_saved.external_media.vimeo == true ) {
            //console.log('vimeo is true');
            document.getElementById("accept_vimeo").checked = true;
        }
        if(nc_cookies_banner_saved.external_media.youtube == true) {
            //console.log('youtube is true');
            document.getElementById("accept_youtube").checked = true;
        }
        if(nc_cookies_banner_saved.external_media.vimeo == true && nc_cookies_banner_saved.external_media.youtube == true) {
            document.getElementById("accept_external_media").checked = true;
        }

    }


    //cookies_essentials_cat
    $('#cookies_essentials_cat').find('input[type=checkbox]').prop('checked', true);
    $('#cookies_essentials_cat').find('input[type=checkbox]').prop('disabled', true);


    //#cookies_preferences
    $('#cookies_preferences').click(function(e){
        e.preventDefault();
        $('#cookie_banner').show();
        $('#cookies_details').toggleClass('opened');
        $('#open_details').find('i').toggleClass('fa-angle-up');
        $('#save_settings').html(cookie_banner_strings.allow_selection);
    });


    $('#open_details, #open_details2').click(function(e){
        e.preventDefault();
        $('#cookies_details').toggleClass('opened');
        $(this).find('i').toggleClass('fa-angle-up');

        $('#save_settings').toggleText(cookie_banner_strings.reject_all, cookie_banner_strings.allow_selection);
    });


    $('.cookies_more_details').click(function(e){
        e.preventDefault();
        $(this).parents('.cookies_cat').children('.cookies_list').toggleClass('showCookies');
        $(this).find('i').toggleClass('fa-angle-up');
    });


    //if the whole cookie category checkbox is checked, check automatically also all checkboxes for all cookies inside
    $("input.cat_checkbox").change(function() {
        if(this.checked) {
            //console.log("checked!");
            $(this).parents('.cookies_cat').find('input[type=checkbox]').prop('checked', true);
        }else {
            $(this).parents('.cookies_cat').find('input[type=checkbox]').prop('checked', false);
        }
    });


    //if one of the children checkboxes is unchecked, uncheck automatically also the category checkbox
    $(".cookies_list").find('input[type=checkbox]').change(function() {
        if(!this.checked) {
            $(this).parents('.cookies_list').siblings('.cookies_cat_inner').find('input[type=checkbox]').prop('checked', false);
        } else {
            //if this is checked, check if all are checked, and in that case check category checkbox
            
            $(this).parents('.cookie_item ').siblings('.cookie_item').find('input[type=checkbox]').each(function(){
                var all_checked = true;
                if(!this.checked) {
                    all_checked = false;
                }

                if (all_checked) {
                    $(this).parents('.cookies_list').siblings('.cookies_cat_inner').find('input[type=checkbox]').prop('checked', true);
                }
            });
            
        }
    });



    //if cookie is not set, show div cookie banner
    if(!getCookie('nc_cookie_banner')){
        $('#cookie_banner').show();
    }

    //set defaults
    var nc_cookie_banner = {
        essentials: true,
        convenience: false,
        statistics: {
            matomo: false
        },
        external_media: {
            youtube: false,
            vimeo: false
        }
    };

    var wpml_current_language = true;

    var nc_form_fields = {
        nc_form_name: false,
        nc_form_email: false,
        nc_form_lang: false,
        nc_form_phone: false
    };

    //var matomo_cookie = false;
    //var nc_youtube_unblocked = false;
    //var nc_vimeo_unblocked = false;


    $('.accept_cookie_btn').click(function(){


        //button accept all cookies
        if( $(this).attr('id') == 'accept_all_btn') {

            //convenience
            nc_cookie_banner.convenience = true;
            nc_form_fields.nc_form_name = true;
            nc_form_fields.nc_form_email = true;
            nc_form_fields.nc_form_lang = true;
            nc_form_fields.nc_form_phone = true;

            //statistics
            nc_cookie_banner.statistics.matomo = true;

            //external media
            //nc_cookie_banner.external_media = true;
            nc_cookie_banner.external_media.youtube = true;
            nc_cookie_banner.external_media.vimeo = true;



            //convenience
            if(nc_cookie_banner.convenience) {
                document.getElementById("accept_convenience_cookies").checked = true;
                document.getElementById("accept_nc_form_fields").checked = true;
            }
            //stats
            if(nc_cookie_banner.statistics.matomo) {
                document.getElementById("accept_statistics").checked = true;
                document.getElementById("accept_matomo").checked = true;
            }
            //external media
            if(nc_cookie_banner.external_media.vimeo == true ) {
                //console.log('vimeo is true');
                document.getElementById("accept_vimeo").checked = true;
            }
            if(nc_cookie_banner.external_media.youtube == true) {
                //console.log('youtube is true');
                document.getElementById("accept_youtube").checked = true;
            }
            if(nc_cookie_banner.external_media.vimeo == true && nc_cookie_banner.external_media.youtube == true) {
                document.getElementById("accept_external_media").checked = true;
            }


        }




        //button save settings
        if( $(this).attr('id') == 'save_settings') { // allow selection
            $('#cookies_details').find('input[type=checkbox]').each(function(){

                    //console.log( $(this).attr('name') + " : " + $(this).val());

                    if($(this).attr('name') == 'nc_cookie_banner[]') {
                        if($(this).val()=='statistics'){

                            if ($(this).is(':checked')) {
                                nc_cookie_banner.statistics.matomo = true;
                            } else {
                                nc_cookie_banner.statistics.matomo = false;
                                console.log("stats set to false");
                                //ideally unset the Matomo tracking

                            }
                            
                        }
                        if($(this).val()=='external_media'){

                            if ($(this).is(':checked')) {
                                nc_cookie_banner.external_media.youtube = true;
                                nc_cookie_banner.external_media.vimeo = true;
                            } else {
                                nc_cookie_banner.external_media.youtube = false;
                                nc_cookie_banner.external_media.vimeo = false;
                                // ideally block youtube and vimeo again
                                
                            }
                        }
                    }

                    if($(this).attr('name') == 'nc_youtube_unblocked') {
                        if ($(this).is(':checked')) {
                            nc_cookie_banner.external_media.youtube = true;
                        } else {
                            nc_cookie_banner.external_media.youtube = false;
                            //ideally block youtube iframes again
                            console.log("block youtube iframes");
                            block_all_iframes('youtube');
                        }
                        
                    }

                    if($(this).attr('name') == 'nc_vimeo_unblocked ') {

                        if ($(this).is(':checked')) {
                            nc_cookie_banner.external_media.vimeo = true;
                        } else {
                            nc_cookie_banner.external_media.vimeo = false;
                            //ideally block vimeo iframes again
                            console.log("block vimeo iframes");
                            block_all_iframes('vimeo');
                        }
                        
                    }

                    

                    //convenience
                    if(nc_cookie_banner.convenience) {
                        document.getElementById("accept_convenience_cookies").checked = true;
                        document.getElementById("accept_nc_form_fields").checked = true;
                    }
                    //stats
                    if(nc_cookie_banner.statistics.matomo) {
                        document.getElementById("accept_statistics").checked = true;
                        document.getElementById("accept_matomo").checked = true;
                    }
                    //external media
                    if(nc_cookie_banner.external_media.vimeo == true ) {
                        //console.log('vimeo is true');
                        document.getElementById("accept_vimeo").checked = true;
                    }
                    if(nc_cookie_banner.external_media.youtube == true) {
                        //console.log('youtube is true');
                        document.getElementById("accept_youtube").checked = true;
                    }
                    if(nc_cookie_banner.external_media.vimeo == true && nc_cookie_banner.external_media.youtube == true) {
                        document.getElementById("accept_external_media").checked = true;
            }


            });
        }



        //save the cookies
        var nc_cookie_banner_str = JSON.stringify(nc_cookie_banner);
        setCookie('nc_cookie_banner', nc_cookie_banner_str, 30);






        //convenience
        if(nc_cookie_banner.convenience == true){
            var nc_form_fields_str = JSON.stringify(nc_form_fields);
            setCookie('nc_form_fields', nc_form_fields_str, 30);
        }

        //statistics
        if(nc_cookie_banner.statistics.matomo == true){
            //Cookies.set('statistics', true, { expires: 30 });
            console.log("load complete Matomo code here...");
            // load complete Matomo code
            //_paq.push(['setSiteId', '1']); // _pk_id.1.7a80 - unique visit tracked, not working without trackPageView
            
            //loadMatomo(true); //load full matomo
            _paq.push(['setCookieConsentGiven']);

        }



        $('#cookie_banner').hide();

    });



});