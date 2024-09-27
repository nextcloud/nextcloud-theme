jQuery(document).ready(function () {
    window.forms = [];

    jQuery(document).on( 'nfFormReady', function( e, layoutView ) {
        //console.log("nfFormReady. "+layoutView.model.id);
        //window.formsLoaded = true;
        forms.push(layoutView.model.id);

        jQuery('.nf-form-cont').each(function(){
            var form_id = jQuery(this).attr('id').match(/\d+/); // get numbers
            //console.log("form ID: "+form_id);
            if(form_id == 4) {
                // whitepapers, case studies, data sheets - add Activity ID as form name
                // Activity ID field = nf-field-309
                var activity_id = jQuery(this).find('input#nf-field-309').val();
                jQuery(this).find('form').attr('name', activity_id);
            } else if(form_id == 75) {
                // webinars form
                // Activity ID field = nf-field-616
                var activity_id = jQuery(this).find('input#nf-field-616').val();
                jQuery(this).find('form').attr('name', activity_id);
            }
            jQuery(this).find('form').attr('id', 'form_id_'+form_id);
        });

        // track form submission in Matomo
        nfRadio.channel('forms').on('submit:response', function(form) {
            console.log("Form errors: "+JSON.stringify(form.errors));
            if( Array.isArray(form.errors) && form.errors.length == 0 ){
                _paq.push(['FormAnalytics::trackFormSubmit', form.data.settings.title]);
                console.log(form.data.settings.title + ' successfully submitted');
            } else {
                console.log(form.data.settings.title + ' NOT submitted. There are errors.');
            }
        });



        jQuery('.select_ed_slides select').on('change', function(){
            var index = this.selectedIndex;
            //console.log("Selected index: "+index);
            
            //auto-select option of the slide URLs select field
            jQuery('.all_slides_urls select option').eq(index).prop('selected',true);
            jQuery(".all_slides_urls select option").eq(index).change();
            jQuery(".all_slides_urls select option").eq(index).attr("selected","selected");

            //auto-select option of the slide interests select field
            jQuery('.all_slides_interests select option').eq(index).prop('selected',true);
            jQuery(".all_slides_interests select option").eq(index).change();
            jQuery(".all_slides_interests select option").eq(index).attr("selected","selected");
        });

        //if preferred language select field is present, add select style to it
        //execute only if intlTelInput is already loaded
        //if ( typeof select2 == 'function' ) {
            var preferred_lang_select = document.querySelector('.preferred_lang_select select');
            if(jQuery(preferred_lang_select).length) {
                jQuery('.preferred_lang_select select').select2({
                        //theme: "flat"
                });
            }
        //}
        
    


        //phone flags
        var country_code = function(callback) {
            jQuery.get("https://ipinfo.io/json?token=644fd25b60168e", function() {}, "json").always(function(resp) {
            var countryCode = (resp && resp.country) ? resp.country : "de";
            callback(countryCode);
            });
        };



        //execute only if intlTelInput is already loaded
        //if ( typeof intlTelInput == 'function' ) {
            document.querySelectorAll('.phone-container input[type=tel]:not([data-intl-tel-input-id])').forEach(function(el, index){
                const input = el;

                const iti = window.intlTelInput(input, {
                    separateDialCode: true,
                    strictMode: true,
                    initialCountry: "auto",
                    geoIpLookup: country_code,
                    preferredCountries: ['de', 'fr', 'uk', 'ch', 'at'],
                    utilsScript: "https://nextcloud.com/c/themes/nextcloud-theme/dist/js/utils.js",
                    //utilsScript: "https://staging.nextcloud.com/c/themes/nextcloud-theme/dist/js/utils.js",
                });

                if (typeof iti.getNumber === "function") {
                    jQuery(input).keyup(function(){
                        jQuery(this).val(iti.getNumber());
                    });
                }
            });
        //}



    });

});




jQuery( document ).ready( function( $ ) {

// if there is a ninja form on this page
if(typeof Marionette !== 'undefined') {
    //console.log('marionette loaded'); 
    //console.log(Marionette);

        var myCustomFieldController = Marionette.Object.extend( {
            initialize: function() {
                // init listener
                var fieldsChannel = Backbone.Radio.channel( 'fields' );
                this.listenTo( fieldsChannel, 'change:modelValue', this.validateRequired  );
            },

            validateRequired : function( model ) {
                // Check the field type.
                if( 'email' != model.get( 'type' ) ) return;
                // Only validate if the field is marked as required?
                if( 0 == model.get( 'required' ) ) return;
                //console.log("ID: "+model.get( 'id' )); // id = 2 - id of the field

                // Get the field value.
                var email = model.get( 'value' );
                
                var disposable_email_blocklist_private = "https://nextcloud.com/wp-content/themes/nextcloud-theme/inc/disposable_email_blocklist_private.txt";

                //const blocked_domains = [];
                //var blocked_domains_string = '';
                var isDisposable = false;
                //console.log("isDisposableEmail initialised");
                //console.log(isDisposable);

                var address_array = email.toLowerCase().split("@");
                //console.log(address_array);
                var domain = address_array[1];

                jQuery.get(disposable_email_blocklist_private,function(txt){
                    var lines = txt.split("\n");
                    for (var i = 0, len = lines.length; i < len; i++) {
                        if(domain == lines[i]){
                            //console.log('checkpoint 2');
                            isDisposable = true;
                            break;
                        }
                    }

                    //console.log("isDisposable before return");
                    console.log("is disposable? "+isDisposable);


                    if(isDisposable){
                        // Add Error to Model
                        //console.log('add error');
                        Backbone.Radio.channel( 'fields' ).request( 'add:error', model.get( 'id' ), 'custom-field-error', 'Please use a valid business email' );
                        
                    } else {
                        //console.log('remove error');
                        Backbone.Radio.channel( 'fields' ).request( 'remove:error', model.get( 'id' ), 'custom-field-error' );
                    }

                });


            }

        });

        jQuery(document).on( 'nfFormReady', function( e, layoutView ) {
            var form_id = layoutView.model.id;
            //console.log("Form ID: "+layoutView.model.id);

            if(
                form_id != 1 // exclude Contact form
                && form_id != 30 // exclude Discuss your app form
                && form_id != 27 // exclude Newsletter form
                && form_id != 33 // exclude Contact Issue form
                && form_id != 68 // exclude Events newsletter form
                && form_id != 72 // exclude  Events lead collection form
                && form_id != 85 // exclude Hub announcements form
                && form_id != 89 // exclude Unsubscribe form
                && form_id != 95 // exclude Conference 2024 form
                && form_id != 96 // exclude call for proposals form for Conference 2024
                && form_id != 98 // exclude Test Newsletters form
                && form_id != 100 // exclude developer-webinar-registration
                ) {
                new myCustomFieldController();
            }
        });

    } else {
        console.log('marionette could not be loaded.'); 
    }
        
});