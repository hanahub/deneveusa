<?php
/*----------------------------------------------------------

Description: 	eCommerce functionality for Themedy themes

----------------------------------------------------------*/

// New Image Sizes
add_image_size('Product Thumb', 190, 190, TRUE);
add_image_size('Gallery Thumb', 96, 96, TRUE);

// Check For Shop Plugins
function themedy_active_plugin(){
	$active_plugins = get_option('active_plugins');
	$plugin_name = '';

	if ( in_array( 'shopp/Shopp.php',$active_plugins ) || in_array( 'Shopp.php',$active_plugins ) ) $plugin_name = 'shopp';
	elseif ( in_array( 'woocommerce/woocommerce.php',$active_plugins ) ) $plugin_name = 'woocommerce';
	elseif ( in_array( 'wp-e-commerce/wp-shopping-cart.php',$active_plugins ) ) $plugin_name = 'wp_ecommerce';
	elseif ( in_array( 'eshop/eshop.php',$active_plugins ) || in_array( 'eshop.php',$active_plugins ) ) $plugin_name = 'eshop';
	elseif ( in_array( 'cart66-lite/cart66.php',$active_plugins ) || in_array( 'cart66/cart66.php',$active_plugins ) || in_array( 'cart66-pro/cart66.php',$active_plugins ) )	$plugin_name = 'cart66';
	elseif ( in_array( 'wordpress-simple-paypal-shopping-cart/wp_shopping_cart.php',$active_plugins ) ) $plugin_name = 'wp_simple_paypal_sc';

	return ( $plugin_name <> '' ) ? $plugin_name : false;
}
global $themedy_active_plugin_name;
$themedy_active_plugin_name = themedy_active_plugin();

// Currency Symbol
function themedy_get_currency_sign() {
	global $themedy_active_plugin_name;

	if ($themedy_active_plugin_name == 'cart66') {
		$currency_sign = defined('CART66_CURRENCY_SYMBOL') ? CART66_CURRENCY_SYMBOL : CURRENCY_SYMBOL;
	}
	elseif ($themedy_active_plugin_name == 'woocommerce') {
		$currency_sign = get_woocommerce_currency_symbol();
	}
	else {
		$currency_sign = themedy_get_option('currency_sign');
	}

	return $currency_sign;
}

// Displayed Prices
function themedy_get_price() {
	global $post, $themedy_active_plugin_name, $wpdb;
	$price = 0;
	if ($themedy_active_plugin_name == 'cart66') {
		$cart66_tablename = Cart66Common::getTableName('products');
		$results = $wpdb->get_results($wpdb->prepare("SELECT price FROM $cart66_tablename WHERE item_number = %s", get_post_meta($post->ID,'cart66_product_id',true)));
		if ( $results ) $price = $results[0]->price;
	}
	else {
		$price = get_post_meta($post->ID, 'product_price', true);
		if (empty($price)) { $price = 0; }
	}
	return $price;
}

// Products Post Type
add_action('init','themedy_create_product_init');
function themedy_create_product_init()  {
	$labels = array
	(
		'name' => _x(PRODUCTS_LABEL, 'post type general name'),
		'singular_name' => _x(PRODUCT_LABEL, 'post type singular name'),
		'add_new' => _x('Add New', PRODUCT_LABEL),
		'add_new_item' => __('Add New '.PRODUCT_LABEL),
		'edit_item' => __('Edit '.PRODUCT_LABEL),
		'new_item' => __('New '.PRODUCT_LABEL),
		'view_item' => __('View '.PRODUCT_LABEL),
		'search_items' => __('Search '.PRODUCTS_LABEL),
		'not_found' =>  __('No '.PRODUCTS_LABEL.' found'),
		'not_found_in_trash' => __('No '.PRODUCTS_LABEL.' found in Trash'),
		'parent_item_colon' => ''
	);
	$support = array
	(
		'title',
		'editor',
		'author',
		'thumbnail',
		'custom-fields',
		'comments',
		'genesis-seo',
		'genesis-layouts',
		'revisions',
		'excerpt'
	);
	$args = array
	(
		'labels' => $labels,
		'public' => TRUE,
		'has_archive' => TRUE,
		'_builtin' => FALSE,
		'rewrite' => array('slug'=>'','with_front'=>false),
		'capability_type' => 'post',
		'hierarchical' => FALSE,
		'query_var' => true,
		'supports' => $support,
		'taxonomies' => array(PRODUCTS_CATEGORY_NAME),
		'show_in_nav_menus' => FALSE,
		'menu_position' => 5
	);
	register_post_type(PRODUCTS_NAME,$args);

	register_taxonomy(
        PRODUCTS_CATEGORY_NAME,
        PRODUCTS_NAME,
        array(
            'hierarchical' => TRUE,
            'label' => 'Categories',
            'query_var' => TRUE,
            'rewrite' => array('slug'=>'/'.PRODUCTS_CATEGORY_NAME,'with_front'=>false),
        )
    );
}

