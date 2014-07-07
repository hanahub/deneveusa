<?php

/**
 * This adds our Themedy settings sub menu page
 */
add_action('admin_menu', 'themedy_add_admin');
function themedy_add_admin() {
	global $_themedy_theme_settings_pagehook;

	$_themedy_theme_settings_pagehook = add_theme_page(__('Themedy Settings','themedy'), __('Themedy Settings','themedy'), 'edit_theme_options', 'themedy', 'themedy_theme_settings_admin');
}

/**
 * This adds our Themedy settings CSS
 */
add_action('admin_init', 'themedy_load_admin_styles');
function themedy_load_admin_styles() {
	wp_enqueue_style('themedy_admin_css', CHILD_THEME_LIB_URL.'/css/themedy-admin.css');
}

/**
 * This registers the settings field and adds defaults to the options table.
 * It also handles settings resets by pushing in the defaults.
 */
add_action('admin_init', 'themedy_register_theme_settings', 5);
function themedy_register_theme_settings() {
	register_setting(  CLIPCART_SETTINGS_FIELD,  CLIPCART_SETTINGS_FIELD );
	add_option(  CLIPCART_SETTINGS_FIELD, themedy_theme_settings_defaults() );

	if ( !isset($_REQUEST['page']) || $_REQUEST['page'] != 'themedy' )
		return;

	if ( themedy_get_option('reset') ) {
		update_option( CLIPCART_SETTINGS_FIELD, themedy_theme_settings_defaults());
		wp_redirect( admin_url( 'admin.php?page=themedy&reset=true' ) );
		exit;
	}
}

/**
 * This is the notice that displays when you successfully save or reset
 * the theme settings.
 */
add_action('admin_notices', 'themedy_theme_settings_notice');
function themedy_theme_settings_notice() {

	if ( !isset($_REQUEST['page']) || $_REQUEST['page'] != 'themedy' )
		return;

	if ( isset( $_REQUEST['reset'] ) && $_REQUEST['reset'] == 'true' ) {
		echo '<div id="message" class="updated"><p><strong>'.__('Theme Settings Reset', 'themedy').'</strong></p></div>';
	}
	if ( isset($_REQUEST['updated']) && $_REQUEST['updated'] == 'true') {
		echo '<div id="message" class="updated"><p><strong>'.__('Theme Settings Saved', 'themedy').'</strong></p></div>';
	}
	elseif ( isset($_REQUEST['settings-updated']) && $_REQUEST['settings-updated'] == 'true') {
		echo '<div id="message" class="updated"><p><strong>'.__('Theme Settings Saved', 'themedy').'</strong></p></div>';
	}

}

/**
 * This is a necessary go-between to get our scripts and boxes loaded
 * on the theme settings page only, and not the rest of the admin
 */
add_action('admin_menu', 'themedy_theme_settings_init');
function themedy_theme_settings_init() {
	global $_themedy_theme_settings_pagehook;

	add_action('load-'.$_themedy_theme_settings_pagehook, 'themedy_theme_settings_scripts');
	add_action('load-'.$_themedy_theme_settings_pagehook, 'themedy_theme_settings_boxes');
}

function themedy_theme_settings_scripts() {
	wp_enqueue_script('common');
	wp_enqueue_script('wp-lists');
	wp_enqueue_script('postbox');
}

/**
 * Tell WordPress that we want only 2 columns available for our meta-boxes
 */
add_filter('screen_layout_columns', 'themedy_theme_settings_layout_columns', 10, 2);
function themedy_theme_settings_layout_columns($columns, $screen) {
	global $_themedy_theme_settings_pagehook;

	if (function_exists('add_screen_option')) { add_screen_option('layout_columns', array('max' => 2, 'default' => 2) ); }

	return $columns;
}

/**
 * This function is what actually gets output to the page. It handles the markup,
 * builds the form, outputs necessary JS stuff, and fires <code>do_meta_boxes()</code>
 */
function themedy_theme_settings_admin() {

	global $_themedy_theme_settings_pagehook, $screen_layout_columns;
	$screen = get_current_screen();
?>	
	<div class="wrap">
		<?php screen_icon('options-general'); ?>
        <h2>
            <?php _e(CHILD_THEME_NAME.' - Theme Settings', 'themedy'); ?>
        </h2>
        <div id="dashboard-widgets-wrap" class="wrap themedy-metaboxes">
        <form method="post" action="options.php">
        	<p class="top-buttons">
                <input type="submit" class="button button-primary" value="<?php _e('Save Settings', 'themedy') ?>" />
                <input type="submit" class="button" name="<?php echo  CLIPCART_SETTINGS_FIELD; ?>[reset]" value="<?php _e('Reset Settings', 'themedy'); ?>" onclick="return confirm('<?php echo esc_js( __('Are you sure you want to reset?', 'themedy') ); ?>');" />
            </p>
    
            <?php wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false ); ?>
            <?php wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false ); ?>
            <?php settings_fields( CLIPCART_SETTINGS_FIELD); // important! ?>
            <input type="hidden" name="<?php echo  CLIPCART_SETTINGS_FIELD; ?>[theme_version]>" value="<?php echo esc_attr(themedy_option('theme_version')); ?>" />
    
            <div id="dashboard-widgets" class="metabox-holder<?php echo ' columns-'.$screen_layout_columns; ?>">
                <?php
                echo "\t<div id='postbox-container-1' class='postbox-container' >\n";
                do_meta_boxes( $screen->id, 'normal', '' );
    
                echo "\t</div><div id='postbox-container-2' class='postbox-container'>\n";
                do_meta_boxes( $screen->id, 'side', '' );
                    ?>
            </div></div>
    
            <div class="bottom-buttons">
                <input type="submit" class="button button-primary" value="<?php _e('Save Settings', 'themedy') ?>" />
                <input type="submit" class="button" name="<?php echo  CLIPCART_SETTINGS_FIELD; ?>[reset]" value="<?php _e('Reset Settings', 'themedy'); ?>" onclick="return confirm('<?php echo esc_js( __('Are you sure you want to reset?', 'themedy') ); ?>');" />
            </div>
        </form>
        </div>
    </div>
	<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready( function($) {
			// close postboxes that should be closed
			$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
			// postboxes setup
			postboxes.add_postbox_toggles('<?php echo $_themedy_theme_settings_pagehook; ?>');
		});
		//]]>
	</script>

<?php
}