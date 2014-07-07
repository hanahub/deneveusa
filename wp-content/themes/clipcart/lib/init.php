<?php

// Define Constants
define('CHILD_THEME_NAME', 'Clip Cart');
define('CHILD_THEME_VERSION', '1.3.4');
define('CHILD_THEME_RELEASE_DATE', date_i18n('F j, Y', '1320645600'));
define('CHILD_THEME_CSS_DEPENDENCY', 'clip-cart');

define('PARENT_DIR', TEMPLATEPATH);
define('CHILD_DIR', STYLESHEETPATH);

define('CHILD_THEME_LIB_DIR', CHILD_DIR.'/lib');
define('CHILD_THEME_LIB_URL', CHILD_URL.'/lib');

define('STYLES_URL', CHILD_URL.'/styles');

// Add Options
require_once(CHILD_THEME_LIB_DIR.'/functions/themedy-options.php');

// Add Settings pages
require_once(CHILD_THEME_LIB_DIR.'/admin/themedy-settings.php'); // Themedy
require_once(CHILD_THEME_LIB_DIR.'/admin/clipcart-settings.php'); // Childtheme

// Add eCommerce Functionality
/*  Note if you change the products variables below and have already created some products they will disappear.
	Use a plugin like http://wordpress.org/extend/plugins/convert-post-types/ to get recover them.
	If you get 404 errors after changing these go to Settings -> Permalinks and resave. */
define('PRODUCTS_NAME', 'products'); // Changes the URL
define('PRODUCT_LABEL', 'Product'); // Shown on admin screen
define('PRODUCTS_LABEL', 'Products'); // Label for multiple products
define('PRODUCTS_CATEGORY_NAME', 'product-category'); // Changes the Category URL
include(CHILD_THEME_LIB_DIR.'/functions/themedy-products.php');

// Add Slides Functionality
include(CHILD_THEME_LIB_DIR.'/functions/themedy-slides.php');

// Add Widgets
include(CHILD_THEME_LIB_DIR.'/widgets/widget-ad120x60.php');
include(CHILD_THEME_LIB_DIR.'/widgets/widget-ad120x240.php');
include(CHILD_THEME_LIB_DIR.'/widgets/widget-ad125.php');
include(CHILD_THEME_LIB_DIR.'/widgets/widget-ad300x250.php');
include(CHILD_THEME_LIB_DIR.'/widgets/widget-ad300x600.php');
include(CHILD_THEME_LIB_DIR.'/widgets/widget-ad468x60.php');
include(CHILD_THEME_LIB_DIR.'/widgets/widget-flickr.php');
include(CHILD_THEME_LIB_DIR.'/widgets/widget-video.php');

// Add Plugins
require_once(CHILD_THEME_LIB_DIR.'/plugins/plugins.php'); // TGM Framework https://github.com/thomasgriffin/TGM-Plugin-Activation

// Localization
load_theme_textdomain( 'themedy', CHILD_THEME_LIB_DIR . '/languages');