// Flush rewrite rules for custom post types.
add_action( 'load-themes.php', 'themedy_flush_rewrite_rules' );

// Flush rewrite rules.
function themedy_flush_rewrite_rules() {
	global $pagenow, $wp_rewrite;

	if ( 'themes.php' == $pagenow && isset( $_GET['activated'] ) )
		$wp_rewrite->flush_rules();
}

/**
* Filter to include the Cart66 dialog box on additional screen ids
*
* Return an array of screen ids.
* Pages, Posts, and the Dashboard are added automatically
*
* @return array
*/
function myCustomPopups(){
  return array(PRODUCTS_NAME);
}
add_filter('cart66_add_popup_screens', 'myCustomPopups');

// Add Product Post Type Template
add_filter( 'template_include', 'themedy_template_include' );
function themedy_template_include( $template ) {
    if ( get_query_var('post_type') == PRODUCTS_NAME ) { // Product Single
        if ( is_single() ) {
            if ( $single = locate_template( array( 'page_product_single.php') ) )
                return $single;
        } elseif (is_archive()) {
			if ( $archive = locate_template( array( 'page_products.php') ) )
				return $archive;
		}
    }
	elseif ( is_tax(PRODUCTS_CATEGORY_NAME) ) { // Product Categories
		return locate_template( array(
			'page_products.php',
			'index.php'
		));
    }
    return $template;
}

// Product Archives Navigation Fix
add_filter('query_string', 'themedy_tax_change');
function themedy_tax_change($query_string) {
	if (stristr($query_string, PRODUCTS_CATEGORY_NAME.'=') or stristr($query_string, 'post_type='.PRODUCTS_NAME) ) {
		$query_string = $query_string.'&posts_per_page='.themedy_get_option('product_limit');
	}
	return $query_string;
}

// Add Custom Columns to Products
add_filter( 'manage_edit-products_columns', 'themedy_edit_products_columns' ) ;
function themedy_edit_products_columns( $columns ) {

	global $themedy_active_plugin_name;
	if ($themedy_active_plugin_name == 'cart66') {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Product' ),
			'author' => __( 'Author' ),
			'product_categories' => __( 'Categories' ),
			'cart66_id' => __( 'Cart66 ID' ),
			'date' => __( 'Date' )
		);
	} else {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Product' ),
			'author' => __( 'Author' ),
			'product_categories' => __( 'Categories' ),
			'date' => __( 'Date' )
		);
	}

	return $columns;
}

add_action( 'manage_products_posts_custom_column', 'themedy_manage_product_columns', 10, 2 );
function themedy_manage_product_columns( $column, $post_id ) {
	global $post;
	switch( $column ) {

		case 'product_categories' :
			$terms = get_the_terms( $post_id, PRODUCTS_CATEGORY_NAME );
			if ( !empty( $terms ) ) {
				$out = array();
				foreach ( $terms as $term ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => $post->post_type, PRODUCTS_CATEGORY_NAME => $term->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, PRODUCTS_CATEGORY_NAME, 'display' ) )
					);
				}
				echo join( ', ', $out );
			}
			else {
				_e( 'No Categories' );
			}
			break;

		case 'cart66_id' :
			$cart66_id = get_post_meta( $post_id, 'cart66_product_id', true );
			echo $cart66_id;
			break;

		default :
			break;
	}
}

add_filter( 'manage_edit-products_sortable_columns', 'themedy_product_sortable_columns' );
function themedy_product_sortable_columns( $columns ) {
	$columns['cart66_id'] = 'cart66_id';

	return $columns;
}

add_action( 'load-edit.php', 'themedy_edit_products_load' );
function themedy_edit_products_load() {
	add_filter( 'request', 'themedy_sort_products' );
}

function themedy_sort_products( $vars ) {
	if ( isset( $vars['post_type'] ) && 'products' == $vars['post_type'] ) {
		if ( isset( $vars['orderby'] ) && 'cart66_id' == $vars['orderby'] ) {
			$vars = array_merge(
				$vars,
				array(

					'meta_key' => 'cart66_product_id',
					'orderby' => 'meta_value_num'

				)
			);
		}
	}

	return $vars;
}

// Add Meta Boxes
 add_action('admin_menu', 'add_themedy_options_meta_box');
