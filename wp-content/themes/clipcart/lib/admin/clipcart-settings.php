<?php

/**
 * This function registers the default values for Themedy theme settings
 */
 
function themedy_theme_settings_defaults() {
	$defaults = array( // define our defaults
		'style' => 'default',
		'custom' => 0,
		'shortcodes-css' => 1,
		'slider' => 1,
		'slider_effect' => 'fade',
		'slider_easing' => 'easeInOutCubic',
		'slider_speed' => '800',
		'slider_pause' => '5000',
		'slider_limit' => '5',
		'homepage_products' => 1,
		'homepage_products_title' => 'Latest Products',
		'homepage_products_limit' => '4',
		'homepage_breadcrumb_text' => 'Hello and welcome to our store!',
		'currency_sign' => '$',
		'product_limit' => '9',
		'product_gallery' => 1,
		'mobile_menu' => 1,
		'footer' => 1,
		'footer_text' => 'Copyright &copy;'.date('Y') . ' <a href="'. get_bloginfo('url') .'">' . get_bloginfo('name') . '</a> &mdash; Built on <a href="http://www.studiopress.com/themes/genesis">Genesis</a> by <a href="http://themedy.com/">Themedy</a>'
	);
	
	return apply_filters('themedy_theme_settings_defaults', $defaults);
}

/**
 * Add our meta boxes
 */

function themedy_theme_settings_boxes() {
	global $_themedy_theme_settings_pagehook;
	
	if (function_exists('add_screen_option')) { add_screen_option('layout_columns', array('max' => 2, 'default' => 2) ); }

	add_meta_box('themedy-theme-settings-version', __('Information', 'themedy'), 'themedy_theme_settings_info_box', $_themedy_theme_settings_pagehook, 'normal');
	add_meta_box('themedy-theme-settings-slider', __('Featured Slider', 'themedy'), 'themedy_theme_settings_slider', $_themedy_theme_settings_pagehook, 'normal');
	add_meta_box('themedy-theme-settings-homepage-products', __('Homepage Products', 'themedy'), 'themedy_theme_settings_homepage_products', $_themedy_theme_settings_pagehook, 'normal');
	add_meta_box('themedy-theme-settings-appearance', __('Appearance', 'themedy'), 'themedy_theme_settings_appearance', $_themedy_theme_settings_pagehook, 'side');
	add_meta_box('themedy-theme-settings-general', __('General Options', 'themedy'), 'themedy_theme_settings_general', $_themedy_theme_settings_pagehook, 'side');
	add_meta_box('themedy_theme_settings_footer', __('Footer', 'themedy'), 'themedy_theme_settings_footer', $_themedy_theme_settings_pagehook, 'side');
}

/**
 * This next section defines functions that contain the content of the meta boxes
 */
 
function themedy_theme_settings_info_box() { ?>
	<p><strong><?php echo CHILD_THEME_NAME; ?></strong> by <a href="http://themedy.com">Themedy.com</a></p>
	<p><strong><?php _e('Version:', 'themedy'); ?></strong> <?php echo CHILD_THEME_VERSION; ?> <?php echo '&middot;'; ?> <strong><?php _e('Released:', 'themedy'); ?></strong> <?php echo CHILD_THEME_RELEASE_DATE; ?></p>
    <p><span class="description"><?php _e('For support, please visit <a href="http://themedy.com/forum/">http://themedy.com/forum/</a> <br /><br />If you purchased from StudioPress you can get <a href="http://www.studiopress.com/support/forumdisplay.php?f=175">support by clicking here.</a>', 'themedy'); ?></span></p>
	
<?php
}

