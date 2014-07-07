<?php
/*----------------------------------------------------------

Description: 	Slides functionality for Themedy themes

----------------------------------------------------------*/

// Slides Post Type
add_action('init','themedy_create_slides_init');
function themedy_create_slides_init()  {
	$labels = array
	(
		'name' => _x('Slides', 'post type general name', 'themedy'),
		'singular_name' => _x('slide', 'post type singular name', 'themedy'),
		'add_new' => _x('Add New', 'Slide', 'themedy'),
		'add_new_item' => __('Add New Slide', 'themedy'),
		'edit_item' => __('Edit Slide', 'themedy'),
		'new_item' => __('New Slide', 'themedy'),
		'view_item' => __('View Slides', 'themedy'),
		'search_items' => __('Search Slides', 'themedy'),
		'not_found' =>  __('No Slides found', 'themedy'),
		'not_found_in_trash' => __('No Slides found in Trash', 'themedy'), 
		'parent_item_colon' => ''
	);
	$support = array
	(
		'title',
		'editor',
		'author',
		'thumbnail',
		'custom-fields',
		'revisions'
	);
	$args = array
	(
		'labels' => $labels,
		'public' => FALSE,
		'show_ui' => TRUE,
		'capability_type' => 'page',
		'hierarchical' => FALSE,
		'query_var' => FALSE,
		'supports' => $support,
		'menu_position' => 6
	); 
	register_post_type('slide',$args);
}