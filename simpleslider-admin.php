<?php 

add_action( 'admin_init', 'sss_settings_init' );
add_action( 'admin_menu', 'sss_load_menu' );

//@TODO Why doesn't this work?
add_filter( 'plugin_action_links', 'sss_add_action_link', 10, 6 );

function sss_settings_init() {
	register_setting( 'sss_settings', 'sss_settings', 'sss_settings_validate');
	add_settings_section( 'sss_settings_main', 'Default Settings', 
		'sss_settings_text', 'wp_simpleslideshow' );
	add_settings_field( 'sss_size', 'Image size', 'sss_settings_size', 
		'wp_simpleslideshow', 'sss_settings_main');
	add_settings_field( 'sss_speed', 'Transition speed', 
		'sss_settings_speed', 'wp_simpleslideshow', 'sss_settings_main');
	add_settings_field( 'sss_link', 'Click image to open full-size version ' . 
		'in a new window', 'sss_settings_click', 'wp_simpleslideshow', 
		'sss_settings_main');
	add_settings_field( 'sss_target', 'Link target ', 'sss_settings_target', 
		'wp_simpleslideshow', 'sss_settings_main');
}

function sss_load_menu() {
	add_options_page( 'Simple Slideshow Settings', 'Simple Slideshow', 
		'manage_options', 'wp_simpleslideshow', 'sss_admin_menu');
	
}

function sss_settings_text() {
	echo 'Options set here become the <em>default</em>, but can still be ', 
			'changed on a per-show basis by using attributes.';
}

function sss_settings_defaults( $field ){
	$defs = array('size' => 'medium',
					'speed' => 100,
					'click' => 0,
					'target' => 'direct');
	return $defs[ $field ];
}

function sss_settings_size() {
	$opts = get_option( 'sss_settings' );
	$sizes = get_intermediate_image_sizes();
	$curr = sss_settings_defaults('size');
	
	if( isset( $opts[ 'size' ] ) )
		$curr = $opts[ 'size' ];
	 	
	echo '<select id="sss_size" name="sss_settings[size]">';
	foreach( $sizes as $size ) {	
		echo '<option ';
		if( $size == $curr )
			echo 'selected ';			
		echo 'value="', $size, '">', ucfirst($size) ,'</option>';
	}
	echo '</select>';
}

function sss_settings_speed() {
	$opts = get_option( 'sss_settings' );
	$curr = sss_settings_defaults('speed');
		
	if( isset( $opts[ 'speed' ] ) )
		$curr = $opts[ 'speed' ];
		
	echo '<input type="number" min="1" max="1000" step="10" value="', 
			$curr, '" id="sss_speed" ',
			'name="sss_settings[speed]">';
}

function sss_settings_click() {
	$opts = get_option( 'sss_settings' );
	$curr = sss_settings_defaults('click');
		
	if( isset( $opts[ 'click' ] ) )
		$curr = $opts[ 'click' ];
	
	echo '<select id="sss_click" name="sss_settings[click]"><option ';
	if( ! $curr )
		echo 'selected ';
	echo 'value="0">No</option><option ';
	if( $curr )
		echo 'selected ';
	echo 'value="1">Yes</option></select>';	
}

function sss_settings_target() {
	$opts = get_option( 'sss_settings' );
	$curr = sss_settings_defaults('target');
		
	if( isset( $opts[ 'target' ] ) )
		$curr = $opts[ 'target' ];
		
	echo '<select id="sss_target" name="sss_settings[target]"><option ';
	if( 'attach' == $curr )
		echo 'selected ';
	echo 'value="attach">Attachment page</option><option ';
	if( 'direct' == $curr )
		echo 'selected ';
	echo 'value="direct">Image file</option></select>';	
}

function sss_settings_size_val( $inp ){
	if( in_array( $inp, get_intermediate_image_sizes() ) )
		return $inp;
	else 
		return sss_settings_defaults('size'); 
}

function sss_settings_speed_val( $inp ){
	$safe_inp = ( int ) $inp;
	if( $safe_inp < 1 or $safe_inp > 1000)
		return sss_settings_defaults('speed');
	else 
		return $safe_inp;
}

function sss_settings_click_val( $inp ){
	$safe_inp = ( int ) $inp;
	if( $safe_inp > 1 or $safe_inp < 0)
		return sss_settings_defaults('click');
	else
		return $safe_inp;
}

function sss_settings_target_val( $inp ){
	if( $inp == 'direct' or $inp == 'attach')
		return $inp;
	else 
		return sss_settings_defaults('target');
		
}

function sss_settings_validate( $inp ) {
	$fields = array( 'size', 'speed', 'click', 'target');
	$safe_inp = array();
	foreach( $fields as $field)
		$safe_inp[ $field ] = call_user_func( 'sss_settings_' . $field . 
			'_val', $inp[ $field ]);	
	return $safe_inp;
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

<p><b>Developers:</b> Got an idea on how to make Simple Slideshow better? Fork 
it from <a href="#">GitHub</a> and send in a pull request!</p> 

<div>
<form method="post" action="options.php">

<?php 
	settings_fields( 'sss_settings' );
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
