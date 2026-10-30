<?php
/*
 * Table of Contents Block Template.
 * Lists the H2/H3 headings of the post. The list is built by the script below.
 * Field: title (text) – so it can be translated per language.
 */
$title = get_field('title');
if (empty($title)) {
	$title = __('Table of contents', 'nextcloud');
}
?>
<div class="nc-toc<?php echo empty($is_preview) ? ' is-collapsed' : ''; ?>">
	<div class="nc-toc__header">
		<p class="nc-toc__title"><?php echo esc_html($title); ?></p>
		<button type="button" class="nc-toc__toggle" aria-controls="nc-toc-nav" aria-expanded="<?php echo empty($is_preview) ? 'false' : 'true'; ?>">
			<span class="nc-toc__sr"><?php echo __('Toggle table of contents', 'nextcloud'); ?></span>
			<svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M3 5h2v2H3zm4 0h14v2H7zm-4 6h2v2H3zm4 0h14v2H7zm-4 6h2v2H3zm4 0h14v2H7z"/></svg>
			<svg class="nc-toc__chevron" width="10" height="10" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 17.4 2.6 8l2.1-2.1L12 13.2l7.3-7.3L21.4 8z"/></svg>
		</button>
	</div>
	<nav class="nc-toc__nav" id="nc-toc-nav" aria-label="<?php echo esc_attr($title); ?>"<?php echo empty($is_preview) ? ' hidden' : ''; ?>>
		<?php
		if (!empty($is_preview)) {
			echo '<p class="nc-toc__notice">The headings of the page will be listed here.</p>';
		}
		?>
	</nav>
</div>
<?php if (empty($is_preview)) : ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
	var toc = document.querySelector('.nc-toc');
	if (!toc) return;

	var OFFSET = 30; // px kept above a heading when jumping to it (sticky header)
	var content = toc.closest('.entry-content, article') || toc.parentElement; // post content wrapper – adjust if needed
	var nav = toc.querySelector('.nc-toc__nav');
	var toggle = toc.querySelector('.nc-toc__toggle');

	var headings = Array.prototype.filter.call(content.querySelectorAll('h2, h3'), function (h) {
		return !toc.contains(h) && h.textContent.trim() !== '';
	});
	if (!headings.length) {
		toc.remove();
		return;
	}

	// Same id format as Easy TOC / WordPress (e.g. built-together-%f0%9f%a4%9d), so old anchor links keep working.
	function slugify(text) {
		return text.toLowerCase().trim()
			.replace(/[\u2018\u2019\u201c\u201d]/g, '')
			.replace(/[\u00a0\u2013\u2014]/g, '-')
			.replace(/[^\x00-\x7f]/gu, function (c) { return encodeURIComponent(c).toLowerCase(); })
			.replace(/\./g, '-')
			.replace(/[^%a-z0-9 _-]/g, '')
			.replace(/\s+/g, '-')
			.replace(/-+/g, '-')
			.replace(/^-|-$/g, '');
	}

	// Build the list: H2 items, with H3s nested under the H2 before them.
	var list = document.createElement('ul');
	var lastItem = null;
	var subList = null;

	headings.forEach(function (h) {
		if (!h.id) {
			var base = slugify(h.textContent) || 'section';
			var id = base;
			var n = 2;
			while (document.getElementById(id)) {
				id = base + '-' + n++;
			}
			h.id = id;
		}

		var li = document.createElement('li');
		var a = document.createElement('a');
		a.href = '#' + h.id;
		var text = document.createElement('span');
		text.className = 'nc-toc__text'; // underline goes on this, not on the number
		text.textContent = h.textContent.trim().replace(/\s+/g, ' ');
		a.appendChild(text);
		li.appendChild(a);

		if (h.tagName === 'H3' && lastItem) {
			if (!subList) {
				subList = document.createElement('ul');
				lastItem.appendChild(subList);
			}
			subList.appendChild(li);
		} else {
			list.appendChild(li);
			lastItem = li;
			subList = null;
		}
	});
	nav.appendChild(list);

	// Smooth scroll with offset
	var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	function scrollToHeading(el, smooth) {
		var top = el.getBoundingClientRect().top + window.pageYOffset - OFFSET;
		window.scrollTo({ top: top, behavior: smooth && !reduceMotion ? 'smooth' : 'auto' });
	}

	nav.addEventListener('click', function (e) {
		var a = e.target.closest('a');
		if (!a) return;
		var el = document.getElementById(a.getAttribute('href').slice(1));
		if (!el) return;
		e.preventDefault();
		scrollToHeading(el, true);
		history.pushState(null, '', a.getAttribute('href'));
	});

	// Show / hide the list
	toggle.addEventListener('click', function () {
		var open = toggle.getAttribute('aria-expanded') === 'true';
		toggle.setAttribute('aria-expanded', String(!open));
		nav.hidden = open;
		toc.classList.toggle('is-collapsed', open);
	});

	// The ids only exist now, so jump to a shared #anchor link ourselves.
	if (location.hash.length > 1) {
		var hash = location.hash.slice(1);
		var target = document.getElementById(hash) || document.getElementById(hash.toLowerCase());
		if (target) {
			window.addEventListener('load', function () {
				scrollToHeading(target, false);
			});
		}
	}
});
</script>
<?php endif; ?>