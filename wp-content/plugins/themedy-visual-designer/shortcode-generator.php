<?php
/*--------------------------------------------------------

Description: 	Code for various shortcodes available.

TABLE OF CONTENTS

Button
Info Boxes
Digg
Stumbleupon
Facebook Like
Google+
Twitter
Reddit
Quote
Styled List
Columns
Related Posts
Dividers
Drop Cap
Highlight
Abbreviation

--------------------------------------------------------*/

// Enable shortcodes in widget areas
add_filter( 'widget_text', 'do_shortcode' );

// Shortcode Styles
add_action( 'wp_enqueue_scripts', 'themedy_shortcodes_style' );
function themedy_shortcodes_style() {
	wp_enqueue_style('themedy-shortcodes-style', plugins_url( 'css/shortcodes.css' , __FILE__ ), array(), '1.1', 'screen');
}

// Replace WP autop formatting
if (!function_exists( "themedy_remove_wpautop")) {
	function themedy_remove_wpautop($content) { 
		$content = do_shortcode( shortcode_unautop( $content ) ); 
		$content = preg_replace( '#^<\/p>|^<br \/>|<p>$#', '', $content);
		return $content;
	}
}

/*--------------------------------------------------------
   Button [button]
--------------------------------------------------------*/

add_shortcode('button', 'themedy_button');
function themedy_button($atts, $content = null) {
	extract(shortcode_atts(array(
				"link" => "#",
				"color" => "silver",
				"size" => "normal",
				"style" => "",
				"window" => "no",
				"class" => ''
			), $atts));
	
	if ($window == 'new') 
		$window = 'target="_blank"';
	
	if ($class != '')
		$class = " {$class}"; 
		
	if ($style != '')
		$style = " th-{$style}"; 
	
	$output = "<a href='{$link}' class='th-button {$color} {$size}{$style}{$class}' {$window}><span>{$content}</span></a>";
		
	return $output;
}

/*--------------------------------------------------------
   Info Boxes [box]
--------------------------------------------------------*/
add_shortcode('box', 'themedy_box');
function themedy_box($atts, $content = null) {
	extract(shortcode_atts(array(
				"style" => "default",
				"class" => ''
			), $atts));
	
	if ($class != '')
		$class = " {$class}"; 
	
	$output = "<p class='th-box {$style}{$class}'>{$content}</p>";
		
	return $output;
}

/*--------------------------------------------------------
   Digg [digg]
--------------------------------------------------------*/
add_shortcode('digg', 'themedy_digg');
function themedy_digg($atts, $content = null) {
	extract(shortcode_atts(array(
				"float" => ""
			), $atts));
	
	if ($float != '')
		$float = " {$float}"; 
	
	$output = "<div class='th-social th-digg{$float}'>
	<script type='text/javascript'>
		(function() {
			var s = document.createElement('SCRIPT'), s1 = document.getElementsByTagName('SCRIPT')[0];
			s.type = 'text/javascript';
			s.async = true;
			s.src = 'http://widgets.digg.com/buttons.js';
			s1.parentNode.insertBefore(s, s1);
		})();
	</script>
	<a class='DiggThisButton DiggMedium'></a>
	</div>";
		
	return $output;
}

/*--------------------------------------------------------
   Stumbleupon [stumble]
--------------------------------------------------------*/
add_shortcode('stumble','themedy_stumble');
function themedy_stumble($atts, $content = null){
	extract(shortcode_atts(array(
				"float" => ""
			), $atts));
	
	if ($float != '')
		$float = " {$float}"; 
	
	$output = "<div class='th-social th-stumble{$float}'><script src='http://www.stumbleupon.com/hostedbadge.php?s=5' type='text/javascript'></script></div>";
	return $output;
}

