<?php
// Add post class to every product item (for default styling)
add_filter('post_class', 'product_post_class');
function product_post_class( $classes ) {
    $classes[] = 'post product-single';
    return $classes;
}

// Load scripts for gallery (if we should)
add_action('get_header', 'themedy_load_product_scripts');
function themedy_load_product_scripts() {
	if (themedy_get_option('product_gallery')) {
		wp_enqueue_script('slide_easing', CHILD_URL.'/lib/js/jquery.easing.js', array('jquery'), '1.3', TRUE);
    	wp_enqueue_script('fancyboxjs', CHILD_URL.'/lib/js/fancybox/jquery.fancybox.pack.js', array('jquery'), '1.3.4', TRUE);
		wp_enqueue_style('fancyboxcss', CHILD_URL.'/lib/js/fancybox/jquery.fancybox.css',$deps,'1.3.4','screen');
	}
}

// Add gallery options (if we should)
if (themedy_get_option('product_gallery')) { add_action('genesis_after', 'themedy_gallery_options'); }
function themedy_gallery_options() { 
?>
	<script>
		jQuery(function(){
			jQuery("a[rel=single-gallery]").fancybox({
				'transitionIn'		: 'fade',
				'transitionOut'		: 'fade',
				'titlePosition' 	: 'over',
				'titlePosition'  	: 'inside'
			});
		});
	</script>
<?php
}

// New Sidebars
remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
remove_action( 'genesis_sidebar_alt', 'genesis_do_sidebar_alt' );
add_action( 'genesis_sidebar', 'themedy_do_product_sidebar' );
add_action( 'genesis_sidebar_alt', 'themedy_do_product_sidebar_alt' );

// Product Images
if (themedy_get_option('product_gallery')) { add_action('genesis_after_post_content', 'themedy_product_images'); }
function themedy_product_images() { ?>
	<?php 
	global $post;
	$images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'numberposts' => 999, 'order'=> 'ASC', 'orderby' => 'menu_order' ) ); 
	if ( $images ) { ?>
	<div class="product_images">
    	<div class="container">
			<?php
            foreach ( $images as $image ){
                $gallery_image_info = wp_get_attachment_image_src( $image->ID, 'full' );
                $gallery_image = $gallery_image_info[0];
                
                $gallery_thumb_info = wp_get_attachment_image_src( $image->ID, 'Gallery Thumb' );
                $gallery_thumb = $gallery_thumb_info[0];
                
                $gallery_image_alt = get_post_meta($image->ID, '_wp_attachment_image_alt', true) ? trim(strip_tags( get_post_meta($image->ID, '_wp_attachment_image_alt', true) )) : trim(strip_tags( $image->post_title ));
                $caption = $image->post_excerpt;

                if ($caption != "hide") {
                echo '<a href="'.$gallery_image.'" title="'.$gallery_image_alt.'" class="fancybox" rel="single-gallery"><img src="' . $gallery_thumb . '" /></a>';}
            }
			?>
        </div>
    </div>
    <?php } ?>
<?php } 

// Remove default content for this Page Template
remove_action('genesis_after_post_content', 'genesis_post_meta');
remove_action('genesis_before_post_content', 'genesis_post_info');
remove_action( 'genesis_after_post', 'genesis_do_author_box_single' );

genesis();