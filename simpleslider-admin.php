<?php 

add_action( 'admin_init', 'sss_settings_init' );
add_action( 'admin_menu', 'sss_load_menu' );
add_filter( 'plugin_action_links', 'sss_add_action_link', 10, 2 );
add_filter( 'contextual_help', 'sss_contextual_help_handler', 10, 3 );

require_once 'simpleslider-admin-help.php';

function sss_settings_init() {
	register_setting( 'sss_settings', 'sss_settings', 'sss_settings_validate');
	add_settings_section( 'sss_settings_main', '', 
		'sss_settings_text', 'simple_slideshow' );
	add_settings_field( 'sss_size', 'Image size', 'sss_settings_size', 
		'simple_slideshow', 'sss_settings_main');
	add_settings_field( 'sss_transition_speed', 'Transition speed', 
		'sss_settings_transition_speed', 'simple_slideshow', 
		'sss_settings_main');
	add_settings_field( 'sss_link_click', 'Click image to open full-size ' . 
		'version in a new window', 'sss_settings_link_click', 
		'simple_slideshow', 'sss_settings_main');
	add_settings_field( 'sss_link_target', 'Link target', 
		'sss_settings_link_target', 'simple_slideshow', 
		'sss_settings_main');
	add_settings_field( 'sss_show_counter', 'Show image counter', 
		'sss_settings_show_counter', 'simple_slideshow', 
		'sss_settings_main');
	add_settings_field( 'sss_show_controls', 'Show image controls', 
		'sss_settings_show_controls', 'simple_slideshow', 
		'sss_settings_main');
	add_settings_field( 'sss_cycle_version', 'Cycle version', 
		'sss_settings_cycle_version', 'simple_slideshow', 
		'sss_settings_main');
	add_settings_field( 'sss_transition', 'Transition effect', 
		'sss_settings_transition', 'simple_slideshow', 
		'sss_settings_main');
	add_settings_field( 'sss_auto_advance', 'Auto-advance', 
		'sss_settings_auto_advance', 'simple_slideshow', 
		'sss_settings_main');
	add_settings_field( 'sss_auto_advance_speed', 'Auto-advance speed', 
		'sss_settings_auto_advance_speed', 'simple_slideshow', 
		'sss_settings_main');
}

function sss_load_menu() {
	global $sss_menu_hook_name;
	$sss_menu_hook_name = add_options_page( 'Simple Slideshow Settings', 'Simple Slideshow', 
		'manage_options', 'simple_slideshow', 'sss_admin_menu');
}

function sss_settings_text() {
	// Moved into the form itself in order to keep consistent look/avoid WP's
	// styling of settings section introductory texts - but still need a 
	// function here to keep the signature expected.
	return;
}

// From http://codex.wordpress.org/Adding_Contextual_Help_to_Administration_Menus
function sss_contextual_help_handler( $contextual_help, $screen_id, $screen ) {
	global $sss_menu_hook_name, $sss_contextual_help;
	
	if( $screen_id == $sss_menu_hook_name ) 
		$contextual_help = $sss_contextual_help;
	
	return $contextual_help;
}

