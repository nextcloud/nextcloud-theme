jQuery(function($){
    let currentPage = 1;
    $('#loadNews').on('click', function() {
    currentPage++; // Do currentPage + 1, because we want to load the next page
    var button = $(this);
    var textBtn = button.html();
    var action = 'nc_load_more';
    var data = {
        paged: currentPage
    };
    if($(this).data("category")){
        var cat_id = $(this).data("category");
        console.log(cat_id);
        action = 'nc_cat_load_more';
        data.category = cat_id;
    }
    data.action = action;

    $.ajax({
        type: 'POST',
        url: '/wp-admin/admin-ajax.php',
        dataType: 'html',
        data: data,
        beforeSend : function ( xhr ) {
            button.text('Loading...'); // change the button text, you can also add a preloader image
        },
        success: function (res) {
            $('.row-list-blog').append(res);
            button.text(textBtn);
        }
    });
    });
});