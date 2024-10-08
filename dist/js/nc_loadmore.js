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
                data.post_type = 'event';
            }


            data.action = action;

            $.ajax({
                type: 'POST',
                url: '/wp-admin/admin-ajax.php',
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

});