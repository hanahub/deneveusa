<?php
/**
 * Template Name: Products
 */
 
// Add .product-page class to content
add_filter('post_class', 'product_post_class');
function product_post_class( $classes ) {
    $classes[] = 'product-page';
    return $classes;
}

// New Sidebars
remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
remove_action( 'genesis_sidebar_alt', 'genesis_do_sidebar_alt' );
add_action( 'genesis_sidebar', 'themedy_do_product_sidebar' );
add_action( 'genesis_sidebar_alt', 'themedy_do_product_sidebar_alt' );

add_action('genesis_after_loop', 'themedy_do_product_loop');
function themedy_do_product_loop() { ?>	
	 <ul class="product_list">
		<?php 
		global $wp_query, $post, $themedy_active_plugin_name;;
		$paged = get_query_var('paged') ? get_query_var('paged') : 1;
		$tax = $wp_query->query_vars[PRODUCTS_CATEGORY_NAME];
		if ($themedy_active_plugin_name == 'woocommerce') {
			query_posts( array ('posts_per_page' => themedy_get_option('product_limit'), 'post_type' => 'product', 'paged' => $paged ) );
		} else {
        	query_posts( array ('posts_per_page' => themedy_get_option('product_limit'), 'post_type' => PRODUCTS_NAME, 'paged' => $paged, PRODUCTS_CATEGORY_NAME => $tax ) );
		}
        $loop_counter = 1;
        if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <li class="product <?php if ( $loop_counter == 3 ) { echo "product-last"; } ?>">
                <div class="product_wrap">
                    <?php 
                    $title = get_the_title().' ';
                    $chars_title = strlen($title);
                    $title = substr($title,0,28);
                    $title = substr($title,0,strrpos($title,' '));
                    if ($chars_title > 28) { $title = $title."..."; } 
                    
                    $excerpt = get_the_excerpt();
                    if (strlen($excerpt) > 150) {
                    $excerpt = substr($excerpt,0,strpos($excerpt,' ',80)); } ;
                    $excerpt = $excerpt.' ...';
                    
                    if (genesis_get_image()) {
                        $img = genesis_get_image( array( 'format' => 'html', 'size' => 'Product Thumb', 'attr' => array( 'title' => $post_title  ) ) );
						printf( '<div class="thumb-wrap"><a href="%s" class="product-thumb" title="%s">%s</a></div>', get_permalink(), the_title_attribute('echo=0'), $img ); 
                    } 
                    ?>
                    <h3 class="entry-title product-title"><a href="<?php the_permalink(); ?>" class="title"><?php echo $title; ?></a></h3>
                    <div class="product_content">
                        <?php echo apply_filters('the_excerpt',$excerpt); ?>
                    </div>
                    <div class="product_details">
                    	<a class="button" href="<?php the_permalink(); ?>">More Info</a>
                        <div class="price">
						<?php
						if ($themedy_active_plugin_name == 'woocommerce') {
							echo woocommerce_template_loop_price();
						}
						else {
							echo themedy_get_currency_sign(); echo themedy_get_price(); 
						}
						?>
                        </div>
                    </div>
                </div>
            </li>
            <?php if ( $loop_counter == 3 ) {
                $loop_counter = 1;
                echo '<li class="clear"></li>';
            } else {
                $loop_counter++;
            } ?>
        <?php endwhile; else: endif;  ?>
    </ul>
    <?php 
	genesis_posts_nav(); // Navigation
	wp_reset_query();    // Reset Query
	?>
<?php }

// Remove standard loop
remove_action('genesis_loop', 'genesis_do_loop');

genesis();