jQuery(document).ready(function () {
    window.forms = [];

    //pre populate select options for the meetups 
    jQuery(document).on('nfFormReady', function () {
        var $select = jQuery('#nf-field-1611');
        if (!$select.length) return;

        jQuery('.nc_meetup').each(function () {
            var headline = jQuery(this)
            .find('h4.meetup__headline')
            .first()
            .text()
            .trim();

            if (!headline) return;

            // Avoid duplicate options
            if (
            $select.find('option').filter(function () {
                return jQuery(this).val() === headline;
            }).length === 0
            ) {
            $select.append(new Option(headline, headline));
            }
        });


        jQuery('.nc_meetup a.register').click(function(){
            $select.val(jQuery(this).parents('.nc_meetup').find('.meetup__headline').text().trim()).trigger('change');
        });


        //for the table
        jQuery('.events_table tr').each(function () {
            var headline = jQuery(this)
            .find('td:first-child a.meetup_link')
            .text()
            .trim();

            if (!headline) return;

            // Avoid duplicate options
            if (
            $select.find('option').filter(function () {
                return jQuery(this).val() === headline;
            }).length === 0
            ) {
            $select.append(new Option(headline, headline));
            }
        });


        jQuery('.events_table a.meetup_link').click(function(){
            $select.val(jQuery(this).text().trim()).trigger('change');
        });


    });


    jQuery(document).on( 'nfFormReady', function( e, layoutView ) {


        //console.log("nfFormReady. "+layoutView.model.id);
        //window.formsLoaded = true;
        forms.push(layoutView.model.id);
        var formId = layoutView.model.id;
        console.log("formId: "+formId);
        var formModel = nfRadio.channel('forms').request('get:model', formId);
        var input_tickets_numbers = jQuery('input#nf-field-1124');
        if(formId == 90) {
            input_tickets_numbers = jQuery('input#nf-field-1459');
        }
        //console.log("input_tickets_numbers: "+input_tickets_numbers);


        // on number of tickets change, add or remove the extra additional names in the Summit LP form 
        jQuery('.additional_attendees ').hide();
        var orig_tickets = 1;
        var prev_tickets = input_tickets_numbers.val(); // default 1
        var extraFields = [];
        var fieldIDs = [];

        input_tickets_numbers.on('change', function(){
            let new_tickets = parseInt(jQuery(this).val());
            //console.log("Changed! New ticket number: "+new_tickets);

            var extraField = jQuery('.additional_attendees').find('input[name="custom_name_additional_name"]');
            //console.log("How many extra names? " + extraField.length);  

            var fieldID = jQuery('.additional_attendees .nf-repeater-fieldsets').find('.field-wrap').data('field-id');


            if(new_tickets > prev_tickets){
                //increase 
                
                if(new_tickets > 1) {
                    //console.log("Show section!");
                    //show the section, which already contains one field 
                    jQuery('.additional_attendees ').show();
                    fieldIDs.push(fieldID);
                }
                if (new_tickets > 2) {
                    //console.log("Click trigger!");
                    jQuery('.additional_attendees button.nf-add-fieldset').trigger('click');
                }

                //add red asterisk as the field is mandatory
                jQuery('.additional_attendees fieldset').last().find('.nf-field-label label').append('<span class="ninja-forms-req-symbol">*</span>');


                
            } else {
                //decrease 
                //console.log("Decrease! New value is: "+new_tickets);

                if(new_tickets > 1) {
                    //console.log("Remove last fieldset!");
                    //jQuery('.additional_attendees fieldset').last().remove(); // is this working?? 
                    jQuery('.additional_attendees fieldset').last().find('button.nf-remove-fieldset').trigger('click');
                }
                
                if(new_tickets == 1) {
                    //console.log("Section hidden!");
                    jQuery('.additional_attendees ').hide();
                    
                    //set extra name fields as not required
                    if (fieldModel) {
                        fieldModel.set('required', false);
                        //If the error message persists, try forcing a re-render
                        fieldModel.trigger('change:value', fieldModel);
                        // Trigger a change to update the form and apply the required setting
                        nfRadio.channel('fields').request('remove:error', fieldModel.get('id'), 'required-error');  // Clear error
                    }
                  
                    

                }
                
            }

            

            //update array 
            extraFields = jQuery('.additional_attendees')
            .find('input[name="custom_name_additional_name"]')
            .toArray();
            //console.log("How many extra names? " + extraFields.length);

            fieldIDs = jQuery('.additional_attendees .nf-repeater-fieldsets .field-wrap')
            .map(function() {
                return jQuery(this).data('field-id');
            })
            .get(); // .get() converts it to a plain JS array
            //console.log("fieldIDs: "+fieldIDs);



            if(new_tickets > prev_tickets) { // increasing
                fieldIDs.forEach(function(fieldID) {
                    // Do something with each id
                    //console.log("Working with field ID:", fieldID);
                    var fieldModel = nfRadio.channel('fields').request('get:field', fieldID); // Get Ninja Forms field model
                    if (fieldModel) {
                        fieldModel.set('required', true);
                    }
                });
            } else {
                //decreasing
                fieldIDs.forEach(function(fieldID) {
                    var fieldModel = nfRadio.channel('fields').request('get:field', fieldID); // Get Ninja Forms field model

                    if (fieldModel) {
                        fieldModel.set('required', false);
                        //If the error message persists, try forcing a re-render
                        fieldModel.trigger('change:value', fieldModel);
                        // Trigger a change to update the form and apply the required setting
                        nfRadio.channel('fields').request('remove:error', fieldModel.get('id'), 'required-error');  // Clear error
                    }
                });

            }


            //update prev_tickets
            prev_tickets = new_tickets;

        });




        jQuery('.select_ed_slides select').on('change', function(){
            var index = this.selectedIndex;

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
        //if ( typeof select2 == 'function' ) {
        var preferred_lang_select = document.querySelector('.preferred_lang_select select');
        if(jQuery(preferred_lang_select).length) {
            jQuery('.preferred_lang_select select').select2();
        }
        //}
        
        //hide preferred language on non-english pages
        jQuery("body:not(.lang-en) .nf-form-content .preferred_lang_select").parents('nf-field').hide();
    


        //phone flags
        var country_code = function(callback) {
            jQuery.get("https://ipinfo.io/json?token=644fd25b60168e", function() {}, "json").always(function(resp) {
            var countryCode = (resp && resp.country) ? resp.country : "de";
            callback(countryCode);
            });
        };



        //execute only if intlTelInput is already loaded
        //if ( typeof intlTelInput == 'function' ) {
            document.querySelectorAll('.phone-container:not(.no_intlTelInput) input[type=tel]:not([data-intl-tel-input-id])').forEach(function(el, index){
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




// if there is a ninja form on this page
//if(typeof Marionette !== 'undefined') {
    //console.log('marionette loaded'); 
    //console.log(Marionette);

        var myCustomFieldController = Marionette.Object.extend( {
            initialize: function() {
                // init listener
                var fieldsChannel = Backbone.Radio.channel( 'fields' );
                this.listenTo( fieldsChannel, 'change:modelValue', this.validateRequired  );
                //console.log("myCustomFieldController is initalized.");
            },

            validateRequired : function( model ) {
                // Check the field type.
                if( 'email' != model.get( 'type' ) ) return;
                // Only validate if the field is marked as required?
                if( 0 == model.get( 'required' ) ) return;
                //console.log("ID of the field: "+model.get( 'id' )); // id = 2 - id of the field

                // Get the field value.
                var email = model.get( 'value' );
                
                //var disposable_email_blocklist_private = "https://nextcloud.com/wp-content/themes/nextcloud-theme/inc/disposable_email_blocklist_private.txt";
                var disposable_email_blocklist_private = "https://nextcloud.com/wp-content/domain-blocklist.txt";

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


        //jQuery(document).on( 'nfFormReady', function( e, layoutView ) {
            var form_id = layoutView.model.id;
            //console.log("how many forms?" + form_id.length);
            //console.log("Form ID: " + form_id);


            if(
                form_id != 1 // exclude Contact form
                && form_id != 30 // exclude Discuss your app form
                && form_id != 27 // exclude Newsletter form
                && form_id != 33 // exclude Contact Issue form
                && form_id != 68 // exclude Events newsletter form
                && form_id != 72 // exclude  Events lead collection form
                && form_id != 85 // exclude Hub announcements form
                && form_id != 89 // exclude Unsubscribe form
                && form_id != 90 // exclude Debug Test form
                && form_id != 95 // exclude Conference 2024 form
                && form_id != 96 // exclude call for proposals form for Conference 2024
                && form_id != 98 // exclude Test Newsletters form
                && form_id != 100 // exclude developer-webinar-registration
                && form_id != 113 // exclude Summit Testing payment form
                && form_id != 116 // exclude Conference announcements form
                && form_id != 118 // exclude Partners newsletter form
                && form_id != 123 // exclude Summit awards form
                && form_id != 124 // exclude Conference 2025 form
                && form_id != 125 // exclude Call for proposals conference 2025
                && form_id != 127 // exclude Conference 2025 registration - with payment
                && form_id != 132 // exclude Generic Enterprise day (Paris, Hague, etc) - With payment
                && form_id != 134 // exclude Hub announcements mailing list form
                && form_id != 135 // exclude Call for proposals Summit
                && form_id != 136  // exclude Unsubscribe form
                && form_id != 137 // exclude form Submit Meetup idea
                && form_id != 138 // exclude form Attend to the meetup
                && form_id != 141 // exclude short Newsletter form
                && form_id != 143 // exclude Hub release form for webinars
                && form_id != 151 // exclude form for chinese webinars - template-webinar-registration-cn
                ) {
                new myCustomFieldController();
                }

        //});

    // } else {
    //     console.log('marionette could not be loaded.'); 
    // }


    //dynamically fill in the utm parameters in the forms 
    const keys = [
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_content',
        'utm_term',
        'ref'
    ];

    function getCookieJson(name) {
        const match = document.cookie
        .split('; ')
        .find(row => row.startsWith(name + '='));

        if (!match) return {};

        try {
        return JSON.parse(decodeURIComponent(match.split('=')[1]));
        } catch (e) {
        return {};
        }
    }

    function getTrackingValue(key) {
        const params = new URLSearchParams(window.location.search);

        if (params.has(key) && params.get(key) !== '') {
        return params.get(key);
        }

        const cookieParams = getCookieJson('nc_utm_parameters');

        if (
        cookieParams &&
        cookieParams[key] !== undefined &&
        cookieParams[key] !== null &&
        cookieParams[key] !== ''
        ) {
        return cookieParams[key];
        }

        return '';
    }

    function setNinjaField(input, value) {
        input.value = value;
        input.dispatchEvent(new Event('change', { bubbles: true }));

        const fieldID = parseInt(input.id.replace('nf-field-', ''), 10);

        if (typeof nfRadio !== 'undefined' && fieldID) {
        const model = nfRadio.channel('fields').request('get:field', fieldID);
        if (model) {
            model.set('value', value);
        }
        }
    }

    keys.forEach(function (key) {
        const value = getTrackingValue(key);

        if (!value) return;

        document
        .querySelectorAll('[data-testid="nf-field-' + key + '"] input')
        .forEach(function (input) {
            setNinjaField(input, value);
        });
    });





    }); // if nfFormReady
});