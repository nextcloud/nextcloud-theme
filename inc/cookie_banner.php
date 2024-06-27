<div id="cookie_banner" class="cookie_banner" style="display: none;">

    <div class="cookie_banner_inner">

        <div class="logo">
            <img src="https://nextcloud.com/wp-content/uploads/2022/10/nextcloud-logo-blue-transparent.svg" width="71px" height="50px" alt="Nextcloud" title="Nextcloud">
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
                    <div class="cookie_cat_desc"><?php echo __('Statistics cookies collect information anonymously and help us understand how our visitors use our website. We use cloud-hosted <a href="https://matomo.org/matomo-cloud/" target="_blank">Matomo</a>','nextcloud')?>
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
                        _pk_id*: <?php echo __('Helps not to double count the visits.', 'nextcloud'); ?><br>
                        mtm_cookie_consent: <?php echo __('Remembers that consent for storing and using cookies was given by the user.', 'nextcloud'); ?>
                        </div>
                        
                        <div class="cookie_duration"><label for=""><?php echo __('Cookies expiry:', 'nextcloud'); ?></label>_pk_ses*: 30 <?php echo __('minutes', 'nextcloud'); ?><br>
                        _pk_id*: 28 <?php echo __('days', 'nextcloud'); ?><br>
                        mtm_cookie_consent: 30 <?php echo __('days', 'nextcloud'); ?>
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



<!-- Matomo Tag Manager -->
<script id="matomo_script" type="text/javascript">
  var _mtm = window._mtm = window._mtm || [];
  var _paq = window._paq = window._paq || [];
  _mtm.push({'mtm.startTime': (new Date().getTime()), 'event': 'mtm.Start'});
  
  setTimeout(() => {
    if(getCookie('nc_cookie_banner') ) {
        const cookieBanner = JSON.parse(getCookie('nc_cookie_banner'));
        if (cookieBanner.statistics && cookieBanner.statistics.matomo) {
            
            if(!getCookie('mtm_cookie_consent')){
                // set cookie only if not recently set
                _paq.push(['rememberCookieConsentGiven', 720]);
            }

        } else {
            //console.log("Stats consent NOT given, do NOT load rememberCookieConsentGiven");
            _paq.push(['forgetCookieConsentGiven']); // Ensure Matomo forgets the consent
            _paq.push(['deleteCookies']); // Delete all Matomo cookies
        }
    }
  }, 500);
  
  (function() {
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.async=true;
    g.id='matomo_js';
    g.src='https://cdn.matomo.cloud/nextcloud.matomo.cloud/container_wlmEL4w2.js';
    s.parentNode.insertBefore(g,s);
  })();
</script>
<!-- End Matomo Tag Manager -->