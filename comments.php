<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.  The actual display of comments is
 * handled by a callback to twentyten_comment which is
 * located in the functions.php file.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>

			<div id="comments">
<?php if ( post_password_required() ) : ?>
				<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'twentyten' ); ?></p>
			</div><!-- #comments -->
<?php
		/* Stop the rest of comments.php from being processed,
		 * but don't kill the script entirely -- we still have
		 * to fully load the template.
		 */
		return;
	endif;
?>

<?php
	// You can start editing here -- including this comment!
?>

<?php if ( have_comments() ) : ?>
			<h3 id="comments-title"><?php
			printf( _n( 'One Response to %2$s', '%1$s Responses to %2$s', get_comments_number(), 'twentyten' ),
			number_format_i18n( get_comments_number() ), get_the_title() );
			?></h3>

			<ol class="commentlist">
				<?php
					/* Loop through and list the comments. Tell wp_list_comments()
					 * to use twentyten_comment() to format the comments.
					 * If you want to overload this in a child theme then you can
					 * define twentyten_comment() and that will be used instead.
					 * See twentyten_comment() in twentyten/functions.php for more.
					 */
					wp_list_comments( 'type=comment&callback=mytheme_comment' );
				?>
			</ol>

		<?php
			if (get_option('page_comments')) {
				$comment_pages = paginate_comments_links('echo=0');
			if ($comment_pages) {
		?>
		<div id="pagenavi"><?php echo ('<span class="pages">Pages of Comments:</span>'); ?><?php echo $comment_pages; ?></div>
		<?php } } ?>

			<?php if ( ! empty($comments_by_type['pings']) ) : ?>
			<h3 id="pingback_title">Trackbacks/Pingbacks:</h3>
			<ol class="pingback">
				<?php wp_list_comments('type=pings&callback=list_pings'); ?>
			</ol>
			<?php endif; ?>

<?php else : // or, if we don't have comments:

	/* If there are no comments and comments are closed,
	 * let's leave a little note, shall we?
	 */
	if ( ! comments_open() ) :
?>
	<p class="nocomments"><?php _e( 'Comments are closed.', 'twentyten' ); ?></p>
<?php endif; // end ! comments_open() ?>

<?php endif; // end have_comments() ?>

<!-- Comment Form -->
<?php if ('open' == $post->comment_status) : ?>
<div id="respond">
	<h3><?php _e( 'Leave a Reply' ); ?></h3>
	<div class="cancel-comment-reply">
		<small><?php cancel_comment_reply_link(); ?></small>
	</div>
	<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
	<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logged in</a> to post a comment.</p>
	<?php else : ?>
	<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
		<?php if ( $user_ID ) : ?>
		<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account">Log out &raquo;</a></p>
		<?php else : ?>
		<div id="author_info">
		<p><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
		<label for="author"><?php _e('Name'); ?> <?php if ($req) echo "(required)"; ?></label></p>
		<p><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
		<label for="email"><?php _e('Mail (will not be published)'); ?> <?php if ($req) echo "(required)"; ?></label></p>
		<p><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
		<label for="url"><?php _e('Website'); ?></label></p>
		<p><?php if(!isset($_COOKIE['comment_author_email_'.COOKIEHASH]))spam_protection_math();?></p>
		</div>
		<?php endif; ?>
		<!--<p><small><strong>XHTML:</strong> You can use these tags: <code><?php echo allowed_tags(); ?></code></small></p>-->

		<p id="textarea"><textarea name="comment" id="comment" cols="100%" rows="10" tabindex="4"></textarea></p>
		<p><input name="submit" type="submit" id="submit" tabindex="5" value="Submit Comment" />
		<?php comment_id_fields(); ?>
		</p>
		<?php do_action('comment_form', $post->ID); ?>
	</form>
<?php endif; // If registration required and not logged in ?>
</div>
<?php endif; // if you delete this the sky will fall on your head ?>

</div><!-- #comments -->