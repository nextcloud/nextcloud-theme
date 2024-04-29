jQuery(document).ready(function () {

    jQuery(document).on( 'nfFormReady', function( e, layoutView ) {
        //console.log("nfFormReady. "+layoutView.model.id);

        var preferred_lang_select = document.querySelector('.preferred_lang_select select');
        if(jQuery(preferred_lang_select).length) {
            jQuery('.preferred_lang_select select').select2({
                //theme: "flat"
            });
        }

        //phone flags
        var input = document.querySelector('input[name="phone"]');
        if(jQuery(input).length) {
            
            var iti = window.intlTelInput(input, {
                separateDialCode: true,        
                initialCountry: "auto",
                geoIpLookup: function(callback) {
                    jQuery.get("https://ipinfo.io/json?token=644fd25b60168e", function() {}, "json").always(function(resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "de";
                    callback(countryCode);
                    });
                },
                
                preferredCountries: ['de', 'fr', 'uk', 'ch', 'at'],
                utilsScript: "https://nextcloud.com/c/themes/nextcloud-theme/dist/js/utils.js",
                //utilsScript: "https://staging.nextcloud.com/c/themes/nextcloud-theme/dist/js/utils.js",
            });
            
            
            if (typeof iti.getNumber === "function") { 
                    jQuery(input).keyup(function(){
                        //console.log("iti getNumber: "+iti.getNumber());
                        jQuery(this).val(iti.getNumber());
                    });
            }

            
        }


        
    
    });
});




jQuery( document ).ready( function( $ ) {

// if there is a ninja form on this page
if(typeof Marionette !== 'undefined') {
    console.log('marionette loaded'); 
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
                console.log("ID: "+model.get( 'id' )); // id = 2 - id of the field

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
                    //console.log(isDisposable);
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
            //console.log(layoutView.model.id);
            var form_id = layoutView.model.id;
            if(form_id != 1 // exclude Contact form
                && form_id != 30 // exclude Discuss your app form
                && form_id != 27 // exclude Newsletter form
                && form_id != 33 // exclude Contact Issue form
                && form_id != 68 // exclude Events newsletter form
                && form_id != 72 // exclude  Events lead collection form
                && form_id != 85 // exclude Hub announcements form
                && form_id != 89 // exclude Unsubscribe form
                ) {
                // exclude Contact form, Discuss your app form, Newsletter form, Contact Issue form, Events newsletter form, Events lead collection form
                new myCustomFieldController();
            }
        });

    } else {
        console.log('marionette could not be loaded.'); 
    }


        

        
});