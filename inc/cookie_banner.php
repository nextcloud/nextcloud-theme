<div id="cookie_banner" class="cookie_banner" style="display: none;">

    <div class="cookie_banner_inner">

        <div class="logo">
            <img src="https://nextcloud.com/wp-content/uploads/2022/10/nextcloud-logo-blue-transparent.svg" alt="Nextcloud" title="Nextcloud">
        </div>

        <div class="description">
            <div class="text">
                <?php echo __("We save some cookies to count visitors and make the site easier to use. This doesn't leave our server and isn't to track you personally!
                See our <a target='_blank' href='/privacy/'>Privacy Policy</a> for more information.", "nextcloud"); ?>
                <a href="#no_scroll" id="open_details" class="open_details"><?php echo __('Customize','nextcloud');?> <i class="icon fas fa-angle-down"></i></a>
            </div>
            
        </div>

        <div class="buttons">
            <button class="accept_cookie_btn accept_all_btn c-btn btn-main" id="accept_all_btn"><?php echo __('Accept all cookies', 'nextcloud'); ?></button>
            <!-- <button class="accept_cookie_btn accept_nc_cookies c-btn btn-main" id="accept_nc_cookies">accept Nextcloud cookies</button>-->
            <button class="accept_cookie_btn save_settings c-btn btn-main" id="save_settings"><?php echo __('Reject all', 'nextcloud'); ?></button>
        </div>

        <div class="customize_btn_container">
            <a href="#no_scroll" id="open_details2" class="open_details"><?php echo __('Customize','nextcloud');?> <i class="icon fas fa-angle-down"></i></a>
        </div>

    </div>


    <div class="cookies_details" id="cookies_details" style="visibility: hidden; height: 0;">
           

            <div class="cookies_cat" id="cookies_essentials_cat">

                <div class="cookies_cat_inner">
                    <div class="cookie_cat_name"><?php echo __('Essentials', 'nextcloud'); ?>
                        <a href="#" class="cookies_more_details" id=""><?php echo __('More details', 'nextcloud'); ?><i class="icon fas fa-angle-down"></i></a>
                    </div>
                    <div class="cookie_cat_desc">
                    <?php echo __('Essential cookies enable basic functions and are necessary for the proper function of the website.', 'nextcloud'); ?>    
                    </div>
                    <div class="cookie_cat_cb">

                        <label class="nc-switch">
                            <input type="checkbox" id="accept_all_essentials" class="cat_checkbox" name="nc_cookie_banner[]" value="essentials">
                            <span class="nc-switch-style"></span>
                        </label>
                        
                    </div>
                </div>
                

                <div class="cookies_list" style="">
                    <div class="cookie_item" id="wpml_current_language">
                        <div class="cookie_name"><label for=""><?php echo __('Cookie name:', 'nextcloud'); ?></label>wp-wpml_current_language</div>
                        <div class="cookie_desc"><label for=""><?php echo __('Cookie description:', 'nextcloud'); ?></label><?php echo __('Set the language on the website', 'nextcloud'); ?></div>
                        <div class="cookie_duration"><label for=""><?php echo __('Cookie expiry:', 'nextcloud'); ?></label><?php echo __('No expiration', 'nextcloud'); ?></div>
                        <div class="cookie_checkbox">
                            
                            <label for="accept_wpml_current_language"><?php echo __('Consent', 'nextcloud'); ?></label>
                            <label class="nc-switch">
                                <input type="checkbox" id="accept_wpml_current_language" name="wpml_current_language" value="1">
                                <span class="nc-switch-style"></span>
                            </label>
                    
                    </div>
                    </div>
                    <div class="cookie_item" id="nc_cookie_banner">
                        <div class="cookie_name"><label for=""><?php echo __('Cookie name:', 'nextcloud'); ?></label>nc_cookie_banner</div>
                        <div class="cookie_desc"><label for=""><?php echo __('Cookie description:', 'nextcloud'); ?></label><?php echo __('Saves the cookie containing the user cookies preferences', 'nextcloud'); ?></div>
                        <div class="cookie_duration"><label for=""><?php echo __('Cookie expiry:', 'nextcloud'); ?></label>30 <?php echo __('days', 'nextcloud'); ?></div>
                        <div class="cookie_checkbox">
                           
                            <label for="accept_wpml_current_language"><?php echo __('Consent', 'nextcloud'); ?></label>
                            <label class="nc-switch">
                                <input type="checkbox" id="accept_nc_cookie_banner" name="nc_cookie_banner[]" value="nc_cookie_banner">
                                <span class="nc-switch-style"></span>
                            </label>
                        
                        </div>
                    </div>
                </div>

            </div>


            <div class="cookies_cat">
                <div class="cookies_cat_inner">
                    <div class="cookie_cat_name"><?php echo __('Convenience', 'nextcloud'); ?>
                        <a href="#" class="cookies_more_details" id=""><?php echo __('More details', 'nextcloud'); ?> <i class="icon fas fa-angle-down"></i></a>
                    </div>
                    <div class="cookie_cat_desc"><?php echo __('Cookies used to save the data entered in forms, such as name, email, phone number, and preferred language.', 'nextcloud'); ?></div>
                    <div class="cookie_cat_cb">
                        
                            <label class="nc-switch">
                            <input type="checkbox" class="cat_checkbox" id="accept_convenience_cookies" name="nc_cookie_banner[]" value="convenience">
                            <span class="nc-switch-style"></span>
                            </label>
                            

                    </div>
                </div>

                <div class="cookies_list" style="">
                    <div class="cookie_item" id="nc_form_fields">
                        <div class="cookie_name"><label for=""><?php echo __('Cookie name:', 'nextcloud'); ?></label>nc_form_fields</div>
                        <div class="cookie_desc"><label for=""><?php echo __('Cookie description:', 'nextcloud'); ?></label><?php echo __('Remembers the data filled in the forms for the next time (name, email, phone and preferred language)', 'nextcloud'); ?></div>
                        <div class="cookie_duration"><label for=""><?php echo __('Cookie expiry:', 'nextcloud'); ?></label>30 <?php echo __('days', 'nextcloud'); ?></div>
                        
                        
                        <div class="cookie_checkbox" style="visibility: hidden;height: 0;">
                            <label for="accept_nc_form_fields"><?php echo __('Consent', 'nextcloud'); ?></label>
                        <label class="nc-switch">
                        <input type="checkbox" id="accept_nc_form_fields" name="nc_form_fields" value="1">
                        <span class="nc-switch-style"></span>
                        </label>

                    </div>
                    </div>
                </div>

            </div>


            <div class="cookies_cat">
                <div class="cookies_cat_inner">
                    <div class="cookie_cat_name"><?php echo __('Statistics', 'nextcloud'); ?>
                        <a href="#" class="cookies_more_details" id=""><?php echo __('More details', 'nextcloud'); ?> <i class="icon fas fa-angle-down"></i></a>
                    </div>
                    <div class="cookie_cat_desc"><?php echo __('Statistics cookies collect information anonymously and help us understand how our visitors use our website. We use on-premises <a href="https://matomo.org/" target="_blank">Matomo</a>','nextcloud')?>
                    </div>
                    <div class="cookie_cat_cb">
                        
                    
                        <label class="nc-switch">
                        <input type="checkbox" class="cat_checkbox" id="accept_statistics" name="nc_cookie_banner[]" value="statistics"/>
                        <span class="nc-switch-style"></span>
                        </label>
                    
                    </div>
                </div>

                <div class="cookies_list" style="">
                    <div class="cookie_item" id="nc_form_fields">
                        <div class="cookie_name"><label for=""><?php echo __('Service', 'nextcloud'); ?>:</label>Matomo</div>
                        
                        <div class="cookie_desc">
                            <label for=""><?php echo __('Cookies description:', 'nextcloud'); ?></label>
                        _pk_ses*: <?php echo __('Counts the first visit of the user', 'nextcloud'); ?><br>
                        _pk_id*: <?php echo __('Helps not to double count the visits.', 'nextcloud'); ?>
                        </div>
                        
                        <div class="cookie_duration"><label for=""><?php echo __('Cookies expiry:', 'nextcloud'); ?></label>_pk_ses*: 30 <?php echo __('minutes', 'nextcloud'); ?><br>
                        _pk_id*: 28 <?php echo __('days', 'nextcloud'); ?>
                        </div>
                        
                        <div class="cookie_checkbox" style="visibility: hidden;height: 0;">
                            <label for="accept_matomo"><?php echo __('Consent', 'nextcloud'); ?></label>
                            <label class="nc-switch">
                            <input type="checkbox" id="accept_matomo" name="matomo" value="1">
                            <span class="nc-switch-style"></span>
                        </label>
                    
                    </div>
                    </div>
                </div>


            </div>


            <div class="cookies_cat">
                <div class="cookies_cat_inner">
                    <div class="cookie_cat_name"><?php echo __('External media', 'nextcloud'); ?>
                        <a href="#" class="cookies_more_details" id=""><?php echo __('More details', 'nextcloud'); ?> <i class="icon fas fa-angle-down"></i></a>
                    </div>
                    <div class="cookie_cat_desc"><?php echo __('Allows connections for loading external media. No cookies from Youtube or Vimeo will be set.', 'nextcloud'); ?></div>
                    <div class="cookie_cat_cb">
                        
                    
                        <label class="nc-switch">
                        <input type="checkbox" class="cat_checkbox" id="accept_external_media" name="nc_cookie_banner[]" value="external_media"/>
                        <span class="nc-switch-style"></span>
                        </label>
                    
                    </div>
                </div>


                <div class="cookies_list" style="">
                    <div class="cookie_item" id="nc_youtube_cookie">
                        <div class="cookie_name"><label for=""><?php echo __('Service:', 'nextcloud'); ?></label><?php echo __('Play Youtube videos', 'nextcloud'); ?></div>
                        <div class="cookie_desc"><label for=""><?php echo __('Service description:', 'nextcloud'); ?></label>
                        <?php echo __('All of the Youtube videos get unblocked if this setting is saved', 'nextcloud'); ?>
                        </div>
                        <div class="cookie_duration"><label for=""><?php echo __('Cookie expiry:', 'nextcloud'); ?></label> - </div>
                        <div class="cookie_checkbox"><label for="accept_youtube"><?php echo __('Consent', 'nextcloud'); ?></label>
                        
                    
                        <label class="nc-switch">
                        <input type="checkbox" id="accept_youtube" name="nc_youtube_unblocked" value="1">
                        <span class="nc-switch-style"></span>
                        </label>
                    
                    </div>
                    </div>

                    <div class="cookie_item" id="nc_vimeo_cookie">
                        <div class="cookie_name"><label for=""><?php echo __('Service:', 'nextcloud'); ?></label><?php echo __('Play Vimeo videos', 'nextcloud'); ?></div>
                        <div class="cookie_desc"><label for=""><?php echo __('Service description:', 'nextcloud'); ?></label>
                        <?php echo __('All of the Vimeo videos get unblocked if this setting is saved', 'nextcloud'); ?>
                        </div>
                        <div class="cookie_duration"><label for=""><?php echo __('Cookie expiry:', 'nextcloud'); ?></label> - </div>
                        <div class="cookie_checkbox"><label for="accept_vimeo"><?php echo __('Consent', 'nextcloud'); ?></label>
                        
                    
                        <label class="nc-switch">
                        <input type="checkbox" id="accept_vimeo" name="nc_vimeo_unblocked" value="1">
                        <span class="nc-switch-style"></span>
                        </label>
                    
                    </div>
                    </div>

                </div>


            </div>
            
        </div>

</div>



<!-- Matomo -->
<script id="matomo_script" type="text/javascript">
//var loadMatomo = function(fullReload = false) {
//console.log("matomo full reload? "+fullReload);
    var _paq = window._paq = window._paq || [];
  /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
  _paq.push(["setDocumentTitle", document.domain + "/" + document.title]);
  _paq.push(["setCookieDomain", "*.nextcloud.com"]);
  _paq.push(["setDomains", ["*.nextcloud.com"]]);
  _paq.push(['trackPageView']); //_pk_ses matomo general cookie
  _paq.push(['enableLinkTracking']);
  _paq.push(['setSiteId', '1']);//live
  //_paq.push(['setSiteId', '7']);//staging
  _paq.push(['requireCookieConsent']);


  if(getCookie('nc_cookie_banner') ) {
    //console.log(getCookie('nc_cookie_banner'));
    //console.log("Matomo: "+JSON.parse(getCookie('nc_cookie_banner')).statistics.matomo);
    //if (typeof getCookie('nc_cookie_banner').statistics.matomo !== 'undefined') {
        if(JSON.parse(getCookie('nc_cookie_banner')).statistics.matomo){
            _paq.push(['setCookieConsentGiven']);
        }
    //}
  }

(function() {
	var u="https://stats.nextcloud.com/";
	_paq.push(['setTrackerUrl', u+'matomo.php']);

	var d=document, 
    g=d.createElement('script'), 
    s=d.getElementsByTagName('script')[0];

	g.type='text/javascript';
    g.id='matomo_js';
    g.async=true; 
    g.src=u+'matomo.js';
    s.parentNode.insertBefore(g,s);


})();

/*
};
loadMatomo();
*/
</script>
<noscript><p><img src="https://stats.nextcloud.com/matomo.php?idsite=1&amp;rec=1" style="border:0;" alt="matomo" /></p></noscript>
<!-- End Matomo Code -->
<script id="cookie_banner_script">
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
            $('#save_settings').html('<?php echo __('Allow selection', 'nextcloud'); ?>');
        });


        $('#open_details, #open_details2').click(function(e){
            e.preventDefault();
            $('#cookies_details').toggleClass('opened');
            $(this).find('i').toggleClass('fa-angle-up');

            $('#save_settings').toggleText('<?php echo __('Reject all', 'nextcloud'); ?>', '<?php echo __('Allow selection', 'nextcloud'); ?>');
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
</script>