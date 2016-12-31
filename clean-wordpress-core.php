<?php

/*
Plugin Name: Clean Core
Plugin URI: https://blog.speedword.press/wordpress-clean-core/
Description: Als eine der ersten Massnahmen bei einer neuen WordPress Seite sollte man gleich ein Mal Balast abwerfen. Keines der folgenden „Features“ ist nützlich, oder sonst irgendwie sinnvoll. Im Gegenteil der Unsinn frisst nicht nur Performence sondern ist stellt auch ein Sicherheitsrisiko dar. Da Mann folgenden Module leider im Core verankert hat, bleibt nichts ausser zu deaktivieren. Ausser der Kommentar Funktion, gehört alles nicht in den Core. Ein Plugin das niemand brauchen würde hätte gereicht und wäre wohl vor sich hingestorben. Nun dafür gibt es heute haufen Plugins die WP Embeds, Header Links oder die Emojis wieder deaktivieren.
Author: Daniel Bieli
Version: 1.0
Author URI: https://blog.speedword.press
*/

add_action('init','remheadlink');function remheadlink(){remove_action('wp_head','rsd_link');remove_action('wp_head','wp_generator');remove_action('wp_head','wlwmanifest_link');remove_action('wp_head','feed_links',2);remove_action('wp_head','feed_links_extra',3);remove_action('wp_head','index_rel_link');remove_action('wp_head','parent_post_rel_link');remove_action('wp_head','start_post_rel_link');remove_action('wp_head','wp_shortlink_wp_head');remove_action('wp_head','wp_shortlink_header');remove_action('wp_head','adjacent_posts_rel_link_wp_head');remove_action('template_redirect','wp_shortlink_header',11);remove_action('wp_head','print_emoji_detection_script',7);remove_action('admin_print_scripts','print_emoji_detection_script');remove_action('admin_print_styles','print_emoji_styles');remove_action('wp_print_styles','print_emoji_styles');remove_filter('the_content_feed','wp_staticize_emoji');remove_filter('comment_text_rss','wp_staticize_emoji');remove_filter('wp_mail','wp_staticize_emoji_for_email');}remove_action('wp_head','rest_output_link_wp_head',10);remove_action('template_redirect','rest_output_link_header',11,0);remove_action('wp_head','wp_oembed_add_discovery_links',10);function my_deregister_scripts(){wp_deregister_script('wp-embed');}add_action('wp_footer','my_deregister_scripts');add_filter('xmlrpc_enabled','__return_false');add_filter('wp_headers','remove_x_pingback');function remove_x_pingback($headers){unset($headers['X-Pingback']);return $headers;}

function remove_class($output)
	{
	$output = preg_replace('//', '', $output);
	return $output;
	}
add_filter('post_thumbnail_html', 'remove_class');
add_filter('the_content', 'remove_class');
function remove_width_attribute($html)
	{
	$html = preg_replace('/(width|height)="\d*"\s/', "", $html);
	return $html;
	}
add_filter('post_thumbnail_html', 'remove_width_attribute');
add_filter('the_content', 'remove_width_attribute');
add_filter( 'wp_get_attachment_image_attributes', function( $attr )
{
    if( isset( $attr['sizes'] ) )
        unset( $attr['sizes'] );
    if( isset( $attr['srcset'] ) )
        unset( $attr['srcset'] );
    return $attr;
 }, PHP_INT_MAX );
add_filter( 'wp_calculate_image_sizes', '__return_false',  PHP_INT_MAX );
add_filter( 'wp_calculate_image_srcset', '__return_false', PHP_INT_MAX );
remove_filter( 'the_content', 'wp_make_content_images_responsive' );
