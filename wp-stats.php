<?php

class UserOnline_WpStats {
	function init() {
		add_filter('wp_stats_page_admin_plugins', array(__CLASS__, 'page_admin_general_stats'));
		add_filter('wp_stats_page_plugins', array(__CLASS__, 'page_general_stats'));

		add_filter('useronline_display_name', array(__CLASS__, 'stats_page_link'));
	}

	function stats_page_link($author) {
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

		$str = _n(
			'<strong>%s</strong> user online now.',
			'<strong>%s</strong> users online now.', 
			get_useronline_count(), 'wp-useronline'
		);

		if ( $stats_display['useronline'] == 1 )
			$content .= 
			html('p', html('strong', __('WP-UserOnline', 'wp-useronline')))
			.html('ul',
				html('li', sprintf($str, number_format_i18n(get_useronline_count())))
				.html('li', _useronline_most_users())
			);

		return $content;
	}
}
UserOnline_WpStats::init();