/*--------------------------------------------------------
   Facebook Like [facebook]
--------------------------------------------------------*/
add_shortcode('facebook','themedy_facebook');
function themedy_facebook($atts, $content = null){
	extract(shortcode_atts(array(
				"float" => ""
			), $atts));
	
	if ($float != '')
		$float = " {$float}"; 
	
	$output = "<div class='th-social th-facebook{$float}'><iframe src=\"//www.facebook.com/plugins/like.php?href=http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "&amp;send=false&amp;layout=box_count&amp;width=50&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font=arial&amp;height=65\" scrolling=\"no\" frameborder=\"0\" style=\"border:none; overflow:hidden; width:50px; height:65px;\" allowTransparency=\"true\"></iframe></div>";
	return $output;
}

/*--------------------------------------------------------
   Twitter [twitter]
--------------------------------------------------------*/
add_shortcode('twitter','themedy_twitter');
function themedy_twitter($atts, $content = null){
	extract(shortcode_atts(array(
				"float" => ""
			), $atts));
	
	if ($float != '')
		$float = " {$float}"; 
	
	$output = "<div class='th-social th-twitter{$float}'><a href='http://twitter.com/share' class='twitter-share-button' data-count='vertical'>Tweet</a><script type='text/javascript' src='http://platform.twitter.com/widgets.js'></script></div>";
	return $output;
}

/*--------------------------------------------------------
   Reddit [reddit]
--------------------------------------------------------*/
add_shortcode('reddit','themedy_reddit');
function themedy_reddit($atts, $content = null){
	extract(shortcode_atts(array(
				"float" => ""
			), $atts));
	
	if ($float != '')
		$float = " {$float}"; 
	
	$output = "<div class='th-social th-reddit{$float}'><script type='text/javascript' src='http://www.reddit.com/static/button/button2.js'></script></div>";
	return $output;
}

/*--------------------------------------------------------
   Tweetmeme Retweet [tweetmeme] *****(NO LONGER AVAILABLE)
--------------------------------------------------------*/
add_shortcode('tweetmeme','themedy_tweetmeme');
function themedy_tweetmeme($atts, $content = null){
	
	$output = "";
	return $output;
}

/*-----------------------------------------------------------------------------------
   Google +1 Button - [google_plusone]
/*-----------------------------------------------------------------------------------*/

function themedy_shortcode_google_plusone ( $atts, $content = null ) {

	global $post;

	$defaults = array(
						'size' => '',
						'language' => '',
						'count' => '',
						'href' => '',
						'callback' => '',
						'float' => 'none', 
						'annotation' => 'none'
					);

	$atts = shortcode_atts( $defaults, $atts );

	extract( $atts );

	$params = array();

	$allowed_floats = array( 'left' => ' fl', 'right' => ' fr', 'none' => '' );
	if ( ! in_array( $float, array_keys( $allowed_floats ) ) ) { $float = 'none'; }
	
	if ( ! in_array( $annotation, array( 'bubble', 'inline', 'none' ) ) ) { $annotation = 'none'; } 

	// A friendly-looking array of supported languages, along with their codes.
	$supported_languages = array(
		'ar' => 'Arabic', 
		'bg' => 'Bulgarian', 
		'ca' => 'Catalan', 
		'zh-CN' => 'Chinese (Simplified)', 
		'zh-TW' => 'Chinese (Traditional)', 
		'hr' => 'Croatian', 
		'cs' => 'Czech', 
		'da' => 'Danish', 
		'nl' => 'Dutch', 
		'en-US' => 'English (US)', 
		'en-GB' => 'English (UK)', 
		'et' => 'Estonian', 
		'fil' => 'Filipino', 
		'fi' => 'Finnish', 
		'fr' => 'French', 
		'de' => 'German', 
		'el' => 'Greek', 
		'iw' => 'Hebrew', 
		'hi' => 'Hindi', 
		'hu' => 'Hungarian', 
		'id' => 'Indonesian', 
		'it' => 'Italian', 
		'ja' => 'Japanese', 
		'ko' => 'Korean', 
		'lv' => 'Latvian', 
		'lt' => 'Lithuanian', 
		'ms' => 'Malay', 
		'no' => 'Norwegian', 
		'fa' => 'Persian', 
		'pl' => 'Polish', 
		'pt-BR' => 'Portuguese (Brazil)', 
		'pt-PT' => 'Portuguese (Portugal)', 
		'ro' => 'Romanian', 
		'ru' => 'Russian', 
		'sr' => 'Serbian', 
		'sv' => 'Swedish', 
		'sk' => 'Slovak', 
		'sl' => 'Slovenian', 
		'es' => 'Spanish', 
		'es-419' => 'Spanish (Latin America)', 
		'th' => 'Thai', 
		'tr' => 'Turkish', 
		'uk' => 'Ukrainian', 
		'vi' => 'Vietnamese'
	);

	$output = '';
	$tag_atts = '';

	// Make sure we only have Google +1 attributes in our array, after parsing the "float" parameter.
	unset( $atts['float'] );

	if ( $atts['href'] == '' & isset( $post->ID ) ) {
		$atts['href'] = get_permalink( $post->ID );
	}

	foreach ( $atts as $k => $v ) {
		if ( ${$k} != '' ) {
			$tag_atts .= ' data-' . $k . '="' . ${$k} . '"';
		}
	}

	$output = '<div class="shortcode-google-plusone' . $allowed_floats[$float] . '"><div class="g-plusone" ' . $tag_atts . '></div></div><!--/.shortcode-google-plusone-->' . "\n";
	
	// Parameters to pass to Google PlusOne JavaScript.
	if ( in_array( $atts['language'] , array_values( $supported_languages ) ) ) {
		$language = '';
		
		foreach ( $supported_languages as $k => $v ) {
			if ( $v == $atts['language'] ) {
				$language = $k;
				break;
			}
		}
		
		$params = array( 'language' => $language );
	}

	// Enqueue the Google +1 button JavaScript from their API.
	// add_action( 'wp_footer', 'themedy_shortcode_google_plusone_js' );
	// add_action( 'themedy_shortcode_generator_preview_footer', 'themedy_shortcode_google_plusone_js' );
	themedy_shortcode_google_plusone_js( $params );

	return $output . "\n";

} // End themedy_shortcode_google_plusone()