function sss_settings_size() {
	$opts = get_option( 'sss_settings' );
	$sizes = get_intermediate_image_sizes();
	$curr = sss_settings_defaults('size');
	
	if( $opts and isset( $opts[ 'size' ] ) )
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

function sss_settings_transition_speed() {
	$opts = get_option( 'sss_settings' );
	$curr = sss_settings_defaults('transition_speed');
		
	if( $opts and isset( $opts[ 'transition_speed' ] ) )
		$curr = $opts[ 'transition_speed' ];
		
	echo '<input type="number" min="10" max="1000" step="10" value="', 
			$curr, '" id="sss_transition_speed" ',
			'name="sss_settings[transition_speed]">';
}

function sss_settings_transition() {
	$opts = get_option( 'sss_settings' );
	$curr = sss_settings_defaults( 'transition' );
		
	if( $opts and isset( $opts[ 'transition' ] ) )
		$curr = $opts[ 'transition' ];
		
	echo '<input type="text" value="', $curr, 
			'" id="sss_transition" ',
			'name="sss_settings[transition]"';
	
	if ( 'lite' == $opts[ 'cycle_version' ] )
		echo ' disabled ';
	
	echo '>';
}

function sss_settings_auto_advance_speed() {
	$opts = get_option( 'sss_settings' );
	$curr = sss_settings_defaults( 'auto_advance_speed' );
		
	if( $opts and isset( $opts[ 'auto_advance_speed' ] ) )
		$curr = $opts[ 'auto_advance_speed' ];
	echo '<input type="number" min="1000" max="30000" step="500" value="', $curr, 
			'" id="sss_auto_advance_speed" ',
			'name="sss_settings[auto_advance_speed]">';
}

function sss_settings_auto_advance() {
	$opts = get_option( 'sss_settings' );
	$curr = sss_settings_defaults( 'auto_advance' );
		
	if( $opts and isset( $opts[ 'auto_advance' ] ) )
		$curr = $opts[ 'auto_advance' ];
	
	echo '<select id="sss_auto_advance" name="sss_settings[auto_advance]">',
			'<option ';
	if( ! $curr )
		echo 'selected ';
	echo 'value="0">Disabled</option><option ';
	if( $curr )
		echo 'selected ';
	echo 'value="1">Enabled</option></select>';	
}

function sss_settings_link_click() {
	$opts = get_option( 'sss_settings' );
	$curr = sss_settings_defaults( 'link_click' );
		
	if( $opts and isset( $opts[ 'link_click' ] ) )
		$curr = $opts[ 'link_click' ];
	
	echo '<select id="sss_link_click" name="sss_settings[link_click]">',
			'<option ';
	if( ! $curr )
		echo 'selected ';
	echo 'value="0">No</option><option ';
	if( $curr )
		echo 'selected ';
	echo 'value="1">Yes</option></select>';	
}

function sss_settings_cycle_version() {
	$opts = get_option( 'sss_settings' );
	$curr = sss_settings_defaults( 'cycle_version' );
	
	if( $opts and isset( $opts[ 'cycle_version' ] ) )
		$curr = $opts[ 'cycle_version' ];
		
	echo '<select id="sss_cycle_version" name="sss_settings[cycle_version]">',
			'<option ';
	if( 'lite' == $curr )
		echo 'selected ';
	echo 'value="lite">Lite</option><option ';
	if( 'all' == $curr )
		echo 'selected ';
	echo 'value="all">All</option></select>';	
}

function sss_settings_link_target() {
	$opts = get_option( 'sss_settings' );
	$curr = sss_settings_defaults( 'link_target' );
		
	if( $opts and isset( $opts[ 'link_target' ] ) )
		$curr = $opts[ 'link_target' ];
		
	echo '<select id="sss_target" name="sss_settings[link_target]"><option ';
	if( 'attach' == $curr )
		echo 'selected ';
	echo 'value="attach">Attachment page</option><option ';
	if( 'direct' == $curr )
		echo 'selected ';
	echo 'value="direct">Image file</option></select>';	
}

function sss_settings_show_counter() {
	$opts = get_option( 'sss_settings' );
	$curr = sss_settings_defaults( 'show_counter' );
		
	if( $opts and isset( $opts[ 'show_counter' ] ) )
		$curr = $opts[ 'show_counter' ];
	
	echo '<select id="sss_show_counter" name="sss_settings[show_counter]">',
			'<option ';
	if( ! $curr )
		echo 'selected ';
	echo 'value="0">No</option><option ';
	if( $curr )
		echo 'selected ';
	echo 'value="1">Yes</option></select>';	
}

function sss_settings_show_controls() {
	$opts = get_option( 'sss_settings' );
	$curr = sss_settings_defaults( 'show_controls' );
		
	if( $opts and isset( $opts[ 'show_controls' ] ) )
		$curr = $opts[ 'show_controls' ];
	
	echo '<select id="sss_show_controls" name="sss_settings[show_controls]">',
			'<option ';
	if( ! $curr )
		echo 'selected ';
	echo 'value="0">No</option><option ';
	if( $curr )
		echo 'selected ';
	echo 'value="1">Yes</option></select>';	
}

// From http://www.wpmods.com/adding-plugin-action-links
function sss_add_action_link( $links, $file ){
	static $this_plugin;
	
	if( ! $this_plugin ) {
		// Adjust to reflect filename of main plugin file
		$this_plugin = plugin_basename( str_replace( '-admin', '', __FILE__ ) );
	}
	
	if( $file == $this_plugin ) {
		$settings_link = '<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin' . 
			'/options-general.php?page=simple_slideshow">Settings</a>';
		array_unshift( $links, $settings_link );
	}
	return $links;
}

function sss_admin_menu() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have sufficient privileges to access this ' .
			'page. Please contact your administrator.' , 'simple_slideshow') );
	}
