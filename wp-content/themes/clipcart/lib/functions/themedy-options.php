<?php
/**
 * The functions in this file act as shortcuts for
 * accessing Themedy-specific Settings that have been
 * stored in the options table and as post meta data.
 */

/**
 * These functions pull options/settings
 * from the options database.
 *
 **/
function themedy_get_option($key, $setting = null) {

	// get setting
	$setting = $setting ? $setting : CLIPCART_SETTINGS_FIELD;

	// setup caches
	static $settings_cache = array();
	static $options_cache = array();

	// allow child theme to short-circuit this function
	$pre = apply_filters('themedy_pre_get_option_'.$key, false, $setting);
	if ( false !== $pre )
		return $pre;

	// Check options cache
	if ( isset($options_cache[$setting][$key]) ) {

		// option has been cached
		return $options_cache[$setting][$key];

	}

	// check settings cache
	if ( isset($settings_cache[$setting]) ) {

		// setting has been cached
		$options = apply_filters('themedy_options', $settings_cache[$setting], $setting);

	} else {

		// set value and cache setting
		$options = $settings_cache[$setting] = apply_filters('themedy_options', get_option($setting), $setting);
	
	}

	// check for non-existent option
	if ( !is_array( $options ) || !array_key_exists($key, (array) $options) ) {

		// cache non-existent option
		$options_cache[$setting][$key] = '';

		return '';
	}

	// option has been cached, cache option
	$options_cache[$setting][$key] = stripslashes( wp_kses_decode_entities( $options[$key] ) );

	return $options_cache[$setting][$key];

}
function themedy_option($key, $setting = null) {
	echo themedy_get_option($key, $setting);
}
