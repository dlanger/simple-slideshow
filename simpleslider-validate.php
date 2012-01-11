<?php

function sss_settings_validate( $inp, $against_user_default = false ) {
	// Setting $against_user_default to true causes inputs to be validated 
	// against the user-supplied defaults (from the admin menu), as opposed
	// to against the hard-coded ones. Used when validating shortcode args
	// (so an invalid arg would fall back to the user-supplied default, which 
	// may not be the same as the hardcoded one). Safe because all user-
	// supplied defaults will have been validated against hard-coded ones
	// when they were saved in the first place.
	$fields = array_keys( sss_settings_defaults(NULL, true) );
	$user_default =  get_option( 'sss_settings' );
	$safe_inp = array();
	
	if( $against_user_default )
		foreach( $fields as $field )
			$safe_inp[ $field ] = call_user_func( 'sss_settings_' . $field . 
				'_val', $inp[ $field ], $user_default[ $field ] );	
	else 
		foreach( $fields as $field )
			$safe_inp[ $field ] = call_user_func( 'sss_settings_' . $field . 
				'_val', $inp[ $field ], false );
	return $safe_inp;
}

function sss_settings_defaults( $field, $return_all = false ){
	$defs = array('size' => 'medium',
					'transition_speed' => 400,
					'link_click' => 0,
					'link_target' => 'direct', 
					'show_counter' => 1, 
					'show_controls' => 1, 
					'cycle_version' => 'lite',
					'transition' => 'fade',
					'auto_advance' => 0,
					'auto_advance_speed' => 5000	
	);
	if( $return_all )
		return $defs;
	else
		return $defs[ $field ];
}

function sss_settings_transition_val( $inp, $user_default = false ){
	// $user_default has no meaning here, because this option can't
	// be set as a shortcode argument - option kept to match signatures.
	
	// Validity of transition depends on the value of cycle_type
	// (if cycle_type == 'lite', the only valid value for 
	/// transition is 'fade'), so we look it up.
	$opts = get_option( 'sss_settings' );
	$cycle_type = sss_settings_defaults( 'cycle_type' );
		
	if( $opts and isset( $opts[ 'cycle_type' ] ) )
		$cycle_type = $opts[ 'cycle_type' ];
	
	if( 'lite' == $cycle_type )
		return 'fade';
	elseif( ctype_alpha( $inp ) )
		return $inp;
	else 
		return sss_settings_defaults( 'transition' );
}

function sss_settings_size_val( $inp, $user_default = false ){
	return validate_in_list( $inp, 'size', get_intermediate_image_sizes(),
		$user_default );
}

function sss_settings_cycle_version_val( $inp, $user_default = false ){
	return validate_in_list( $inp, 'cycle_version', array( 'lite', 'all' ), 
		$user_default );
}

function sss_settings_link_target_val( $inp, $user_default = false ){
	return validate_in_list( $inp, 'link_target', 
		array( 'direct', 'attach' ), $user_default );
}

function sss_settings_transition_speed_val( $inp, $user_default = false ){
	return validate_range( $inp, 'transition_speed', 10, 1000, 
		$user_default);
}

function sss_settings_auto_advance_speed_val( $inp, $user_default = false ){
	return validate_range( $inp, 'auto_advance_speed', 1000, 30000, 
		$user_default);
}

function sss_settings_link_click_val( $inp, $user_default = false ){
	return validate_bool( $inp, 'link_click', $user_default );
}

function sss_settings_show_counter_val( $inp, $user_default = false ){
	return validate_bool( $inp, 'show_counter', $user_default );
}
function sss_settings_show_controls_val( $inp, $user_default = false ){
	return validate_bool( $inp, 'show_controls', $user_default );
}

function sss_settings_auto_advance_val( $inp, $user_default = false ){
	return validate_bool( $inp, 'auto_advance', $user_default );
}

function validate_bool( $inp, $field, $user_default ){
	if( false === $user_default )
		$default_value = sss_settings_defaults( $field );
	else 
		$default_value = $user_default;
		
	$safe_inp = ( int ) $inp;
	if( ! ctype_digit( $inp ) or $safe_inp > 1 or $safe_inp < 0)
		return $default_value;
	else
		return $safe_inp;
}

function validate_in_list( $inp, $field, $options, $user_default ){
	if( false === $user_default )
		$default_value = sss_settings_defaults( $field );
	else 
		$default_value = $user_default;
	
	if( in_array( $inp, $options, true ) )
		return $inp;
	else 
		return $default_value;
}

function validate_range( $inp, $field, $minval, $maxval, $user_default ){
	if( false === $user_default )
		$default_value = sss_settings_defaults( $field );
	else 
		$default_value = $user_default;
	
	$safe_inp = ( int ) $inp;
	if( ! ctype_digit( $inp ) or $safe_inp < $minval or $safe_inp > $maxval )
		return $default_value;
	else 
		return $safe_inp;
}

?>