<?php
/**
 * The Sidebar containing the primary and secondary widget areas.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>

		<div id="primary" class="widget-area" role="complementary">
			<ul class="xoxo">

<?php
	/* When we call the dynamic_sidebar() function, it'll spit out
	 * the widgets for that widget area. If it instead returns false,
	 * then the sidebar simply doesn't exist, so we'll hard-code in
	 * some default sidebar stuff just in case.
	 */
	if ( ! dynamic_sidebar( 'primary-widget-area' ) ) : ?>

			<li id="search" class="widget-container widget_search">
				<h3 class="widget-title"><?php _e("Search"); ?></h3>
				<?php get_search_form(); ?>
			</li>

			<li id="category" class="widget-container widget_categories">
				<h3 class="widget-title"><?php _e("Categories"); ?></h3>
				<ul><?php wp_list_categories("title_li="); ?></ul>
			</li>

			<?php if (is_single() or is_archive() or is_tag() or is_page()) { ?>
			<li id="recent-posts" class="widget-container">
				<h3 class="widget-title"><?php _e("Recent Posts"); ?></h3>
				<ul><?php get_archives("postbypost", 10); ?></ul>
			</li>
			<?php } ?>

			<?php if (is_home()) { ?>
			<li id="random-posts" class="widget-container">
				<h3 class="widget-title"><?php _e("Random Posts"); ?></h3>
				<ul>
				<?php $rand_posts = get_posts("numberposts=10&orderby=rand"); foreach( $rand_posts as $post ) : ?>
					<li>
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
					</li>
				<?php endforeach; ?>
				</ul>
			</li>
			<?php } ?>

			<li id="recent-comments" class="widget-container">
				<h3 class="widget-title"><?php _e("Recent Comments"); ?></h3>
				<ul>
					<?php
						global $wpdb;
						$my_email = get_bloginfo ('admin_email');
						$rc_comms = $wpdb->get_results("
							SELECT ID, post_title, comment_ID, comment_author, comment_author_email, comment_content
							FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts
							ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID)
							WHERE comment_approved = '1'
							AND comment_type = ''
							AND post_password = ''
							AND comment_author_email != '$my_email'
							ORDER BY comment_date_gmt
							DESC LIMIT 10
						");
						$rc_comments = '';
						foreach ($rc_comms as $rc_comm) {
							$rc_comments .= "<li><a href='"
							. get_permalink($rc_comm->ID) . "#comment-" . $rc_comm->comment_ID
							. "' title='on " . $rc_comm->post_title . "'>".strip_tags($rc_comm->comment_author) .": " . "" . strip_tags($rc_comm->comment_content)
							. "</a></li>\n";
						}
						$rc_comments = convert_smilies($rc_comments);
					echo $rc_comments; ?>
				</ul>
			</li>

			<li id="archives" class="widget-container widget_archive">
				<h3 class="widget-title"><?php _e( 'Archives', 'twentyten' ); ?></h3>
				<ul>
					<?php wp_get_archives( 'type=monthly' ); ?>
				</ul>
			</li>

			<li id="meta" class="widget-container">
				<h3 class="widget-title"><?php _e( 'Meta', 'twentyten' ); ?></h3>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<?php wp_meta(); ?>
				</ul>
			</li>

		<?php endif; // end primary widget area ?>
			</ul>
		</div><!-- #primary .widget-area -->