function themedy_theme_settings_slider() { ?>
	<p><input type="checkbox" name="<?php echo CLIPCART_SETTINGS_FIELD; ?>[slider]" id="<?php echo CLIPCART_SETTINGS_FIELD; ?>[slider]" value="1" <?php checked(1, themedy_get_option('slider')); ?> /> <label for="<?php echo CLIPCART_SETTINGS_FIELD; ?>[slider]"><?php _e("Include The Featured Slider on Homepage?", 'themedy'); ?></label>
	</p>
    <hr class="div" />
    <p><?php _e("Transition Effect:", 'themedy'); ?>
	<select name="<?php echo CLIPCART_SETTINGS_FIELD; ?>[slider_effect]">
        <option style="padding-right:10px;" value="fade" <?php selected('fade', themedy_get_option('slider_effect')); ?>><?php _e("Fade", 'themedy'); ?></option>
        <option style="padding-right:10px;" value="slide" <?php selected('slide', themedy_get_option('slider_effect')); ?>><?php _e("Slide", 'themedy'); ?></option>
	</select>
    <br/><small><strong><?php _e("Slide effect will not work at browser widths under ~1000px", 'themedy'); ?></strong></small></p>
    <p><?php _e("Transition Easing (set to slide above):", 'themedy'); ?>
	<select name="<?php echo CLIPCART_SETTINGS_FIELD; ?>[slider_easing]">
    	<option style="padding-right:10px;" value="easeInOutCubic" <?php selected('easeInOutCubic', themedy_get_option('slider_easing')); ?>><?php _e("Cubic", 'themedy'); ?></option>
        <option style="padding-right:10px;" value="easeInOutBounce" <?php selected('easeInOutBounce', themedy_get_option('slider_easing')); ?>><?php _e("Bounce", 'themedy'); ?></option>
        <option style="padding-right:10px;" value="easeInOutElastic" <?php selected('easeInOutElastic', themedy_get_option('slider_easing')); ?>><?php _e("Elastic", 'themedy'); ?></option>
	</select></p>	
    <p><?php _e("Enter Slide Effect Speed (ms):", 'themedy'); ?>
	<input type="text" name="<?php echo CLIPCART_SETTINGS_FIELD; ?>[slider_speed]" value="<?php echo esc_attr( themedy_get_option('slider_speed') ); ?>" size="15" /></p>
    <p><?php _e("Enter Pause (ms):", 'themedy'); ?>
	<input type="text" name="<?php echo CLIPCART_SETTINGS_FIELD; ?>[slider_pause]" value="<?php echo esc_attr( themedy_get_option('slider_pause') ); ?>" size="15" /><br/>
     <small><strong><?php _e("Enter value 0 to remove autoplay", 'themedy'); ?></strong></small></p>
    <p><?php _e("Amount of slides:", 'themedy'); ?>
	<input type="text" name="<?php echo CLIPCART_SETTINGS_FIELD; ?>[slider_limit]" value="<?php echo esc_attr( themedy_get_option('slider_limit') ); ?>" size="5" /></p>
<?php
}

function themedy_theme_settings_homepage_products() { ?>
	<p><input type="checkbox" name="<?php echo CLIPCART_SETTINGS_FIELD; ?>[homepage_products]" id="<?php echo CLIPCART_SETTINGS_FIELD; ?>[homepage_products]" value="1" <?php checked(1, themedy_get_option('homepage_products')); ?> /> <label for="<?php echo CLIPCART_SETTINGS_FIELD; ?>[homepage_products]"><?php _e("Include The Products List on Homepage?", 'themedy'); ?></label>
	</p>
	<p><?php _e("Enter Product Category ID's to Include:", 'themedy'); ?><br />
	<input type="text" name="<?php echo CLIPCART_SETTINGS_FIELD; ?>[homepage_products_categories]" value="<?php echo esc_attr( themedy_get_option('homepage_products_categories') ); ?>" size="40" />
    <br/><small><strong><?php _e("Comma separated - 1,2,3 for example, <a href=\"http://themedy.com/usermanual/#category_id\">read here for more info.</a>", 'themedy'); ?></strong></small></p>
    <hr class="div" />
	<p><?php _e("Enter Title:", 'themedy'); ?><br />
	<input type="text" name="<?php echo CLIPCART_SETTINGS_FIELD; ?>[homepage_products_title]" value="<?php echo esc_attr( themedy_get_option('homepage_products_title') ); ?>" size="40" /></p>
    <p><?php _e("Amount of products:", 'themedy'); ?>
	<input type="text" name="<?php echo CLIPCART_SETTINGS_FIELD; ?>[homepage_products_limit]" value="<?php echo esc_attr( themedy_get_option('homepage_products_limit') ); ?>" size="5" /></p>
<?php
}

