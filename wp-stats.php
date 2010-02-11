<?php

class UserOnline_WpStats {
	function init() {
		if ( strpos(get_option('stats_url'), $_SERVER['REQUEST_URI']) 
		  || strpos($_SERVER['REQUEST_URI'], 'stats-options.php') 
		  || strpos($_SERVER['REQUEST_URI'], 'wp-stats/wp-stats.php') 
		) {
			add_filter('wp_stats_page_admin_plugins', array(__CLASS__, 'page_admin_general_stats'));
			add_filter('wp_stats_page_plugins', array(__CLASS__, 'page_general_stats'));
		}

		add_filter('useronline_display_name', array(__CLASS__, 'stats_page_link'), 10, 2);
	}

	function stats_page_link($author, $type) {
		if ( 'member' != $type )
			return $author;

		$stats_url = add_query_arg('stats_author', urlencode($author), get_option('stats_url'));

		return html_link($stats_url, $author);
	}

	// Add WP-UserOnline General Stats To WP-Stats Page Options
	function page_admin_general_stats($content) {
		$stats_display = get_option('stats_display');
		
		$content .= '<input type="checkbox" name="stats_display[]" id="wpstats_useronline" value="useronline"' . checked($stats_display['useronline'], 1, false) . '/>&nbsp;&nbsp;<label for="wpstats_useronline">'.__('WP-UserOnline', 'wp-useronline').'</label><br />'."\n";

		return $content;
	}

	// Add WP-UserOnline General Stats To WP-Stats Page
	function page_general_stats($content) {
		$stats_display = get_option('stats_display');

		if ( $stats_display['useronline'] == 1 )
			$content .= 
			html('p', html('strong', __('WP-UserOnline', 'wp-useronline')))
			.html('ul',
				html('li', sprintf(_n('<strong>%s</strong> user online now.', '<strong>%s</strong> users online now.', get_useronline_count(), 'wp-useronline'), number_format_i18n(get_useronline_count())))
				.html('li', _useronline_most_users())
			);

		return $content;
	}
}
UserOnline_WpStats::init();

