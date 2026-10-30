<form action="/" role="search" class="search-form" method="get">
	<label for="search">
        <span class="screen-reader-text">Search for:</span>    
        <input type="search" class="search-field " placeholder="Search …" value="<?php the_search_query(); ?>" name="s">    
    </label>
	<input type="text" name="s" id="search" value="<?php the_search_query(); ?>" />
	<input type="image" alt="Search" src="<?php bloginfo('template_url'); ?>/images/search.png" />
</form>