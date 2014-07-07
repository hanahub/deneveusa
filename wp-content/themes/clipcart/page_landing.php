<?php 
/**
 * Template Name: Landing Page
 * Version: 1.0
 */

wp_enqueue_style('themedy-landingpage-style', CHILD_THEME_LIB_URL.'/css/landingpage.css','',1,'screen');
wp_enqueue_style('themedy-shortcodes-style', CHILD_THEME_LIB_URL.'/css/shortcodes.css','',1,'screen');
wp_deregister_style('themedy-child-theme-style'); 	

$post_obj = $wp_query->get_queried_object();
$post_name = $post_obj->post_name;

// Genesis
do_action( 'genesis_doctype' );
do_action( 'genesis_title' );

// Thesis
if (PARENT_THEME_NAME == "Thesis") { 
	$head = new thesis_head;
	$head->title();
	$head->meta();
	$head->links();
	
	echo apply_filters('thesis_doctype', '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">') . "\n"; 
	?><html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>> <?php
	echo "<head " . apply_filters('thesis_head_profile', 'profile="http://gmpg.org/xfn/11"') . ">\n"; #filter
	echo '<meta http-equiv="Content-Type" content="' . get_bloginfo('html_type') . '; charset=' . get_bloginfo('charset') . '" />' . "\n"; #wp
	$head->output();
} 
?>
<?php wp_head(); ?>
    </head>
 
    <body id="<?php echo $post_name; ?>" class="custom">
        <div id="wrap">
			<?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <div id="content" class="hfeed">
                    <div class="post-<?php the_ID(); ?> type-landingpage hentry">
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                        <div class="entry-content">
                            <?php the_content(); ?>
                            <?php edit_post_link('Edit This Page', '<p class="edit-this"><strong>ADMIN:</strong> ', ' &mdash; <a href="http://themedy.com/recommends/premise/">Get More Features With <strong>Premise</strong></a></p>'); ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </body>
</html>