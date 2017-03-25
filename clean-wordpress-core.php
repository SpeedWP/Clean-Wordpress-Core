<?php

/*
Plugin Name: Clean WordPress Core & Backend Tuning
Plugin URI: https://speedword.press/wordpress-clean-core/
Description: Als eine der ersten Massnahmen bei einer neuen WordPress Seite sollte man gleich ein Mal Balast abwerfen. Keines der folgenden „Features“ ist nützlich, oder sonst irgendwie sinnvoll. Im Gegenteil der Unsinn frisst nicht nur Performence sondern ist stellt auch ein Sicherheitsrisiko dar. Da Mann folgenden Module leider im Core verankert hat, bleibt nichts ausser zu deaktivieren. Ausser der Kommentar Funktion, gehört alles nicht in den Core. Ein Plugin das niemand brauchen würde hätte gereicht und wäre wohl vor sich hingestorben. Nun dafür gibt es heute haufen Plugins die WP Embeds, Header Links oder die Emojis wieder deaktivieren.
Author: Daniel Bieli
Version: 2.0
Author URI: https://speedword.press
*/

/* 01. Remove Header Links */

add_action('init', 'speedwp_remove_header_links'); function speedwp_remove_header_links() { remove_action('wp_head', 'rsd_link'); remove_action('wp_head', 'wp_generator'); remove_action('wp_head', 'index_rel_link'); remove_action('wp_head', 'wlwmanifest_link'); remove_action('wp_head', 'feed_links', 2); remove_action('wp_head', 'feed_links_extra', 3); remove_action('wp_head', 'parent_post_rel_link', 10, 0); remove_action('wp_head', 'start_post_rel_link', 10, 0); remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0); remove_action('wp_head', 'wp_shortlink_header', 10, 0); remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0); }

/* 02. Remove WordPress Embeds */

function speedwp_remove_wp_embed() {
    wp_deregister_script('wp-embed'); }
add_action('init', 'speedwp_remove_wp_embed');


/* 03. Remove WordPress Emojis */

function speedwp_remove_emoji()
	{
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('admin_print_styles', 'print_emoji_styles');
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('comment_text_rss', 'wp_staticize_emoji');
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
	add_filter('tiny_mce_plugins', 'speedwp_remove_tinymce_emoji');
	}
add_action('init', 'speedwp_remove_emoji');
function speedwp_remove_tinymce_emoji($plugins)
	{
	if (!is_array($plugins))
		{
		return array();
		}
	return array_diff($plugins, array(
		'wpemoji'
	));
	}

/* 04. Remove WordPress Heartbeat API everywhere - only enable on post */

add_action('init', 'speedwp_remove_heartbeat_only_post', 1);
function speedwp_remove_heartbeat_only_post()
	{
	global $pagenow;
	if ($pagenow != 'post.php' && $pagenow != 'post-new.php') wp_deregister_script('heartbeat');
	}

/* 05. Remove Responsive Images */

remove_filter( 'the_content', 'wp_make_content_images_responsive' );

/* 06. Remove Width and Hight Tag from Images */

add_filter( 'post_thumbnail_html', 'speedwp_remove_thumbnail_dimensions', 10 );
add_filter( 'image_send_to_editor', 'speedwp_remove_thumbnail_dimensions', 10 );
add_filter( 'the_content', 'speedwp_remove_thumbnail_dimensions', 10 );
function speedwp_remove_thumbnail_dimensions( $html ) {
    $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
    return $html;
}

/* 07. Remove WordPress XML-RPC */

add_filter( 'xmlrpc_enabled', '__return_false' );

add_filter( 'wp_headers', 'speedwp_remove_xmlrpc_pingback' );
 function speedwp_remove_xmlrpc_pingback( $headers )
 {
 unset( $headers['X-Pingback'] );
 return $headers;
 }

/* 08. Remove WordPress Mail to Post */

add_filter('enable_post_by_email_configuration', '__return_false');

/* 09. Move jQuery to Footer */

function speedwp_move_jquery_in_footer( &$scripts) {
    if ( ! is_admin() )
        $scripts->add_data( 'jquery', 'group', 1 );
}
add_action( 'wp_default_scripts', 'speedwp_move_jquery_in_footer' );

/* 10. Remove Query Strings from Filename */

function speedwp_remove_version_css_js( $src ) {
    if ( strpos( $src, 'ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
add_filter( 'style_loader_src', 'speedwp_remove_version_css_js', 9999 );
add_filter( 'script_loader_src', 'speedwp_remove_version_css_js', 9999 );
