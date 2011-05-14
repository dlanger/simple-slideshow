<?php 
/*
Plugin Name: Simple Slider
Plugin URI:	http://daniellanger.com/simpleslider
Description: A basic image slider. Attach images to a post, and then use a shortcode to display them. Their thumbnails (at a configurable size) will be shown in a jQuery Cycle slideshow, and each will be a link to the full-sized version.
Version: 1.0
Author: Daniel Langer
Author URI: http://www.daniellanger.me
License: BSD
*/

define("SIMPLESLIDER_VERSION", "1.0");


function load_js() {
	// Don't want any of this if we're on an admin page
	if ( is_admin() ) {
		return;
	}
	
	// Load jQuery Cycle Lite locally, and jQuery from the Google CDN
	wp_deregister_script( 'jquery');
	wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/' . 
		'jquery/1.6.0/jquery.min.js', false, '1.6.0', false );
	wp_register_script( 'mini_cycle', plugins_url( 
		'jquery.cycle.lite.1.1.min.js', __FILE__ ), array('jquery'), 
		'1.1', false );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'mini_cycle' );
}

function list_images_handler( $atts ) {
		
	extract( shortcode_atts( array( 
		'size' => 'medium',
		'link_click' => true,
		'link_to' => 'direct'
		), $atts ) );
	
	// @TODO - Why does this go to hell when "size" is something not "medium"?	
		
	// @TODO - Somethign to make sure that the value for 'size' is an appropriate string	
		
	$images =& get_children( 'post_type=attachment&post_mime_type=' .
								'image&post_parent=' . get_the_ID() ); 	
	
	// Don't load anything or start processing if there are no 
	// images to display
	if( empty($images) ) {
		return '';
	}
	
	// What's the maximum size the images that will be cycling will be?
	$thumb_w = get_option( $size . '_size_w' );
	$thumb_h = get_option( $size . '_size_h' );
	
	$slider_show_id = 'simpleslider_show_' . get_the_ID();
	
	// JS to set up the Cycle for this instance
	$resp .= '<script>' .
				'$(document).ready(function(){$(\'#' . $slider_show_id . 
				'\').cycle({timeout: 0, speed: 500, next: \'#' . 
				$slider_show_id . '_next\', prev: \'#' . $slider_show_id .
				'_prev\'}); ' .
				'$(\'#' . $slider_show_id. ' > a > img\').' .
				'removeAttr(\'title\'); ' .
				'$(\'#' . $slider_show_id . 
				' > div\').css(\'display\', \'block\');});';
	$resp .= '</script>' . "\n";
	
	// Sliding DIV
	$resp .= '<div class="simpleslider_show" id="' . $slider_show_id . '" ' . 
				'style="height: ' . $thumb_h . 'px; width: ' .
				$thumb_w . 'px; margin: 10px auto;">' . "\n";
	
	$first = true;
	foreach ( $images as $image_id => $image_data ) {
		// To prevent flash of unstyled content - JS in the document.ready
		// handler will remove this (because Cycle Lite can't deal with it)
		// once Cycle Lite has set all the opacities.
		$pre_image = $first ? 
						'<div style="display: block">' : 
						'<div style="display: none">'; 
		$post_image = '';
		$first = false;
				
		if ( true == $link_click ){
			$post_image .= "</a>";
			if( 'direct' == $link_to )
				$pre_image .= '<a href="' . wp_get_attachment_url( $image_id ) . '" target="_new">';
			else 
				$pre_image .= '<a href="' . get_attachment_link( $image_id ) . '" target="_new">';
		}
		
		$post_image .= "</div>\n";
		$resp .= $pre_image . wp_get_attachment_image( $image_id, $size) . $post_image;
	}	
	
	// Sliding DIV
	$resp .= '</div>' . "\n";
	
	// Controls
	$resp .= '<div style="width: ' . $thumb_w . 'px; margin: 10px auto; ' .
				'text-align: center">';
	$resp .= '<a href="#" id="' . $slider_show_id . '_prev">prev</a> &nbsp;' .
				'&nbsp; <a href="#" id="' . $slider_show_id . 
				'_next">next</a>';
	$resp .= '</div>' . "\n";

	return $resp;
}


add_shortcode( 'list_images', 'list_images_handler' );
add_action('wp_enqueue_scripts', 'load_js')
?>