?>
<script type="text/javascript">
jQuery(document).ready(function($){

	var transition_field = $('#sss_transition');
	$('#sss_cycle_version').change(function(e){
		if($(e.target).val() == 'lite'){
			transition_field.attr('disabled', true).val('fade');
		} else {
			transition_field.attr('disabled', false);
		}
	});

	var tablist = $("#tabs").tabs();
	$("#show-attributes-tab").click(function(){
		tablist.tabs('select', 2);
	});

	var transitions = [
		'blindX',
		'blindY',
		'blindZ',
		'cover',
		'curtainX',
		'curtainY',
		'fade',
		'fadeZoom',
		'growX',
		'growY',
		'none',
		'scrollUp',
		'scrollDown',
		'scrollLeft',
		'scrollRight',
		'scrollHorz',
		'scrollVert',
		'shuffle',
		'slideX',
		'slideY',
		'toss',
		'turnUp',
		'turnDown',
		'turnLeft',
		'turnRight',
		'uncover',
		'wipe',
		'zoom'];

		$('#sss_transition').autocomplete({
			source: transitions
		});
});
</script>


<div class="wrap">
<div id="icon-options-general" class="icon32"></div>
<h2>Simple Slideshow</h2>

<p>Thanks for downloading Simple Slideshow. If you like it, please feel free 
to give it a positive review at Wordpress.org so that others can 
learn about it.</p>

<p><b>Developers:</b> Got an idea on how to make Simple Slideshow better? Fork 
it from <a href="https://github.com/dlanger/simple-slideshow/">github</a> and send in a pull request!</p> 

<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Settings</a></li>
		<li><a href="#tabs-2">Instructions</a></li>
		<li><a href="#tabs-3">Attributes</a></li>
	</ul>

	<div id="tabs-1">
		<p>
			<form method="post" action="options.php">			
				<p>Options set here become the <em>default</em>, but can still 
				be changed on a per-show basis by using attributes.</p> 
				
				<p>For more information about the meaning of each option, please click 
				the <em>Help</em> button above. For instructions on how to set these options on
				a per-show basis, consult the 
				<em><a href='#' id='show-attributes-tab'>Attributes</a></em> tab.</p>
				<?php 
					settings_fields( 'sss_settings' );
					do_settings_sections( 'simple_slideshow' );
				?>
				
				<p class="submit">
					<input type="submit" class="button-primary" value="Save Changes">
				</p>
			</form>
		</p>
	</div>
	
	<div id="tabs-2"> 
		<p>
			<?php 
				global $sss_tab_help;
				echo $sss_tab_help; 
			?>
		</p>
	</div>
	
	<div id="tabs-3"> 
		<p>
			<?php 
				global $sss_attribute_tab_help;
				echo $sss_attribute_tab_help; 
			?>
		</p>
	</div>

</div>

<?php 	
}
?>
