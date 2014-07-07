<?php
/*----------------------------------------------------------

Plugin Name: Themedy Visual Designer
Plugin URI: http://themedy.com/
Description: Allows you to easily add various shortcodes to your site. Once activated look for the "T" on the visual editor toolbar in the page/post editor. This plugin has no settings.
Author: Themedy
Version: 1.1.6
Author URI: http://themedy.com/

Based on the work of VisualShortcodes.com and Woothemes.com.

----------------------------------------------------------*/

class Themedy_Shortcode_Generator {

/*----------------------------------------------------------
  Class Constructor

  * Constructor function. Sets up the class and registers variable action hooks.
----------------------------------------------------------*/

	function Themedy_Shortcode_Generator () {

		// Register the necessary actions on `admin_init`.
		add_action( 'admin_init', array( &$this, 'init' ) );

		// wp_ajax_... is only run for logged users.
		add_action( 'wp_ajax_themedy_check_url_action', array( &$this, 'ajax_action_check_url' ) );

		// Shortcode testing functionality.
		//if ( ! function_exists( 'add_shortcode' ) ) return;
		//add_shortcode( 'testing',     array( &$this, 'shortcode_testing' ) );

	} // End Themedy_Shortcode_Generator()

/*----------------------------------------------------------
  init()
  *
----------------------------------------------------------*/

	function init() {

		if ( ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) && get_user_option( 'rich_editing') == 'true' )  {

		  	// Add the tinyMCE buttons and plugins.
			add_filter( 'mce_buttons', array( &$this, 'filter_mce_buttons' ) );
			add_filter( 'mce_external_plugins', array( &$this, 'filter_mce_external_plugins' ) );


			// Register the custom CSS styles.
			wp_register_style( 'themedy-shortcode-generator', $this->plugin_url() . 'css/shortcode-generator.css' );
			wp_enqueue_style( 'themedy-shortcode-generator' );

		} // End IF Statement

	} // End init()

/*----------------------------------------------------------
  filter_mce_buttons()

  * Add our new button to the tinyMCE editor.
----------------------------------------------------------*/

	function filter_mce_buttons( $buttons ) {

		array_push( $buttons, '|', 'themedy_shortcodes_button' );

		return $buttons;

	} // End filter_mce_buttons()

/*----------------------------------------------------------
  filter_mce_external_plugins()

  * Add functionality to the tinyMCE editor as an external plugin.
----------------------------------------------------------*/

	function filter_mce_external_plugins( $plugins ) {

        $plugins['ThemedyShortcodes'] = $this->plugin_url() . 'tinymce/editor_plugin.js';

        return $plugins;

	} // End filter_mce_external_plugins()

/*----------------------------------------------------------
  Utility Functions

  * Helper functions for this class.
----------------------------------------------------------*/

	/**
	 * Returns the full URL of this plugin including trailing slash.
	 */
	function plugin_url() {

		return WP_PLUGIN_URL . '/' . str_replace( basename( __FILE__ ), "", plugin_basename( __FILE__ ) );
	}

	/**
	 * Returns the directory url of plugin
	 */
	function plugin_dir_url( $file ) {
    	return trailingslashit( plugins_url( '', $file ) );
	}

/*----------------------------------------------------------
  ajax_action_check_url()

  * Checks if a given url (via GET or POST) exists.
  * Returns JSON.
  *
  * NOTE: For users that are not logged in this is not called.
  * The client recieves <code>-1</code> in that case.
----------------------------------------------------------*/

function ajax_action_check_url() {

	$hadError = true;

	$url = isset( $_REQUEST['url'] ) ? $_REQUEST['url'] : '';

	if ( strlen( $url ) > 0  && function_exists( 'get_headers' ) ) {

		$file_headers = @get_headers( $url );
		$exists       = $file_headers && $file_headers[0] != 'HTTP/1.1 404 Not Found';
		$hadError     = false;
	}

	echo '{ "exists": '. ($exists ? '1' : '0') . ($hadError ? ', "error" : 1 ' : '') . ' }';

	die();

} // End ajax_action_check_url()

/*----------------------------------------------------------
  shortcode_testing()

  * Used for testing that the shortcodes are functioning.
----------------------------------------------------------*/

function shortcode_testing( $atts, $content = null ) {

	if ($content === null) return '';

	return '<strong>Working: ' . $content . '</strong>' . "\n";

} // End shortcode_testing()

} // End Class

/*----------------------------------------------------------
  INSTANTIATE CLASS
----------------------------------------------------------*/

$themedy_shortcode_generator = new Themedy_Shortcode_Generator();

include(WP_PLUGIN_DIR .'/themedy-visual-designer/shortcode-generator.php');

// Setup plugin updater
define( 'THEMEDY_STORE_URL', 'http://themedy.com' );
define( 'THEMEDY_ITEM_NAME', 'Themedy Visual Designer' );

if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	// load our custom updater
	include( dirname( __FILE__ ) . '/updater.php' );
}

// free license key
$license_key = '58ff21191855bc46efcec5d69827fb7c';

$edd_updater = new EDD_SL_Plugin_Updater( THEMEDY_STORE_URL, __FILE__, array(
		'version' 	=> '1.1.6', 				// current version number
		'license' 	=> $license_key, 		// license key
		'item_name' => THEMEDY_ITEM_NAME, 	// name of this plugin
		'author' 	=> 'Themedy'  // author of this plugin
	)
);

register_activation_hook( __FILE__, 'edd_themedy_activate_license' );
register_deactivation_hook( __FILE__, 'edd_themedy_deactivate_license' );