add_shortcode( 'google_plusone', 'themedy_shortcode_google_plusone' );

/*-----------------------------------------------------------------------------------
  Load Javascript for Google +1 Button
/*-----------------------------------------------------------------------------------*/

function themedy_shortcode_google_plusone_js ( $params ) {
	echo '<script src="https://apis.google.com/js/plusone.js" type="text/javascript">' . "\n";
	if ( isset( $params['language'] ) && ( $params['language'] != '' ) ) {
		echo ' {lang: \'' . $params['language'] . '\'}' . "\n";
	}
	echo '</script>' . "\n";
	echo '<script type="text/javascript">gapi.plusone.go();</script>' . "\n";
} // End themedy_shortcode_google_plusone_js()

/*--------------------------------------------------------
  Quote [quote]
--------------------------------------------------------*/
add_shortcode('quote', 'themedy_quote');
function themedy_quote($atts, $content = null) {
	extract(shortcode_atts(array(
				"style" => "default",
				"float" => ''
			), $atts));
	
	if ($float != '')
		$float = " {$float}"; 
	
	$output = "<div class='th-quote{$float}'><p>{$content}</p></div>";
		
	return $output;
}

/*--------------------------------------------------------
   Styled List [list]
--------------------------------------------------------*/
add_shortcode('list', 'themedy_list');
function themedy_list($atts, $content = null) {
	extract(shortcode_atts(array(
				"style" => "check",
			), $atts));
	
	$output = '<div class="th-list th-list-'.$style.'">'.themedy_remove_wpautop($content).'</div>';
		
	return $output;
}

/*--------------------------------------------------------
   Columns
--------------------------------------------------------*/
/*

Description:

Columns are named with this convention Xcol_Y where X is the total number of columns and Y is 
the number of columns you want this column to span. Add _last behind the shortcode if it is the
last column.

*/

/* ============= Two Columns ============= */

function themedy_shortcode_twocol_one($atts, $content = null) {
   return '<div class="twocol-one">' . themedy_remove_wpautop($content) . '</div>';
}
add_shortcode( 'twocol_one', 'themedy_shortcode_twocol_one' );