function themedy_theme_settings_appearance() { ?>
	<p><?php _e("Select a Theme Style:", 'themedy'); ?>
    <select name="<?php echo CLIPCART_SETTINGS_FIELD; ?>[style]">
		<option style="padding-right:10px;" value="default" <?php selected('default', themedy_get_option('style')); ?>><?php _e("Default", 'themedy'); ?></option>
        <option style="padding-right:10px;" value="red" <?php selected('red', themedy_get_option('style')); ?>><?php _e("Red", 'themedy'); ?></option>
        <option style="padding-right:10px;" value="green" <?php selected('green', themedy_get_option('style')); ?>><?php _e("Green", 'themedy'); ?></option>
        <option style="padding-right:10px;" value="pink" <?php selected('pink', themedy_get_option('style')); ?>><?php _e("Pink", 'themedy'); ?></option>
        <option style="padding-right:10px;" value="blue" <?php selected('blue', themedy_get_option('style')); ?>><?php _e("Blue", 'themedy'); ?></option>
        <option style="padding-right:10px;" value="default-page_width" <?php selected('default-page_width', themedy_get_option('style')); ?>><?php _e("Default (Page Width)", 'themedy'); ?></option>
        <option style="padding-right:10px;" value="red-page_width" <?php selected('red-page_width', themedy_get_option('style')); ?>><?php _e("Red (Page Width)", 'themedy'); ?></option>
        <option style="padding-right:10px;" value="green-page_width" <?php selected('green-page_width', themedy_get_option('style')); ?>><?php _e("Green (Page Width)", 'themedy'); ?></option>
        <option style="padding-right:10px;" value="pink-page_width" <?php selected('pink-page_width', themedy_get_option('style')); ?>><?php _e("Pink (Page Width)", 'themedy'); ?></option>
        <option style="padding-right:10px;" value="blue-page_width" <?php selected('blue-page_width', themedy_get_option('style')); ?>><?php _e("Blue (Page Width)", 'themedy'); ?></option>
	</select></p>	
	<hr class="div" />
    <p><input type="checkbox" name="<?php echo CLIPCART_SETTINGS_FIELD; ?>[custom]" id="<?php echo CLIPCART_SETTINGS_FIELD; ?>[custom]" value="1" <?php checked(1, themedy_get_option('custom')); ?> /> <label for="<?php echo CLIPCART_SETTINGS_FIELD; ?>[custom]"><?php _e("Use the custom.css stylesheet?", 'themedy'); ?></label>
	</p>
<?php
}

function themedy_theme_settings_general() { ?>
	<p><?php _e("Homepage Breadcrumb Replacement Text:", 'themedy'); ?><br />
	<input type="text" name="<?php echo CLIPCART_SETTINGS_FIELD; ?>[homepage_breadcrumb_text]" value="<?php echo esc_attr( themedy_get_option('homepage_breadcrumb_text') ); ?>" size="62" /></p>
    <p><?php _e("Product Currency Sign:", 'themedy'); ?>
	<input type="text" name="<?php echo CLIPCART_SETTINGS_FIELD; ?>[currency_sign]" value="<?php echo esc_attr( themedy_get_option('currency_sign') ); ?>" size="3" />
    <br/><small><strong><?php _e("eCommerce plugin may overwrite this value", 'themedy'); ?></strong></small></p>
    <p><?php _e("Product pages show at most:", 'themedy'); ?>
	<input type="text" name="<?php echo CLIPCART_SETTINGS_FIELD; ?>[product_limit]" value="<?php echo esc_attr( themedy_get_option('product_limit') ); ?>" size="3" /> products</p>
    <p><input type="checkbox" name="<?php echo CLIPCART_SETTINGS_FIELD; ?>[product_gallery]" id="<?php echo CLIPCART_SETTINGS_FIELD; ?>[product_gallery]" value="1" <?php checked(1, themedy_get_option('product_gallery')); ?> /> <label for="<?php echo CLIPCART_SETTINGS_FIELD; ?>[product_gallery]"><?php _e("Enable images gallery on product pages?", 'themedy'); ?></label></p>
    <p><input type="checkbox" name="<?php echo CLIPCART_SETTINGS_FIELD; ?>[mobile_menu]" id="<?php echo CLIPCART_SETTINGS_FIELD; ?>[mobile_menu]" value="1" <?php checked(1, themedy_get_option('mobile_menu')); ?> /> <label for="<?php echo CLIPCART_SETTINGS_FIELD; ?>[mobile_menu]"><?php _e("Enable the jQuery mobile menu?", 'themedy'); ?></label></p>

<?php
}

function themedy_theme_settings_footer() { ?>
	<p><input type="checkbox" name="<?php echo CLIPCART_SETTINGS_FIELD; ?>[footer]" id="<?php echo CLIPCART_SETTINGS_FIELD; ?>[footer]" value="1" <?php checked(1, themedy_get_option('footer')); ?> /> <label for="<?php echo CLIPCART_SETTINGS_FIELD; ?>[footer]"><?php _e("Use custom footer text?", 'themedy'); ?></label>
	</p>
	<p><?php _e('Footer text', 'themedy'); ?>:<br />
	<textarea name="<?php echo CLIPCART_SETTINGS_FIELD; ?>[footer_text]" rows="5" cols="42"><?php echo htmlspecialchars( themedy_get_option('footer_text') ); ?></textarea></p>
	
<?php
}