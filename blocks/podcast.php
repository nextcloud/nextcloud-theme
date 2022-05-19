<?php
/*
 * Podcast Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$dir = get_stylesheet_directory_uri();
?>
<script src="https://cdn.podlove.org/web-player/5.x/embed.js"></script>
<script>
    const RSS_URL = "<?php echo $dir; ?>/podcast-feed.rss";
    const config_URL = "<?php echo $dir; ?>/dist/js/config.json";
    const version = 5;
    const show = {
        title: "Nextcloud Podcast",
        subtitle: "Nextcloud's own podcast",
        summary: "Every week we will talk about a subject concerning the community, data privacy and digital sovereignty",
        poster: "<?php echo $dir; ?>/dist/img/nextcloud-podcast-logo.png",
        link: "<?php echo site_url(); ?>/podcast"
    };

    Promise.all([
        fetch(RSS_URL)
        .then(rss_response => rss_response.text()),
        fetch(config_URL)
        .then(config_response => config_response.json())
    ]).then((response) => {
        const rss_data = new window.DOMParser().parseFromString(response[0], "text/xml");
        const episodes_nodes = rss_data.querySelectorAll("item");
        let config_data = response[1];

        let episodes_list = [];
        let playlist = [];
        episodes_nodes.forEach(episode => {
            let audio_object = {
                url: episode.querySelector("enclosure").getAttribute("url"),
                size: episode.querySelector("enclosure").getAttribute("length"),
                title: episode.querySelector("title").textContent,
                mimeType: episode.querySelector("enclosure").getAttribute("type")
            };

            /**
             * Episode related Information
             */
            let episode_config = {
                version: version,
                show: show,
                title: episode.querySelector("title").textContent,
                subtitle: episode.getElementsByTagName("itunes:subtitle")[0].textContent,
                summary: episode.getElementsByTagName("itunes:summary")[0].textContent,
                publicationDate: episode.querySelector("pubDate").textContent,
                poster: "<?php echo $dir; ?>/dist/img/nextcloud-podcast-logo.png",
                duration: episode.getElementsByTagName("itunes:duration")[0].textContent,
                link: episode.querySelector("link").textContent,
                audio: [audio_object],
                playlist: [],
                contributors: [{
                    id: "",
                    name: "",
                    avatar: "",
                    group: {
                        "id": "",
                        "slug": "",
                        "title": ""
                    }
                }]
            };
            episodes_list.push(episode_config);

            let episode_object = {
                title: episode_config.title,
                duration: episode_config.duration,
                href: episode_config.link,
                image: "author.jpg",
                config: {}
            };
            playlist.push(episode_object);
        });

        // add config for each episode in playlist
        let episode_number = 0;
        playlist.forEach(episode => {
            episode.config = episodes_list[episode_number];
            episode_number++;
        });

        // add playlist to episodes
        episodes_list.forEach(episode => {
            episode.playlist = playlist;
        });

        config_data.playlist = playlist;
        window.podlovePlayer("#podcast-player", episodes_list[0], config_data)
            .then(store => {
                store.subscribe(() => {
                    console.log(store.getState());
                });
            });
    });
</script>
<section class="podcast-section" id="<?php echo $id; ?>">
    <div class="container">
        <?php
		if (!empty($title)) {
			echo '<div class="row justify-content-center">';
			echo '<div class="col-lg-10">';
			echo '<div class="section-title">';
			echo '<h3>' . $title . '</h3>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
		?>
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div id="podcast-player"></div>
            </div>
        </div>
        <?php
		if (have_rows('subs_links')) {
			echo '<div class="row justify-content-center">';
			echo '<div class="col-lg-10">';
			echo '<ul class="links">';
			echo '<li><h4>Subscribe now!</h4></li>';
			while (have_rows('subs_links')) {
				the_row();
				$link = get_sub_field('link');
				if ($link) {
					$link_url = $link['url'];
					$link_title = $link['title'];
					$link_target = $link['target'] ? $link['target'] : '_self';
					echo '<li><a href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a></li>';
				}
			}
			echo '</ul>';
			echo '</div>';
			echo '</div>';
		}
		?>
    </div>
</section>
<section class="whitepaper-list-section">
    <div class="container">
        <?php
		echo '<div class="row">';
		echo '<div class="col-12">';
		echo '<div class="section-title">';
		echo '<h2>Previous episodes</h2>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
		?>
        <div class="row">
            <?php
			$my_wp_query = new WP_Query();
			$onepost = $my_wp_query->query(array(
				'post_type' => 'post',
				'category_name' => 'podcast',
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'orderby' => 'date',
				'order' => 'DSC',
			));
			foreach ($onepost as $onepostsingle) {
				$img = wp_get_attachment_url(get_post_thumbnail_id($onepostsingle->ID));
				$title = $onepostsingle->post_title;
				$date = get_the_date('F d, Y', $onepostsingle->ID);
				$cat = get_the_category($onepostsingle->ID);
				$link = get_permalink($onepostsingle->ID);
				$author_id = $onepostsingle->post_author;
				echo '<div class="col-lg-4 col-md-6 spacer">';
				echo '<div class="paper-box">';
				echo '<ul class="cats">';
				echo '<li>posted in </li>';
				foreach ($cat as $c) {
					//    $category_link = get_category_link($c->term_id);
					echo '<li>' . $c->cat_name . ', </li>';
				}
				echo '<li>by ' . get_the_author_meta('display_name', $author_id) . '</li>';
				echo '</ul>';
				echo '<h4>' . $title . '</h4>';
				echo '<ul class="info">';
				echo '<li>' . $date . '</li>';
				echo '<li><a class="c-btn" href="' . $link . '">Read More</a></li>';
				echo '</ul>';
				echo '</div>';
				echo '</div>';
			}
			wp_reset_query();
			?>
        </div>
    </div>
</section>