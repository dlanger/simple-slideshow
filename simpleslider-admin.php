<?php 

add_action( 'admin_init', 'sss_settings_init' );
add_action( 'admin_menu', 'sss_load_menu' );

//@TODO Why doesn't this work?
add_filter( 'plugin_action_links', 'sss_add_action_link', 10, 6 );

function sss_settings_init() {
	
	add_settings_section( 'sss_settings', 'Default Settings', 
		'sss_settings_text', 'wp_simpleslideshow' );
	add_settings_field( 'sss_size', 'Image size', 'sss_settings_size', 
		'wp_simpleslideshow', 'sss_settings');
}

function sss_load_menu() {
	add_options_page( 'Simple Slideshow Settings', 'Simple Slideshow', 
		'manage_options', 'wp_simpleslideshow', 'sss_admin_menu');
	
}

function sss_settings_text() {
	echo 'SSS_SETTINGS_TEXT';
}

function sss_settings_size() {
	$opts = get_option( 'sss_settings_size' );
	echo '<input id="sss_setting_size" name'; 
}

//@TODO Why doesn't this work?

// Adding 'Settings' link to plugin listing, 
// from http://www.wpmods.com/adding-plugin-action-links
function sss_add_action_link( $links, $file ){
	static $this_plugin;
	
	if( ! $this_plugin ) {
		$this_plugin = plugin_basename(__FILE__);
	}
	
	if( $file == $this_plugin ) {
		$settings_link = '<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin' . 
			'/options-general.php?page=wp_simpleslideshow">Settings</a>';
		array_unshift( $links, $settings_link );
	}
	return $links;
}


function sss_admin_menu() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have sufficient privileges to access this ' .
			'page. Please contact your administrator.' ) );
	}
	
?>

<div class="wrap">
<h2>Simple Slideshow</h2>

<p>Thanks for downloading Simple Slideshow. If you like it, please feel free 
to give it a positive review at Wordpress.org so that others can 
learn about it.</p>

<p><b>Developers:</b> If you see something you don't like, change it! 
Fork Simple Slideshow from <a href="#">GitHub</a>.</p> 

<div>
<form method="post" action="options.php">

<?php 
	settings_fields( 'wp_simpleslideshow' );
	do_settings_sections( 'wp_simpleslideshow' );
?>

<p class="submit">
<input type="submit" class="button-primary" value="Save Changes">
</p>
</form>
</div>

<?php 	
}
?>
