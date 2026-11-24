// Open/close arrow for the tables and rows of the "Nextcloud event agenda (Translatable)"
// element in the WPBakery backend editor. Styles are in dist/css/admin-theme.css.
jQuery(function ($) {
    var COLLAPSIBLE = '.nc_agenda_el[data-element_type="nc_agenda_table"], .nc_agenda_el[data-element_type="nc_agenda_row"]';

    // Collapsed elements by WPBakery model ID, so the state survives WPBakery re-rendering an element after editing
    var collapsed = {};

    function countLabel($el) {
        var count = $el.find('> .wpb_element_wrapper > .vc_container_for_children > .nc_agenda_el').length;
        return count === 1 ? '1 item' : count + ' items';
    }

    function setCollapsed($el, state) {
        var $wrapper = $el.children('.wpb_element_wrapper');

        $el.toggleClass('nc-agenda-collapsed', state);
        $wrapper.children('.nc-agenda-toggle').attr('aria-expanded', state ? 'false' : 'true');
        $wrapper.attr('data-nc-agenda-count', state ? countLabel($el) : '');

        if (state) {
            collapsed[$el.attr('data-model-id')] = true;
        } else {
            delete collapsed[$el.attr('data-model-id')];
        }
    }

    // Add the arrow to every table and row (also to ones added or re-rendered later)
    function addToggles() {
        $(COLLAPSIBLE).each(function () {
            var $el = $(this);
            var $wrapper = $el.children('.wpb_element_wrapper');

            if (!$wrapper.children('.nc-agenda-toggle').length) {
                $wrapper.prepend('<button type="button" class="nc-agenda-toggle" aria-expanded="true" title="Open/close"><i class="fas fa-chevron-down" aria-hidden="true"></i></button>');
            }
            if (collapsed[$el.attr('data-model-id')] && !$el.hasClass('nc-agenda-collapsed')) {
                setCollapsed($el, true);
            }
        });
    }

    $(document).on('click', '.nc-agenda-toggle', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var $el = $(this).closest('.nc_agenda_el');
        setCollapsed($el, !$el.hasClass('nc-agenda-collapsed'));
    });

    var container = document.getElementById('wpb_wpbakery');
    if (!container) {
        return;
    }

    var scheduled = false;
    new MutationObserver(function () {
        if (scheduled) {
            return;
        }
        scheduled = true;
        window.requestAnimationFrame(function () {
            scheduled = false;
            addToggles();
        });
    }).observe(container, { childList: true, subtree: true });

    addToggles();
});
