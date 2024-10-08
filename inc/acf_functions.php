<?php
//enable shortcode to be used inside textareas
add_filter('acf/format_value/type=textarea', 'do_shortcode');


//get countries function to append on the region field for partners
function get_countries($continent) {
	$continents = [
		"Europe" => ["Albania","Andorra","Armenia","Austria","Azerbaijan","Belarus","Belgium","Bosnia and Herzegovina","Bulgaria","Croatia","Cyprus","Czech Republic","Denmark","Estonia","Finland","France","Georgia","Germany","Greece","Iceland","Ireland","Italy","Kazakhstan","Kosovo","Latvia","Liechtenstein","Lithuania","Luxembourg","Macedonia","Malta","Moldova","Monaco","Montenegro","Netherlands","Norway","Poland","Portugal","Romania","Russia","San Marino","Serbia","Slovakia","Slovenia","Spain","Sweden","Switzerland","Ukraine","United Kingdom","Vatican City"],

		"North America" => ["Antigua and Barbuda","Bahamas","Barbados","Belize","Canada","Costa Rica","Cuba","Dominica","Dominican Republic","El Salvador","Grenada","Guatemala","Haiti","Honduras","Jamaica","Mexico","Nicaragua","Panama","Saint Kitts and Nevis","Saint Lucia","Saint Vincent and the Grenadines","Trinidad and Tobago","United States of America"],
		
		"South America" => ["Argentina", "Bolivia","Brazil","Chile","Colombia","Ecuador","Guyana","Paraguay","Peru","Suriname","Uruguay","Venezuela"],

		"Africa" => ["Algeria","Angola","Benin","Botswana","Burkina Faso","Burundi","Cabo Verde","Cameroon","Central African Republic","Chad","Comoros","Democratic Republic of the Congo","Republic of the Congo","Cote d'Ivoire","Djibouti","Equatorial Guinea","Eritrea","Ethiopia","Gabon","Gambia","Ghana","Guinea","Guinea Bissau","Kenya","Lesotho","Liberia","Libya","Madagascar","Malawi","Mali","Mauritania","Mauritius","Morocco","Mozambique","Namibia","Niger","Nigeria","Rwanda","Sao Tome and Principe","Senegal","Seychelles","Sierra Leone","Somalia","South Africa","South Sudan","Sudan","Swaziland","Tanzania","Togo","Tunisia","Uganda","Zambia","Zimbabwe"],
		
		"Asia" => ["Afghanistan", "Armenia","Azerbaijan","Bangladesh","Bhutan","Brunei", "Cambodia","China","Georgia","India","Indonesia", "Japan","Kazakhstan","Kyrgyzstan","Laos","Malaysia","Maldives","Mongolia","Myanmar","Nepal","North Korea","Pakistan","Philippines","Russia","Singapore","South Korea","Sri Lanka","Taiwan","Tajikistan","Thailand","Timor Leste","Turkmenistan","Uzbekistan","Vietnam"],

		"Middle East" => ["Bahrain","Cyprus","Egypt","Iran","Iraq","Israel","Jordan","Kuwait","Lebanon","Oman","Palestine","Qatar","Saudi Arabia","Syria","Turkey","United Arab Emirates","Yemen"],

		"Ocean Pacific" => ["Australia","Federated Islands of Micronesia","Fiji","French Polynesia","Guam","Kiribati","Marshall Islands","Nauru","New Zealand","Paulau","Papua New Guinea","Samoa","Solomon Islands","Tonga","Tuvala","Vanuata"]
	];

	if($continent != 'all') {
		$countries = $continents[$continent];
		$countries_new = [];
		foreach($countries as $country) {
			//$country_value = preg_replace("/[^a-zA-Z]+/", "", $country);
			$country_value = $country;
			$countries_new[$country_value] = $country;
		}
		return $countries_new;

	} else {
		return $continents;
	}

}


