jQuery(function($){
    let currentPage = 1;
    $('#loadNews').on('click', function() {
    currentPage++; // Do currentPage + 1, because we want to load the next page
    var button = $(this);
    var textBtn = button.html();

    $.ajax({
        type: 'POST',
        url: '/wp-admin/admin-ajax.php',
        dataType: 'html',
        data: {
            action: 'weichie_load_more',
            paged: currentPage,
        },
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