<?php 
/*
Plugin Name: Simple Slider
Plugin URI:	http://daniellanger.com/simpleslider
Description: A basic image slider. Attach images to a post, and then use a shortcode to display them. Their thumbnails (at a configurable size) will be shown in a jQuery Cycle slideshow, and each will be a link to the full-sized version.
Version: 1.0
Author: Daniel Langer
Author URI: http://www.daniellanger.me
License: X11 
*/

define("SIMPLESLIDER_VERSION", "1.0");


function load_js(){
	// Don't want any of this if we're on an admin page
	if( is_admin() ){
		return;
	}
	
	// Load jQuery Cycle Lite locally, and jQuery from the Google CDN
	wp_deregister_script( 'jquery');
	wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/' . 
		'jquery/1.6.0/jquery.min.js', false, '1.6.0', false);
	wp_register_script( 'mini_cycle', plugins_url( 
		'jquery.cycle.lite.1.1.min.js', '__FILE__' ), array('jquery'), 
		'1.1', false);
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'min_cycle' );
}

function list_images_handler( $atts ){
		
	extract( shortcode_atts( array( 
		'size' => 'medium'
		), $atts ) );
	
	$images =& get_children( 'post_type=attachment&post_mime_type=' .
								'image&post_parent=' . get_the_ID() ); 	
	
	// Don't load anything or start processing if there are no 
	// images to display
	if( empty($images) ){
		return '';
	}
			
	$resp = ''. plugins_url('js.oinm', __FILE__);
	foreach ( $images as $image_id => $image_data){
		$resp .= wp_get_attachment_image( $image_id ) ;
	}	

	return $resp;
}


add_shortcode( 'list_images', 'list_images_handler' );
add_action('wp_enqueue_scripts', 'load_js')
?>