// Add choices dynamically to field with key "field_6266caac1a171" - Region for partners
add_filter('acf/prepare_field/key=field_6266caac1a171', 'nc_populate_countries_field');
function nc_populate_countries_field($field) {
	//$field['required'] = true;

	$field['choices'] = [
		//europe
		'Europe' => 'Europe',
		'eu_countries' => get_countries('Europe'),
		//'eu_countries' => array("Albania","Andorra","Armenia","Austria","Azerbaijan","Belarus","Belgium","Bosnia and Herzegovina", "Bulgaria","Croatia","Cyprus","Czech Republic","Denmark","Estonia","Finland","France","Georgia","Germany","Greece","Iceland","Ireland","Italy","Kazakhstan","Kosovo","Latvia","Liechtenstein","Lithuania","Luxembourg","Macedonia","Malta","Moldova","Monaco","Montenegro","Netherlands","Norway","Poland","Portugal","Romania","Russia","San Marino","Serbia","Slovakia","Slovenia","Spain","Sweden","Switzerland","Ukraine","United Kingdom","Vatican City"),

		//north america
		'North America' => 'North America',
		//'north_america_countries' => get_countries('North America'),
		'north_america_countries' => ["Antigua and Barbuda","Bahamas","Barbados","Belize","Canada","Costa Rica","Cuba","Dominica","Dominican Republic","El Salvador","Grenada","Guatemala","Haiti","Honduras","Jamaica","Mexico","Nicaragua","Panama","Saint Kitts and Nevis","Saint Lucia","Saint Vincent and the Grenadines","Trinidad and Tobago","United States of America"],
		
		//latin america
		'South America' => 'South America',
		//'south_america_countries' => get_countries('South America'),
		'south_america_countries' => ["Argentina", "Bolivia","Brazil","Chile","Colombia","Ecuador","Guyana","Paraguay","Peru","Suriname","Uruguay","Venezuela"],

		//africa
		'Africa' => 'Africa',
		//'africa_countries' => get_countries('Africa'),
		'africa_countries' => ["Algeria","Angola","Benin","Botswana","Burkina Faso","Burundi","Cabo Verde","Cameroon","Central African Republic","Chad","Comoros","Democratic Republic of the Congo","Republic of the Congo","Cote d'Ivoire","Djibouti","Equatorial Guinea","Eritrea","Ethiopia","Gabon","Gambia","Ghana","Guinea","Guinea Bissau","Kenya","Lesotho","Liberia","Libya","Madagascar","Malawi","Mali","Mauritania","Mauritius","Morocco","Mozambique","Namibia","Niger","Nigeria","Rwanda","Sao Tome and Principe","Senegal","Seychelles","Sierra Leone","Somalia","South Africa","South Sudan","Sudan","Swaziland","Tanzania","Togo","Tunisia","Uganda","Zambia","Zimbabwe"],

		//asia
		'Asia' => 'Asia',
		//'asia_countries' => get_countries('Asia'),
		'asia_countries' => ["Afghanistan", "Armenia","Azerbaijan","Bangladesh","Bhutan","Brunei", "Cambodia","China","Georgia","India","Indonesia", "Japan","Kazakhstan","Kyrgyzstan","Laos","Malaysia","Maldives","Mongolia","Myanmar","Nepal","North Korea","Pakistan","Philippines","Russia","Singapore","South Korea","Sri Lanka","Taiwan","Tajikistan","Thailand","Timor Leste","Turkmenistan","Uzbekistan","Vietnam"],

		//middle east
		'Middle East' => 'Middle East',
		'middle_east_countries' => ["Bahrain","Cyprus","Egypt","Iran","Iraq","Israel","Jordan","Kuwait","Lebanon","Oman","Palestine","Qatar","Saudi Arabia","Syria","Turkey","United Arab Emirates","Yemen"],

		//pacific
		'Ocean Pacific' => 'Ocean Pacific',
		'pacific_countries' => ["Australia","Federated Islands of Micronesia","Fiji","French Polynesia","Guam","Kiribati","Marshall Islands","Nauru","New Zealand","Paulau","Papua New Guinea","Samoa","Solomon Islands","Tonga","Tuvala","Vanuata"],

		'other' => 'Other'
	];
	return $field;
}


function my_acf_input_admin_footer() {
	?>
    <style>
        .acf-field[data-key="field_6266caac1a171"] ul.acf-checkbox-list {
            max-width: 250px;
        }

        .acf-field[data-key="field_6266caac1a171"] ul.acf-checkbox-list label {
            display: block;
        }

        .acf-field[data-key="field_6266caac1a171"] ul.acf-checkbox-list li.children_list.opened {
            
        }

        .acf-field[data-key="field_6266caac1a171"] ul.acf-checkbox-list ul {
            margin-left: 20px;
        }

        .acf-field[data-key="field_6266caac1a171"] ul.acf-checkbox-list li.parent {
            position: relative;
            margin: 12px 0;
        }

        .acf-field[data-key="field_6266caac1a171"] ul.acf-checkbox-list li.parent:hover {
            cursor: pointer;
        }

        .acf-field[data-key="field_6266caac1a171"] ul.acf-checkbox-list li .opener {
            position: absolute;
            right: 5px;
            top: 0;
            bottom: 0;
            margin: auto;
            line-height: 25px;
            width: 25px;
            text-align: center;
            background: #f0f0f0;
            border-radius: 5px;
        }
    </style>

    <script type="text/javascript">
    (function($) {
        
        // JS here
        //console.log("ACF test");
        $('.acf-field[data-key=field_6266caac1a171] ul.acf-checkbox-list > li').each(function(){
            
            if($(this).find('ul').length !== 0) {
                //console.log('it has ul inside');
                $(this).hide();
                $(this).addClass('children_list');
                $(this).prev().addClass('parent');
                $(this).prev().append('<div class="opener fa fa-angle-down"></div>');
            } else {
                //console.log('it doesnt have ul inside');
                //$(this).addClass('parent');
            }
        });

        $('.acf-field[data-key=field_6266caac1a171] li.parent .opener').click(function(){
            $(this).toggleClass('fa-angle-up');
            $(this).parents('li').next('li.children_list').slideToggle();
            $(this).parents('li').next('li.children_list').toggleClass('opened');
        });

        //when selecting or deselecting the parent checkbox, children checkboxes will be also selected or deselected 
        $('.acf-field[data-key=field_6266caac1a171] li.parent input[type="checkbox"]').change(function(){
            if(this.checked) {
                $(this).parents('li.parent').next('.children_list').find('input[type="checkbox"]').prop( "checked", true );
            } else {
                $(this).parents('li.parent').next('.children_list').find('input[type="checkbox"]').prop( "checked", false );
            }
        });

        //select parent checkbox when one of the children is selected
        $('.acf-field[data-key=field_6266caac1a171] li.children_list input[type="checkbox"]').change(function(){
            if(this.checked) {
                $(this).parents('li.children_list').prev('.parent').find('input[type="checkbox"]').prop( "checked", true );
            }
        });

        
    })(jQuery); 
    </script>
    <?php
			
}
add_action('acf/input/admin_footer', 'my_acf_input_admin_footer');