function themedy_shortcode_twocol_one_last($atts, $content = null) {
   return '<div class="twocol-one last">' . themedy_remove_wpautop($content) . '</div><div class="clear"></div>';
}
add_shortcode( 'twocol_one_last', 'themedy_shortcode_twocol_one_last' );


/* ============= Three Columns ============= */

function themedy_shortcode_threecol_one($atts, $content = null) {
   return '<div class="threecol-one">' . themedy_remove_wpautop($content) . '</div>';
}
add_shortcode( 'threecol_one', 'themedy_shortcode_threecol_one' );

function themedy_shortcode_threecol_one_last($atts, $content = null) {
   return '<div class="threecol-one last">' . themedy_remove_wpautop($content) . '</div><div class="clear"></div>';
}
add_shortcode( 'threecol_one_last', 'themedy_shortcode_threecol_one_last' );

function themedy_shortcode_threecol_two($atts, $content = null) {
   return '<div class="threecol-two">' . themedy_remove_wpautop($content) . '</div>';
}
add_shortcode( 'threecol_two', 'themedy_shortcode_threecol_two' );

function themedy_shortcode_threecol_two_last($atts, $content = null) {
   return '<div class="threecol-two last">' . themedy_remove_wpautop($content) . '</div><div class="clear"></div>';
}
add_shortcode( 'threecol_two_last', 'themedy_shortcode_threecol_two_last' );

/* ============= Four Columns ============= */

function themedy_shortcode_fourcol_one($atts, $content = null) {
   return '<div class="fourcol-one">' . themedy_remove_wpautop($content) . '</div>';
}
add_shortcode( 'fourcol_one', 'themedy_shortcode_fourcol_one' );

function themedy_shortcode_fourcol_one_last($atts, $content = null) {
   return '<div class="fourcol-one last">' . themedy_remove_wpautop($content) . '</div><div class="clear"></div>';
}
add_shortcode( 'fourcol_one_last', 'themedy_shortcode_fourcol_one_last' );

function themedy_shortcode_fourcol_two($atts, $content = null) {
   return '<div class="fourcol-two">' . themedy_remove_wpautop($content) . '</div>';
}
add_shortcode( 'fourcol_two', 'themedy_shortcode_fourcol_two' );

function themedy_shortcode_fourcol_two_last($atts, $content = null) {
   return '<div class="fourcol-two last">' . themedy_remove_wpautop($content) . '</div><div class="clear"></div>';
}
add_shortcode( 'fourcol_two_last', 'themedy_shortcode_fourcol_two_last' );

function themedy_shortcode_fourcol_three($atts, $content = null) {
   return '<div class="fourcol-three">' . themedy_remove_wpautop($content) . '</div>';
}
add_shortcode( 'fourcol_three', 'themedy_shortcode_fourcol_three' );

function themedy_shortcode_fourcol_three_last($atts, $content = null) {
   return '<div class="fourcol-three last">' . themedy_remove_wpautop($content) . '</div><div class="clear"></div>';
}
add_shortcode( 'fourcol_three_last', 'themedy_shortcode_fourcol_three_last' );

/* ============= Five Columns ============= */

function themedy_shortcode_fivecol_one($atts, $content = null) {
   return '<div class="fivecol-one">' . themedy_remove_wpautop($content) . '</div>';
}
add_shortcode( 'fivecol_one', 'themedy_shortcode_fivecol_one' );

function themedy_shortcode_fivecol_one_last($atts, $content = null) {
   return '<div class="fivecol-one last">' . themedy_remove_wpautop($content) . '</div><div class="clear"></div>';
}
add_shortcode( 'fivecol_one_last', 'themedy_shortcode_fivecol_one_last' );

function themedy_shortcode_fivecol_two($atts, $content = null) {
   return '<div class="fivecol-two">' . themedy_remove_wpautop($content) . '</div>';
}
add_shortcode( 'fivecol_two', 'themedy_shortcode_fivecol_two' );

