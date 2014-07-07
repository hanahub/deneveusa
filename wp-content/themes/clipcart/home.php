<?php
/** 
 * Template Name: Homepage
 */
 
// Force layout to content-sidebar
add_filter('genesis_pre_get_option_site_layout', 'themedy_home_layout');
function themedy_home_layout($layout) {
    $layout = 'full-width-content';
    return $layout;
}

// Load scripts for slider (if we should)
add_action('get_header', 'themedy_load_slider_scripts');
function themedy_load_slider_scripts() {
	if (themedy_get_option('slider')) {
		wp_enqueue_script('slide_easing', CHILD_THEME_LIB_URL.'/js/jquery.easing.min.js', array('jquery'), '1.3', TRUE);
    	wp_enqueue_script('slidesjs', CHILD_THEME_LIB_URL.'/js/jquery.slides.min.js', array('jquery'), '1.1.9', TRUE);
	}
}

// Add slider options (if we should)
if (themedy_get_option('slider')) { add_action('genesis_after', 'themedy_slider_options'); }
function themedy_slider_options() { 
?>
	<script type="text/javascript">
		jQuery(function(){
			jQuery('#slides').slides({				
				preload: true,
				preloadImage: '<?php echo CHILD_THEME_LIB_URL; ?>/js/loading.gif',
				effect: '<?php echo themedy_option('slider_effect'); ?>',
				play: <?php echo themedy_option('slider_pause'); ?>,
				pause: 2500,
				slideSpeed: <?php echo themedy_option('slider_speed'); ?>,
				generatePagination: false,
				hoverPause: true,
				slideEasing: '<?php echo themedy_option('slider_easing'); ?>',
				fadeEasing: '<?php echo themedy_option('slider_easing'); ?>',
				autoHeight: true
			});
		});
	</script>
<?php
}

// Slider Area Before Inner
add_action('genesis_after_header', 'slider_area');
function slider_area() { ?>
	<?php if (themedy_get_option('slider')) { ?>
        <div id="slider_area">
            <div class="wrap">
                <div id="slides">
                	<div class="slides_container">
                    <?php 
                    // Query our slides
                    query_posts(array('posts_per_page' => themedy_get_option('slider_limit'), 'post_type' => 'slide')); 
                    if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                        <div class="slide">
                            <div class="content">
                                <?php the_content(); ?>
                            </div>
                        </div>
                  	<?php endwhile; else: endif; wp_reset_query(); ?>
                    </div>
                    <a class="prev nav" href="#"><?php _e('Previous', 'themedy') ?></a>
                	<a class="next nav" href="#"><?php _e('Next', 'themedy') ?></a>
                </div>
            </div>
           
        <div class="clear"></div>
        </div>
    <?php } ?>
<?php }


// Rest of Genesis
get_header(); 
genesis_before_content_sidebar_wrap(); ?>

<div id="content-sidebar-wrap">
	<?php if (themedy_get_option('homepage_products')) { ?>
    <div id="featured">
        <h4><?php echo themedy_get_option('homepage_products_title'); ?></h4>
        <ul class="product_list">
            <?php 
			global $themedy_active_plugin_name;
            $terms = explode(',', themedy_get_option('homepage_products_categories'));
			
			if ($themedy_active_plugin_name == 'woocommerce' and themedy_get_option('homepage_products_categories')) {
                query_posts( array ('posts_per_page' => themedy_get_option('homepage_products_limit'), 'post_type' => 'product', 'tax_query' => array( array( 'taxonomy' => 'product_cat', 'field' => 'id', 'terms' => $terms, 'operator' => 'IN' ) ) ) );
			}
			elseif ($themedy_active_plugin_name == 'woocommerce') {
                query_posts( array ('posts_per_page' => themedy_get_option('homepage_products_limit'), 'post_type' => 'product' ) );
			}
            elseif (themedy_get_option('homepage_products_categories')) {
                query_posts( array ('posts_per_page' => themedy_get_option('homepage_products_limit'), 'post_type' => PRODUCTS_NAME, 'tax_query' => array( array( 'taxonomy' => PRODUCTS_CATEGORY_NAME, 'field' => 'id', 'terms' => $terms, 'operator' => 'IN' ) ) ) ); 
            }
            else {
                query_posts( array ('posts_per_page' => themedy_get_option('homepage_products_limit'), 'post_type' => PRODUCTS_NAME ) );
            }
            global $post;
            $loop_counter = 1;
            if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <li class="product <?php if ( $loop_counter == 4 ) { echo "product-last"; } ?>">
                    <div class="product_wrap">1111111111111111111111
                        <?php 
                        $title = get_the_title().' ';
                        $chars_title = strlen($title);
                        $title = substr($title,0,28);
                        $title = substr($title,0,strrpos($title,' '));
                        if ($chars_title > 28) { $title = $title."..."; } 
						
						$excerpt = get_the_excerpt();
						/*if (strlen($excerpt) > 150) {
						$excerpt = substr($excerpt,0,strpos($excerpt,' ',80)); } ;
						$excerpt = $excerpt.' ...';*/
                        $excerpt = excerpt_paragraph($excerpt, 80);
                        
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
                <?php if ( $loop_counter == 4 ) {
                    $loop_counter = 1;
                    echo '<li class="clear"></li>';
                } else {
                    $loop_counter++;
                } ?>
            <?php endwhile; else: endif; wp_reset_query(); ?>
        </ul>
    </div>
    <?php } ?>
    
	<?php genesis_before_content(); ?>
    <?php if ( is_active_sidebar('home-sidebar-1') or is_active_sidebar('home-sidebar-2') ) { ?>
	<div id="content" class="hfeed homepage">
		<?php if ( is_active_sidebar('home-sidebar-1') ) { ?>
            <div id="home_sidebar_1"<?php if ( !is_active_sidebar('home-sidebar-2') ) { echo ' class="fullwidth"'; } ?>>
                <?php dynamic_sidebar('Homepage Widget Area 1'); ?> 
            </div>
        <?php } ?>
        
       <?php if ( is_active_sidebar('home-sidebar-2') ) { ?>
            <div id="home_sidebar_2">
                <?php dynamic_sidebar('Homepage Widget Area 2'); ?> 
            </div>
       <?php } ?>
	</div>
    <?php } ?>

</div>

<?php
genesis_after_content_sidebar_wrap();
get_footer();