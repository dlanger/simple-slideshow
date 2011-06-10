<?php 
/*
Plugin Name: Simple Slider
Plugin URI:	http://daniellanger.com/simpleslider
Description: A basic image slider. Attach images to a post, and then use a shortcode to display them. Their thumbnails (at a configurable size) will be shown in a jQuery Cycle slideshow, and each will be a link to the full-sized version.
Version: 1.0
Author: Daniel Langer
Author URI: http://www.daniellanger.com
License: FreeBSD

Copyright (c) 2011 Daniel Langer <http://www.daniellanger.com>. 
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions
are met:

1. Redistributions of source code must retain the above copyright
   notice, this list of conditions and the following disclaimer.

2. Redistributions in binary form must reproduce the above copyright
   notice, this list of conditions and the following disclaimer in the
   documentation and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE AUTHORS AND CONTRIBUTORS ``AS IS'' AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
ARE DISCLAIMED. IN NO EVENT SHALL THE AUTHORS OR CONTRIBUTORS BE LIABLE
FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
SUCH DAMAGE.

*/

define("SIMPLESLIDESHOW_VERSION", "1.0");

add_shortcode( 'simpleslideshow', 'sss_handle_shortcode' );
add_action( 'init', 'sss_load_externals' );

if( is_admin() ){
	require_once 'simpleslider-admin.php';
}


function sss_load_externals() {
	// Don't want any of this if we're on an admin page
	if ( is_admin() ) {
		return;
	}
	
	// Load jQuery Cycle Lite locally, and jQuery from the Google CDN
	wp_deregister_script( 'jquery' );
	wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/' . 
		'jquery/1.6.0/jquery.min.js', false, null, false );
	wp_register_script( 'mini_cycle', plugins_url( 
		'jquery.cycle.lite.1.1.min.js', __FILE__ ), array( 'jquery' ), 
		'1.1', true );
	wp_register_script( 'simpleslider', plugins_url( 
		'simpleslider.js', __FILE__ ), array( 'jquery', 'mini_cycle' ), 1.0, false);
	wp_register_style( 'simpleslider_css', plugins_url( 
		'simpleslider.css', __FILE__ ), false, 1.0);
	wp_enqueue_style( 'simpleslider_css' ); 
	wp_enqueue_script( 'simpleslider' );		
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'mini_cycle' );
}


function sss_handle_shortcode( $atts ) {
	extract( shortcode_atts( array( 
		'size' => 'medium',
		'link_click' => 1,
		'link_to' => 'attach',
		'show_counter' => 0,
		'transition_speed' => 100
		), $atts ) );
		
	$images =& get_children( 'post_type=attachment&post_mime_type=' .
								'image&post_parent=' . get_the_ID() ); 	
	
	// Don't load anything or start processing if there are no 
	// images to display.
	if( empty($images) ) {
		return '';
	}

	// If the size specified in the argument to the shortcode isn't one 
	// WordPress recognizes, default to the 'medium' size.	
	if ( ! in_array($size, get_intermediate_image_sizes(), true))
		$size = 'medium';	
	
	// Figure out the maximum size of the images being displayed so we can 
	// set the smallest possible fixed-size container to Cycle in.
	$thumb_w = $thumb_h = 0;
	foreach ( $images as $image_id => $image_data ) {
		$info = wp_get_attachment_metadata( $image_id );
		$thumb_w = max ( $thumb_w, $info[ 'sizes' ][ $size ][ 'width' ] );
		$thumb_h = max ( $thumb_h, $info[ 'sizes' ][ $size ][ 'height' ] );
	}
	
	$slider_show_id = 'simpleslider_show_' . get_the_ID();
	$slider_show_number = get_the_ID();
	
	$resp = '<script>'.
				'lp = {};'.
				'lp["slides"] = ' . count( $images ) . ';' . 
				"lp[\"transition_speed\"] = ${transition_speed};" .
				"simpleslider_prefs[{$slider_show_number}] = lp;";
	$resp .= '</script>' . "\n";
	
	$resp .= "<div class=\"simpleslider_show\" id=\"{$slider_show_id}\" " . 
				"style=\"height: {$thumb_h}px; width: {$thumb_w}px;\">\n";
	
	$first = true;
	foreach ( $images as $image_id => $image_data ) {
		// To prevent flash of unstyled content, we set all slideshow
		// elements aside from the first one to be hidden. JS
		// in document.onready then sets them back to block once
		// Cycle has done its setup and set the opacities.
		$display_style = $first ? 'block' : 'none'; 
		$first = false;
		$image_tag = wp_get_attachment_image( $image_id, $size );
		
		$resp .= "<div style=\"display: {$display_style}\">";
						
		if ( true == $link_click ){	
			if ('direct' == $link_to ) {
				$resp .= '<a href="' . wp_get_attachment_url( $image_id ) . 
							"\" class=\"simpleslider_image_link " . 
							"simpleslider_link\" " . 
							"target=\"_new\">{$image_tag}</a>";
			} else {
				$resp .= '<a href="' . get_attachment_link( $image_id ) . 
							"\" class=\"simpleslider_image_link " .
							"simpleslider_link\" " . 
							"target=\"_new\">{$image_tag}</a>";
			}
		} else { 
			$resp .= $image_tag;
		}				
		$resp .= "</div>\n";
	}	
	
	$resp .= "</div>\n";
	
	// @TODO - style these controls a bit

	// @todo - admin control panel
	
	//@TODO - JS for the counter
	
	// Controls
	if ( true == $show_counter ) 
		$image_counter = "<span id=\"{$slider_show_id}_count\">1</span>" . 
							'/' . count($images);
	else
		$image_counter = '';	
	
	$resp .= "<div style=\"width: {$thumb_w}px; \" " .
				"class=\"simpleslider_controls\">";
	$resp .= "<a href=\"#\" id=\"{$slider_show_id}_prev\" " . 
				"title=\"Previous Image\" class=\"simpleslider_link\"><</a> " .
				"&nbsp; ${image_counter} " . 
				"&nbsp; <a href=\"#\" id=\"{$slider_show_id}_next\" " .
				"title=\"Next Image\" class=\"simpleslider_link\">></a>";
	$resp .= "</div>\n";

	return $resp;
}
?>