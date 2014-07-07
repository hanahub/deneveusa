<?php
remove_action( 'genesis_site_title', 'genesis_seo_site_title' );
function be_customize_site_title($title, $inside, $wrap) {
	$custom = 'test';
	return $custom;
}
add_filter('genesis_seo_title','be_customize_site_title', 10, 3);