function add_themedy_options_meta_box() {
	add_meta_box( 'themedy_options', 'Themedy Options', 'themedy_options', PRODUCTS_NAME, 'side', 'core' );
}
function themedy_options() {
	global $post;
	echo '<input type="hidden" name="themedy_options_noncename" id="themedy_options_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	$cart66_id = get_post_meta($post->ID, 'cart66_product_id', true);
	$product_price = get_post_meta($post->ID, 'product_price', true);

	global $themedy_active_plugin_name;
	if ($themedy_active_plugin_name == 'cart66') {
		echo '<p><strong>Cart66 Product Number: </strong>';
		echo '<input style="" size="10" id="cart66_product_id" name="cart66_product_id" value="' . $cart66_id . '">';
		echo '<br /><small>Insert Cart66 Product Number here. ie: <code>0001</code></small></p>';
	} else {
		echo '<p><strong>Price: </strong>';
		echo '<input style="" size="10" id="product_price" name="product_price" value="' . $product_price . '">';
		echo '<br /><small><a href="http://themedy.com/recommends/Cart66/">Cart66</a> plugin is not active- enter a custom price above</small></p>';
	}
}
function themedy_save_postdata( $post_id, $post ) {
  if ( !wp_verify_nonce( $_POST['themedy_options_noncename'], plugin_basename(__FILE__) )) {
	return $post_id;
  }
  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
	return $post_id;
  if ( 'page' == $_POST['post_type'] ) {
	if ( !current_user_can( 'edit_page', $post_id ) )
	  return $post_id;
  } else {
	if ( !current_user_can( 'edit_post', $post_id ) )
	  return $post_id;
  }
  $cart66_product_id = $_POST['cart66_product_id'];
  $product_price = $_POST['product_price'];

	global $themedy_active_plugin_name;
   if ($themedy_active_plugin_name == 'cart66') {
   		add_post_meta($post_id, 'cart66_product_id', $cart66_product_id, true) or update_post_meta($post_id, 'cart66_product_id', $cart66_product_id);
   }
   else {
   		add_post_meta($post_id, 'product_price', $product_price, true) or update_post_meta($post_id, 'product_price', $product_price);
   }
}

add_action('save_post', 'themedy_save_postdata', 1, 2); // save the custom fields

// Add Thesis Meta Boxes
if (PARENT_THEME_NAME == "Thesis") {
	function custom_thesis_meta_boxes() {
		$post_options = new thesis_post_options;
		$post_options->meta_boxes();
		foreach ($post_options->meta_boxes as $meta_name => $meta_box) {
			add_meta_box($meta_box['id'], $meta_box['title'], array('thesis_post_options', 'output_' . $meta_name . '_box'), 'product', 'normal', 'high');
		}
		add_action('save_post', array('thesis_post_options', 'save_meta'));
	}
	add_action('admin_menu','custom_thesis_meta_boxes');
}


// Add custom post types counts to dashboard
add_action( 'right_now_content_table_end', 'themedy_add_counts_to_dashboard' );
function themedy_add_counts_to_dashboard() {

    // Custom post types counts
    $post_types = get_post_types( array( '_builtin' => false ), 'objects' );
    foreach ( $post_types as $post_type ) {
        $num_posts = wp_count_posts( $post_type->name );
        $num = number_format_i18n( $num_posts->publish );
        $text = _n( $post_type->labels->singular_name, $post_type->labels->name, $num_posts->publish );
        if ( current_user_can( 'edit_posts' ) ) {
            $num = '<a href="edit.php?post_type=' . $post_type->name . '">' . $num . '</a>';
            $text = '<a href="edit.php?post_type=' . $post_type->name . '">' . $text . '</a>';
        }
        echo '<td class="first b b-' . $post_type->name . 's">' . $num . '</td>';
        echo '<td class="t ' . $post_type->name . 's">' . $text . '</td>';
        echo '</tr>';

        if ( $num_posts->pending > 0 ) {
            $num = number_format_i18n( $num_posts->pending );
            $text = _n( $post_type->labels->singular_name . ' pending', $post_type->labels->name . ' pending', $num_posts->pending );
            if ( current_user_can( 'edit_posts' ) ) {
                $num = '<a href="edit.php?post_status=pending&post_type=' . $post_type->name . '">' . $num . '</a>';
                $text = '<a href="edit.php?post_status=pending&post_type=' . $post_type->name . '">' . $text . '</a>';
            }
            echo '<td class="first b b-' . $post_type->name . 's">' . $num . '</td>';
            echo '<td class="t ' . $post_type->name . 's">' . $text . '</td>';
            echo '</tr>';
        }
    }
}

// WooCommerce Fixes
if (PARENT_THEME_NAME == "Thesis") {
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar' );
	add_action( 'woocommerce_sidebar', 'themedy_get_sidebar' );
	add_action ('woocommerce_before_main_content', 'themedy_format_woo_text_start');
	add_action ('woocommerce_after_main_content', 'themedy_format_woo_text_end');
}
function themedy_get_sidebar() {
	echo thesis_sidebars();
}
function themedy_format_woo_text_start() {
	echo '<div class="format_text">';
}
function themedy_format_woo_text_end() {
	echo '</div>';
}

// Hide Themedy Custom Products if Woo Enabled
add_action( 'admin_menu', 'themedy_remove_products_menu' );
function themedy_remove_products_menu() {
	global $themedy_active_plugin_name;
	if ($themedy_active_plugin_name == 'woocommerce') {
    	remove_menu_page( 'edit.php?post_type=products' );
	}
}