function themedy_shortcode_fivecol_two_last($atts, $content = null) {
   return '<div class="fivecol-two last">' . themedy_remove_wpautop($content) . '</div><div class="clear"></div>';
}
add_shortcode( 'fivecol_two_last', 'themedy_shortcode_fivecol_two_last' );

function themedy_shortcode_fivecol_three($atts, $content = null) {
   return '<div class="fivecol-three">' . themedy_remove_wpautop($content) . '</div>';
}
add_shortcode( 'fivecol_three', 'themedy_shortcode_fivecol_three' );

function themedy_shortcode_fivecol_three_last($atts, $content = null) {
   return '<div class="fivecol-three last">' . themedy_remove_wpautop($content) . '</div><div class="clear"></div>';
}
add_shortcode( 'fivecol_three_last', 'themedy_shortcode_fivecol_three_last' );

function themedy_shortcode_fivecol_four($atts, $content = null) {
   return '<div class="fivecol-four">' . themedy_remove_wpautop($content) . '</div>';
}
add_shortcode( 'fivecol_four', 'themedy_shortcode_fivecol_four' );

function themedy_shortcode_fivecol_four_last($atts, $content = null) {
   return '<div class="fivecol-four last">' . themedy_remove_wpautop($content) . '</div><div class="clear"></div>';
}
add_shortcode( 'fivecol_four_last', 'themedy_shortcode_fivecol_four_last' );


/* ============= Six Columns ============= */

function themedy_shortcode_sixcol_one($atts, $content = null) {
   return '<div class="sixcol-one">' . themedy_remove_wpautop($content) . '</div>';
}
add_shortcode( 'sixcol_one', 'themedy_shortcode_sixcol_one' );

function themedy_shortcode_sixcol_one_last($atts, $content = null) {
   return '<div class="sixcol-one last">' . themedy_remove_wpautop($content) . '</div><div class="clear"></div>';
}
add_shortcode( 'sixcol_one_last', 'themedy_shortcode_sixcol_one_last' );

function themedy_shortcode_sixcol_two($atts, $content = null) {
   return '<div class="sixcol-two">' . themedy_remove_wpautop($content) . '</div>';
}
add_shortcode( 'sixcol_two', 'themedy_shortcode_sixcol_two' );

function themedy_shortcode_sixcol_two_last($atts, $content = null) {
   return '<div class="sixcol-two last">' . themedy_remove_wpautop($content) . '</div><div class="clear"></div>';
}
add_shortcode( 'sixcol_two_last', 'themedy_shortcode_sixcol_two_last' );

function themedy_shortcode_sixcol_three($atts, $content = null) {
   return '<div class="sixcol-three">' . themedy_remove_wpautop($content) . '</div>';
}
add_shortcode( 'sixcol_three', 'themedy_shortcode_sixcol_three' );

function themedy_shortcode_sixcol_three_last($atts, $content = null) {
   return '<div class="sixcol-three last">' . themedy_remove_wpautop($content) . '</div><div class="clear"></div>';
}
add_shortcode( 'sixcol_three_last', 'themedy_shortcode_sixcol_three_last' );

function themedy_shortcode_sixcol_four($atts, $content = null) {
   return '<div class="sixcol-four">' . themedy_remove_wpautop($content) . '</div>';
}
add_shortcode( 'sixcol_four', 'themedy_shortcode_sixcol_four' );

function themedy_shortcode_sixcol_four_last($atts, $content = null) {
   return '<div class="sixcol-four last">' . themedy_remove_wpautop($content) . '</div><div class="clear"></div>';
}
add_shortcode( 'sixcol_four_last', 'themedy_shortcode_sixcol_four_last' );

function themedy_shortcode_sixcol_five($atts, $content = null) {
   return '<div class="sixcol-five">' . themedy_remove_wpautop($content) . '</div>';
}
add_shortcode( 'sixcol_five', 'themedy_shortcode_sixcol_five' );

function themedy_shortcode_sixcol_five_last($atts, $content = null) {
   return '<div class="sixcol-five last">' . themedy_remove_wpautop($content) . '</div><div class="clear"></div>';
}
add_shortcode( 'sixcol_five_last', 'themedy_shortcode_sixcol_five_last' );

