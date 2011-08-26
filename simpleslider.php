<?php 

/*
Plugin Name: Simple Slideshow
Plugin URI:	http://daniellanger.com/blog/simple-slideshow
Description: An easy-to use jQuery+Cycle Lite slideshow - just attach the images to your post using Wordpress' built-in media uploader, and add <code>[simple_slideshow]</code> to the body of your post where you'd like the slideshow to be.
Version: 1.1
Author: Daniel Langer
Author URI: http://www.daniellanger.com
Text Domain: simple_slideshow
License: FreeBSD
*/

require_once 'simpleslider-validate.php';

register_activation_hook( __FILE__, 'sss_activation' );
register_uninstall_hook( __FILE__, 'sss_uninstall' ); 
add_shortcode( 'simple_slideshow', 'sss_handle_shortcode' );
add_action( 'init', 'sss_load_externals' );

if( is_admin() ){
	require_once 'simpleslider-admin.php';
}

function sss_activation() {
	// When activating don't clobber any existing settings, but load 
	// defaults if none are present
	if( ! get_option( 'sss_settings') ) 
		update_option( 'sss_settings', sss_settings_defaults( NULL, true ) );	
}

function sss_uninstall() {
	delete_option( 'sss_settings' );
}

function sss_load_externals() {
	if ( is_admin() ) {

		wp_enqueue_script( 'jquery' );
		wp_register_script( 'jquery-ui-custom', plugins_url( 
			'jquery-ui-1.8.15.custom.min.js', __FILE__ ), array( 'jquery' ),
			'1.8.15', false );
		wp_enqueue_script( 'jquery-ui-custom' );
		wp_register_style( 'simpleslider_admin', plugins_url(
			'simpleslider-admin.css', __FILE__ ), false, 1.1);
		wp_enqueue_style( 'simpleslider_admin');
		return;
	}
	
	$defaults = get_option( 'sss_settings' );
	if( ! $defaults ) 
		$defaults = sss_settings_defaults(NULL, true);
		
	if( $defaults[ 'cycle_version' ] == 'lite' ) 
		wp_register_script( 'cycle', plugins_url( 
		'jquery.cycle.lite.1.3.min.js', __FILE__ ), array( 'jquery' ), 
		'1.3', true );
	else 
		wp_register_script( 'cycle', plugins_url( 
		'jquery.cycle.all.2.88.min.js', __FILE__ ), array( 'jquery' ), 
		'2.88', true );
	
	wp_enqueue_script( 'cycle' );
	wp_register_script( 'simpleslider', plugins_url( 
		'simpleslider.js', __FILE__ ), array( 'jquery', 'cycle' ), 1.0, false);
	wp_register_style( 'simpleslider_css', plugins_url( 
		'simpleslider.css', __FILE__ ), false, 1.0);
	wp_enqueue_style( 'simpleslider_css' ); 
	wp_enqueue_script( 'simpleslider' );		
	wp_enqueue_script( 'jquery' );
	
	load_plugin_textdomain( 'simple_slideshow', false, basename( 
		dirname( __FILE__ ) ) );
}


function sss_handle_shortcode( $attrs ) {
	$defaults = get_option( 'sss_settings' );
	if( ! $defaults ) 
		$defaults = sss_settings_defaults(NULL, true);

	$default_attrs =  array( 'size' => $defaults[ 'size' ],
						'link_click' => $defaults[ 'link_click' ],
						'link_target' => $defaults[ 'link_target' ],
						'show_counter' => $defaults[ 'show_counter' ],
						'transition_speed' => $defaults[ 'transition_speed' ],
						'transition' => $defaults[ 'transition' ]);
	
	extract( sss_settings_validate( shortcode_atts( $default_attrs, 
				$attrs ) ) );
	
	$images =& get_children( 'post_type=attachment&post_mime_type=' .
								'image&post_parent=' . get_the_ID() .
								'&orderby=menu_order&order=ASC' ); 	
	
	// Don't load anything or start processing if there are no 
	// images to display.
	if( empty($images) ) 
		return '';
		
	// Figure out the maximum size of the images being displayed so we can 
	// set the smallest possible fixed-size container to cycle in.
	$thumb_w = $thumb_h = 0;
	foreach ( $images as $image_id => $image_data ) {
		$info = wp_get_attachment_metadata( $image_id );
		$thumb_w = max ( $thumb_w, $info[ 'sizes' ][ $size ][ 'width' ] );
		$thumb_h = max ( $thumb_h, $info[ 'sizes' ][ $size ][ 'height' ] );
	}
	
	$slider_show_id = 'simpleslider_show_' . get_the_ID();
	$slider_show_number = get_the_ID();
	
	$resp = '<script type="text/javascript">'.
				"simpleslider_prefs[{$slider_show_number}] = {".
					'\'slides\' : ' . count( $images ) . ', '.
					"'transition_speed' : ${transition_speed}";
	if( 'all' == $defaults[ 'cycle_version' ])
		$resp .= ", 'fx': '${transition}'";
	$resp .= '};';
	$resp .= '</script>' . "\n";
	
	$resp .= "<div class=\"simpleslider_show\" id=\"{$slider_show_id}\" " . 
				"style=\"height: {$thumb_h}px; width: {$thumb_w}px;\">\n";
	
	$first = true;
	foreach ( $images as $image_id => $image_data ) {
		$opacity = $first ? '1' : '0'; 
		$first = false;
		$image_tag = wp_get_attachment_image( $image_id, $size );
		
		$resp .= "<div style=\"opacity: {$opacity}\">";
						
		if ( 1 == $link_click ){	
			if ( 'direct' == $link_target ) {
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
	
	// Controls
	if ( true == $show_counter ) 
		$image_counter = "<span id=\"{$slider_show_id}_count\">1</span>" . 
							'/' . count($images);
	else
		$image_counter = '';	
	
	$resp .= "<div style=\"width: {$thumb_w}px; \" " .
				"class=\"simpleslider_controls\">";
	$resp .= "<a href=\"#\" id=\"{$slider_show_id}_prev\" " . 
				"title=\"Previous Image\" class=\"simpleslider_link\">◄ " . 
				__( 'Prev.', 'simple_slideshow' ) . "</a> " .
				"&nbsp; ${image_counter} " . 
				"&nbsp; <a href=\"#\" id=\"{$slider_show_id}_next\" " .
				"title=\"Next Image\" class=\"simpleslider_link\">" .
				__( 'Next', 'simple_slideshow' ) . " ►</a>";
	$resp .= "</div>\n";
	
	return $resp;
}
?>