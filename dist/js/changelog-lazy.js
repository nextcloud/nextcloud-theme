(function ($) {

    const $container = $("#changelog-container");
    const $sentinel  = $("#changelog-sentinel");

    if (!$container.length || !$sentinel.length || typeof ChangelogAjax === "undefined") {
        return;
    }

    let nextIndex = parseInt($sentinel.data("next-index"), 10) || 0; // start from where PHP stopped
    let postId    = parseInt($sentinel.data("post-id"), 10);
    let loading   = false;
    let hasMore   = true;

    function loadNextBatch() {
        if (loading || !hasMore) return;
        loading = true;

        $.post(ChangelogAjax.ajax, {
            action:     "my_changelog_load_sections_batch",
            nonce:      ChangelogAjax.nonce,
            post_id:    postId,
            startIndex: nextIndex
        }, function (response) {
            if (response.success && response.data.html) {
                $container.append(response.data.html);

                hasMore   = response.data.has_more;
                nextIndex = response.data.nextIndex;
            } else {
                hasMore = false;
            }

            loading = false;
        });
    }

    const observer = new IntersectionObserver(
        entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    loadNextBatch();
                }
            });
        },
        { rootMargin: "300px" } // start loading a bit before visible bottom
    );

    observer.observe($sentinel[0]);

})(jQuery);