/*--------------------------------------------------------
   Related Posts [related_posts]
--------------------------------------------------------*/
add_shortcode( 'related_posts', 'themedy_related_posts' );
function themedy_related_posts($atts) {

	extract(shortcode_atts(array(
	    'limit' => '5',
	    'image' => '',
	), $atts));
 
	global $wpdb, $post, $table_prefix;
 
	if ($post->ID) {
 
		$retval = '
<ul class="th-sc-related-posts">';
 
		// Get tags
		$tags = wp_get_post_tags($post->ID);
		$tagsarray = array();
		foreach ($tags as $tag) {
			$tagsarray[] = $tag->term_id;
		}
		$tagslist = implode( ',', $tagsarray);
 
		// Do the query
		if (!is_single() or empty($tagslist)) { $q = ''; } else
		$q = "
			SELECT p.*, count(tr.object_id) as count
			FROM $wpdb->term_taxonomy AS tt, $wpdb->term_relationships AS tr, $wpdb->posts AS p
			WHERE tt.taxonomy ='post_tag'
				AND tt.term_taxonomy_id = tr.term_taxonomy_id
				AND tr.object_id  = p.ID
				AND tt.term_id IN ($tagslist)
				AND p.ID != $post->ID
				AND p.post_status = 'publish'
				AND p.post_date_gmt < NOW()
			GROUP BY tr.object_id
			ORDER BY count DESC, p.post_date_gmt DESC
			LIMIT $limit;";
 		
		$related = $wpdb->get_results($q);
 
		if ( $related ) {
			foreach($related as $r) {
				$retval .= '
	<li><a class="related-title" title="'.wptexturize($r->post_title).'" href="'.get_permalink($r->ID).'"><span>'.wptexturize($r->post_title).'</span></a></li>
';
			}
		} else {
			$retval .= '
	<li>'.__( 'No related posts found', 'themedy' ).'</li>
';
		}
		$retval .= '</ul>
';
		return $retval;
	}
	return;
}

/*--------------------------------------------------------
   Dividers [hr] , [divider] , [divider_flat]
--------------------------------------------------------*/

add_shortcode( 'hr', 'themedy_shortcode_hr' );
add_shortcode( 'divider', 'themedy_shortcode_divider' );
add_shortcode( 'divider_flat', 'themedy_shortcode_divider_flat' );

function themedy_shortcode_hr($atts, $content = null) {
   return '<div class="th-sc-hr"></div>';
}

function themedy_shortcode_divider($atts, $content = null) {
   return '<div class="th-sc-divider"></div>';
}

function themedy_shortcode_divider_flat($atts, $content = null) {
   return '<div class="th-sc-divider flat"></div>';
}

/*--------------------------------------------------------
   Dropcap - [dropcap]
--------------------------------------------------------*/

add_shortcode( 'dropcap', 'themedy_shortcode_dropcap' );
function themedy_shortcode_dropcap ( $atts, $content = null ) {
	$defaults = array();
	extract( shortcode_atts( $defaults, $atts ) );
	return '<span class="dropcap">' . $content . '</span><!--/.dropcap-->';
}

/*--------------------------------------------------------
   Highlight - [highlight]
--------------------------------------------------------*/

add_shortcode( 'highlight', 'themedy_shortcode_highlight' );
function themedy_shortcode_highlight ( $atts, $content = null ) {
	$defaults = array();
	extract( shortcode_atts( $defaults, $atts ) );
	return '<span class="th-highlight">' . $content . '</span><!--/.shortcode-highlight-->';
}

/*--------------------------------------------------------
   Abbreviation - [abbr]
--------------------------------------------------------*/

add_shortcode( 'abbr', 'themedy_shortcode_abbreviation' );
function themedy_shortcode_abbreviation ( $atts, $content = null ) {
	$defaults = array( 'title' => '' );
	extract( shortcode_atts( $defaults, $atts ) );
	return '<abbr title="' . $title . '">' . $content . '</abbr>';
}