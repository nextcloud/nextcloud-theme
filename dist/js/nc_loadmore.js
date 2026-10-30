jQuery(function($){

    $('#loadNews_test').each(function(){
        let currentPage = 1;
        var count = $(this).data('count');
        var limit = $(this).data('limit');


        $(this).on('click', function() {
            var thisBtn = $(this);
            //console.log("Current page:" + currentPage);
            currentPage++; // Do currentPage + 1, because we want to load the next page
            //console.log("next loaded page:" + currentPage);
            console.log("AJax URL: "+nc_loadmore_strings.ajaxurl);

            var button = $(this);
            var textBtn = button.html();

            var action = 'nc_load_more_test';
            var data = {
                paged: currentPage,
                post_type: $(this).data('post-type'),
                count : $(this).data('count'),
                limit : $(this).data('limit')
            };

            data.action = action;

            $.ajax({
                type: 'POST',
                url: nc_loadmore_strings.ajaxurl,
                dataType: 'html',
                data: data,
                beforeSend : function ( xhr ) {
                    button.text(nc_loadmore_strings.loading); // change the button text, you can also add a preloader image
                    //console.log(data);
                },
                success: function (res) {
                    $(thisBtn).parents('.loadNews_row').siblings('.row-list-blog').append(res);
                    button.text(textBtn);

                    //console.log("current page: "+currentPage);
                    //console.log("count: "+count);
                    //console.log("limit: "+limit);

                    if( (currentPage * limit) > count) {
                        button.hide();
                    }
                },
                error: function (res) {
                    $(thisBtn).parents('.loadNews_row').siblings('.row-list-blog').append(res);
                    button.text(textBtn);
                    
                }
            });
        });
    });




    $('.loadNews').each(function(){
        let currentPage = 1;
        var count = $(this).data('count');
        var limit = $(this).data('limit');


        $(this).on('click', function() {
            var thisBtn = $(this);
            //console.log("Current page:" + currentPage);
            currentPage++; // Do currentPage + 1, because we want to load the next page
            //console.log("next loaded page:" + currentPage);
            var button = $(this);
            var textBtn = button.html();

            var action = 'nc_load_more';
            var data = {
                paged: currentPage,
                post_type: $(this).data('post-type'),
                count : $(this).data('count'),
                limit : $(this).data('limit')
            };

            if($(this).data("category")){
                var cat_id = $(this).data("category");
                //console.log(cat_id);
                action = 'nc_cat_load_more';
                data.category = cat_id;
            }

            //searching page
            if($(this).data("search")){
                action = 'nc_search_load_more';
                data.post_type = $(this).data('post-type');
                data.search_query = $(this).data('search_query');
            }


            if($(this).data('post-type') == 'event') {
                var cat_id = $(this).data("category");

                if(cat_id && cat_id!='') {
                    //console.log("cat_id: "+cat_id);
                    action = 'nc_term_load_more';
                    data.category = cat_id;
                    data.post_type = $(this).data('post-type');
                } else {
                    //console.log("NC load more events");
                    action = 'nc_load_more';
                    data.post_type = $(this).data('post-type');
                }
            }

            if(
                $(this).data('post-type') == 'case_studies'
                || $(this).data('post-type') == 'whitepapers'
                || $(this).data('post-type') == 'data_sheets'
                ) {
                action = 'nc_whitepapers_load_more';
                data.post_type = $(this).data('post-type');
                //data.limit = $(this).data('limit');
            }

            if($(this).data('post-type') == 'whitepaper_posts') {
                action = 'nc_whitepaper_posts_load_more';
                data.post_type = 'post';
            }


            if($(this).data('post-type') == 'past_webinars') {
                action = 'nc_past_webinars_load_more';
                //data.post_type = 'event';
                data.post_type = 'on-demand-webinar';
            }

            if($(this).data('include_all_lang')) {
                data.include_all_lang = true;
                data.recordings_ids = $(this).data('recordings_ids');
            }

            if($(this).data('filtered_tag')) {
                data.filtered_tag = true;
                data.filtered_tag = $(this).data('filtered_tag');
            }


            data.action = action;

            $.ajax({
                type: 'POST',
                url: nc_loadmore_strings.ajaxurl,
                dataType: 'html',
                data: data,
                beforeSend : function ( xhr ) {
                    button.text(nc_loadmore_strings.loading); // change the button text, you can also add a preloader image
                    console.log(data);
                },
                success: function (res) {
                    $(thisBtn).parents('.loadNews_row').siblings('.row-list-blog').append(res);
                    button.text(textBtn);

                    //console.log("AJax URL: "+nc_loadmore_strings.ajaxurl);
                    //console.log("current page: "+currentPage);
                    //console.log("count: "+count);
                    //console.log("limit: "+limit);

                    if( (currentPage * limit) >= count) {
                        button.hide();
                    }
                },
                error: function (res) {
                    $(thisBtn).parents('.loadNews_row').siblings('.row-list-blog').append(res);
                    button.text(textBtn);
                }
            });
        });
    });



    //click on tag button loads webinar recordings 
    $('a.webinar_tag').each(function(){
        //let currentPage = 1;
        //var count = $(this).data('count');
        //var limit = $(this).data('limit');

        $(this).on('click', function(e) {
            e.preventDefault();
            
            var thisBtn = $(this);
            thisBtn.siblings('.webinar_tag').removeClass("active");
            var tag_label = thisBtn.html();
            thisBtn.addClass("active");


            $(thisBtn).parents('.row').siblings('.loadNews_row').find('button.loadNews').attr('data-filtered_tag', thisBtn.data("tag_id"));
            //console.log("Tag ID: "+thisBtn.data("tag_id"));


            var action = 'nc_webinar_recordings_load_tagged';
            var data = {
                //paged: currentPage,
                post_type: 'on-demand-webinar',
                //count : $(this).data('count'),
                //limit : $(this).data('limit')
            };
            data.tag_id = $(this).data("tag_id");


            data.action = action;
            $.ajax({
                type: 'POST',
                url: nc_loadmore_strings.ajaxurl,
                dataType: 'html',
                data: data,
                beforeSend : function ( xhr ) {
                    //thisBtn.text(nc_loadmore_strings.loading); // change the button text, you can also add a preloader image
                    //load the whole container
                    $(thisBtn).parents('.row').siblings('.row-list-blog').css('opacity', 0.5);
                    //console.log(data);
                },
                success: function (res) {
                    $(thisBtn).parents('.row').siblings('.row-list-blog').empty();
                    $(thisBtn).parents('.row').siblings('.row-list-blog').css('opacity', '');
                    $(thisBtn).parents('.row').siblings('.row-list-blog').append(res);
                    //thisBtn.text(textBtn);

                    $(thisBtn).parents('.row').siblings('.loadNews_row').hide();
                    
                    
                },
                error: function (res) {
                    $(thisBtn).parents('.loadNews_row').siblings('.row-list-blog').append(res);
                    //thisBtn.text(textBtn);
                }
            });
        });
    });



});