function my_acf_op_init() {

	// Check function exists.
	if (function_exists('acf_add_options_page')) {
		// Add parent.
		$parent = acf_add_options_page([
			'page_title' => __('Theme General Settings'),
			'menu_title' => __('Theme Settings'),
			'redirect' => false,
		]);
	}
}
add_action('acf/init', 'my_acf_op_init');


function my_theme_block_category($categories, $post) {
	return array_merge(
		$categories,
		[
			[
				'slug' => 'theme-blocks',
				'title' => __('Theme Blocks', 'theme-blocks'),
			],
		]
	);
}

add_filter('block_categories', 'my_theme_block_category', 10, 2);

function register_acf_block_types() {
	// register a Home Hero Block
	acf_register_block_type([
		'name' => 'home-hero-block',
		'title' => __('Home Hero Block'),
		'description' => __('Home Hero Block'),
		'render_template' => 'blocks/home-hero.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'desktop',
		'keywords' => ['home', 'hero'],
	]);
	// register a Page Hero Block
	acf_register_block_type([
		'name' => 'page-hero-block',
		'title' => __('Page Hero Block'),
		'description' => __('Page Hero Block'),
		'render_template' => 'blocks/page-hero.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'desktop',
		'keywords' => ['page', 'hero'],
	]);
	// register a Page Hero Block Background
	acf_register_block_type([
		'name' => 'page-hero-block-background',
		'title' => __('Page Hero Block Background'),
		'description' => __('Page Hero Block Background'),
		'render_template' => 'blocks/page-hero-background.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'desktop',
		'keywords' => ['page', 'hero-background'],
	]);
	// register a Collaboration Block
	acf_register_block_type([
		'name' => 'collaboration-block',
		'title' => __('Collaboration Block'),
		'description' => __('Collaboration Block'),
		'render_template' => 'blocks/collaboration.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'admin-site-alt2',
		'keywords' => ['collaboration', 'content'],
	]);
	// register a Why Nextcloud Block
	acf_register_block_type([
		'name' => 'why-block',
		'title' => __('Why Nextcloud Block'),
		'description' => __('Why Nextcloud Block'),
		'render_template' => 'blocks/why.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['why', 'content'],
	]);
	// register a Needs Block
	acf_register_block_type([
		'name' => 'needs-block',
		'title' => __('Needs Block'),
		'description' => __('Needs Block'),
		'render_template' => 'blocks/needs.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['needs', 'content'],
	]);
	// register a Needs 2 Block
	acf_register_block_type([
		'name' => 'needs2-block',
		'title' => __('Needs 2 Block'),
		'description' => __('Needs 2 Block'),
		'render_template' => 'blocks/needs2.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['needs', 'content'],
	]);
	// register a Products Block
	acf_register_block_type([
		'name' => 'products-block',
		'title' => __('Products Block'),
		'description' => __('Products Block'),
		'render_template' => 'blocks/products.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['products', 'content'],
	]);
	// register a Columns Block
	acf_register_block_type([
		'name' => 'columns-block',
		'title' => __('Columns Block'),
		'description' => __('Columns Block'),
		'render_template' => 'blocks/columns.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['columns', 'content'],
	]);
	// register a Columns 2 Block
	acf_register_block_type([
		'name' => 'columns2-block',
		'title' => __('Columns 2 Block'),
		'description' => __('Columns 2 Block'),
		'render_template' => 'blocks/columns2.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['columns', 'content'],
	]);
	// register a Columns 3 Block
	acf_register_block_type([
		'name' => 'columns3-block',
		'title' => __('Columns 3 Block'),
		'description' => __('Columns 3 Block'),
		'render_template' => 'blocks/columns3.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['columns', 'content'],
	]);
	// register a Columns 4 Block
	acf_register_block_type([
		'name' => 'columns4-block',
		'title' => __('Columns 4 Block'),
		'description' => __('Columns 4 Block'),
		'render_template' => 'blocks/columns4.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['columns', 'content'],
	]);
	// register a Columns 5 Block
	acf_register_block_type([
		'name' => 'columns5-block',
		'title' => __('Columns 5 Block'),
		'description' => __('Columns 5 Block'),
		'render_template' => 'blocks/columns5.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['columns', 'content'],
	]);
	// register a Columns 6 Block
	acf_register_block_type([
		'name' => 'columns6-block',
		'title' => __('Columns 6 Block'),
		'description' => __('Columns 6 Block'),
		'render_template' => 'blocks/columns6.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['columns', 'content'],
	]);
	// register a Compliant Block
	acf_register_block_type([
		'name' => 'compliant-block',
		'title' => __('Compliant Block'),
		'description' => __('Compliant Block'),
		'render_template' => 'blocks/compliant.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['compliant', 'content'],
	]);
	// register a Promo Block
	acf_register_block_type([
		'name' => 'promo-block',
		'title' => __('Promo Block'),
		'description' => __('Promo Block'),
		'render_template' => 'blocks/promo.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['promo', 'content'],
		'example' => [
			'attributes' => [
				'mode' => 'preview',
				'data' => [
					'preview_image_help' => get_stylesheet_directory_uri().'/blocks/previews/promo_block_preview.png',
				]
			]
		]
	]);

	// register a Webinar Promo Block
	acf_register_block_type([
		'name' => 'webinar-promo-block',
		'title' => __('Webinar Promo Block'),
		'description' => __('Webinar Promo Block with Ninja form'),
		'render_template' => 'blocks/webinar_promo.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['webinar', 'promo', 'content'],
		'example' => [
			'attributes' => [
				'mode' => 'preview',
				'data' => [
					'preview_image_help' => get_stylesheet_directory_uri().'/blocks/previews/webinar_registration_promo_block_preview.png',
				]
			]
		]
	]);

	// register a Promo 2 Block
	acf_register_block_type([
		'name' => 'promo2-block',
		'title' => __('Promo 2 Block'),
		'description' => __('Promo 2 Block'),
		'render_template' => 'blocks/promo2.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['promo', 'content'],
	]);
	// register a Promo 3 Block
	acf_register_block_type([
		'name' => 'promo3-block',
		'title' => __('Promo 3 Block'),
		'description' => __('Promo 3 Block'),
		'render_template' => 'blocks/promo3.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['promo', 'content'],
	]);
	// register a More About Block
	acf_register_block_type([
		'name' => 'more-about-block',
		'title' => __('More About Block'),
		'description' => __('More About Block'),
		'render_template' => 'blocks/more-about.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['about', 'content'],
	]);
	// register a Subscribe Block
	acf_register_block_type([
		'name' => 'subs-block',
		'title' => __('Subscribe Block'),
		'description' => __('Subscribe Block'),
		'render_template' => 'blocks/subs.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'email-alt2',
		'keywords' => ['subscribe', 'content'],
	]);
	// register a Subscribe 2 Block
	acf_register_block_type([
		'name' => 'subs2-block',
		'title' => __('Subscribe 2 Block'),
		'description' => __('Subscribe 2 Block'),
		'render_template' => 'blocks/subs2.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'email-alt2',
		'keywords' => ['subscribe', 'content'],
	]);


	// register a Video Block
	acf_register_block_type([
		'name' => 'video-block-embed',
		'title' => __('NC Video Embed Block'),
		'description' => __('Video Block embedded'),
		'render_template' => 'blocks/nc-video-embed.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'video-alt3',
		'keywords' => ['video', 'content'],
	]);

	// register a Video Block
	acf_register_block_type([
		'name' => 'video-block',
		'title' => __('Video Block'),
		'description' => __('Video Block'),
		'render_template' => 'blocks/video.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'video-alt3',
		'keywords' => ['video', 'content'],
	]);
	// register a Video 2 Block
	acf_register_block_type([
		'name' => 'video2-block',
		'title' => __('Video 2 Block'),
		'description' => __('Video 2 Block'),
		'render_template' => 'blocks/video2.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'video-alt3',
		'keywords' => ['video', 'content'],
	]);
	// register a Video 3 Block
	acf_register_block_type([
		'name' => 'video3-block',
		'title' => __('Video 3 Block'),
		'description' => __('Video 3 Block'),
		'render_template' => 'blocks/video3.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'video-alt3',
		'keywords' => ['video', 'content'],
	]);
	// register a Video 4 Block
	acf_register_block_type([
		'name' => 'video4-block',
		'title' => __('Video 4 Block'),
		'description' => __('Video 4 Block'),
		'render_template' => 'blocks/video4.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'video-alt3',
		'keywords' => ['video', 'content'],
	]);
	// register a Solution Block
	acf_register_block_type([
		'name' => 'solution-block',
		'title' => __('Solution Block'),
		'description' => __('Solution Block'),
		'render_template' => 'blocks/solution.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['solution', 'content'],
	]);

	// register a Solution Block
	acf_register_block_type([
		'name' => 'solution-4columns-block',
		'title' => __('Solution Block 4 Columns'),
		'description' => __('Solution 4 Columns Block'),
		'render_template' => 'blocks/solution-4columns.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['solution', 'content'],
	]);

	// register a Integration Block
	acf_register_block_type([
		'name' => 'integration-block',
		'title' => __('Integration Block'),
		'description' => __('Integration Block'),
		'render_template' => 'blocks/integration.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['integration', 'content'],
	]);
	// register a Why Hub Block
	acf_register_block_type([
		'name' => 'whyhub-block',
		'title' => __('Why Hub Block'),
		'description' => __('Why Hub Block'),
		'render_template' => 'blocks/whyhub.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['why hub', 'content'],
	]);
	// register a Capabilities Block
	acf_register_block_type([
		'name' => 'capabilities-block',
		'title' => __('Capabilities Block'),
		'description' => __('Capabilities Block'),
		'render_template' => 'blocks/capabilities.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['capabilities', 'content'],
	]);
	// register a Capabilities 2 Block
	acf_register_block_type([
		'name' => 'capabilities2-block',
		'title' => __('Capabilities 2 Block'),
		'description' => __('Capabilities 2 Block'),
		'render_template' => 'blocks/capabilities2.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['capabilities', 'content'],
		'example' => [
			'attributes' => [
				'mode' => 'preview',
				'data' => [
					'preview_image_help' => get_stylesheet_directory_uri().'/blocks/previews/capabilities block-block.png',
				]
			]
		]
	]);
	// register a Collaboration Block
	acf_register_block_type([
		'name' => 'collaboration2-block',
		'title' => __('Collaboration 2 Block'),
		'description' => __('Collaboration 2 Block'),
		'render_template' => 'blocks/collaboration2.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'admin-site-alt2',
		'keywords' => ['collaboration', 'content'],
	]);


	// register a Collaboration Slider Block
	acf_register_block_type([
		'name' => 'collaboration3-block',
		'title' => __('Collaboration Slider Block'),
		'description' => __('Collaboration Slider Block'),
		'render_template' => 'blocks/collaboration3.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['collaboration', 'slider', 'content'],
	]);


	// register a Simple Slider Block
	acf_register_block_type([
		'name' => 'simple-slider-block',
		'title' => __('Simple Slider Block'),
		'description' => __('Simple Slider Block'),
		'render_template' => 'blocks/simple_slider.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['simple', 'slider', 'content', 'slideshow'],
		'example' => [
			'attributes' => [
				'mode' => 'preview',
				'data' => [
					'preview_image_help' => get_stylesheet_directory_uri().'/blocks/previews/simple-slider-block-preview.png',
				]
			]
		]
	]);



	// register a Carousel Slider Block
	acf_register_block_type([
		'name' => 'carousel-slider-block',
		'title' => __('Carousel Slider Block'),
		'description' => __('Carousel Slider Block'),
		'render_template' => 'blocks/carousel_slider.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['carousel', 'slider', 'content', 'slideshow', 'partners', 'clients'],
		'example' => [
			'attributes' => [
				'mode' => 'preview',
				'data' => [
					'preview_image_help' => get_stylesheet_directory_uri().'/blocks/previews/carousel_block_preview.png',
				]
			]
		]
	]);




	// register a What Can Hub Block
	acf_register_block_type([
		'name' => 'whatcanhub-block',
		'title' => __('What Can Hub Block'),
		'description' => __('What Can Hub Block'),
		'render_template' => 'blocks/whatcanhub.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['what can hub', 'content'],
	]);
	// register a Self Hosting Block
	acf_register_block_type([
		'name' => 'hosting-block',
		'title' => __('Self Hosting Block'),
		'description' => __('Self Hosting Block'),
		'render_template' => 'blocks/hosting.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['hosting', 'content'],
	]);
	// register a Self Hosting Block
	acf_register_block_type([
		'name' => 'hosting2-block',
		'title' => __('Self Hosting 2 Block'),
		'description' => __('Self Hosting 2 Block'),
		'render_template' => 'blocks/hosting2.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['hosting', 'content'],
	]);
	// register a Extend Block
	acf_register_block_type([
		'name' => 'extend-block',
		'title' => __('Extend Block'),
		'description' => __('Extend Block'),
		'render_template' => 'blocks/extend.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['extend', 'content'],
	]);
	// register a Benefits Block
	acf_register_block_type([
		'name' => 'benefits-block',
		'title' => __('Benefits Block'),
		'description' => __('Benefits Block'),
		'render_template' => 'blocks/benefits.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['benefits', 'content'],
	]);
	// register a Benefits 2 Block
	acf_register_block_type([
		'name' => 'benefits2-block',
		'title' => __('Benefits 2 Block'),
		'description' => __('Benefits 2 Block'),
		'render_template' => 'blocks/benefits2.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['benefits', 'content'],
	]);
	// register a Benefits 3 Block
	acf_register_block_type([
		'name' => 'benefits3-block',
		'title' => __('Benefits 3 Block'),
		'description' => __('Benefits 3 Block'),
		'render_template' => 'blocks/benefits3.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['benefits', 'content'],
	]);
	// register a What Sets Apart Block
	acf_register_block_type([
		'name' => 'whatsets-block',
		'title' => __('What Sets Apart Block'),
		'description' => __('What Sets Apart Block'),
		'render_template' => 'blocks/whatsets.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['what sets apart', 'content'],
	]);
	// register a Much More Block
	acf_register_block_type([
		'name' => 'muchmore-block',
		'title' => __('Much More Block'),
		'description' => __('Much More Block'),
		'render_template' => 'blocks/muchmore.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['much more', 'content'],
	]);
	// register a Unique Column Block
	acf_register_block_type([
		'name' => 'ucol-block',
		'title' => __('Unique Column Block'),
		'description' => __('Unique Column Block'),
		'render_template' => 'blocks/ucol.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['unique column', 'content'],
	]);
	// register a Compliance Slider Block
	acf_register_block_type([
		'name' => 'compliance-block',
		'title' => __('Compliance Slider Block'),
		'description' => __('Compliance Slider Block'),
		'render_template' => 'blocks/compliance.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['compliance', 'content'],
	]);
	// register a Single Quote Block
	acf_register_block_type([
		'name' => 'squote-block',
		'title' => __('Single Quote Block'),
		'description' => __('Single Quote Block'),
		'render_template' => 'blocks/squote.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['quote', 'content'],
	]);
	// register a Single Quote Block
	acf_register_block_type([
		'name' => 'squote2-block',
		'title' => __('Single Quote 2 Block'),
		'description' => __('Single Quote 2 Block'),
		'render_template' => 'blocks/squote2.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['quote', 'content'],
	]);

	// register a Single Quote Block
	acf_register_block_type([
		'name' => 'squote3-block',
		'title' => __('Single Quote 3 Block'),
		'description' => __('Single Quote 3 Block'),
		'render_template' => 'blocks/squote3.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['quote', 'content'],
		'example' => [
			'attributes' => [
				'mode' => 'preview',
				'data' => [
					'preview_image_help' => get_stylesheet_directory_uri().'/blocks/previews/single-quote-3-preview.png',
				]
			]
		]
	]);


	// register a Platform Block
	acf_register_block_type([
		'name' => 'platform-block',
		'title' => __('Platform Block'),
		'description' => __('Platform Block'),
		'render_template' => 'blocks/platform.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['platform', 'content'],
	]);
	// register a Enterprise Block
	acf_register_block_type([
		'name' => 'enterprise-block',
		'title' => __('Enterprise Block'),
		'description' => __('Enterprise Block'),
		'render_template' => 'blocks/enterprise.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['enterprise', 'content'],
	]);
	// register a Pricing Block
	acf_register_block_type([
		'name' => 'pricing-block',
		'title' => __('Pricing Block'),
		'description' => __('Pricing Block'),
		'render_template' => 'blocks/pricing.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['pricing', 'content'],
	]);
	// register a Get Started Block
	acf_register_block_type([
		'name' => 'started-block',
		'title' => __('Get Started Block'),
		'description' => __('Get Started Block'),
		'render_template' => 'blocks/get-started.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['get started', 'content'],
	]);
	// register a Single Text Block
	acf_register_block_type([
		'name' => 'singles-block',
		'title' => __('Single Text Block'),
		'description' => __('Single Text Block'),
		'render_template' => 'blocks/singles.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['text', 'content'],
	]);
	// register a Content 1 Block
	acf_register_block_type([
		'name' => 'content1-block',
		'title' => __('Content 1 Block'),
		'description' => __('Content 1 Block'),
		'render_template' => 'blocks/content1.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['text', 'content'],
	]);
	// register a Content 2 Block
	acf_register_block_type([
		'name' => 'content2-block',
		'title' => __('Content 2 Block'),
		'description' => __('Content 2 Block'),
		'render_template' => 'blocks/content2.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['text', 'content'],
	]);
	// register a Documentation Block
	acf_register_block_type([
		'name' => 'documentation-block',
		'title' => __('Documentation Block'),
		'description' => __('Documentation Block'),
		'render_template' => 'blocks/documentation.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['documentation', 'content'],
	]);
	// register a Get Involved Block
	acf_register_block_type([
		'name' => 'involved-block',
		'title' => __('Get Involved Block'),
		'description' => __('Get Involved Block'),
		'render_template' => 'blocks/involved.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['involved', 'content'],
	]);
	// register a Content Cards Block
	acf_register_block_type([
		'name' => 'cards-block',
		'title' => __('Content Cards Block'),
		'description' => __('Content Cards Block'),
		'render_template' => 'blocks/cards.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['cards', 'content'],
	]);
	// register a Contact Block
	acf_register_block_type([
		'name' => 'contact-block',
		'title' => __('Contact Block'),
		'description' => __('Contact Block'),
		'render_template' => 'blocks/contact.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['contact', 'content'],
	]);
	// register a Contact Block
	acf_register_block_type([
		'name' => 'map-block',
		'title' => __('Map Block'),
		'description' => __('Map Block'),
		'render_template' => 'blocks/map.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['map', 'content'],
	]);
	// register a Get Quote Form Block
	acf_register_block_type([
		'name' => 'quote-form-block',
		'title' => __('Get Quote Form Block'),
		'description' => __('Get Quote Form Block'),
		'render_template' => 'blocks/quote-form.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['quote', 'content'],
	]);
	// register a Info Block
	acf_register_block_type([
		'name' => 'info-block',
		'title' => __('Info Block'),
		'description' => __('Info Block'),
		'render_template' => 'blocks/info.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['info', 'content'],
	]);
	// register a Get Quote Form Block
	acf_register_block_type([
		'name' => 'trial-form-block',
		'title' => __('Trial Form Block'),
		'description' => __('Trial Form Block'),
		'render_template' => 'blocks/trial-form.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['trial', 'content'],
	]);
	// register a Partner Form Block
	acf_register_block_type([
		'name' => 'partner-form-block',
		'title' => __('Partner Form Block'),
		'description' => __('Partner Form Block'),
		'render_template' => 'blocks/partner-form.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['partner', 'content'],
	]);
	// register a Marketing Block
	acf_register_block_type([
		'name' => 'marketing-block',
		'title' => __('Marketing Block'),
		'description' => __('Marketing Block'),
		'render_template' => 'blocks/marketing.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['marketing', 'content'],
	]);
	// register a Text Block
	acf_register_block_type([
		'name' => 'text1-block',
		'title' => __('Text Block'),
		'description' => __('Text Block'),
		'render_template' => 'blocks/text1.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['text', 'content'],
	]);
	// register a Text 2 Block
	acf_register_block_type([
		'name' => 'text2-block',
		'title' => __('Text 2 Block'),
		'description' => __('Text 2 Block'),
		'render_template' => 'blocks/text2.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['text', 'content'],
	]);
	// register a Order Form Block
	acf_register_block_type([
		'name' => 'order-form-block',
		'title' => __('Order Form Block'),
		'description' => __('Order Form Block'),
		'render_template' => 'blocks/order-form.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['order', 'content'],
	]);
	// register a Boxes Block
	acf_register_block_type([
		'name' => 'boxes-block',
		'title' => __('Boxes Block'),
		'description' => __('Boxes Block'),
		'render_template' => 'blocks/boxes.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['boxes', 'content'],
	]);
	// register a Ionos Form Block
	acf_register_block_type([
		'name' => 'ionos-block',
		'title' => __('Ionos Form Block'),
		'description' => __('Ionos Form Block'),
		'render_template' => 'blocks/ionos.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['ionos', 'content'],
	]);
	// register a Ionos Form Block
	acf_register_block_type([
		'name' => 'ionos-form-block',
		'title' => __('Ionos Form Block New'),
		'description' => __('Ionos Form Block Ninja Forms'),
		'render_template' => 'blocks/ionos-form.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['ionos', 'content'],
	]);
	// register a Sign Up Form Block
	acf_register_block_type([
		'name' => 'signup-block',
		'title' => __('Sign Up Form Block'),
		'description' => __('Sign Up Form Block'),
		'render_template' => 'blocks/signup.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['signup', 'content'],
	]);
	// register a Providers Block
	acf_register_block_type([
		'name' => 'providers-block',
		'title' => __('Providers Block'),
		'description' => __('Providers Block'),
		'render_template' => 'blocks/providers.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['providers', 'content'],
	]);
	// register a Industries Content Block
	acf_register_block_type([
		'name' => 'industries-block',
		'title' => __('Industries / Emoji Boxes Content Block'),
		'description' => __('Industries and Emoji Boxes Content Block'),
		'render_template' => 'blocks/industries.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['industries', 'emoji', 'boxes', 'content', 'iconbox'],
		'example' => [
			'attributes' => [
				'mode' => 'preview',
				'data' => [
					'preview_image_help' => get_stylesheet_directory_uri().'/blocks/previews/industries-block-preview.png',
				]
			]
		]
	]);
	// register a Analysis Block
	acf_register_block_type([
		'name' => 'analysis-block',
		'title' => __('Analysis Block'),
		'description' => __('Analysis Block'),
		'render_template' => 'blocks/analysis.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['analysis', 'content'],
	]);
	// register a Case Study Listing Block
	acf_register_block_type([
		'name' => 'case-study-block',
		'title' => __('Case Study Listing Block'),
		'description' => __('Case Study Listing Block'),
		'render_template' => 'blocks/case-study.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['case study', 'content'],
	]);
	// register a Whitepaper Listing Block
	acf_register_block_type([
		'name' => 'whitepaper-block',
		'title' => __('Whitepaper Listing Block'),
		'description' => __('Whitepaper Listing Block'),
		'render_template' => 'blocks/whitepaper.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['whitepaper', 'content'],
	]);
	// register a Data Sheet Listing Block
	acf_register_block_type([
		'name' => 'data-sheet-block',
		'title' => __('Data Sheet Listing Block'),
		'description' => __('Data Sheet Listing Block'),
		'render_template' => 'blocks/data-sheet.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['data sheet', 'content'],
	]);
	// register a Team Listing Block
	acf_register_block_type([
		'name' => 'team-list-block',
		'title' => __('Team Listing Block'),
		'description' => __('Team Listing Block'),
		'render_template' => 'blocks/team-list.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['team', 'content'],
	]);
	// register a Blog Listing Block
	acf_register_block_type([
		'name' => 'blog-list-block',
		'title' => __('Blog Listing Block'),
		'description' => __('Blog Listing Block'),
		'render_template' => 'blocks/blog-list.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['blog', 'content'],
	]);
	// register a FAQ Block
	acf_register_block_type([
		'name' => 'faq-block',
		'title' => __('FAQ Block'),
		'description' => __('FAQ Block'),
		'render_template' => 'blocks/faq.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['faq', 'content'],
	]);
	// register a Jobs Block
	acf_register_block_type([
		'name' => 'jobs-block',
		'title' => __('Jobs Block'),
		'description' => __('Jobs Block'),
		'render_template' => 'blocks/jobs.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['jobs', 'content'],
	]);
	// register a Single Post Content Block
	acf_register_block_type([
		'name' => 'single-block',
		'title' => __('Single Post Content Block'),
		'description' => __('Single Post Content Block'),
		'render_template' => 'blocks/single.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['single', 'content'],
	]);
	// register a Whitepaper Blog Posts Block
	acf_register_block_type([
		'name' => 'white-post-block',
		'title' => __('Whitepaper Blog Posts Block'),
		'description' => __('Whitepaper Blog Posts Block'),
		'render_template' => 'blocks/whitepaper-list.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['whitepaper', 'content'],
	]);
	// register a Image Columns Block
	acf_register_block_type([
		'name' => 'img-cols-block',
		'title' => __('Image Columns Block'),
		'description' => __('Image Columns Block'),
		'render_template' => 'blocks/img-cols.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['image', 'content'],
	]);
	// register a Get Started 2 Block
	acf_register_block_type([
		'name' => 'started2-block',
		'title' => __('Get Started 2 Block'),
		'description' => __('Get Started 2 Block'),
		'render_template' => 'blocks/get-started2.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['get started', 'content'],
	]);
	// register a Events Block
	acf_register_block_type([
		'name' => 'events-block',
		'title' => __('Events Block'),
		'description' => __('Events Block'),
		'render_template' => 'blocks/events.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['events', 'content'],
	]);
	// register a Counter Block
	acf_register_block_type([
		'name' => 'counter-block',
		'title' => __('Counter Block'),
		'description' => __('Counter Block'),
		'render_template' => 'blocks/counter.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['counter', 'content'],
	]);
	// register a Pricing Tabs Block
	acf_register_block_type([
		'name' => 'price-tab-block',
		'title' => __('Pricing Tabs Block'),
		'description' => __('Pricing Tabs Block'),
		'render_template' => 'blocks/price-tab.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['pricing', 'content'],
	]);
	// register a Footnote Block
	acf_register_block_type([
		'name' => 'footnote-block',
		'title' => __('Footnote Block'),
		'description' => __('Footnote Block'),
		'render_template' => 'blocks/footnote.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['footnote', 'content'],
	]);

	// register a Note Block
	acf_register_block_type([
		'name' => 'note-block',
		'title' => __('Nextcloud Note Block'),
		'description' => __('Footnote Block'),
		'render_template' => 'blocks/nc_note.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['note', 'content'],
	]);


	// register a Testimonial Block
	acf_register_block_type([
		'name' => 'testimonial-block',
		'title' => __('Testimonial Block'),
		'description' => __('Testimonial Block'),
		'render_template' => 'blocks/testimonial.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['testimonial', 'content'],
	]);
	// register a Partners Block
	acf_register_block_type([
		'name' => 'partners-block',
		'title' => __('Partners Block'),
		'description' => __('Partners Block'),
		'render_template' => 'blocks/partners.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['partners', 'content'],
	]);
	// register a Podcast Block
	acf_register_block_type([
		'name' => 'podcast-block',
		'title' => __('Podcast Block'),
		'description' => __('Podcast Block'),
		'render_template' => 'blocks/podcast.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => ['podcast', 'content'],
	]);
}

// Check if function exists and hook into setup.
if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'register_acf_block_types');
}
